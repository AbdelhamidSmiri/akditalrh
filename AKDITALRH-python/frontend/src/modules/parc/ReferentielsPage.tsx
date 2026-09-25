import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { parcApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Referentiel } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { FileUploader } from '../../components/ui/FileUploader';
import { PageHeader } from '../../components/layout/PageHeader';

function fmt(v: string | number | null | undefined) {
  if (v === null || v === undefined || v === '') return '—';
  return String(v);
}

function fmtNum(v: string | number | null | undefined, digits = 2) {
  if (v === null || v === undefined || v === '') return '—';
  const n = typeof v === 'number' ? v : Number(v);
  if (Number.isNaN(n)) return String(v);
  return n.toFixed(digits);
}

function fmtPct(v: string | number | null | undefined) {
  if (v === null || v === undefined || v === '') return '—';
  const n = typeof v === 'number' ? v : Number(v);
  if (Number.isNaN(n)) return String(v);
  return n.toFixed(4);
}

const EMPTY_FORM = {
  modele: '',
  duree: '',
  kilometrage: '',
  loyer_ht_ald: '',
  loyer_ttc_ald: '',
  km_supp_ht_ald: '',
  nb_pneus_ald: '',
  cat_remplacement_ald: '',
  loyer_ht_arval: '',
  loyer_ttc_arval: '',
  km_supp_ht_arval: '',
  nb_pneus_arval: '',
  cat_remplacement_arval: '',
  moyenne_offres_ttc: '',
  valeur_actuelle_moy_ttc: '',
  augmentation_pourcent: '',
  categorie: '',
};

