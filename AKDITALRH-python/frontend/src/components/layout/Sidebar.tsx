import { useEffect, useState, type ReactNode } from 'react';
import { Link, NavLink, useLocation } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';

type NavChild = { to: string; label: string; icon?: ReactNode };
type NavGroup = {
  id: string;
  label: string;
  icon: ReactNode;
  children: NavChild[];
  roles?: string[];
  hideForAgence?: boolean;
  agenceOnly?: boolean;
};

const iconDashboard = (
  <svg width="16" height="17" className="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M14.6667 7.5C14.6667 6.69333 14.1267 5.66 13.4667 5.2L9.34667 2.31333C8.41333 1.66 6.91333 1.69333 6.01333 2.39333L2.42 5.19333C1.82 5.66 1.33333 6.65333 1.33333 7.40666V12.3467C1.33333 13.8933 2.59333 15.16 4.14 15.16H11.86C13.4067 15.16 14.6667 13.8933 14.6667 12.3533V10.2867" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
    <path d="M8 12.4933V10.4933" stroke="white" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
  </svg>
);

const iconBillets = (
  <svg width="14" height="15" className="me-2" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g clipPath="url(#clip-billets)">
      <path d="M6.125 4V4.875H3.5V4H6.125ZM3.5 6.625V5.75H6.125V6.625H3.5ZM3.5 8.375V7.5H5.25V8.375H3.5ZM2.625 4V4.875H1.75V4H2.625ZM2.625 5.75V6.625H1.75V5.75H2.625ZM1.75 8.375V7.5H2.625V8.375H1.75ZM0.875 1.375V13.625H5.25V14.5H0V0.5H7.62207L11.375 4.25293V5.75H10.5V4.875H7V1.375H0.875ZM7.875 1.99707V4H9.87793L7.875 1.99707ZM12.25 7.5H14V14.5H6.125V7.5H7.875V6.625H8.75V7.5H11.375V6.625H12.25V7.5ZM13.125 13.625V10.125H7V13.625H13.125ZM13.125 9.25V8.375H7V9.25H13.125Z" fill="white" />
    </g>
    <defs>
      <clipPath id="clip-billets">
        <rect width="14" height="14" fill="white" transform="translate(0 0.5)" />
      </clipPath>
    </defs>
  </svg>
);

const iconHotels = (
  <svg width="14" height="15" className="me-2" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
    <g clipPath="url(#clip-hotels)">
      <path d="M6.125 4V4.875H3.5V4H6.125ZM3.5 6.625V5.75H6.125V6.625H3.5ZM3.5 8.375V7.5H5.25V8.375H3.5ZM2.625 4V4.875H1.75V4H2.625ZM2.625 5.75V6.625H1.75V5.75H2.625ZM1.75 8.375V7.5H2.625V8.375H1.75ZM0.875 1.375V13.625H5.25V14.5H0V0.5H7.62207L11.375 4.25293V5.75H10.5V4.875H7V1.375H0.875ZM7.875 1.99707V4H9.87793L7.875 1.99707ZM12.25 7.5H14V14.5H6.125V7.5H7.875V6.625H8.75V7.5H11.375V6.625H12.25V7.5ZM13.125 13.625V10.125H7V13.625H13.125ZM13.125 9.25V8.375H7V9.25H13.125Z" fill="white" />
    </g>
    <defs>
      <clipPath id="clip-hotels">
        <rect width="14" height="14" fill="white" transform="translate(0 0.5)" />
      </clipPath>
    </defs>
  </svg>
);

const iconParc = (
  <svg width="16" height="16" className="me-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M5 17a2 2 0 1 0 4 0 2 2 0 0 0-4 0Zm10 0a2 2 0 1 0 4 0 2 2 0 0 0-4 0ZM3 11l2-5h9l3 5v6H3v-6Zm3.5-3.5L5.2 11h11.1l-1.8-3.5H6.5Z" fill="white" />
  </svg>
);

