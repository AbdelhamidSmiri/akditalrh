import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { jawazApi, sitesApi, usersApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Site, TagJawaz, User } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { FileUploader } from '../../components/ui/FileUploader';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';

function userNom(r: TagJawaz) {
  return r.user?.nom || (r.user_id ? `#${r.user_id}` : '—');
}

function siteLabel(r: TagJawaz) {
  return r.site?.site || (r.site_id ? String(r.site_id) : '—');
}

export function TagJawazPage() {
  const [rows, setRows] = useState<TagJawaz[]>([]);
  const [sites, setSites] = useState<Site[]>([]);
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [q, setQ] = useState('');
  const [open, setOpen] = useState(false);
  const [detail, setDetail] = useState<TagJawaz | null>(null);
  const [editing, setEditing] = useState<TagJawaz | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    user_id: '',
    site_id: '',
    ref_jawaz: '',
  });

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const [t, s, u] = await Promise.all([
        jawazApi.list(),
        sitesApi.list(),
        usersApi.list(),
      ]);
      setRows(unwrapList<TagJawaz>(t.data));
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
      [userNom(r), siteLabel(r), r.ref_jawaz]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()
        .includes(s),
    );
  }, [rows, q]);

  const openForm = (item?: TagJawaz) => {
    setEditing(item || null);
    setForm({
      user_id: item?.user_id ? String(item.user_id) : '',
      site_id: item?.site_id ? String(item.site_id) : '',
      ref_jawaz: item?.ref_jawaz || item?.numero || '',
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
        ref_jawaz: form.ref_jawaz,
      };
      if (editing) await jawazApi.update(editing.id, payload);
      else await jawazApi.create(payload);
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer ce TAG ?')) return;
    try {
      await jawazApi.remove(id);
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
      await jawazApi.import(fd);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<TagJawaz>[] = [
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
    { key: 'ref', header: 'Ref jawaz', render: (r) => r.ref_jawaz || '—' },
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
        title="Tagjawazs"
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
        title={`TAG #${detail?.id ?? ''}`}
        onClose={() => setDetail(null)}
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
                <th>Ref jawaz</th>
                <td>{detail.ref_jawaz || '—'}</td>
              </tr>
            </tbody>
          </table>
        )}
      </Modal>

      <Modal
        open={open}
        title={editing ? 'Éditer le TAG' : 'Nouveau TAG'}
        onClose={() => setOpen(false)}
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="jawaz-form" type="submit" loading={saving}>
              Enregistrer
            </Button>
          </>
        }
      >
        <form id="jawaz-form" onSubmit={onSave} className="stack">
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
            label="Ref jawaz"
            value={form.ref_jawaz}
            onChange={(e) => setForm((f) => ({ ...f, ref_jawaz: e.target.value }))}
            required
          />
        </form>
      </Modal>
    </>
  );
}
