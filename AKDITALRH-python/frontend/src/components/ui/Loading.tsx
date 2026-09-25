import './Loading.css';

interface Props {
  label?: string;
  fullPage?: boolean;
}

export function Loading({ label = 'Chargement…', fullPage }: Props) {
  return (
    <div className={`loading ${fullPage ? 'loading-full' : ''}`}>
      <div className="loading-spinner" aria-hidden />
      <span>{label}</span>
    </div>
  );
}
