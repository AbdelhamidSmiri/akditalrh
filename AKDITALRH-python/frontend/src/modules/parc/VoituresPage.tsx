import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { parcApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Voiture } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { FileUploader } from '../../components/ui/FileUploader';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';

function fmt(v: string | number | null | undefined) {
  if (v === null || v === undefined || v === '') return '—';
  return String(v);
}

function userLabel(r: Voiture) {
  const n = [r.user?.nom, r.user?.prenom].filter(Boolean).join(' ').trim();
  return n || (r.user_id ? `#${r.user_id}` : '—');
}

export function VoituresPage() {
  const [rows, setRows] = useState<Voiture[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [q, setQ] = useState('');
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Voiture | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    immatriculation: '',
    marque: '',
    modele: '',
    ste_location: '',
    categorie_vr: '',
    duree_mois: '',
    km: '',
    livraison: '',
    fin_contrat: '',
  });

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const { data } = await parcApi.listVoitures();
      setRows(unwrapList<Voiture>(data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const filtered = useMemo(() => {
    const s = q.trim().toLowerCase();
    if (!s) return rows;
    return rows.filter((r) =>
      [
        userLabel(r),
        r.ste_location,
        r.immatriculation,
        r.marque,
        r.modele,
        r.categorie_vr,
        r.service_link,
      ]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(s),
    );
  }, [rows, q]);

  const openForm = (item?: Voiture) => {
    setEditing(item || null);
    setForm({
      immatriculation: item?.immatriculation || '',
      marque: item?.marque || '',
      modele: item?.modele || '',
      ste_location: item?.ste_location || item?.fournisseur || '',
      categorie_vr: item?.categorie_vr || item?.categorie || '',
      duree_mois: item?.duree_mois != null ? String(item.duree_mois) : '',
      km: item?.km != null ? String(item.km) : '',
      livraison: item?.livraison?.slice(0, 10) || '',
      fin_contrat: item?.fin_contrat?.slice(0, 10) || '',
    });
    setOpen(true);
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = {
        immatriculation: form.immatriculation,
        marque: form.marque,
        modele: form.modele,
        ste_location: form.ste_location,
        categorie_vr: form.categorie_vr,
        duree_mois: form.duree_mois ? Number(form.duree_mois) : null,
        km: form.km ? Number(form.km) : null,
        livraison: form.livraison || null,
        fin_contrat: form.fin_contrat || null,
      };
      if (editing) await parcApi.updateVoiture(editing.id, payload);
      else await parcApi.createVoiture(payload);
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer ce véhicule ?')) return;
    try {
      await parcApi.removeVoiture(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const onImport = async (files: FileList | null) => {
    if (!files?.[0]) return;
    const fd = new FormData();
    fd.append('file', files[0]);
    try {
      await parcApi.importVoitures(fd);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<Voiture>[] = [
    {
      key: 'user',
      header: 'Utilisateur',
      render: (r) =>
        r.user?.id ? (
          <EntityLink to={`/users/${r.user.id}`}>{userLabel(r)}</EntityLink>
        ) : (
          userLabel(r)
        ),
    },
    { key: 'ste', header: 'Ste location', render: (r) => fmt(r.ste_location) },
    { key: 'immat', header: 'Immatriculation', render: (r) => fmt(r.immatriculation) },
    { key: 'marque', header: 'Marque', render: (r) => fmt(r.marque) },
    { key: 'modele', header: 'Modèle', render: (r) => fmt(r.modele) },
    {
      key: 'vl',
      header: 'Valeur loc. HT',
      render: (r) => fmt(r.valeur_locative_ht),
    },
    { key: 'duree', header: 'Durée (mois)', render: (r) => fmt(r.duree_mois) },
    { key: 'km', header: 'Km', render: (r) => fmt(r.km) },
    {
      key: 'cout',
      header: 'Coût km suppl. TTC',
      render: (r) => fmt(r.cout_km_suplm_ttc),
    },
    { key: 'pneus', header: 'Nbr pneus', render: (r) => fmt(r.nbr_pneus) },
    { key: 'service', header: 'Service link', render: (r) => fmt(r.service_link) },
    { key: 'cat', header: 'Catégorie VR', render: (r) => fmt(r.categorie_vr) },
    {
      key: 'livraison',
      header: 'Livraison',
      render: (r) => r.livraison?.slice(0, 10) || '—',
    },
    {
      key: 'fin',
      header: 'Fin contrat',
      render: (r) => r.fin_contrat?.slice(0, 10) || '—',
    },
    { key: 'km_actuel', header: 'Km actuel', render: (r) => fmt(r.km_actuel) },
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
            Edit
          </a>
          {' / '}
          <a
            href="#delete"
            onClick={(e) => {
              e.preventDefault();
              void onDelete(r.id);
            }}
          >
            Delete
          </a>
        </span>
      ),
    },
  ];

  return (
    <>
      <PageHeader title="Voitures" subtitle="Consultez et gérez les informations du système." />
      <div className="col-md-5">
        <div className="search-section">
          <div className="input-group mb-3">
            <span className="input-group-text">
              <i className="fa-solid fa-magnifying-glass" />
            </span>
            <input
              type="text"
              className="form-control"
              id="search_input"
              placeholder="Rechercher"
              value={q}
              onChange={(e) => setQ(e.target.value)}
            />
            <button type="button" className="btn btn-primary-rounded">
              <i className="fa-solid fa-magnifying-glass" /> Rechercher
            </button>
          </div>
        </div>
      </div>
      <div className="page-toolbar">
        <FileUploader label="Importer Excel" accept=".xlsx,.xls,.csv" onChange={onImport} />
        <Button onClick={() => openForm()}>Ajouter</Button>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable columns={columns} rows={filtered} rowKey={(r) => r.id} loading={loading} />
      <Modal
        open={open}
        title={editing ? 'Éditer le véhicule' : 'Nouveau véhicule'}
        onClose={() => setOpen(false)}
        size="lg"
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="voiture-form" type="submit" loading={saving}>
              Enregistrer
            </Button>
          </>
        }
      >
        <form id="voiture-form" onSubmit={onSave} className="stack">
          <Input
            label="Immatriculation"
            value={form.immatriculation}
            onChange={(e) => setForm((f) => ({ ...f, immatriculation: e.target.value }))}
            required
          />
          <Input
            label="Marque"
            value={form.marque}
            onChange={(e) => setForm((f) => ({ ...f, marque: e.target.value }))}
          />
          <Input
            label="Modèle"
            value={form.modele}
            onChange={(e) => setForm((f) => ({ ...f, modele: e.target.value }))}
          />
          <Input
            label="Ste location"
            value={form.ste_location}
            onChange={(e) => setForm((f) => ({ ...f, ste_location: e.target.value }))}
          />
          <Input
            label="Catégorie VR"
            value={form.categorie_vr}
            onChange={(e) => setForm((f) => ({ ...f, categorie_vr: e.target.value }))}
          />
          <Input
            label="Durée (mois)"
            type="number"
            value={form.duree_mois}
            onChange={(e) => setForm((f) => ({ ...f, duree_mois: e.target.value }))}
          />
          <Input
            label="Km"
            type="number"
            value={form.km}
            onChange={(e) => setForm((f) => ({ ...f, km: e.target.value }))}
          />
          <Input
            label="Livraison"
            type="date"
            value={form.livraison}
            onChange={(e) => setForm((f) => ({ ...f, livraison: e.target.value }))}
          />
          <Input
            label="Fin contrat"
            type="date"
            value={form.fin_contrat}
            onChange={(e) => setForm((f) => ({ ...f, fin_contrat: e.target.value }))}
          />
        </form>
      </Modal>
    </>
  );
}
