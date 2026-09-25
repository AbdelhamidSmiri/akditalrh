import type { InputHTMLAttributes } from 'react';

interface Props extends InputHTMLAttributes<HTMLInputElement> {
  label?: string;
  error?: string;
}

const FLOATING_TYPES = new Set(['text', 'password', 'tel', 'email', 'search', '']);

export function Input({ label, error, id, className = '', ...rest }: Props) {
  const inputId = id || rest.name || label?.replace(/\s+/g, '-').toLowerCase();
  const type = rest.type || 'text';
  const useFloating = FLOATING_TYPES.has(type);
  const wrapClass =
    type === 'password' ? 'password' : type === 'tel' ? 'tel' : 'text';

  if (!useFloating) {
    return (
      <div className={`field-static ${className}`.trim()}>
        {label && (
          <label htmlFor={inputId} className="title-section-form">
            {label}
          </label>
        )}
        <input id={inputId} {...rest} />
        {error && (
          <div className="message-error" style={{ display: 'block' }}>
            {error}
          </div>
        )}
      </div>
    );
  }

  return (
    <>
      <div className={`input ${wrapClass} ${className}`.trim()}>
        {label && <label htmlFor={inputId}>{label}</label>}
        <input
          id={inputId}
          {...rest}
          type={type === 'password' || type === 'tel' ? type : 'text'}
          inputMode={type === 'email' ? 'email' : rest.inputMode}
          placeholder={rest.placeholder || ' '}
          style={error ? { border: '1px solid #b80000', ...rest.style } : rest.style}
        />
      </div>
      {error && (
        <div className="message-error" style={{ display: 'block' }}>
          {error}
        </div>
      )}
    </>
  );
}
