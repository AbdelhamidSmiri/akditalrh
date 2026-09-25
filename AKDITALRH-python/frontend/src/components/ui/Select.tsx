import type { SelectHTMLAttributes, ReactNode, ChangeEvent } from 'react';

interface Option {
  value: string | number;
  label: string;
}

interface Props extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'children'> {
  label?: string;
  options?: Option[];
  placeholder?: string;
  children?: ReactNode;
}

export function Select({
  label,
  id,
  options,
  placeholder,
  children,
  className = '',
  value,
  onChange,
  ...rest
}: Props) {
  const selectId = id || rest.name || label?.replace(/\s+/g, '-').toLowerCase();

  const handleChange = (e: ChangeEvent<HTMLSelectElement>) => {
    onChange?.(e);
  };

  return (
    <div className={`field-select ${className}`.trim()}>
      {label && (
        <label htmlFor={selectId} className="title-section-form">
          {label}
        </label>
      )}
      <div className="select">
        <select id={selectId} value={value} onChange={handleChange} {...rest}>
          {placeholder !== undefined && (
            <option value="">{placeholder || '—'}</option>
          )}
          {options
            ? options.map((o) => (
                <option key={String(o.value)} value={o.value}>
                  {o.label}
                </option>
              ))
            : children}
        </select>
      </div>
    </div>
  );
}
