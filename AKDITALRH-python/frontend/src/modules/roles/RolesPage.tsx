import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { rolesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Role } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { SearchBar, matchesSearch } from '../../components/ui/SearchBar';
import { flash } from '../../components/ui/Flash';
import { PageHeader } from '../../components/layout/PageHeader';

type FormErrors = { role?: string; plafond_hotel?: string };

export function RolesPage() {
  const [rows, setRows] = useState<Role[]>([]);
  const [loading, setLoading] = useState(true);
  const [term, setTerm] = useState('');
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Role | null>(null);
  const [viewing, setViewing] = useState<Role | null>(null);
  const [role, setRole] = useState('');
  const [plafond, setPlafond] = useState('');
  const [errors, setErrors] = useState<FormErrors>({});
  const [saving, setSaving] = useState(false);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const { data } = await rolesApi.list();
      setRows(unwrapList<Role>(data));
    } catch (err) {
      flash.error(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const filtered = useMemo(
    () => rows.filter((r) => matchesSearch(term, r.role, r.plafond_hotel)),
    [rows, term],
  );

  const openCreate = () => {
    setEditing(null);
    setRole('');
    setPlafond('');
    setErrors({});
    setOpen(true);
  };

  const openEdit = (r: Role) => {
    setEditing(r);
    setRole(r.role || '');
    setPlafond(r.plafond_hotel ?? '');
    setErrors({});
    setOpen(true);
  };

  const validate = (): FormErrors => {
    const errs: FormErrors = {};
    if (!role.trim()) errs.role = 'Veuillez saisir un nom de rôle.';
    if (!plafond.trim() || Number.isNaN(Number(plafond))) {
      errs.plafond_hotel = 'Veuillez saisir un montant valide.';
    }
    return errs;
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length) return;
    setSaving(true);
    try {
      const payload = { role: role.trim(), plafond_hotel: plafond.trim() };
      if (editing) await rolesApi.update(editing.id, payload);
      else await rolesApi.create(payload);
      flash.success('Le rôle a été enregistré.');
      setOpen(false);
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "Le rôle n'a pas pu être enregistré. Veuillez réessayer."));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (r: Role) => {
    if (!confirm('Voulez-vous vraiment supprimer ce rôle?')) return;
    try {
      await rolesApi.remove(r.id);
      flash.success('Le rôle a été supprimé.');
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "Le rôle n'a pas pu être supprimé. Veuillez réessayer."));
    }
  };

  const columns: Column<Role>[] = [
    { key: 'role', header: 'Rôle', render: (r) => r.role },
    { key: 'plafond', header: 'Plafond Hôtel', render: (r) => r.plafond_hotel ?? '' },
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
              setViewing(r);
            }}
          >
            Voir
          </a>{' '}
          /{' '}
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
      <PageHeader title="Gestion des Rôles" subtitle=" " />
      <div className="row align-items-start">
        <SearchBar onSearch={setTerm} />
        <div className="col text-end">
          <Button onClick={openCreate}>
            <i className="fa-solid fa-plus" /> Ajouter un rôle
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
        open={Boolean(viewing)}
        title="Détails du rôle"
        onClose={() => setViewing(null)}
        footer={
          <Button variant="secondary" onClick={() => setViewing(null)}>
            Fermer
          </Button>
        }
      >
        {viewing && (
          <div className="card view-card">
            <div className="card-body">
              <div className="row row-gap-3">
                <div className="col-md-6">
                  <div className="info">
                    <label>Rôle</label>
                    <span>{viewing.role}</span>
                  </div>
                </div>
                <div className="col-md-6">
                  <div className="info">
                    <label>Plafond hôtel</label>
                    <span>{viewing.plafond_hotel ?? ''}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}
      </Modal>

      <Modal
        open={open}
        title={editing ? 'Modifier un rôle' : 'Ajouter un rôle'}
        onClose={() => setOpen(false)}
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="role-form" type="submit" loading={saving}>
              <i className="fa-solid fa-paper-plane" /> Enregistrer
            </Button>
          </>
        }
      >
        <form id="role-form" onSubmit={onSave} noValidate className="cake-form">
          <Input
            label="Rôle"
            name="role"
            value={role}
            onChange={(e) => setRole(e.target.value)}
            error={errors.role}
          />
          <Input
            label="Plafond Hôtel"
            name="plafond_hotel"
            inputMode="decimal"
            value={plafond}
            onChange={(e) => setPlafond(e.target.value)}
            error={errors.plafond_hotel}
          />
        </form>
      </Modal>
    </>
  );
}
