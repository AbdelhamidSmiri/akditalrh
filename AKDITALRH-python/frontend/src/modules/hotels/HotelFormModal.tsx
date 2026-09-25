import { FormEvent, useEffect, useState } from 'react';
import { filesApi, hotelsApi } from '../../api/endpoints';
import { getErrorMessage, publicFileUrl } from '../../api/client';
import type { Hotel, Ville } from '../../types';
import { Modal } from '../../components/ui/Modal';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { Textarea } from '../../components/ui/Textarea';
import { DropZone, uploadFiles } from '../../components/ui/DropZone';
import { flash } from '../../components/ui/Flash';

const AUTRE = '__autre__';
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_RE = /^[0-9]{10}$/;

type HotelForm = {
  hotel: string;
  etoile: string;
  region: string;
  ville_select: string;
  ville_autre: string;
  adresse: string;
  mail: string;
  telephone: string;
  nom_responsable: string;
  reglement: string;
};
type HotelErrors = Partial<Record<keyof HotelForm | 'images', string>>;

const EMPTY: HotelForm = {
  hotel: '',
  etoile: '',
  region: '',
  ville_select: '',
  ville_autre: '',
  adresse: '',
  mail: '',
  telephone: '',
  nom_responsable: '',
  reglement: '',
};

interface Props {
  open: boolean;
  hotel: Hotel | null;
  villes: Ville[];
  onClose: () => void;
  onSaved: () => void;
}

