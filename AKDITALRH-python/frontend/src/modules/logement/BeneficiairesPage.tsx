import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { logementApi, sitesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Beneficiaire, Site } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';
import './LogementPages.css';

export function BeneficiairesPage() {
  const [rows, setRows] = useState<Beneficiaire[]>([]);
  const [sites, setSites] = useState<Site[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [q, setQ] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const [siteFilter, setSiteFilter] = useState('');
  const [dateDebut, setDateDebut] = useState('');
  const [dateFin, setDateFin] = useState('');
  const [detail, setDetail] = useState<Beneficiaire | null>(null);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Beneficiaire | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    nom: '',
    mail: '',
    telephone: '',
    status: '',
    etat: 'En cours',
    note: '',
    date_debut: '',
    date_fin: '',
  });

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const params: Record<string, unknown> = {};
      if (q.trim()) params.q = q.trim();
      if (statusFilter) params.status = statusFilter;
      if (siteFilter) params.site_id = Number(siteFilter);
      if (dateDebut) params.date_debut = dateDebut;
      if (dateFin) params.date_fin = dateFin;
      const [b, s] = await Promise.all([
        logementApi.listBeneficiaires(params),
        sitesApi.list(),
      ]);
      setRows(unwrapList<Beneficiaire>(b.data));
      setSites(unwrapList<Site>(s.data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, [q, statusFilter, siteFilter, dateDebut, dateFin]);

  useEffect(() => {
    void load();
  }, [load]);

  const resetFilters = () => {
    setStatusFilter('');
    setSiteFilter('');
    setDateDebut('');
    setDateFin('');
    setQ('');
  };

  const openForm = (item?: Beneficiaire) => {
    setEditing(item || null);
    setForm({
      nom: item?.nom || '',
      mail: item?.mail || '',
      telephone: item?.telephone || '',
      status: item?.status || '',
      etat: item?.etat || 'En cours',
      note: item?.note || '',
      date_debut: item?.date_debut?.slice(0, 10) || item?.date_entree?.slice(0, 10) || '',
      date_fin: item?.date_fin?.slice(0, 10) || item?.date_sortie?.slice(0, 10) || '',
    });
    setOpen(true);
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    if (!editing) return;
    setSaving(true);
    try {
      await logementApi.updateBeneficiaire(editing.id, {
        nom: form.nom,
        mail: form.mail,
        telephone: form.telephone,
        status: form.status,
        etat: form.etat,
        note: form.note,
        date_debut: form.date_debut,
        date_fin: form.date_fin,
      });
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) return;
    try {
      await logementApi.removeBeneficiaire(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const siteOptions = useMemo(
    () => sites.map((s) => ({ value: String(s.id), label: s.site })),
    [sites],
  );

  const columns: Column<Beneficiaire>[] = [
    {
      key: 'site',
      header: 'Site',
      render: (r) =>
        r.site?.id || r.site_id ? (
          <EntityLink to={`/sites/${r.site?.id || r.site_id}`}>
            {r.site?.site || `#${r.site_id}`}
          </EntityLink>
        ) : (
          '—'
        ),
    },
    {
      key: 'affecte',
      header: 'Affecté par',
      render: (r) =>
        r.user?.id || r.user_id ? (
          <EntityLink to={`/users/${r.user?.id || r.user_id}`}>
            {r.user?.nom || `#${r.user_id}`}
          </EntityLink>
        ) : (
          '—'
        ),
    },
    {
      key: 'appart',
      header: 'Appartement',
      render: (r) => r.appartement?.nom || r.appartement_id || '—',
    },
    { key: 'nom', header: 'Nom', render: (r) => r.nom || '—' },
    { key: 'email', header: 'Email', render: (r) => r.mail || '—' },
    { key: 'tel', header: 'Téléphone', render: (r) => r.telephone || '—' },
    { key: 'status', header: 'Statut', render: (r) => r.status || '—' },
    {
      key: 'etat',
      header: 'État',
      render: (r) => <StatusBadge status={r.etat || r.statut} />,
    },
    {
      key: 'dates',
      header: 'Date',
      render: (r) => (
        <div>
          <span>{r.date_debut?.slice(0, 10) || '—'}</span>{' '}
          <i className="fa-solid fa-arrow-right" />{' '}
          <span>{r.date_fin?.slice(0, 10) || '—'}</span>
        </div>
      ),
    },
    { key: 'note', header: 'Note', render: (r) => r.note || '—' },
    {
      key: 'created',
      header: "Date d'ajout",
      render: (r) => (r.created ? r.created.replace('T', ' ').slice(0, 19) : '—'),
    },
    {
      key: 'actions',
      header: 'Actions',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <a
            href="#voir"
            onClick={(e) => {
              e.preventDefault();
              setDetail(r);
            }}
          >
            Voir
          </a>
          {' / '}
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

  return (
    <>
      <PageHeader
        title="Historique logements"
        subtitle="Consultez, modifiez ou terminez les affectations de logement des collaborateurs."
      />

      <div className="search-section w-100">
        <div className="d-flex align-items-center justify-content-between flex-wrap w-100">
          <div className="input-group mb-3" style={{ maxWidth: '40%' }}>
            <span className="input-group-text">
              <i className="fa-solid fa-magnifying-glass" />
            </span>
            <input
              type="text"
              className="form-control"
              placeholder="Rechercher"
              value={q}
              onChange={(e) => setQ(e.target.value)}
            />
            <button
              type="button"
              className="btn btn-primary-rounded"
              onClick={() => void load()}
            >
              <i className="fa-solid fa-magnifying-glass" /> Rechercher
            </button>
          </div>
          <div className="mb-3">
            <Link
              to="/logement/affectations"
              className="btn redirect-btn-rounded add-index-button"
            >
              <i className="fa-solid fa-paper-plane" /> Ajouter une affectation
            </Link>
          </div>
        </div>
      </div>

      <div className="filters-container mb-4">
        <div className="filter-group">
          <label className="filter-group-label">Statut</label>
          <select
            className="filter-dropdown"
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value)}
          >
            <option value="">Tous</option>
            <option value="Stagiaire">Stagiaire</option>
            <option value="Employe">Employé</option>
            <option value="Externe">Externe</option>
            <option value="Nouvelle recrue">Nouvelle recrue</option>
            <option value="Autre">Autre</option>
          </select>
        </div>
        <div className="filter-group">
          <label className="filter-group-label">Site d&apos;affectation</label>
          <select
            className="filter-dropdown"
            value={siteFilter}
            onChange={(e) => setSiteFilter(e.target.value)}
          >
            <option value="">Choisir un site</option>
            {siteOptions.map((o) => (
              <option key={o.value} value={o.value}>
                {o.label}
              </option>
            ))}
          </select>
        </div>
        <div className="filter-group">
          <label className="filter-group-label">Date d&apos;arrivée</label>
          <input
            type="date"
            className="filter-dropdown date-field"
            value={dateDebut}
            onChange={(e) => setDateDebut(e.target.value)}
          />
        </div>
        <div className="filter-group">
          <label className="filter-group-label">Date de départ</label>
          <input
            type="date"
            className="filter-dropdown date-field"
            value={dateFin}
            onChange={(e) => setDateFin(e.target.value)}
          />
        </div>
        <button
          type="button"
          className="reset-filters-button"
          onClick={resetFilters}
        >
          Réinitialiser les filtres
          <i className="fa-solid fa-arrow-rotate-left" />
        </button>
      </div>

      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable columns={columns} rows={rows} rowKey={(r) => r.id} loading={loading} />

      <Modal
        open={!!detail}
        title={`Affectation #${detail?.id ?? ''}`}
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
                <th>Site</th>
                <td>{detail.site?.site || '—'}</td>
              </tr>
              <tr>
                <th>Affecté par</th>
                <td>{detail.user?.nom || '—'}</td>
              </tr>
              <tr>
                <th>Appartement</th>
                <td>{detail.appartement?.nom || '—'}</td>
              </tr>
              <tr>
                <th>Nom</th>
                <td>{detail.nom || '—'}</td>
              </tr>
              <tr>
                <th>Email</th>
                <td>{detail.mail || '—'}</td>
              </tr>
              <tr>
                <th>Téléphone</th>
                <td>{detail.telephone || '—'}</td>
              </tr>
              <tr>
                <th>Statut</th>
                <td>{detail.status || '—'}</td>
              </tr>
              <tr>
                <th>État</th>
                <td>
                  <StatusBadge status={detail.etat} />
                </td>
              </tr>
              <tr>
                <th>Période</th>
                <td>
                  {detail.date_debut?.slice(0, 10) || '—'} →{' '}
                  {detail.date_fin?.slice(0, 10) || '—'}
                </td>
              </tr>
              <tr>
                <th>Note</th>
                <td>{detail.note || '—'}</td>
              </tr>
            </tbody>
          </table>
        )}
      </Modal>

      <Modal
        open={open}
        title="Modifier l’affectation"
        onClose={() => setOpen(false)}
        size="lg"
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="benef-edit-form" type="submit" loading={saving}>
              Enregistrer
            </Button>
          </>
        }
      >
        <form id="benef-edit-form" onSubmit={onSave} className="stack">
          <Input
            label="Nom"
            value={form.nom}
            onChange={(e) => setForm((f) => ({ ...f, nom: e.target.value }))}
          />
          <Input
            label="Email"
            value={form.mail}
            onChange={(e) => setForm((f) => ({ ...f, mail: e.target.value }))}
          />
          <Input
            label="Téléphone"
            value={form.telephone}
            onChange={(e) => setForm((f) => ({ ...f, telephone: e.target.value }))}
          />
          <Select
            label="Statut"
            value={form.status}
            onChange={(e) => setForm((f) => ({ ...f, status: e.target.value }))}
            placeholder="Choisissez…"
            options={[
              { value: 'Stagiaire', label: 'Stagiaire' },
              { value: 'Employe', label: 'Employé' },
              { value: 'Externe', label: 'Externe' },
              { value: 'Nouvelle recrue', label: 'Nouvelle recrue' },
              { value: 'Autre', label: 'Autre' },
            ]}
          />
          <Select
            label="État"
            value={form.etat}
            onChange={(e) => setForm((f) => ({ ...f, etat: e.target.value }))}
            options={[
              { value: 'En cours', label: 'En cours' },
              { value: 'Valider', label: 'Valider' },
              { value: 'Checkin', label: 'Checkin' },
              { value: 'Checkout', label: 'Checkout' },
              { value: 'Annuler', label: 'Annuler' },
            ]}
          />
          <Input
            label="Date début"
            type="date"
            value={form.date_debut}
            onChange={(e) => setForm((f) => ({ ...f, date_debut: e.target.value }))}
          />
          <Input
            label="Date fin"
            type="date"
            value={form.date_fin}
            onChange={(e) => setForm((f) => ({ ...f, date_fin: e.target.value }))}
          />
          <Input
            label="Note"
            value={form.note}
            onChange={(e) => setForm((f) => ({ ...f, note: e.target.value }))}
          />
        </form>
      </Modal>
    </>
  );
}
