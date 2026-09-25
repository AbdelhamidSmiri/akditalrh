import { FormEvent, useCallback, useEffect, useState } from 'react';
import { parcApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { DemandeVoiture, Voiture } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';

export function DemandesVoituresPage() {
  const [rows, setRows] = useState<DemandeVoiture[]>([]);
  const [voitures, setVoitures] = useState<Voiture[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    voiture_id: '',
    date_debut: '',
    date_fin: '',
    motif: '',
  });

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const [d, v] = await Promise.all([
        parcApi.listDemandes(),
        parcApi.listVoitures(),
      ]);
      setRows(unwrapList<DemandeVoiture>(d.data));
      setVoitures(unwrapList<Voiture>(v.data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      await parcApi.createDemande({
        voiture_id: form.voiture_id ? Number(form.voiture_id) : null,
        date_debut: form.date_debut,
        date_fin: form.date_fin,
        motif: form.motif,
      });
      setOpen(false);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  const onTransition = async (id: number, etat: string) => {
    try {
      await parcApi.transitionDemande(id, etat);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer cette demande ?')) return;
    try {
      await parcApi.removeDemande(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<DemandeVoiture>[] = [
    {
      key: 'demandeur',
      header: 'Demandeur',
      render: (r) => {
        const label = r.user
          ? [r.user.prenom, r.user.nom].filter(Boolean).join(' ') || r.user.username
          : r.user_id
            ? `#${r.user_id}`
            : '—';
        const uid = r.user?.id || r.user_id;
        return uid ? <EntityLink to={`/users/${uid}`}>{label}</EntityLink> : label;
      },
    },
    {
      key: 'voiture',
      header: 'Véhicule',
      render: (r) => r.voiture?.immatriculation || r.voiture_id || '—',
    },
    {
      key: 'periode',
      header: 'Période',
      render: (r) =>
        `${r.date_debut?.slice(0, 10) || '—'} → ${r.date_fin?.slice(0, 10) || '—'}`,
    },
    {
      key: 'statut',
      header: 'Statut',
      render: (r) => <StatusBadge status={r.statut || r.etat} />,
    },
    {
      key: 'actions',
      header: 'Action',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <a
            href="#valider"
            onClick={(e) => {
              e.preventDefault();
              void onTransition(r.id, 'Validé');
            }}
          >
            Valider
          </a>
          {' · '}
          <a
            href="#rejeter"
            onClick={(e) => {
              e.preventDefault();
              void onTransition(r.id, 'Rejeté');
            }}
          >
            Rejeter
          </a>
          {' · '}
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
        title="Demandes véhicules"
        subtitle="Gérez les demandes d’affectation de véhicules."
      />
      <div className="page-toolbar">
        <Button onClick={() => setOpen(true)}>Nouvelle demande</Button>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable columns={columns} rows={rows} rowKey={(r) => r.id} loading={loading} />
      <Modal
        open={open}
        title="Nouvelle demande"
        onClose={() => setOpen(false)}
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="demande-form" type="submit" loading={saving}>
              Envoyer
            </Button>
          </>
        }
      >
        <form id="demande-form" onSubmit={onSave} className="stack">
          <Select
            label="Véhicule (optionnel)"
            value={form.voiture_id}
            onChange={(e) => setForm((f) => ({ ...f, voiture_id: e.target.value }))}
            placeholder="À assigner"
            options={voitures.map((v) => ({
              value: v.id,
              label: v.immatriculation || `#${v.id}`,
            }))}
          />
          <Input
            label="Date début"
            type="date"
            value={form.date_debut}
            onChange={(e) => setForm((f) => ({ ...f, date_debut: e.target.value }))}
            required
          />
          <Input
            label="Date fin"
            type="date"
            value={form.date_fin}
            onChange={(e) => setForm((f) => ({ ...f, date_fin: e.target.value }))}
            required
          />
          <Input
            label="Motif"
            value={form.motif}
            onChange={(e) => setForm((f) => ({ ...f, motif: e.target.value }))}
          />
        </form>
      </Modal>
    </>
  );
}
