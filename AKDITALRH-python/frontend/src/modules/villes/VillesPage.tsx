import { FormEvent, useCallback, useEffect, useMemo, useState } from 'react';
import { villesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Ville } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Modal } from '../../components/ui/Modal';
import { Input } from '../../components/ui/Input';
import { SearchBar, matchesSearch } from '../../components/ui/SearchBar';
import { flash } from '../../components/ui/Flash';
import { PageHeader } from '../../components/layout/PageHeader';

export function VillesPage() {
  const [rows, setRows] = useState<Ville[]>([]);
  const [loading, setLoading] = useState(true);
  const [term, setTerm] = useState('');
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Ville | null>(null);
  const [viewing, setViewing] = useState<Ville | null>(null);
  const [ville, setVille] = useState('');
  const [villeError, setVilleError] = useState('');
  const [saving, setSaving] = useState(false);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const { data } = await villesApi.list();
      setRows(unwrapList<Ville>(data));
    } catch (err) {
      flash.error(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const filtered = useMemo(() => rows.filter((r) => matchesSearch(term, r.ville)), [rows, term]);

  const openCreate = () => {
    setEditing(null);
    setVille('');
    setVilleError('');
    setOpen(true);
  };

  const openEdit = (v: Ville) => {
    setEditing(v);
    setVille(v.ville);
    setVilleError('');
    setOpen(true);
  };

  const onSave = async (e: FormEvent) => {
    e.preventDefault();
    if (ville.trim().length < 2) {
      setVilleError('Le nom de la ville est obligatoire');
      return;
    }
    setVilleError('');
    setSaving(true);
    try {
      if (editing) await villesApi.update(editing.id, { ville: ville.trim() });
      else await villesApi.create({ ville: ville.trim() });
      flash.success('La ville a été enregistrée.');
      setOpen(false);
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "La ville n'a pas pu être enregistrée."));
    } finally {
      setSaving(false);
    }
  };

  const onDelete = async (v: Ville) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette ville ?')) return;
    try {
      await villesApi.remove(v.id);
      flash.success('La ville a été supprimée.');
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "La ville n'a pas pu être supprimée."));
    }
  };

  const columns: Column<Ville>[] = [
    { key: 'ville', header: 'ville', render: (r) => r.ville },
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
      <PageHeader title="Liste des Villes" subtitle=" " />
      <div className="row align-items-start">
        <SearchBar onSearch={setTerm} />
        <div className="col text-end">
          <Button onClick={openCreate}>
            <i className="fa-solid fa-plus" /> Ajouter une ville
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
        title="Détails de la ville"
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
              <div className="info">
                <label>Ville</label>
                <span>{viewing.ville}</span>
              </div>
            </div>
          </div>
        )}
      </Modal>

      <Modal
        open={open}
        title={editing ? 'Modifier la ville' : 'Ajouter une ville'}
        onClose={() => setOpen(false)}
        footer={
          <>
            <Button variant="secondary" onClick={() => setOpen(false)}>
              Annuler
            </Button>
            <Button form="ville-form" type="submit" loading={saving}>
              <i className="fa-solid fa-paper-plane" /> Enregistrer
            </Button>
          </>
        }
      >
        <form id="ville-form" onSubmit={onSave} noValidate className="cake-form">
          <Input
            label="Nom de la ville"
            name="ville"
            value={ville}
            onChange={(e) => setVille(e.target.value)}
            error={villeError}
          />
        </form>
      </Modal>
    </>
  );
}
