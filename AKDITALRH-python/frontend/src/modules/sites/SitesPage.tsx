import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { sitesApi, villesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Site, Ville } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { SearchBar, matchesSearch } from '../../components/ui/SearchBar';
import { flash } from '../../components/ui/Flash';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';

type SiteForm = { site: string; ville_id: string; adresse: string; telephone: string; mail: string };
type SiteErrors = Partial<Record<keyof SiteForm, string>>;

const EMPTY_FORM: SiteForm = { site: '', ville_id: '', adresse: '', telephone: '', mail: '' };
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_RE = /^[0-9]{10}$/;

export function SitesPage() {
  const [rows, setRows] = useState<Site[]>([]);
  const [villes, setVilles] = useState<Ville[]>([]);
  const [loading, setLoading] = useState(true);
  const [term, setTerm] = useState('');
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Site | null>(null);
  const [form, setForm] = useState<SiteForm>(EMPTY_FORM);
  const [errors, setErrors] = useState<SiteErrors>({});
  const [saving, setSaving] = useState(false);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const [s, v] = await Promise.all([sitesApi.list(), villesApi.list()]);
      setRows(unwrapList<Site>(s.data));
      setVilles(unwrapList<Ville>(v.data));
    } catch (err) {
      flash.error(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const villeName = useCallback(
    (s: Site) => s.ville?.ville || villes.find((v) => v.id === s.ville_id)?.ville || '',
    [villes],
  );

  const filtered = useMemo(
    () =>
      rows.filter((r) =>
        matchesSearch(term, villeName(r), r.site, r.adresse, r.telephone, r.mail, r.created),
      ),
    [rows, term, villeName],
  );

  const set = (key: keyof SiteForm) => (value: string) => setForm((f) => ({ ...f, [key]: value }));

  const openCreate = () => {
    setEditing(null);
    setForm(EMPTY_FORM);
    setErrors({});
    setOpen(true);
  };

  const openEdit = (s: Site) => {
    setEditing(s);
    setForm({
      site: s.site || '',
      ville_id: s.ville_id ? String(s.ville_id) : '',
      adresse: s.adresse || '',
      telephone: s.telephone || '',
      mail: s.mail || '',
    });
    setErrors({});
    setOpen(true);
  };

  const validate = (): SiteErrors => {
    const errs: SiteErrors = {};
    if (!form.ville_id) errs.ville_id = 'Sélectionnez une ville.';
    if (!form.site.trim()) errs.site = 'Le nom du site est requis.';
    if (!form.adresse.trim()) errs.adresse = "L'adresse complète est obligatoire.";
    if (!PHONE_RE.test(form.telephone.replace(/\s/g, ''))) {
      errs.telephone = 'Un numéro de téléphone valide est requis.';
    }
    if (!EMAIL_RE.test(form.mail.trim())) errs.mail = 'Veuillez entrer une adresse email valide.';
    return errs;
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;
    setSaving(true);
    try {
      const payload = {
        site: form.site.trim(),
        ville_id: Number(form.ville_id),
        adresse: form.adresse.trim(),
        telephone: form.telephone.trim(),
        mail: form.mail.trim(),
      };
      if (editing) await sitesApi.update(editing.id, payload);
      else await sitesApi.create(payload);
      flash.success('Le site a été enregistré.');
      setOpen(false);
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "Le site n'a pas pu être enregistré."));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (s: Site) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce site ?')) return;
    try {
      await sitesApi.remove(s.id);
      flash.success('Le site a été supprimé.');
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "Le site n'a pas pu être supprimé."));
    }
  };

  const columns: Column<Site>[] = [
    { key: 'ville', header: 'Ville', render: (r) => villeName(r) },
    {
      key: 'site',
      header: 'Site',
      render: (r) => <EntityLink to={`/sites/${r.id}`}>{r.site}</EntityLink>,
    },
    { key: 'adresse', header: 'Adresse', render: (r) => r.adresse || '' },
    { key: 'telephone', header: 'Téléphone', render: (r) => r.telephone || '' },
    { key: 'mail', header: 'Mail', render: (r) => r.mail || '' },
    {
      key: 'created',
      header: 'Créé le',
      render: (r) => (r.created ? r.created.replace('T', ' ').slice(0, 19) : ''),
    },
    {
      key: 'actions',
      header: 'Actions',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <Link to={`/sites/${r.id}`}>Voir</Link> /{' '}
          <a
            href="#modifier"
            onClick={(e) => {
              e.preventDefault();
              openEdit(r);
            }}
          >
            Modifier
          </a>{' '}
          /{' '}
          <a
            href="#supprimer"
            onClick={(e) => {
              e.preventDefault();
              void onDelete(r);
            }}
          >
            Supprimer
          </a>
        </span>
      ),
    },
  ];

  return (
    <>
      <PageHeader title="Liste des Sites" subtitle=" " />
      <div className="row align-items-start">
        <SearchBar onSearch={setTerm} />
        <div className="col text-end">
          <Button onClick={openCreate}>
            <i className="fa-solid fa-plus" /> Ajouter un site
          </Button>
        </div>
      </div>
      <DataTable
        columns={columns}
        rows={filtered}
        rowKey={(r) => r.id}
        loading={loading}
        emptyMessage="Aucune donnée disponible"
      />
      <Modal
        open={open}
        title={editing ? 'Modifier le site' : 'Ajouter un site'}
        onClose={() => setOpen(false)}
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="site-form" type="submit" loading={saving}>
              <i className="fa-solid fa-paper-plane" /> Enregistrer
            </Button>
          </>
        }
      >
        <form id="site-form" onSubmit={onSave} noValidate className="cake-form">
          <Select
            label="Ville"
            name="ville_id"
            value={form.ville_id}
            onChange={(e) => set('ville_id')(e.target.value)}
            placeholder="Sélectionner une ville"
            options={villes.map((v) => ({ value: v.id, label: v.ville }))}
            style={errors.ville_id ? { border: '1px solid #b80000' } : undefined}
          />
          {errors.ville_id && (
            <div className="message-error" style={{ display: 'block' }}>
              {errors.ville_id}
            </div>
          )}
          <Input
            label="Site"
            name="site"
            value={form.site}
            onChange={(e) => set('site')(e.target.value)}
            error={errors.site}
          />
          <Input
            label="Adresse"
            name="adresse"
            value={form.adresse}
            onChange={(e) => set('adresse')(e.target.value)}
            error={errors.adresse}
          />
          <Input
            label="Téléphone"
            name="telephone"
            type="tel"
            value={form.telephone}
            onChange={(e) => set('telephone')(e.target.value)}
            error={errors.telephone}
          />
          <Input
            label="Email"
            name="mail"
            type="email"
            value={form.mail}
            onChange={(e) => set('mail')(e.target.value)}
            error={errors.mail}
          />
        </form>
      </Modal>
    </>
  );
}
