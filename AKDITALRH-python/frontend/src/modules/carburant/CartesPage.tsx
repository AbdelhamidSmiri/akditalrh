import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { carburantApi, sitesApi, usersApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { CarteCarburant, Site, User } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { FileUploader } from '../../components/ui/FileUploader';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';

function fmt(v: string | number | null | undefined) {
  if (v === null || v === undefined || v === '') return '—';
  return String(v);
}

function userNom(r: CarteCarburant) {
  return r.user?.nom || (r.user_id ? `#${r.user_id}` : '—');
}

function siteLabel(r: CarteCarburant) {
  return r.site?.site || (r.site_id ? String(r.site_id) : '—');
}

export function CartesPage() {
  const [rows, setRows] = useState<CarteCarburant[]>([]);
  const [sites, setSites] = useState<Site[]>([]);
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [q, setQ] = useState('');
  const [open, setOpen] = useState(false);
  const [detail, setDetail] = useState<CarteCarburant | null>(null);
  const [editing, setEditing] = useState<CarteCarburant | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    user_id: '',
    site_id: '',
    prestataire: 'AFRIQUIA',
    identifiant: '',
    carte_labelle: '',
    utilisateur: '',
    nv_plafond: '',
    type_mt: '',
    status: '',
  });

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const [c, s, u] = await Promise.all([
        carburantApi.list(),
        sitesApi.list(),
        usersApi.list(),
      ]);
      setRows(unwrapList<CarteCarburant>(c.data));
      setSites(unwrapList<Site>(s.data));
      setUsers(unwrapList<User>(u.data));
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
        userNom(r),
        siteLabel(r),
        r.prestataire,
        r.identifiant,
        r.carte_labelle,
        r.utilisateur,
        r.type_mt,
        r.status,
      ]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(s),
    );
  }, [rows, q]);

  const openForm = (item?: CarteCarburant) => {
    setEditing(item || null);
    setForm({
      user_id: item?.user_id ? String(item.user_id) : '',
      site_id: item?.site_id ? String(item.site_id) : '',
      prestataire: item?.prestataire || 'AFRIQUIA',
      identifiant: item?.identifiant || item?.numero || '',
      carte_labelle: item?.carte_labelle || '',
      utilisateur: item?.utilisateur || '',
      nv_plafond:
        item?.nv_plafond != null
          ? String(item.nv_plafond)
          : item?.plafond || '',
      type_mt: item?.type_mt || '',
      status: item?.status || item?.statut || item?.etat || '',
    });
    setOpen(true);
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = {
        user_id: form.user_id ? Number(form.user_id) : null,
        site_id: form.site_id ? Number(form.site_id) : 0,
        prestataire: form.prestataire,
        identifiant: form.identifiant,
        carte_labelle: form.carte_labelle,
        utilisateur: form.utilisateur,
        nv_plafond: form.nv_plafond ? Number(form.nv_plafond) : null,
        type_mt: form.type_mt || null,
        status: form.status || null,
      };
      if (editing) await carburantApi.update(editing.id, payload);
      else await carburantApi.create(payload);
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer cette carte ?')) return;
    try {
      await carburantApi.remove(id);
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
      await carburantApi.import(fd);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<CarteCarburant>[] = [
    {
      key: 'user',
      header: 'Utilisateur',
      render: (r) =>
        r.user?.id ? (
          <EntityLink to={`/users/${r.user.id}`}>{userNom(r)}</EntityLink>
        ) : (
          userNom(r)
        ),
    },
    {
      key: 'site',
      header: 'Site',
      render: (r) =>
        r.site?.id || r.site_id ? (
          <EntityLink to={`/sites/${r.site?.id || r.site_id}`}>{siteLabel(r)}</EntityLink>
        ) : (
          siteLabel(r)
        ),
    },
    { key: 'prestataire', header: 'Prestataire', render: (r) => fmt(r.prestataire) },
    { key: 'identifiant', header: 'Identifiant', render: (r) => fmt(r.identifiant) },
    { key: 'labelle', header: 'Carte labelle', render: (r) => fmt(r.carte_labelle) },
    { key: 'utilisateur', header: 'Utilisateur carte', render: (r) => fmt(r.utilisateur) },
    {
      key: 'plafond',
      header: 'Nv plafond',
      render: (r) =>
        r.nv_plafond != null
          ? Number(r.nv_plafond).toFixed(2)
          : '—',
    },
    { key: 'type_mt', header: 'Type mt', render: (r) => fmt(r.type_mt) },
    { key: 'status', header: 'Status', render: (r) => fmt(r.status) },
    {
      key: 'created',
      header: 'Created',
      render: (r) => (r.created ? r.created.replace('T', ' ').slice(0, 19) : '—'),
    },
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
        title="Cartecarburants"
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
        title={`Carte #${detail?.id ?? ''}`}
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
              <tr>
                <th>Utilisateur</th>
                <td>{userNom(detail)}</td>
              </tr>
              <tr>
                <th>Site</th>
                <td>{siteLabel(detail)}</td>
              </tr>
              <tr>
                <th>Prestataire</th>
                <td>{fmt(detail.prestataire)}</td>
              </tr>
              <tr>
                <th>Identifiant</th>
                <td>{fmt(detail.identifiant)}</td>
              </tr>
              <tr>
                <th>Carte labelle</th>
                <td>{fmt(detail.carte_labelle)}</td>
              </tr>
              <tr>
                <th>Utilisateur carte</th>
                <td>{fmt(detail.utilisateur)}</td>
              </tr>
              <tr>
                <th>Nv plafond</th>
                <td>
                  {detail.nv_plafond != null
                    ? Number(detail.nv_plafond).toFixed(2)
                    : '—'}
                </td>
              </tr>
              <tr>
                <th>Type mt</th>
                <td>{fmt(detail.type_mt)}</td>
              </tr>
              <tr>
                <th>Status</th>
                <td>{fmt(detail.status)}</td>
              </tr>
            </tbody>
          </table>
        )}
      </Modal>

      <Modal
        open={open}
        title={editing ? 'Éditer la carte' : 'Nouvelle carte'}
        onClose={() => setOpen(false)}
        size="lg"
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="carte-form" type="submit" loading={saving}>
              Enregistrer
            </Button>
          </>
        }
      >
        <form id="carte-form" onSubmit={onSave} className="stack">
          <Select
            label="Utilisateur"
            value={form.user_id}
            onChange={(e) => setForm((f) => ({ ...f, user_id: e.target.value }))}
            placeholder="Sélectionner…"
            options={users.map((u) => ({
              value: u.id,
              label: [u.nom, u.prenom].filter(Boolean).join(' ') || u.username || `#${u.id}`,
            }))}
          />
          <Select
            label="Site"
            value={form.site_id}
            onChange={(e) => setForm((f) => ({ ...f, site_id: e.target.value }))}
            placeholder="Sélectionner…"
            options={sites.map((s) => ({ value: s.id, label: s.site }))}
          />
          <Input
            label="Prestataire"
            value={form.prestataire}
            onChange={(e) => setForm((f) => ({ ...f, prestataire: e.target.value }))}
          />
          <Input
            label="Identifiant"
            value={form.identifiant}
            onChange={(e) => setForm((f) => ({ ...f, identifiant: e.target.value }))}
            required
          />
          <Input
            label="Carte labelle"
            value={form.carte_labelle}
            onChange={(e) => setForm((f) => ({ ...f, carte_labelle: e.target.value }))}
          />
          <Input
            label="Utilisateur (libellé carte)"
            value={form.utilisateur}
            onChange={(e) => setForm((f) => ({ ...f, utilisateur: e.target.value }))}
          />
          <Input
            label="Nv plafond"
            type="number"
            step="0.01"
            value={form.nv_plafond}
            onChange={(e) => setForm((f) => ({ ...f, nv_plafond: e.target.value }))}
          />
          <Input
            label="Type mt"
            value={form.type_mt}
            onChange={(e) => setForm((f) => ({ ...f, type_mt: e.target.value }))}
          />
          <Input
            label="Status"
            value={form.status}
            onChange={(e) => setForm((f) => ({ ...f, status: e.target.value }))}
          />
        </form>
      </Modal>
    </>
  );
}
