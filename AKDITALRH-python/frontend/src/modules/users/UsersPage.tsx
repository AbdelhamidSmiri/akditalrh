import { useCallback, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { usersApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { User } from '../../types';
import { DataTable, type Column } from '../../components/ui/DataTable';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { StatusBadge } from '../../components/ui/StatusBadge';
import { PageHeader } from '../../components/layout/PageHeader';
import { EntityLink } from '../../components/ui/EntityLink';
import { useAuth } from '../../hooks/useAuth';

export function UsersPage() {
  const { isAdmin } = useAuth();
  const [rows, setRows] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const load = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const { data } = await usersApi.list();
      setRows(unwrapList<User>(data));
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const onDelete = async (id: number) => {
    if (!confirm('Supprimer cet utilisateur ?')) return;
    try {
      await usersApi.remove(id);
      await load();
    } catch (err) {
      setError(getErrorMessage(err));
    }
  };

  const columns: Column<User>[] = [
    { key: 'id', header: 'ID', render: (r) => r.id },
    {
      key: 'nom',
      header: 'Nom',
      render: (r) => (
        <EntityLink to={`/users/${r.id}`}>
          {[r.prenom, r.nom].filter(Boolean).join(' ') || r.username || `#${r.id}`}
        </EntityLink>
      ),
    },
    { key: 'username', header: 'Identifiant', render: (r) => r.username },
    {
      key: 'role',
      header: 'Rôle',
      render: (r) => r.role?.role || r.role_id,
    },
    {
      key: 'site',
      header: 'Site',
      render: (r) =>
        r.site_id ? (
          <EntityLink to={`/sites/${r.site_id}`}>
            {r.site?.site || String(r.site_id)}
          </EntityLink>
        ) : (
          '—'
        ),
    },
    {
      key: 'etat',
      header: 'État',
      render: (r) => <StatusBadge status={r.etat || r.statut} />,
    },
    {
      key: 'actions',
      header: 'Action',
      className: 'actions',
      render: (r) => (
        <span className="actions" onClick={(e) => e.stopPropagation()}>
          <Link to={`/users/${r.id}`}>Voir</Link>
          {' / '}
          <Link to={`/admin/users/${r.id}/edit`}>Éditer</Link>
          {isAdmin && (
            <>
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
            </>
          )}
        </span>
      ),
    },
  ];

  return (
    <>
      <PageHeader
        title="Gestion des utilisateurs"
        subtitle="Consultez et gérez les comptes collaborateurs."
      />
      <div className="page-toolbar">
        <Link to="/admin/users/new">
          <Button>Ajouter</Button>
        </Link>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <DataTable columns={columns} rows={rows} rowKey={(r) => r.id} loading={loading} />
    </>
  );
}