const iconBuilding = (
  <svg width="16" height="17" className="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1.33333 13.1667C1.14445 13.1667 0.986223 13.1027 0.858668 12.9747C0.731112 12.8467 0.667112 12.6885 0.666668 12.5V3.83334C0.666668 3.64445 0.730668 3.48623 0.858668 3.35867C0.986668 3.23112 1.14489 3.16712 1.33333 3.16667C1.52178 3.16623 1.68022 3.23023 1.80867 3.35867C1.93711 3.48712 2.00089 3.64534 2 3.83334V9.83334H7.33333V5.83334C7.33333 5.46667 7.464 5.1529 7.72534 4.89201C7.98667 4.63112 8.30045 4.50045 8.66667 4.50001H12.6667C13.4 4.50001 14.0278 4.76112 14.55 5.28334C15.0722 5.80556 15.3333 6.43334 15.3333 7.16667V12.5C15.3333 12.6889 15.2693 12.8473 15.1413 12.9753C15.0133 13.1033 14.8551 13.1671 14.6667 13.1667C14.4782 13.1662 14.32 13.1022 14.192 12.9747C14.064 12.8471 14 12.6889 14 12.5V11.1667H2V12.5C2 12.6889 1.936 12.8473 1.808 12.9753C1.68 13.1033 1.52178 13.1671 1.33333 13.1667ZM4.66667 9.16667C4.11111 9.16667 3.63889 8.97223 3.25 8.58334C2.86111 8.19445 2.66667 7.72223 2.66667 7.16667C2.66667 6.61112 2.86111 6.1389 3.25 5.75001C3.63889 5.36112 4.11111 5.16667 4.66667 5.16667C5.22222 5.16667 5.69445 5.36112 6.08333 5.75001C6.47222 6.1389 6.66667 6.61112 6.66667 7.16667C6.66667 7.72223 6.47222 8.19445 6.08333 8.58334C5.69445 8.97223 5.22222 9.16667 4.66667 9.16667ZM8.66667 9.83334H14V7.16667C14 6.80001 13.8696 6.48623 13.6087 6.22534C13.3478 5.96445 13.0338 5.83379 12.6667 5.83334H8.66667V9.83334ZM4.66667 7.83334C4.85556 7.83334 5.014 7.76934 5.142 7.64134C5.27 7.51334 5.33378 7.35512 5.33333 7.16667C5.33289 6.97823 5.26889 6.82001 5.14133 6.69201C5.01378 6.56401 4.85556 6.50001 4.66667 6.50001C4.47778 6.50001 4.31956 6.56401 4.192 6.69201C4.06445 6.82001 4.00045 6.97823 4 7.16667C3.99956 7.35512 4.06356 7.51356 4.192 7.64201C4.32045 7.77045 4.47867 7.83423 4.66667 7.83334Z" fill="white" />
  </svg>
);

