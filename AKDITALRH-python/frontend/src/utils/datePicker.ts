const PICKER_TYPES = new Set(['date', 'datetime-local', 'month', 'week', 'time']);

/** Ouvre le sélecteur natif dès qu’on clique n’importe où dans un champ date (pas seulement sur l’icône). */
export function enableDatePickerOnClick(root: Document = document) {
  root.addEventListener('click', (event) => {
    const input = event.target;
    if (!(input instanceof HTMLInputElement)) return;
    if (!PICKER_TYPES.has(input.type) || input.disabled || input.readOnly) return;
    try {
      input.showPicker();
    } catch {
      // showPicker absent (vieux navigateurs) ou refusé : comportement natif conservé
    }
  });
}