export function ReferentielsPage() {
  const [rows, setRows] = useState<Referentiel[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [q, setQ] = useState('');
  const [open, setOpen] = useState(false);
  const [detail, setDetail] = useState<Referentiel | null>(null);
  const [editing, setEditing] = useState<Referentiel | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState(EMPTY_FORM);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const { data } = await parcApi.listReferentiels();
      setRows(unwrapList<Referentiel>(data));
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
      [r.modele, r.duree, r.kilometrage, r.categorie, r.cat_remplacement_ald]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(s),
    );
  }, [rows, q]);

  const openForm = (item?: Referentiel) => {
    setEditing(item || null);
    setForm({
      modele: item?.modele || '',
      duree: item?.duree || '',
      kilometrage: item?.kilometrage || '',
      loyer_ht_ald: item?.loyer_ht_ald != null ? String(item.loyer_ht_ald) : '',
      loyer_ttc_ald: item?.loyer_ttc_ald != null ? String(item.loyer_ttc_ald) : '',
      km_supp_ht_ald: item?.km_supp_ht_ald != null ? String(item.km_supp_ht_ald) : '',
      nb_pneus_ald: item?.nb_pneus_ald != null ? String(item.nb_pneus_ald) : '',
      cat_remplacement_ald: item?.cat_remplacement_ald || '',
      loyer_ht_arval: item?.loyer_ht_arval != null ? String(item.loyer_ht_arval) : '',
      loyer_ttc_arval: item?.loyer_ttc_arval != null ? String(item.loyer_ttc_arval) : '',
      km_supp_ht_arval: item?.km_supp_ht_arval != null ? String(item.km_supp_ht_arval) : '',
      nb_pneus_arval: item?.nb_pneus_arval != null ? String(item.nb_pneus_arval) : '',
      cat_remplacement_arval: item?.cat_remplacement_arval || '',
      moyenne_offres_ttc:
        item?.moyenne_offres_ttc != null ? String(item.moyenne_offres_ttc) : '',
      valeur_actuelle_moy_ttc:
        item?.valeur_actuelle_moy_ttc != null
          ? String(item.valeur_actuelle_moy_ttc)
          : '',
      augmentation_pourcent: item?.augmentation_pourcent || '',
      categorie: item?.categorie || '',
    });
    setOpen(true);
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const num = (v: string) => (v === '' ? null : Number(v));
      const payload = {
        modele: form.modele,
        duree: form.duree,
        kilometrage: form.kilometrage,
        loyer_ht_ald: num(form.loyer_ht_ald),
        loyer_ttc_ald: num(form.loyer_ttc_ald),
        km_supp_ht_ald: num(form.km_supp_ht_ald),
        nb_pneus_ald: form.nb_pneus_ald ? Number(form.nb_pneus_ald) : null,
        cat_remplacement_ald: form.cat_remplacement_ald || null,
        loyer_ht_arval: num(form.loyer_ht_arval),
        loyer_ttc_arval: num(form.loyer_ttc_arval),
        km_supp_ht_arval: num(form.km_supp_ht_arval),
        nb_pneus_arval: form.nb_pneus_arval ? Number(form.nb_pneus_arval) : null,
        cat_remplacement_arval: form.cat_remplacement_arval || null,
        moyenne_offres_ttc: num(form.moyenne_offres_ttc),
        valeur_actuelle_moy_ttc: num(form.valeur_actuelle_moy_ttc),
        augmentation_pourcent: form.augmentation_pourcent || null,
        categorie: form.categorie || null,
      };
      if (editing) await parcApi.updateReferentiel(editing.id, payload);
      else await parcApi.createReferentiel(payload);
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer ?')) return;
    try {
      await parcApi.removeReferentiel(id);
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
      await parcApi.importReferentiels(fd);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<Referentiel>[] = [
    { key: 'modele', header: 'modele', render: (r) => fmt(r.modele) },
    { key: 'duree', header: 'duree', render: (r) => fmt(r.duree) },
    { key: 'km', header: 'kilometrage', render: (r) => fmt(r.kilometrage) },
    {
      key: 'loyer_ht_ald',
      header: 'loyer_ht_ald',
      render: (r) => fmtNum(r.loyer_ht_ald),
    },
    {
      key: 'loyer_ttc_ald',
      header: 'loyer_ttc_ald',
      render: (r) => fmtNum(r.loyer_ttc_ald),
    },
    {
      key: 'km_supp_ald',
      header: 'km_supp_ht_ald',
      render: (r) => fmtNum(r.km_supp_ht_ald, 4),
    },
    { key: 'pneus_ald', header: 'nb_pneus_ald', render: (r) => fmt(r.nb_pneus_ald) },
    {
      key: 'cat_ald',
      header: 'cat_remplacement_ald',
      render: (r) => fmt(r.cat_remplacement_ald),
    },
    {
      key: 'loyer_ht_arval',
      header: 'loyer_ht_arval',
      render: (r) => fmtNum(r.loyer_ht_arval),
    },
    {
      key: 'loyer_ttc_arval',
      header: 'loyer_ttc_arval',
      render: (r) => fmtNum(r.loyer_ttc_arval),
    },
    {
      key: 'km_supp_arval',
      header: 'km_supp_ht_arval',
      render: (r) => fmtNum(r.km_supp_ht_arval, 4),
    },
    {
      key: 'pneus_arval',
      header: 'nb_pneus_arval',
      render: (r) => fmt(r.nb_pneus_arval),
    },
    {
      key: 'cat_arval',
      header: 'cat_remplacement_arval',
      render: (r) => fmt(r.cat_remplacement_arval),
    },
    {
      key: 'moyenne',
      header: 'moyenne_offres_ttc',
      render: (r) => fmtNum(r.moyenne_offres_ttc),
    },
    {
      key: 'valeur',
      header: 'valeur_actuelle_moy_ttc',
      render: (r) => fmtNum(r.valeur_actuelle_moy_ttc),
    },
    {
      key: 'aug',
      header: 'augmentation_pourcent',
      render: (r) => fmtPct(r.augmentation_pourcent),
    },
    { key: 'categorie', header: 'categorie', render: (r) => fmt(r.categorie) },
    {
      key: 'actions',
      header: 'Actions',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <a
            href="#view"
            onClick={(e) => {
              e.preventDefault();
              setDetail(r);
            }}
          >
            View
          </a>
          {' / '}
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
      <PageHeader
        title="Referentiels"
        subtitle="Consultez et gérez les informations du système."
      />
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
        <FileUploader label="Importer" accept=".xlsx,.xls,.csv" onChange={onImport} />
        <Button onClick={() => openForm()}>Ajouter</Button>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable columns={columns} rows={filtered} rowKey={(r) => r.id} loading={loading} />

      <Modal
        open={!!detail}
        title={`Référentiel #${detail?.id ?? ''}`}
        onClose={() => setDetail(null)}
        size="lg"
        footer={
          <Button variant="secondary" onClick={() => setDetail(null)}>
            Fermer
          </Button>
        }
      >
        {detail && (
          <table className="table table-striped">
            <tbody>
              {(
                [
                  ['Modèle', detail.modele],
                  ['Durée', detail.duree],
                  ['Kilométrage', detail.kilometrage],
                  ['Loyer HT ALD', detail.loyer_ht_ald],
                  ['Loyer TTC ALD', detail.loyer_ttc_ald],
                  ['Km supp HT ALD', detail.km_supp_ht_ald],
                  ['Nb pneus ALD', detail.nb_pneus_ald],
                  ['Cat. remplacement ALD', detail.cat_remplacement_ald],
                  ['Loyer HT Arval', detail.loyer_ht_arval],
                  ['Loyer TTC Arval', detail.loyer_ttc_arval],
                  ['Km supp HT Arval', detail.km_supp_ht_arval],
                  ['Nb pneus Arval', detail.nb_pneus_arval],
                  ['Cat. remplacement Arval', detail.cat_remplacement_arval],
                  ['Moyenne offres TTC', detail.moyenne_offres_ttc],
                  ['Valeur actuelle moy TTC', detail.valeur_actuelle_moy_ttc],
                  ['Augmentation %', detail.augmentation_pourcent],
                  ['Catégorie', detail.categorie],
                ] as [string, string | number | null | undefined][]
              ).map(([label, value]) => (
                <tr key={label}>
                  <th>{label}</th>
                  <td>{fmt(value)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </Modal>

      <Modal
        open={open}
        title={editing ? 'Éditer le référentiel' : 'Nouveau référentiel'}
        onClose={() => setOpen(false)}
        size="lg"
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="ref-form" type="submit" loading={saving}>
              Enregistrer
            </Button>
          </>
        }
      >
        <form id="ref-form" onSubmit={onSave} className="stack">
          <Input
            label="Modèle"
            value={form.modele}
            onChange={(e) => setForm((f) => ({ ...f, modele: e.target.value }))}
            required
          />
          <Input
            label="Durée"
            value={form.duree}
            onChange={(e) => setForm((f) => ({ ...f, duree: e.target.value }))}
          />
          <Input
            label="Kilométrage"
            value={form.kilometrage}
            onChange={(e) => setForm((f) => ({ ...f, kilometrage: e.target.value }))}
          />
          <Input
            label="Loyer HT ALD"
            type="number"
            step="0.01"
            value={form.loyer_ht_ald}
            onChange={(e) => setForm((f) => ({ ...f, loyer_ht_ald: e.target.value }))}
          />
          <Input
            label="Loyer TTC ALD"
            type="number"
            step="0.01"
            value={form.loyer_ttc_ald}
            onChange={(e) => setForm((f) => ({ ...f, loyer_ttc_ald: e.target.value }))}
          />
          <Input
            label="Km supp HT ALD"
            type="number"
            step="0.0001"
            value={form.km_supp_ht_ald}
            onChange={(e) => setForm((f) => ({ ...f, km_supp_ht_ald: e.target.value }))}
          />
          <Input
            label="Nb pneus ALD"
            type="number"
            value={form.nb_pneus_ald}
            onChange={(e) => setForm((f) => ({ ...f, nb_pneus_ald: e.target.value }))}
          />
          <Input
            label="Cat. remplacement ALD"
            value={form.cat_remplacement_ald}
            onChange={(e) =>
              setForm((f) => ({ ...f, cat_remplacement_ald: e.target.value }))
            }
          />
          <Input
            label="Loyer HT Arval"
            type="number"
            step="0.01"
            value={form.loyer_ht_arval}
            onChange={(e) => setForm((f) => ({ ...f, loyer_ht_arval: e.target.value }))}
          />
          <Input
            label="Loyer TTC Arval"
            type="number"
            step="0.01"
            value={form.loyer_ttc_arval}
            onChange={(e) => setForm((f) => ({ ...f, loyer_ttc_arval: e.target.value }))}
          />
          <Input
            label="Km supp HT Arval"
            type="number"
            step="0.0001"
            value={form.km_supp_ht_arval}
            onChange={(e) => setForm((f) => ({ ...f, km_supp_ht_arval: e.target.value }))}
          />
          <Input
            label="Nb pneus Arval"
            type="number"
            value={form.nb_pneus_arval}
            onChange={(e) => setForm((f) => ({ ...f, nb_pneus_arval: e.target.value }))}
          />
          <Input
            label="Cat. remplacement Arval"
            value={form.cat_remplacement_arval}
            onChange={(e) =>
              setForm((f) => ({ ...f, cat_remplacement_arval: e.target.value }))
            }
          />
          <Input
            label="Moyenne offres TTC"
            type="number"
            step="0.01"
            value={form.moyenne_offres_ttc}
            onChange={(e) =>
              setForm((f) => ({ ...f, moyenne_offres_ttc: e.target.value }))
            }
          />
          <Input
            label="Valeur actuelle moy TTC"
            type="number"
            step="0.01"
            value={form.valeur_actuelle_moy_ttc}
            onChange={(e) =>
              setForm((f) => ({ ...f, valeur_actuelle_moy_ttc: e.target.value }))
            }
          />
          <Input
            label="Augmentation %"
            value={form.augmentation_pourcent}
            onChange={(e) =>
              setForm((f) => ({ ...f, augmentation_pourcent: e.target.value }))
            }
          />
          <Input
            label="Catégorie"
            value={form.categorie}
            onChange={(e) => setForm((f) => ({ ...f, categorie: e.target.value }))}
          />
        </form>
      </Modal>
    </>
  );
}
