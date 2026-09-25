import { useState } from 'react';

interface Props {
  onSearch: (term: string) => void;
  className?: string;
}

/** Barre de recherche Cake (#search_input + bouton Rechercher, filtre au clic ou sur Entrée). */
export function SearchBar({ onSearch, className = 'col-md-5' }: Props) {
  const [value, setValue] = useState('');

  const submit = () => {
    const term = value.trim();
    setValue(term);
    onSearch(term.toLowerCase());
  };

  return (
    <div className={className}>
      <div className="search-section">
        <div className="input-group mb-3">
          <span className="input-group-text">
            <i className="fa-solid fa-magnifying-glass" />
          </span>
          <input
            type="text"
            className="form-control"
            id="search_input"
            placeholder="Rechercher"
            aria-label="Search"
            value={value}
            onChange={(e) => setValue(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === 'Enter') {
                e.preventDefault();
                submit();
              }
            }}
            onBlur={() => setValue((v) => v.trim())}
          />
          <button type="button" className="btn btn-primary-rounded search-btn" onClick={submit}>
            <i className="fa-solid fa-magnifying-glass" /> Rechercher
          </button>
        </div>
      </div>
    </div>
  );
}

export function matchesSearch(term: string, ...values: unknown[]): boolean {
  if (!term) return true;
  return values
    .map((v) => (v == null ? '' : String(v)))
    .join(' ')
    .toLowerCase()
    .includes(term);
}
