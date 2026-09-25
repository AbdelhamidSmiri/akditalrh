import type { ReactNode } from 'react';
import { Link } from 'react-router-dom';
import './EntityLink.css';

interface Props {
  to: string;
  children: ReactNode;
  className?: string;
}

/** Lien bleu souligné style Cake (user_id / site_id dans les tableaux). */
export function EntityLink({ to, children, className = '' }: Props) {
  return (
    <Link to={to} className={`entity-link ${className}`.trim()}>
      {children}
    </Link>
  );
}
