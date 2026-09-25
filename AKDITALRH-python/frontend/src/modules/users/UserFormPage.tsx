import { FormEvent, useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { usersApi, rolesApi, sitesApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Role, Site } from '../../types';
import { Card } from '../../components/ui/Card';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { Button } from '../../components/ui/Button';
import { Alert } from '../../components/ui/Alert';
import { PageHeader } from '../../components/layout/PageHeader';

export function UserFormPage() {
  const { id } = useParams();
  const isNew = !id || id === 'new';
  const navigate = useNavigate();
  const [roles, setRoles] = useState<Role[]>([]);
  const [sites, setSites] = useState<Site[]>([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [form, setForm] = useState({
    username: '',
    password: '',
    nom: '',
    prenom: '',
    tel: '',
    poste: '',
    direction: '',
    role_id: '',
    site_id: '',
    etat: 'Actif',
  });

  useEffect(() => {
    void (async () => {
      try {
        const [r, s] = await Promise.all([rolesApi.list(), sitesApi.list()]);
        setRoles(unwrapList<Role>(r.data));
        setSites(unwrapList<Site>(s.data));
        if (!isNew && id) {
          const { data } = await usersApi.get(id);
          setForm({
            username: data.username || '',
            password: '',
            nom: data.nom || '',
            prenom: data.prenom || '',
            tel: data.tel || '',
            poste: data.poste || '',
            direction: data.direction || '',
            role_id: String(data.role_id || ''),
            site_id: String(data.site_id || ''),
            etat: data.etat || 'Actif',
          });
        }
      } catch (err) {
        setError(getErrorMessage(err));
      }
    })();
  }, [id, isNew]);

  const set = (key: string, value: string) =>
    setForm((f) => ({ ...f, [key]: value }));

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    const payload = {
      username: form.username,
      nom: form.nom,
      prenom: form.prenom,
      tel: form.tel,
      poste: form.poste,
      direction: form.direction,
      role_id: Number(form.role_id),
      site_id: Number(form.site_id),
      etat: form.etat,
      ...(form.password ? { password: form.password } : {}),
    };
    try {
      if (isNew) {
        await usersApi.create(payload);
      } else if (id) {
        await usersApi.update(id, payload);
      }
      navigate('/admin/users');
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <PageHeader
        title={isNew ? 'Nouvel utilisateur' : 'Éditer l’utilisateur'}
        subtitle="Renseignez les informations du compte collaborateur."
      />
      <div className="page-toolbar">
        <Link to="/admin/users">
          <Button variant="secondary">Retour</Button>
        </Link>
      </div>
      {error && <Alert variant="danger">{error}</Alert>}
      <Card>
        <form onSubmit={onSubmit}>
          <div className="form-grid">
            <Input
              label="Identifiant"
              value={form.username}
              onChange={(e) => set('username', e.target.value)}
              required
            />
            <Input
              label={isNew ? 'Mot de passe' : 'Nouveau mot de passe'}
              type="password"
              value={form.password}
              onChange={(e) => set('password', e.target.value)}
              required={isNew}
            />
            <Input label="Prénom" value={form.prenom} onChange={(e) => set('prenom', e.target.value)} />
            <Input label="Nom" value={form.nom} onChange={(e) => set('nom', e.target.value)} />
            <Input label="Téléphone" value={form.tel} onChange={(e) => set('tel', e.target.value)} />
            <Input label="Poste" value={form.poste} onChange={(e) => set('poste', e.target.value)} />
            <Input
              label="Direction"
              value={form.direction}
              onChange={(e) => set('direction', e.target.value)}
            />
            <Select
              label="Rôle"
              value={form.role_id}
              onChange={(e) => set('role_id', e.target.value)}
              required
              placeholder="Sélectionner…"
              options={roles.map((r) => ({ value: r.id, label: r.role }))}
            />
            <Select
              label="Site"
              value={form.site_id}
              onChange={(e) => set('site_id', e.target.value)}
              required
              placeholder="Sélectionner…"
              options={sites.map((s) => ({ value: s.id, label: s.site }))}
            />
            <Select
              label="État"
              value={form.etat}
              onChange={(e) => set('etat', e.target.value)}
              options={[
                { value: 'Actif', label: 'Actif' },
                { value: 'Inactif', label: 'Inactif' },
              ]}
            />
          </div>
          <div className="form-actions">
            <Button type="submit" loading={loading}>
              Enregistrer
            </Button>
            <Button type="button" variant="secondary" onClick={() => navigate('/admin/users')}>
              Annuler
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
}
