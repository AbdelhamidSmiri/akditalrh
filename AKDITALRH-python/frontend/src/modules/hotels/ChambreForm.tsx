import { FormEvent, useEffect, useState } from 'react';
import { filesApi, hotelsApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import type { Chambre } from '../../types';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { DropZone, uploadFiles } from '../../components/ui/DropZone';
import { flash } from '../../components/ui/Flash';

/** Modales de app/View/Hotels/view.ctp : nouvelle chambre, prix, informations. */

type PriceRow = {
  key: number;
  id?: number;
  date_debut: string;
  date_fin: string;
  prix: string;
};

let rowSeq = 0;
const emptyRow = (): PriceRow => ({ key: ++rowSeq, date_debut: '', date_fin: '', prix: '' });

function PriceRows({
  rows,
  onChange,
  onRemove,
}: {
  rows: PriceRow[];
  onChange: (rows: PriceRow[]) => void;
  onRemove?: (row: PriceRow) => void;
}) {
  const update = (key: number, field: keyof PriceRow, value: string) =>
    onChange(rows.map((r) => (r.key === key ? { ...r, [field]: value } : r)));

  const remove = (row: PriceRow) => {
    onRemove?.(row);
    onChange(rows.filter((r) => r.key !== row.key));
  };

  return (
    <div className="price-rows">
      {rows.map((row) => (
        <div key={row.key} className="row price-row">
          <div className="col-md-4">
            <div className="input text date-field">
              <label htmlFor={`date_debut_${row.key}`}>Date debut prix</label>
              <input
                id={`date_debut_${row.key}`}
                type="date"
                value={row.date_debut}
                onChange={(e) => update(row.key, 'date_debut', e.target.value)}
              />
            </div>
          </div>
          <div className="col-md-4">
            <div className="input text date-field">
              <label htmlFor={`date_fin_${row.key}`}>Date fin prix</label>
              <input
                id={`date_fin_${row.key}`}
                type="date"
                value={row.date_fin}
                onChange={(e) => update(row.key, 'date_fin', e.target.value)}
              />
            </div>
          </div>
          <div className="col-md-3">
            <Input
              id={`prix_${row.key}`}
              label="Prix"
              inputMode="decimal"
              value={row.prix}
              onChange={(e) => update(row.key, 'prix', e.target.value)}
            />
          </div>
          <div className="col-md-1">
            {rows.length > 1 && (
              <button
                type="button"
                className="btn btn-danger-rounded remove-row-btn"
                aria-label="Supprimer la ligne"
                onClick={() => remove(row)}
              >
                <i className="fa-solid fa-trash" />
              </button>
            )}
          </div>
        </div>
      ))}
      <div className="col-12">
        <div className="text-end">
          <button
            type="button"
            className="btn btn-primary-rounded"
            aria-label="Ajouter un prix"
            onClick={() => onChange([...rows, emptyRow()])}
          >
            <i className="fa-solid fa-plus" />
          </button>
        </div>
      </div>
    </div>
  );
}

const rowsComplete = (rows: PriceRow[]) =>
  rows.every((r) => r.date_debut && r.date_fin && r.prix.trim());

const rowsPayload = (rows: PriceRow[]) =>
  rows.map((r) => ({
    ...(r.id ? { id: r.id } : {}),
    date_debut: r.date_debut,
    date_fin: r.date_fin,
    prix: r.prix.trim(),
  }));

interface NewProps {
  open: boolean;
  hotelId: number;
  onClose: () => void;
  onSaved: () => void;
}

export function NewChambreModal({ open, hotelId, onClose, onSaved }: NewProps) {
  const [step, setStep] = useState<1 | 2>(1);
  const [nom, setNom] = useState('');
  const [type, setType] = useState('');
  const [files, setFiles] = useState<File[]>([]);
  const [rows, setRows] = useState<PriceRow[]>([emptyRow()]);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (!open) return;
    setStep(1);
    setNom('');
    setType('');
    setFiles([]);
    setRows([emptyRow()]);
  }, [open]);

  const next = () => {
    if (!nom.trim() || !type.trim()) {
      alert('Veuillez remplir tous les champs obligatoires');
      return;
    }
    setStep(2);
  };

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (step === 1) {
      next();
      return;
    }
    if (!rowsComplete(rows)) {
      alert('Veuillez remplir tous les champs de prix');
      return;
    }
    setSaving(true);
    try {
      const images = files.length ? await uploadFiles(files, 'chambres', filesApi.upload) : [];
      await hotelsApi.createChambre(hotelId, {
        nom: nom.trim(),
        type: type.trim(),
        images: images.join(',') || null,
        prices: rowsPayload(rows) as unknown as Chambre['prices'],
      });
      flash.success('La chambre a été ajoutée avec succès.');
      onSaved();
    } catch (err) {
      flash.error(getErrorMessage(err, "Erreur lors de l'ajout de la chambre."));
    } finally {
      setSaving(false);
    }
  };

  return (
    <Modal
      open={open}
      size="lg"
      title="Ajouter une nouvelle chambre"
      onClose={onClose}
      footer={
        <>
          <button type="button" className="btn btn-secondary-rounded" onClick={onClose}>
            Fermer
          </button>
          {step === 2 && (
            <button type="button" className="btn btn-secondary-rounded" onClick={() => setStep(1)}>
              Retour
            </button>
          )}
          {step === 1 ? (
            <button type="button" className="btn btn-primary-rounded" onClick={next}>
              Suivant
            </button>
          ) : (
            <button
              type="submit"
              form="chambre-add-form"
              className="btn btn-primary-rounded"
              disabled={saving}
            >
              {saving ? '…' : 'Enregistrer'}
            </button>
          )}
        </>
      }
    >
      <form id="chambre-add-form" onSubmit={onSubmit} noValidate className="cake-form chambre-form">
        {step === 1 ? (
          <>
            <Input id="ChambreNom" label="Nom/N° de la chambre" value={nom} onChange={(e) => setNom(e.target.value)} />
            <Input id="ChambreType" label="Catégorie" value={type} onChange={(e) => setType(e.target.value)} />
            <DropZone
              accept=".jpg,.jpeg,.png"
              description="Téléversez les images de la chambre."
              files={files}
              onChange={setFiles}
            />
          </>
        ) : (
          <PriceRows rows={rows} onChange={setRows} />
        )}
      </form>
    </Modal>
  );
}

