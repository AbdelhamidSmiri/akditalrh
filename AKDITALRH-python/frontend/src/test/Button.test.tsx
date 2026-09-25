import { describe, expect, it, vi } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import { Button } from '../components/ui/Button';

describe('Button', () => {
  it('rend le label et gère le clic', () => {
    const onClick = vi.fn();
    render(
      <Button variant="primary" onClick={onClick}>
        Enregistrer
      </Button>,
    );
    fireEvent.click(screen.getByRole('button', { name: 'Enregistrer' }));
    expect(onClick).toHaveBeenCalledOnce();
  });

  it('désactive pendant loading', () => {
    render(
      <Button loading variant="primary">
        Save
      </Button>,
    );
    const btn = screen.getByRole('button');
    expect(btn).toBeDisabled();
    expect(btn).toHaveTextContent('…');
  });

  it('applique la variante danger', () => {
    render(<Button variant="danger">Supprimer</Button>);
    expect(screen.getByRole('button')).toHaveClass('btn-primary-rounded');
  });
});
