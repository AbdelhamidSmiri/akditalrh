import { describe, expect, it } from 'vitest';
import { render, screen } from '@testing-library/react';
import { Alert } from '../components/ui/Alert';
import { Loading } from '../components/ui/Loading';
import { Pagination } from '../components/ui/Pagination';

describe('Alert', () => {
  it('affiche le message', () => {
    render(<Alert variant="danger">Erreur serveur</Alert>);
    expect(screen.getByText('Erreur serveur')).toBeInTheDocument();
  });
});

describe('Loading', () => {
  it('affiche le label', () => {
    render(<Loading label="Chargement…" />);
    expect(screen.getByText('Chargement…')).toBeInTheDocument();
  });
});

describe('Pagination', () => {
  it('affiche la page courante', () => {
    render(
      <Pagination page={2} totalPages={5} onChange={() => undefined} />,
    );
    expect(screen.getByText(/2/)).toBeInTheDocument();
  });
});