interface ChambreProps {
  chambre: Chambre | null;
  onClose: () => void;
  onSaved: () => void;
}

export function PricesModal({ chambre, onClose, onSaved }: ChambreProps) {
  const [rows, setRows] = useState<PriceRow[]>([]);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (!chambre) return;
    setDeleteIds([]);
    const prices = chambre.prices || [];
    setRows(
      prices.length
        ? prices.map((p) => ({
            key: ++rowSeq,
            id: p.id,
            date_debut: p.date_debut || '',
            date_fin: p.date_fin || '',
            prix: p.prix || '',
          }))
        : [emptyRow()],
    );
  }, [chambre]);

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (!chambre) return;
    setSaving(true);
    const prices = rowsPayload(rows.filter((r) => r.date_debut && r.date_fin && r.prix.trim()));
    try {
      await hotelsApi.syncPrices(chambre.id, { prices, delete_ids: deleteIds });
      flash.success(
        prices.length && deleteIds.length
          ? 'Les prix ont été sauvegardés et supprimés avec succès.'
          : prices.length
            ? 'Les prix de la chambre ont été sauvegardés avec succès.'
            : 'Les prix de la chambre ont été supprimés avec succès.',
      );
      onSaved();
    } catch (err) {
      flash.error(getErrorMessage(err, "Aucun prix n'a été sauvegardé."));
    } finally {
      setSaving(false);
    }
  };

  return (
    <Modal
      open={Boolean(chambre)}
      size="lg"
      title={`Mettre à jour les prix${chambre?.nom ? ` - ${chambre.nom}` : ''}`}
      onClose={onClose}
      footer={
        <>
          <button type="button" className="btn btn-secondary-rounded" onClick={onClose}>
            Fermer
          </button>
          <button type="submit" form="chambre-prices-form" className="btn btn-primary-rounded" disabled={saving}>
            {saving ? '…' : 'Enregistrer'}
          </button>
        </>
      }
    >
      <form id="chambre-prices-form" onSubmit={onSubmit} noValidate className="cake-form chambre-form">
        <PriceRows
          rows={rows}
          onChange={setRows}
          onRemove={(row) => row.id && setDeleteIds((ids) => [...ids, row.id as number])}
        />
      </form>
    </Modal>
  );
}

export function EditChambreModal({ chambre, onClose, onSaved }: ChambreProps) {
  const [nom, setNom] = useState('');
  const [type, setType] = useState('');
  const [files, setFiles] = useState<File[]>([]);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    if (!chambre) return;
    setNom(chambre.nom || '');
    setType(chambre.type || '');
    setFiles([]);
  }, [chambre]);

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (!chambre) return;
    setSaving(true);
    try {
      const payload: Partial<Chambre> = { nom: nom.trim(), type: type.trim() };
      if (files.length) {
        payload.images = (await uploadFiles(files, 'chambres', filesApi.upload)).join(',');
      }
      await hotelsApi.updateChambre(chambre.id, payload);
      flash.success('La chambre a été modifiée avec succès.');
      onSaved();
    } catch (err) {
      flash.error(getErrorMessage(err, 'Erreur lors de la modification de la chambre.'));
    } finally {
      setSaving(false);
    }
  };

  return (
    <Modal
      open={Boolean(chambre)}
      size="lg"
      title={`Éditer les informations de la chambre${chambre?.nom ? ` - ${chambre.nom}` : ''}`}
      onClose={onClose}
      footer={
        <>
          <button type="button" className="btn btn-secondary-rounded" onClick={onClose}>
            Fermer
          </button>
          <button type="submit" form="chambre-edit-form" className="btn btn-primary-rounded" disabled={saving}>
            {saving ? '…' : 'Enregistrer'}
          </button>
        </>
      }
    >
      <form id="chambre-edit-form" onSubmit={onSubmit} noValidate className="cake-form chambre-form">
        <div className="row">
          <div className="col-md-6">
            <Input id="ChambreNomedite" label="Nom/N° de la chambre" value={nom} onChange={(e) => setNom(e.target.value)} />
          </div>
          <div className="col-md-6">
            <Input id="Chambretypeedite" label="Catégorie" value={type} onChange={(e) => setType(e.target.value)} />
          </div>
        </div>
        <DropZone
          accept=".jpg,.jpeg,.png"
          description="Téléversez les images de la chambre."
          files={files}
          onChange={setFiles}
        />
      </form>
    </Modal>
  );
}
