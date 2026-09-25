import { useRef, useState, type ChangeEvent } from 'react';
import { Button } from './Button';
import './FileUploader.css';

interface Props {
  label?: string;
  accept?: string;
  multiple?: boolean;
  onChange: (files: FileList | null) => void;
  hint?: string;
}

export function FileUploader({
  label = 'Choisir un fichier',
  accept,
  multiple,
  onChange,
  hint,
}: Props) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [names, setNames] = useState<string[]>([]);

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files;
    setNames(files ? Array.from(files).map((f) => f.name) : []);
    onChange(files);
  };

  return (
    <div className="file-uploader">
      <input
        ref={inputRef}
        type="file"
        accept={accept}
        multiple={multiple}
        onChange={handleChange}
        className="file-uploader-input"
      />
      <Button type="button" variant="secondary" onClick={() => inputRef.current?.click()}>
        {label}
      </Button>
      {names.length > 0 && (
        <ul className="file-uploader-list">
          {names.map((n) => (
            <li key={n} className="file-uploader-name">
              <i className="fa-solid fa-paperclip" /> {n}
            </li>
          ))}
        </ul>
      )}
      {hint && <span className="field-hint">{hint}</span>}
    </div>
  );
}
