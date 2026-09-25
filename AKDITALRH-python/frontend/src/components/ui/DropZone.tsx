import { useRef, type DragEvent } from 'react';
import './DropZone.css';

function formatSize(bytes: number) {
  if (bytes < 1024) return `${bytes} o`;
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} Ko`;
  return `${(bytes / (1024 * 1024)).toFixed(1)} Mo`;
}

export interface DropZoneProps {
  label?: string;
  description?: string;
  accept?: string;
  multiple?: boolean;
  files: File[];
  onChange: (files: File[]) => void;
  error?: boolean;
  className?: string;
}

/** Zone d’upload alignée Cake (input_file.js + akdital_style.css). */
export function DropZone({
  label,
  description,
  accept = '.jpg,.jpeg,.png,.pdf',
  multiple = true,
  files,
  onChange,
  error,
  className = '',
}: DropZoneProps) {
  const inputRef = useRef<HTMLInputElement>(null);

  const openPicker = () => inputRef.current?.click();

  const mergeFiles = (incoming: FileList | File[] | null) => {
    if (!incoming) return;
    const next = Array.from(incoming);
    if (!next.length) return;
    if (multiple) {
      const map = new Map<string, File>();
      for (const f of [...files, ...next]) {
        map.set(`${f.name}-${f.size}-${f.lastModified}`, f);
      }
      onChange(Array.from(map.values()));
    } else {
      onChange(next.slice(0, 1));
    }
    // permet de re-sélectionner le même fichier
    if (inputRef.current) inputRef.current.value = '';
  };

  const removeAt = (index: number) => {
    onChange(files.filter((_, i) => i !== index));
  };

  const onDragOver = (e: DragEvent) => {
    e.preventDefault();
    e.currentTarget.classList.add('dragover');
  };

  const onDragLeave = (e: DragEvent) => {
    e.currentTarget.classList.remove('dragover');
  };

  const onDrop = (e: DragEvent) => {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
    mergeFiles(e.dataTransfer.files);
  };

  return (
    <div className={`mb-4 input-file drop-zone ${className}`.trim()}>
      {label && <label className="drop-zone-label">{label}</label>}
      <div className="file-upload-wrapper">
        <div
          className="file-upload-area"
          style={error ? { border: '2px dashed #b80000' } : undefined}
          onClick={(e) => {
            // ne pas double-ouvrir si clic sur le bouton (géré à part)
            if ((e.target as HTMLElement).closest('.choose-files-btn')) return;
            openPicker();
          }}
          onDragOver={onDragOver}
          onDragLeave={onDragLeave}
          onDrop={onDrop}
        >
          <div className="upload-text">Glissez-déposez les fichiers ici</div>
          <div className="upload-subtext">Ou</div>
          <button
            type="button"
            className="choose-files-btn"
            onClick={(e) => {
              e.preventDefault();
              e.stopPropagation();
              openPicker();
            }}
          >
            Choisir des fichiers <i className="fa-solid fa-cloud-arrow-up" />
          </button>
          <input
            ref={inputRef}
            type="file"
            className="file-input drop-zone-input"
            accept={accept}
            multiple={multiple}
            onChange={(e) => mergeFiles(e.target.files)}
          />
        </div>

        <div className={`file-info ${files.length ? 'show' : ''}`}>
          <div className="files-list">
            {files.map((f, i) => (
              <div key={`${f.name}-${f.size}-${f.lastModified}-${i}`} className="file-item">
                <div>
                  <div className="file-name">{f.name}</div>
                  <div className="file-size">{formatSize(f.size)}</div>
                </div>
                <button
                  type="button"
                  className="remove-file"
                  aria-label={`Retirer ${f.name}`}
                  onClick={() => removeAt(i)}
                >
                  ×
                </button>
              </div>
            ))}
          </div>
        </div>
      </div>
      {description && <div className="description-text">{description}</div>}
    </div>
  );
}

export async function uploadFiles(
  files: File[],
  folder: string,
  upload: (fd: FormData) => Promise<{ data: unknown }>,
): Promise<string[]> {
  const names: string[] = [];
  for (const file of files) {
    const fd = new FormData();
    fd.append('file', file);
    fd.append('folder', folder);
    const { data } = await upload(fd);
    const name =
      (data as { filename?: string; name?: string })?.filename ||
      (data as { name?: string })?.name ||
      file.name;
    names.push(name);
  }
  return names;
}