const iconParametres = (
  <svg width="16" height="17" className="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8 11C6.62 11 5.5 9.88 5.5 8.5C5.5 7.12 6.62 6 8 6C9.38 6 10.5 7.12 10.5 8.5C10.5 9.88 9.38 11 8 11ZM8 7C7.17333 7 6.5 7.67333 6.5 8.5C6.5 9.32667 7.17333 10 8 10C8.82667 10 9.5 9.32667 9.5 8.5C9.5 7.67333 8.82667 7 8 7Z" fill="white" />
    <path d="M10.14 15.2933C10 15.2933 9.86004 15.2733 9.72004 15.24C9.30671 15.1267 8.96004 14.8667 8.74004 14.5L8.66004 14.3667C8.26671 13.6867 7.72671 13.6867 7.33337 14.3667L7.26004 14.4933C7.04004 14.8667 6.69337 15.1333 6.28004 15.24C5.86004 15.3533 5.42671 15.2933 5.06004 15.0733L3.91337 14.4133C3.50671 14.18 3.21337 13.8 3.08671 13.34C2.96671 12.88 3.02671 12.4067 3.26004 12C3.45337 11.66 3.50671 11.3533 3.39337 11.16C3.28004 10.9667 2.99337 10.8533 2.60004 10.8533C1.62671 10.8533 0.833374 10.06 0.833374 9.08665V7.91332C0.833374 6.93999 1.62671 6.14665 2.60004 6.14665C2.99337 6.14665 3.28004 6.03332 3.39337 5.83999C3.50671 5.64665 3.46004 5.33999 3.26004 4.99999C3.02671 4.59332 2.96671 4.11332 3.08671 3.65999C3.20671 3.19999 3.50004 2.81999 3.91337 2.58665L5.06671 1.92665C5.82004 1.47999 6.81337 1.73999 7.26671 2.50665L7.34671 2.63999C7.74004 3.31999 8.28004 3.31999 8.67337 2.63999L8.74671 2.51332C9.20004 1.73999 10.1934 1.47999 10.9534 1.93332L12.1 2.59332C12.5067 2.82665 12.8 3.20665 12.9267 3.66665C13.0467 4.12665 12.9867 4.59999 12.7534 5.00665C12.56 5.34665 12.5067 5.65332 12.62 5.84665C12.7334 6.03999 13.02 6.15332 13.4134 6.15332C14.3867 6.15332 15.18 6.94665 15.18 7.91999V9.09332C15.18 10.0667 14.3867 10.86 13.4134 10.86C13.02 10.86 12.7334 10.9733 12.62 11.1667C12.5067 11.36 12.5534 11.6667 12.7534 12.0067C12.9867 12.4133 13.0534 12.8933 12.9267 13.3467C12.8067 13.8067 12.5134 14.1867 12.1 14.42L10.9467 15.08C10.6934 15.22 10.42 15.2933 10.14 15.2933ZM8.00004 12.8267C8.59337 12.8267 9.14671 13.2 9.52671 13.86L9.60004 13.9867C9.68004 14.1267 9.81337 14.2267 9.97337 14.2667C10.1334 14.3067 10.2934 14.2867 10.4267 14.2067L11.58 13.54C11.7534 13.44 11.8867 13.2733 11.94 13.0733C11.9934 12.8733 11.9667 12.6667 11.8667 12.4933C11.4867 11.84 11.44 11.1667 11.7334 10.6533C12.0267 10.14 12.6334 9.84665 13.3934 9.84665C13.82 9.84665 14.16 9.50665 14.16 9.07999V7.90665C14.16 7.48665 13.82 7.13999 13.3934 7.13999C12.6334 7.13999 12.0267 6.84665 11.7334 6.33332C11.44 5.81999 11.4867 5.14665 11.8667 4.49332C11.9667 4.31999 11.9934 4.11332 11.94 3.91332C11.8867 3.71332 11.76 3.55332 11.5867 3.44665L10.4334 2.78665C10.1467 2.61332 9.76671 2.71332 9.59337 3.00665L9.52004 3.13332C9.14004 3.79332 8.58671 4.16665 7.99337 4.16665C7.40004 4.16665 6.84671 3.79332 6.46671 3.13332L6.39337 2.99999C6.22671 2.71999 5.85337 2.61999 5.56671 2.78665L4.41337 3.45332C4.24004 3.55332 4.10671 3.71999 4.05337 3.91999C4.00004 4.11999 4.02671 4.32665 4.12671 4.49999C4.50671 5.15332 4.55337 5.82665 4.26004 6.33999C3.96671 6.85332 3.36004 7.14665 2.60004 7.14665C2.17337 7.14665 1.83337 7.48665 1.83337 7.91332V9.08665C1.83337 9.50665 2.17337 9.85332 2.60004 9.85332C3.36004 9.85332 3.96671 10.1467 4.26004 10.66C4.55337 11.1733 4.50671 11.8467 4.12671 12.5C4.02671 12.6733 4.00004 12.88 4.05337 13.08C4.10671 13.28 4.23337 13.44 4.40671 13.5467L5.56004 14.2067C5.70004 14.2933 5.86671 14.3133 6.02004 14.2733C6.18004 14.2333 6.31337 14.1267 6.40004 13.9867L6.47337 13.86C6.85337 13.2067 7.40671 12.8267 8.00004 12.8267Z" fill="white" />
  </svg>
);