export function HotelFormModal({ open, hotel, villes, onClose, onSaved }: Props) {
  const [form, setForm] = useState<HotelForm>(EMPTY);
  const [errors, setErrors] = useState<HotelErrors>({});
  const [existingImages, setExistingImages] = useState<string[]>([]);
  const [newFiles, setNewFiles] = useState<File[]>([]);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (!open) return;
    setErrors({});
    setNewFiles([]);
    if (!hotel) {
      setForm(EMPTY);
      setExistingImages([]);
      return;
    }
    const known = villes.some((v) => v.id === hotel.ville_id);
    setForm({
      hotel: hotel.hotel || '',
      etoile: hotel.etoile || '',
      region: hotel.region || '',
      ville_select: known ? String(hotel.ville_id) : AUTRE,
      ville_autre: known ? '' : hotel.ville?.ville || '',
      adresse: hotel.adresse || '',
      mail: (hotel.mail || '').split(';').map((m) => m.trim()).filter(Boolean).join('\n'),
      telephone: hotel.telephone || '',
      nom_responsable: hotel.nom_responsable || '',
      reglement: hotel.reglement || '',
    });
    setExistingImages(
      (hotel.images || '')
        .split(',')
        .map((s) => s.trim())
        .filter(Boolean),
    );
  }, [open, hotel, villes]);

  const set = (key: keyof HotelForm) => (value: string) => setForm((f) => ({ ...f, [key]: value }));

  const emails = () =>
    form.mail
      .split(/\r?\n/)
      .map((e) => e.trim())
      .filter(Boolean);

  const validate = (): HotelErrors => {
    const errs: HotelErrors = {};
    if (form.hotel.trim().length < 3) {
      errs.hotel = "Le nom de l'hôtel est obligatoire (min. 3 caractères)";
    }
    const etoile = Number(form.etoile);
    if (!form.etoile || Number.isNaN(etoile) || etoile < 1 || etoile > 5) {
      errs.etoile = "Veuillez indiquer le nombre d'étoiles (1-5)";
    }
    if (form.ville_select === AUTRE) {
      if (form.ville_autre.trim().length < 2) errs.ville_autre = 'Le nom de la ville est obligatoire';
    } else if (!form.ville_select) {
      errs.ville_select = 'Sélectionnez une ville ou spécifiez "Autre"';
    }
    if (!form.adresse.trim()) errs.adresse = "L'adresse complète est requise";
    if (!hotel && newFiles.length === 0) errs.images = 'Au moins une image est requise';
    const list = emails();
    if (!list.length || list.some((m) => !EMAIL_RE.test(m))) {
      errs.mail = 'Une adresse email valide est requise';
    }
    if (!PHONE_RE.test(form.telephone.replace(/\s/g, ''))) {
      errs.telephone = 'Un numéro valide (10 chiffres) est requis';
    }
    if (!form.nom_responsable.trim()) errs.nom_responsable = 'Le nom du responsable est obligatoire';
    if (!form.reglement.trim()) errs.reglement = 'Les conditions de règlement sont requises';
    return errs;
  };

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;
    setSaving(true);
    try {
      const uploaded = newFiles.length ? await uploadFiles(newFiles, 'hotels', filesApi.upload) : [];
      const payload: Partial<Hotel> & { ville_autre?: string } = {
        hotel: form.hotel.trim(),
        etoile: form.etoile.trim(),
        adresse: form.adresse.trim(),
        mail: emails().join(';'),
        telephone: form.telephone.trim(),
        nom_responsable: form.nom_responsable.trim(),
        reglement: form.reglement.trim(),
        images: [...existingImages, ...uploaded].join(','),
      };
      if (hotel) payload.region = form.region.trim();
      if (form.ville_select === AUTRE) payload.ville_autre = form.ville_autre.trim();
      else payload.ville_id = Number(form.ville_select);

      if (hotel) await hotelsApi.update(hotel.id, payload);
      else await hotelsApi.create(payload);
      flash.success(hotel ? "L'hôtel a été enregistré." : "L'hôtel a été ajouté avec succès.");
      onSaved();
    } catch (err) {
      flash.error(
        getErrorMessage(err, hotel ? "L'hôtel n'a pas pu être enregistré." : "Erreur lors de l'ajout de l'hôtel."),
      );
    } finally {
      setSaving(false);
    }
  };

  const errorLine = (msg?: string) =>
    msg ? (
      <div className="message-error" style={{ display: 'block' }}>
        {msg}
      </div>
    ) : null;

  return (
    <Modal
      open={open}
      size="lg"
      title={hotel ? 'Modifier l’Hôtel' : 'Ajouter un Hôtel'}
      onClose={onClose}
      footer={
        <>
          <Button variant="secondary" onClick={onClose}>
            Annuler
          </Button>
          <Button form="hotel-form" type="submit" loading={saving}>
            <i className="fa-solid fa-paper-plane" /> {hotel ? 'Envoyer' : 'Enregistrer'}
          </Button>
        </>
      }
    >
      <form id="hotel-form" onSubmit={onSubmit} noValidate className="cake-form hotel-form">
        <Input
          label={hotel ? 'Nom de l’hôtel' : "Nom de l'hôtel"}
          value={form.hotel}
          onChange={(e) => set('hotel')(e.target.value)}
          error={errors.hotel}
        />
        <Input
          label={hotel ? 'Étoile' : "Nombre d'étoiles"}
          inputMode="numeric"
          value={form.etoile}
          onChange={(e) => set('etoile')(e.target.value)}
          error={errors.etoile}
        />
        {hotel && (
          <Input label="Région" value={form.region} onChange={(e) => set('region')(e.target.value)} />
        )}
        <Select
          label="Ville"
          value={form.ville_select}
          onChange={(e) => set('ville_select')(e.target.value)}
          placeholder="Sélectionner une ville"
          style={errors.ville_select ? { border: '1px solid #b80000' } : undefined}
        >
          {villes.map((v) => (
            <option key={v.id} value={v.id}>
              {v.ville}
            </option>
          ))}
          <option value={AUTRE}>Autre</option>
        </Select>
        {errorLine(errors.ville_select)}
        {form.ville_select === AUTRE && (
          <Input
            label="Autre ville"
            value={form.ville_autre}
            onChange={(e) => set('ville_autre')(e.target.value)}
            error={errors.ville_autre}
          />
        )}
        <Input
          label="Adresse"
          value={form.adresse}
          onChange={(e) => set('adresse')(e.target.value)}
          error={errors.adresse}
        />

        {existingImages.length > 0 && (
          <div className="existing-images">
            {existingImages.map((img) => (
              <div
                key={img}
                className="thumb"
                style={{ backgroundImage: `url('${publicFileUrl('hotels', img)}')` }}
              >
                <button
                  type="button"
                  aria-label="Supprimer l'image"
                  onClick={() => setExistingImages((list) => list.filter((i) => i !== img))}
                >
                  ×
                </button>
              </div>
            ))}
          </div>
        )}
        <DropZone
          label={hotel ? undefined : "Images de l'hôtel"}
          description={hotel ? "Téléversez les images de l'hôtel." : undefined}
          accept=".jpg,.jpeg,.png"
          files={newFiles}
          onChange={setNewFiles}
          error={Boolean(errors.images)}
        />
        {errorLine(errors.images)}

        <Textarea
          label={hotel ? 'Email(s)' : 'Mail'}
          rows={5}
          value={form.mail}
          onChange={(e) => set('mail')(e.target.value)}
          error={errors.mail}
        />
        <Input
          label="Téléphone"
          type="tel"
          value={form.telephone}
          onChange={(e) => set('telephone')(e.target.value)}
          error={errors.telephone}
        />
        <Input
          label="Nom du responsable"
          value={form.nom_responsable}
          onChange={(e) => set('nom_responsable')(e.target.value)}
          error={errors.nom_responsable}
        />
        <Textarea
          label="Règlement"
          rows={3}
          value={form.reglement}
          onChange={(e) => set('reglement')(e.target.value)}
          error={errors.reglement}
        />
      </form>
    </Modal>
  );
}
