import type { TextareaHTMLAttributes } from 'react';

interface Props extends TextareaHTMLAttributes<HTMLTextAreaElement> {
  label?: string;
  error?: string;
}

/** Équivalent du textarea généré par le FormHelper CakePHP (div.input.textarea). */
export function Textarea({ label, error, id, className = '', ...rest }: Props) {
  const areaId = id || rest.name || label?.replace(/\s+/g, '-').toLowerCase();
  return (
    <>
      <div className={`input textarea ${className}`.trim()}>
        {label && <label htmlFor={areaId}>{label}</label>}
        <textarea
          id={areaId}
          {...rest}
          style={error ? { border: '1px solid #b80000', ...rest.style } : rest.style}
        />
      </div>
      {error && (
        <div className="message-error textarea-error" style={{ display: 'block' }}>
          {error}
        </div>
      )}
    </>
  );
}
