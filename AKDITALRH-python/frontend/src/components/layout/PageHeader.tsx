import { useAuth } from '../../hooks/useAuth';

interface Props {
  title: string;
  subtitle?: string;
}

export function PageHeader({ title, subtitle }: Props) {
  const { user } = useAuth();
  const nom = user?.nom || '';
  const prenom = user?.prenom || '';
  const initials =
    `${(nom || 'U').charAt(0)}${(prenom || 'N').charAt(0)}`.toUpperCase() || 'UN';
  const role = user?.role?.role || user?.role_name || '';

  return (
    <div className="page-header">
      <div className="profile-header">
        <div className="profile-card">
          <div className="d-flex align-items-center names_profile">
            <div className="profile-avatar me-2">{initials}</div>
            <div className="profile-infos">
              <small className="name-profile">
                {nom} {prenom}
              </small>
              <small className="role-profile">{role}</small>
            </div>
          </div>
        </div>
        <div className="ms-2">
          <h2 className="title-page">{title}</h2>
          <p className="slogan">
            {subtitle || 'Consultez et gérez les informations du système.'}
          </p>
        </div>
      </div>
    </div>
  );
}
