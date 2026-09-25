import { FormEvent, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { logementApi, sitesApi, villesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Appartement, Site, Ville } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { PageHeader } from '../../components/layout/PageHeader';
import './LogementPages.css';

export function AffectationsPage() {
  const navigate = useNavigate();
  const [step, setStep] = useState(1);
  const [villes, setVilles] = useState<Ville[]>([]);
  const [sites, setSites] = useState<Site[]>([]);
  const [disponibles, setDisponibles] = useState<Appartement[]>([]);
  const [error, setError] = useState('');
  const [searching, setSearching] = useState(false);
  const [saving, setSaving] = useState(false);

  const [search, setSearch] = useState({
    date_debut: '',
    date_fin: '',
    sexe: '',
    ville_id: '',
  });
  const [selectedApt, setSelectedApt] = useState<Appartement | null>(null);
  const [form, setForm] = useState({
    site_id: '',
    nom: '',
    mail: '',
    telephone: '',
    status: '',
    note: '',
  });

  useEffect(() => {
    void (async () => {
      try {
        const [v, s] = await Promise.all([villesApi.list(), sitesApi.list()]);
        setVilles(unwrapList<Ville>(v.data));
        setSites(unwrapList<Site>(s.data));
      } catch (err) {
        setError(getErrorMessage(err));
      }
    })();
  }, []);

  const validateStep1 = () => {
    if (!search.date_debut || !search.date_fin || !search.sexe || !search.ville_id) {
      setError('Tous les champs de recherche sont obligatoires.');
      return false;
    }
    setError('');
    return true;
  };

  const onSearch = async () => {
    if (!validateStep1()) return;
    setSearching(true);
    setError('');
    try {
      const { data } = await logementApi.recherche({
        date_debut: search.date_debut,
        date_fin: search.date_fin,
        sexe: search.sexe,
        ville_id: Number(search.ville_id),
      });
      const list = unwrapList<Appartement>(data);
      setDisponibles(list);
      setStep(2);
      if (list.length === 0) {
        setError('Aucun appartement disponible pour ces critères.');
      }
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSearching(false);
    }
  };

  const pickApt = (apt: Appartement) => {
    setSelectedApt(apt);
    setStep(3);
    setError('');
  };

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (!selectedApt) return;
    if (!form.site_id || !form.nom || !form.mail || !form.telephone || !form.status) {
      setError('Veuillez remplir tous les champs obligatoires.');
      return;
    }
    setSaving(true);
    setError('');
    try {
      await logementApi.createBeneficiaire({
        appartement_id: selectedApt.id,
        site_id: Number(form.site_id),
        nom: form.nom,
        mail: form.mail,
        telephone: form.telephone,
        status: form.status,
        note: form.note,
        sexe: search.sexe,
        date_debut: search.date_debut,
        date_fin: search.date_fin,
        etat: 'En cours',
      });
      navigate('/logement/beneficiaires');
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  return (
    <>
      <PageHeader
        title="Nouvelle demande de logement"
        subtitle="Complétez les informations pour loger un collaborateur."
      />
      {error && <Alert variant="danger">{error}</Alert>}

      <div className="beneficiaires-form">
        {step === 1 && (
          <div className="affectation-step">
            <h1 className="title-step mb-4">Recherche d&apos;appartements</h1>
            <label className="title-section-form">Période d’hébergement</label>
            <div className="row g-3">
              <div className="col-md-6">
                <Input
                  label="Date d’arrivée prévue"
                  type="date"
                  value={search.date_debut}
                  onChange={(e) =>
                    setSearch((s) => ({ ...s, date_debut: e.target.value }))
                  }
                />
                <div className="message">
                  Date estimée de début d’occupation du logement.
                </div>
              </div>
              <div className="col-md-6">
                <Input
                  label="Date de départ prévue"
                  type="date"
                  value={search.date_fin}
                  onChange={(e) =>
                    setSearch((s) => ({ ...s, date_fin: e.target.value }))
                  }
                />
                <div className="message">Date estimée de fin de l’hébergement.</div>
              </div>
              <div className="col-12">
                <Select
                  label="Sexe"
                  value={search.sexe}
                  onChange={(e) => setSearch((s) => ({ ...s, sexe: e.target.value }))}
                  placeholder="Choisissez le sexe"
                  options={[
                    { value: 'Homme', label: 'Homme' },
                    { value: 'Femme', label: 'Femme' },
                  ]}
                />
                <div className="message">
                  Sélectionnez le sexe de la personne à loger.
                </div>
              </div>
              <div className="col-12">
                <Select
                  label="Ville"
                  value={search.ville_id}
                  onChange={(e) =>
                    setSearch((s) => ({ ...s, ville_id: e.target.value }))
                  }
                  placeholder="Choisissez la ville"
                  options={villes.map((v) => ({ value: v.id, label: v.ville }))}
                />
                <div className="message">Sélectionnez la ville où loger.</div>
              </div>
            </div>
            <div className="text-end mt-4">
              <Button onClick={() => void onSearch()} loading={searching}>
                <i className="fa-solid fa-search" /> Rechercher
              </Button>
            </div>
            <div className="d-flex justify-content-between mt-3">
              <span className="import-span">Tous les champs sont obligatoires.</span>
              <span className="pagin-steps">1 sur 3 étapes</span>
            </div>
          </div>
        )}

        {step === 2 && (
          <div className="affectation-step">
            <h1 className="title-step mb-5">Liste des appartements disponibles</h1>
            <div className="row g-3">
              {disponibles.length === 0 ? (
                <p className="no-results">Aucun appartement disponible.</p>
              ) : (
                disponibles.map((apt) => (
                  <div key={apt.id} className="col-md-6 col-lg-4">
                    <button
                      type="button"
                      className={`appartement-card${
                        selectedApt?.id === apt.id ? ' selected' : ''
                      }`}
                      onClick={() => pickApt(apt)}
                    >
                      <strong>{apt.nom || `Appartement #${apt.id}`}</strong>
                      <span>
                        {apt.ville?.ville || ''} · {apt.sexe}
                      </span>
                      <span>
                        Capacité {apt.capacite} · Occupants{' '}
                        {apt.nb_occupants ?? apt.ocupants ?? 0} · Places{' '}
                        {apt.places_dispo ?? '—'}
                      </span>
                      {apt.adresse ? <span className="muted">{apt.adresse}</span> : null}
                    </button>
                  </div>
                ))
              )}
            </div>
            <div className="d-flex justify-content-between mt-3">
              <Button variant="secondary" onClick={() => setStep(1)}>
                <i className="fa-solid fa-arrow-left" /> Retour
              </Button>
              <span className="pagin-steps">2 sur 3 étapes</span>
            </div>
          </div>
        )}

        {step === 3 && (
          <div className="affectation-step">
            <h1 className="title-step mb-4">Informations du bénéficiaire</h1>
            {selectedApt && (
              <p className="muted mb-3">
                Logement sélectionné : <strong>{selectedApt.nom}</strong> (
                {search.date_debut} → {search.date_fin})
              </p>
            )}
            <form onSubmit={onSubmit} className="stack">
              <Select
                label="Site"
                value={form.site_id}
                onChange={(e) => setForm((f) => ({ ...f, site_id: e.target.value }))}
                placeholder="Site d’affectation"
                options={sites.map((s) => ({ value: s.id, label: s.site }))}
              />
              <div className="message">
                Indiquez le site Akdital auquel le bénéficiaire est rattaché.
              </div>
              <Input
                label="Nom et prénom du bénéficiaire"
                value={form.nom}
                onChange={(e) => setForm((f) => ({ ...f, nom: e.target.value }))}
                required
              />
              <Input
                label="L’adresse email du bénéficiaire"
                type="email"
                value={form.mail}
                onChange={(e) => setForm((f) => ({ ...f, mail: e.target.value }))}
                required
              />
              <Input
                label="Numéro WhatsApp du bénéficiaire"
                value={form.telephone}
                onChange={(e) => setForm((f) => ({ ...f, telephone: e.target.value }))}
                required
              />
              <Select
                label="Statut du bénéficiaire"
                value={form.status}
                onChange={(e) => setForm((f) => ({ ...f, status: e.target.value }))}
                placeholder="Choisissez le statut"
                options={[
                  { value: 'Stagiaire', label: 'Stagiaire' },
                  { value: 'Employe', label: 'Employé' },
                  { value: 'Externe', label: 'Externe' },
                  { value: 'Nouvelle recrue', label: 'Nouvelle recrue' },
                  { value: 'Autre', label: 'Autre' },
                ]}
              />
              <Input
                label="Notes / Contraintes particulières (facultatif)"
                value={form.note}
                onChange={(e) => setForm((f) => ({ ...f, note: e.target.value }))}
              />
              <div className="submit-section mt-4">
                <Button type="submit" loading={saving}>
                  <i className="fa-solid fa-paper-plane" /> Enregistrer la demande
                </Button>
              </div>
            </form>
            <div className="d-flex justify-content-between mt-3">
              <Button variant="secondary" onClick={() => setStep(2)}>
                <i className="fa-solid fa-arrow-left" /> Retour
              </Button>
              <span className="pagin-steps">3 sur 3 étapes</span>
            </div>
          </div>
        )}
      </div>
    </>
  );
}
