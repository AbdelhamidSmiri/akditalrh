import { useEffect, useState } from 'react';
import './Flash.css';

type FlashType = 'success' | 'error' | 'warning' | 'info';

interface FlashItem {
  id: number;
  type: FlashType;
  message: string;
  timeout: number;
}

const EVENT = 'akdital:flash';
let seq = 0;

function emit(type: FlashType, message: string, timeout = 5000) {
  window.dispatchEvent(
    new CustomEvent<FlashItem>(EVENT, { detail: { id: ++seq, type, message, timeout } }),
  );
}

export const flash = {
  success: (message: string, timeout?: number) => emit('success', message, timeout),
  error: (message: string, timeout?: number) => emit('error', message, timeout),
  warning: (message: string, timeout?: number) => emit('warning', message, timeout),
  info: (message: string, timeout?: number) => emit('info', message, timeout),
};

export function FlashContainer() {
  const [items, setItems] = useState<FlashItem[]>([]);

  useEffect(() => {
    const onFlash = (e: Event) => {
      const item = (e as CustomEvent<FlashItem>).detail;
      setItems((prev) =>
        prev.some((p) => p.message === item.message && p.type === item.type)
          ? prev
          : [...prev, item],
      );
      window.setTimeout(() => {
        setItems((prev) => prev.filter((p) => p.id !== item.id));
      }, item.timeout);
    };
    window.addEventListener(EVENT, onFlash);
    return () => window.removeEventListener(EVENT, onFlash);
  }, []);

  if (!items.length) return null;

  return (
    <div className="flash-container" role="status" aria-live="polite">
      {items.map((item) => (
        <div key={item.id} className={`flash-message flash-${item.type}`}>
          <div className="flash-text">{item.message}</div>
          <button
            type="button"
            className="flash-close"
            aria-label="Fermer"
            onClick={() => setItems((prev) => prev.filter((p) => p.id !== item.id))}
          >
            ×
          </button>
          <div
            className="flash-progress"
            style={{ animationDuration: `${item.timeout}ms` }}
          />
        </div>
      ))}
    </div>
  );
}
