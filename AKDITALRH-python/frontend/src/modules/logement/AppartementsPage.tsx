import { FormEvent, useCallback, useEffect, useState } from 'react';
import { logementApi, villesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { LogementDisponibilites, Ville } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { Loading } from '../../components/ui/Loading';
import { PageHeader } from '../../components/layout/PageHeader';
import './LogementPages.css';

type AppartRow = LogementDisponibilites['appart_info'][number];
type StatsRow = LogementDisponibilites['stats'][number];

export function AppartementsPage() {
  const [data, setData] = useState<LogementDisponibilites | null>(null);
  const [villes, setVilles] = useState<Ville[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<AppartRow | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    nom: '',
    adresse: '',
    ville_id: '',
    capacite: '',
    sexe: 'Homme',
  });

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const [d, v] = await Promise.all([
        logementApi.disponibilites(),
        villesApi.list(),
      ]);
      setData(d.data as LogementDisponibilites);
      setVilles(unwrapList<Ville>(v.data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const openForm = (item?: AppartRow) => {
    setEditing(item || null);
    const ville = villes.find(
      (x) => x.ville?.toLowerCase() === item?.ville?.toLowerCase(),
    );
    setForm({
      nom: item?.nom || '',
      adresse: '',
      ville_id: ville ? String(ville.id) : '',
      capacite: item?.capacite != null ? String(item.capacite) : '',
      sexe: item?.sexe || 'Homme',
    });
    setOpen(true);
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = {
        nom: form.nom,
        adresse: form.adresse,
        ville_id: form.ville_id ? Number(form.ville_id) : null,
        capacite: form.capacite ? Number(form.capacite) : null,
        sexe: form.sexe,
      };
      if (editing) await logementApi.updateAppartement(editing.id, payload);
      else await logementApi.createAppartement(payload);
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer cet appartement ?')) return;
    try {
      await logementApi.removeAppartement(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const statsColumns: Column<StatsRow>[] = [
    { key: 'ville', header: 'Ville', render: (r) => (r.ville || '').toLowerCase() },
    { key: 'sexe', header: 'Sexe', render: (r) => r.sexe || '—' },
    { key: 'total', header: 'Total d’appartements', render: (r) => r.total },
    { key: 'ocupes', header: 'Appartements pleins', render: (r) => r.ocupes },
    {
      key: 'dispo',
      header: 'Places disponibles',
      render: (r) => r.places_dispo,
    },
  ];

  const detailColumns: Column<AppartRow>[] = [
    { key: 'nom', header: 'Nom', render: (r) => r.nom || '—' },
    {
      key: 'ville',
      header: 'Ville',
      render: (r) => (r.ville || '').toLowerCase() || '—',
    },
    { key: 'sexe', header: 'Sexe', render: (r) => r.sexe || '—' },
    { key: 'capacite', header: 'Capacité', render: (r) => r.capacite },
    { key: 'ocupants', header: 'Occupants', render: (r) => r.ocupants },
    {
      key: 'places',
      header: 'Places disponibles',
      render: (r) => r.places_dispo,
    },
    {
      key: 'actions',
      header: 'Actions',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <a
            href="#edit"
            onClick={(e) => {
              e.preventDefault();
              openForm(r);
            }}
          >
            modifier
          </a>
          {' / '}
          <a
            href="#delete"
            onClick={(e) => {
              e.preventDefault();
              void onDelete(r.id);
            }}
          >
            Supprimer
          </a>
        </span>
      ),
    },
  ];

  if (loading && !data) return <Loading />;

  const g = data?.global || {
    total_appartements: 0,
    total_ocupes: 0,
    total_places_dispo: 0,
  };

  return (
    <>
      <PageHeader
        title="Disponibilités des logements"
        subtitle="Consultez la capacité et le taux d'occupation de chaque appartement."
      />
      <div className="page-toolbar">
        <Button onClick={() => openForm()}>Ajouter</Button>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}

      <h1 className="little-title">Tableau de bord des Appartements</h1>
      <div className="row g-3 mb-5 logement-stats-row">
        <div className="col-12 col-sm-6 col-lg-3">
          <div className="card view-card h-100 dark-card">
            <div className="stats-card card-body d-flex align-items-center justify-content-between pt-4">
              <div className="d-flex flex-column justify-content-center h-100 flex-grow-1">
                <div className="stats-number mb-1">{g.total_appartements}</div>
                <div className="stats-label mb-0 text-muted">Total Appartements</div>
              </div>
              <div className="dark-icon d-flex align-items-center justify-content-center flex-shrink-0">
                <i className="fa-regular fa-building" />
              </div>
            </div>
          </div>
        </div>
        <div className="col-12 col-sm-6 col-lg-3">
          <div className="card view-card h-100">
            <div className="stats-card card-body d-flex align-items-center justify-content-between pt-4">
              <div className="d-flex flex-column justify-content-center h-100 flex-grow-1">
                <div className="stats-number mb-1">{g.total_ocupes}</div>
                <div className="stats-label mb-0 text-muted">Appartement complet</div>
              </div>
              <div className="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                <i className="fa-regular fa-door-closed" />
              </div>
            </div>
          </div>
        </div>
        <div className="col-12 col-sm-6 col-lg-3">
          <div className="card view-card h-100">
            <div className="stats-card card-body d-flex align-items-center justify-content-between pt-4">
              <div className="d-flex flex-column justify-content-center h-100 flex-grow-1">
                <div className="stats-number mb-1">{g.total_places_dispo}</div>
                <div className="stats-label mb-0 text-muted">Places Disponibles</div>
              </div>
              <div className="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                <i className="fa-solid fa-bed" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <h1 className="little-title">Statistiques par Ville et Sexe</h1>
      <DataTable
        columns={statsColumns}
        rows={data?.stats || []}
        rowKey={(r) => `${r.ville}-${r.sexe}`}
        loading={loading}
      />

      <h1 className="little-title" style={{ marginTop: '2rem' }}>
        Détails par Appartement
      </h1>
      <DataTable
        columns={detailColumns}
        rows={data?.appart_info || []}
        rowKey={(r) => r.id}
        loading={loading}
      />

      <Modal
        open={open}
        title={editing ? 'Modifier l’appartement' : 'Nouvel appartement'}
        onClose={() => setOpen(false)}
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="appart-form" type="submit" loading={saving}>
              Enregistrer
            </Button>
          </>
        }
      >
        <form id="appart-form" onSubmit={onSave} className="stack">
          <Input
            label="Nom"
            value={form.nom}
            onChange={(e) => setForm((f) => ({ ...f, nom: e.target.value }))}
            required
          />
          <Input
            label="Adresse"
            value={form.adresse}
            onChange={(e) => setForm((f) => ({ ...f, adresse: e.target.value }))}
          />
          <Select
            label="Ville"
            value={form.ville_id}
            onChange={(e) => setForm((f) => ({ ...f, ville_id: e.target.value }))}
            placeholder="Sélectionner…"
            options={villes.map((v) => ({ value: v.id, label: v.ville }))}
          />
          <Input
            label="Capacité"
            type="number"
            value={form.capacite}
            onChange={(e) => setForm((f) => ({ ...f, capacite: e.target.value }))}
          />
          <Select
            label="Sexe"
            value={form.sexe}
            onChange={(e) => setForm((f) => ({ ...f, sexe: e.target.value }))}
            options={[
              { value: 'Homme', label: 'Homme' },
              { value: 'Femme', label: 'Femme' },
            ]}
          />
        </form>
      </Modal>
    </>
  );
}
