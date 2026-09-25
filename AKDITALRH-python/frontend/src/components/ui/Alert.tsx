import type { ReactNode } from 'react';

interface Props {
  variant?: 'danger' | 'success' | 'info' | 'warning';
  children: ReactNode;
}

export function Alert({ variant = 'info', children }: Props) {
  const map = {
    danger: 'danger',
    success: 'success',
    info: 'info',
    warning: 'info',
  } as const;
  return (
    <div className={`alert-akdital ${map[variant]}`} role="alert">
      {children}
    </div>
  );
}
