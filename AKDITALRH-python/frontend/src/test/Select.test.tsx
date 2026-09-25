import { describe, expect, it } from 'vitest';
import { render, screen } from '@testing-library/react';
import { Select } from '../components/ui/Select';

describe('Select', () => {
  it('rend les options et le label', () => {
    render(
      <Select
        label="Ville"
        options={[
          { value: '1', label: 'Casablanca' },
          { value: '2', label: 'Rabat' },
        ]}
        value="1"
        onChange={() => undefined}
      />,
    );
    expect(screen.getByText('Ville')).toBeInTheDocument();
    expect(screen.getByDisplayValue('Casablanca')).toBeInTheDocument();
    expect(screen.getByRole('combobox')).toBeInTheDocument();
  });
});