/** Score de correspondance d’un lien avec l’URL courante (-1 = ne correspond pas). */
function matchScore(to: string, pathname: string, search: string): number {
  const [path, query = ''] = to.split('?');
  if (pathname !== path && !pathname.startsWith(`${path}/`)) return -1;
  const current = new URLSearchParams(search);
  const wanted = new URLSearchParams(query);
  for (const [key, value] of wanted) {
    if (current.get(key) !== value) return -1;
  }
  return path.length * 10 + [...wanted.keys()].length;
}

/** Un seul lien actif : celui qui correspond le plus précisément (ex. /vols/new plutôt que /vols). */
function activeChildTo(children: NavChild[], pathname: string, search: string): string | null {
  let best: string | null = null;
  let bestScore = -1;
  for (const c of children) {
    const score = matchScore(c.to, pathname, search);
    if (score > bestScore) {
      best = c.to;
      bestScore = score;
    }
  }
  return best;
}

function NavDropdown({
  group,
  open,
  onToggle,
}: {
  group: NavGroup;
  open: boolean;
  onToggle: () => void;
}) {
  const location = useLocation();
  const activeTo = activeChildTo(group.children, location.pathname, location.search);
  const childActive = activeTo !== null;

  return (
    <li className="nav-item dropdown">
      <button
        className={`nav-link dropdown-toggle ${childActive ? 'active' : ''}`}
        type="button"
        aria-expanded={open}
        onClick={onToggle}
      >
        {group.icon}
        {group.label}
      </button>
      <div className={`collapse ${open ? 'show' : ''}`}>
        <div className="dropdown-menu show">
          {group.children.map((child) => (
            <Link
              key={child.to}
              to={child.to}
              className={`dropdown-item ${child.to === activeTo ? 'active' : ''}`}
              aria-current={child.to === activeTo ? 'page' : undefined}
            >
              {child.icon}
              {child.label}
            </Link>
          ))}
        </div>
      </div>
    </li>
  );
}

