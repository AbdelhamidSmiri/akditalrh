import { useAuth } from '../../hooks/useAuth';
import { Button } from '../ui/Button';
import './Topbar.css';

interface Props {
  title?: string;
  onMenuClick?: () => void;
}

export function Topbar({ title, onMenuClick }: Props) {
  const { user, logout } = useAuth();
  const displayName =
    [user?.prenom, user?.nom].filter(Boolean).join(' ') ||
    user?.username ||
    'Utilisateur';
  const role = user?.role_name || user?.role?.role || '';

  return (
    <header className="topbar">
      <div className="topbar-left">
        {onMenuClick && (
          <button type="button" className="topbar-menu" onClick={onMenuClick}>
            ☰
          </button>
        )}
        {title && <h1 className="topbar-title">{title}</h1>}
      </div>
      <div className="topbar-right">
        <div className="topbar-user">
          <strong>{displayName}</strong>
          {role && <span>{role}</span>}
        </div>
        <Button variant="secondary" size="sm" onClick={() => void logout()}>
          Déconnexion
        </Button>
      </div>
    </header>
  );
}
