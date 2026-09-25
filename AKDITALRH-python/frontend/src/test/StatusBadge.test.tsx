import { describe, expect, it } from 'vitest';
import { render, screen } from '@testing-library/react';
import { StatusBadge } from '../components/ui/StatusBadge';

describe('StatusBadge', () => {
  it('affiche En cours', () => {
    render(<StatusBadge status="En cours" />);
    expect(screen.getByText(/En cours/)).toBeInTheDocument();
    expect(document.querySelector('.status-btn.in-progress')).toBeTruthy();
  });

  it('affiche Validé comme Confirmée (vert)', () => {
    render(<StatusBadge status="Validé" />);
    expect(screen.getByText(/Confirmée/)).toBeInTheDocument();
    expect(document.querySelector('.status-btn.confirmed')).toBeTruthy();
  });

  it('affiche Rejeté', () => {
    render(<StatusBadge status="Rejeté" />);
    expect(document.querySelector('.status-btn.refused')).toBeTruthy();
  });

  it('gère status vide', () => {
    render(<StatusBadge status={null} />);
    expect(screen.getByText('—')).toBeInTheDocument();
  });
});