export function Sidebar({ mobileOpen, onClose }: { mobileOpen?: boolean; onClose?: () => void }) {
  const { isAdmin, isAgence, logout } = useAuth();
  const location = useLocation();
  const [openId, setOpenId] = useState<string | null>(null);

  const groups: NavGroup[] = [];

  if (isAgence && !isAdmin) {
    groups.push({
      id: 'agence',
      label: 'Billets d’avion',
      icon: iconBillets,
      agenceOnly: true,
      children: [
        {
          to: '/agence/vols',
          label: 'Demandes en cours',
          icon: <i className="fa-solid fa-spinner me-2" />,
        },
        {
          to: '/agence/vols?etat=valide',
          label: 'Billets émis',
          icon: <i className="fa-regular fa-circle-check me-2" />,
        },
        {
          to: '/agence/vols?etat=annule',
          label: 'Demandes annulées',
          icon: <i className="fa-regular fa-circle-xmark me-2" />,
        },
      ],
    });
  }

  if (!isAgence || isAdmin) {
    groups.push({
      id: 'vols',
      label: 'Billets d’avion',
      icon: iconBillets,
      children: isAdmin
        ? [
            {
              to: '/vols/new',
              label: 'Demande de billet d’avion',
              icon: <i className="fa-regular fa-plus me-2" />,
            },
            {
              to: '/vols',
              label: 'Historique des billets d’avion',
              icon: <i className="fa-solid fa-ticket me-2" />,
            },
          ]
        : [
            {
              to: '/vols/new',
              label: 'Nouvelle demande',
              icon: <i className="fa-regular fa-plus me-2" />,
            },
            {
              to: '/vols',
              label: 'Mes billets',
              icon: <i className="fa-solid fa-ticket me-2" />,
            },
          ],
    });
    groups.push({
      id: 'hotels',
      label: 'Réservations d’hôtels',
      icon: iconHotels,
      children: isAdmin
        ? [
            {
              to: '/reservations/new',
              label: 'Demande d’hôtel',
              icon: <i className="fa-regular fa-plus me-2" />,
            },
            {
              to: '/reservations',
              label: 'Réservations d’hôtels',
              icon: <i className="fa-solid fa-bed me-2" />,
            },
          ]
        : [
            {
              to: '/reservations/new',
              label: 'Demande d’hôtel',
              icon: <i className="fa-regular fa-plus me-2" />,
            },
            {
              to: '/reservations',
              label: 'Mes demandes d’hôtel',
              icon: <i className="fa-solid fa-bed me-2" />,
            },
          ],
    });
  }

  if (isAdmin) {
    groups.push({
      id: 'parc',
      label: 'Parc automobile',
      icon: iconParc,
      children: [
        { to: '/parc/voitures', label: 'Liste des véhicules' },
        { to: '/parc/requetes', label: 'Requêtes collaborateurs' },
        { to: '/parc/calendrier', label: 'Calendrier contrats' },
        { to: '/carburant', label: 'Suivi des cartes carburant' },
        { to: '/jawaz', label: 'Suivi des Tag Jawaz' },
        { to: '/parc/referentiels', label: 'Référentiel de prix' },
        { to: '/parc/demandes', label: 'Demandes véhicules' },
      ],
    });
    groups.push({
      id: 'appartements',
      label: 'Appartements',
      icon: iconBuilding,
      children: [
        { to: '/logement/appartements', label: 'Disponibilités' },
        { to: '/logement/affectations', label: 'Affectations' },
        { to: '/logement/beneficiaires', label: 'Historique logements' },
      ],
    });
    groups.push({
      id: 'parametres',
      label: 'Paramètres',
      icon: iconParametres,
      children: [
        { to: '/admin/users', label: 'Gestion des utilisateurs' },
        { to: '/admin/roles', label: 'Gestion des rôles' },
        { to: '/admin/sites', label: 'Gestion des sites' },
        { to: '/admin/villes', label: 'Gestion des villes' },
        { to: '/hotels', label: 'Gestion des hôtels' },
      ],
    });
  }

  useEffect(() => {
    const match = groups.find(
      (g) => activeChildTo(g.children, location.pathname, location.search) !== null,
    );
    if (match) setOpenId(match.id);
    // eslint-disable-next-line react-hooks/exhaustive-deps -- groups rebuilt each render
  }, [location.pathname, location.search, isAdmin, isAgence]);

  return (
    <nav className={`sidebar ${mobileOpen ? 'show' : ''}`}>
      <div className="sidebar-logo">
        <div className="logo-circle">
          <img src="/img/svg/logo.svg" alt="Akdital" className="akdital_logo" />
        </div>
      </div>

      <div className="sidebar-nav">
        <ul className="nav flex-column">
          {(!isAgence || isAdmin) && (
            <li className="nav-item">
              <NavLink
                to="/dashboard"
                className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
                onClick={onClose}
              >
                {iconDashboard} Dashboard
              </NavLink>
            </li>
          )}

          {groups.map((g) => (
            <NavDropdown
              key={g.id}
              group={g}
              open={openId === g.id}
              onToggle={() => setOpenId((id) => (id === g.id ? null : g.id))}
            />
          ))}
        </ul>
      </div>

      <div className="sidebar-footer">
        <Link
          to="/login"
          className="logout-btn"
          onClick={async (e) => {
            e.preventDefault();
            await logout();
            window.location.href = '/login';
          }}
        >
          <i className="fa-solid fa-arrow-right-from-bracket" /> Se déconnecter
        </Link>
      </div>
    </nav>
  );
}
