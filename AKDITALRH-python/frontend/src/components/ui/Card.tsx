import type { ReactNode, CSSProperties } from 'react';
import './Card.css';

interface Props {
  title?: string;
  actions?: ReactNode;
  children: ReactNode;
  className?: string;
  style?: CSSProperties;
}

/** Carte UI React — classes préfixées pour ne pas écraser Bootstrap `.card` (dashboard Cake). */
export function Card({ title, actions, children, className = '', style }: Props) {
  return (
    <section className={`ui-card ${className}`.trim()} style={style}>
      {(title || actions) && (
        <header className="ui-card-header">
          {title && <h3>{title}</h3>}
          {actions && <div className="ui-card-header-actions">{actions}</div>}
        </header>
      )}
      <div className="ui-card-body">{children}</div>
    </section>
  );
}
