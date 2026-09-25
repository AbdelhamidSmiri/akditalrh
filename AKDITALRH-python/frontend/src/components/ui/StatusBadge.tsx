interface Props {
  status?: string | null;
  className?: string;
}

/** Aligné sur Cake Volreservations/index : Validé → badge vert « Confirmée ». */
export function StatusBadge({ status, className = '' }: Props) {
  const s = (status || '').trim();
  let cls = 'status-btn other';
  let icon = 'fas fa-question-circle';
  let label = s || '—';

  switch (s) {
    case '0':
    case 'En cours':
    case 'En attente RH':
    case 'En attente':
      cls = 'status-btn in-progress';
      icon = 'fas fa-clock';
      label = s === '0' || s === 'En attente RH' ? 'En cours' : s;
      break;
    case '1':
    case 'Validée RH':
    case 'Validé RH':
      cls = 'status-btn confirmed';
      icon = 'fa-solid fa-circle-check';
      label = 'Validée RH';
      break;
    case '2':
    case 'Validée':
    case 'Validé':
    case 'Validé DGD':
    case 'Valider':
    case 'Confirmée':
    case 'Confirmé':
    case 'confirmed':
    case 'acceptée':
      cls = 'status-btn confirmed';
      icon = 'fa-solid fa-circle-check';
      if (s === '2' || s === 'Validée') label = 'Validée';
      else if (s === 'Valider') label = 'Confirmée';
      else if (s === 'acceptée') label = s;
      else if (s === 'Validé DGD') label = s;
      else label = 'Confirmée';
      break;
    case 'Checkin':
    case 'Check-in':
      cls = 'status-btn checked';
      icon = 'fa-regular fa-right-to-bracket';
      label = 'Arrivée';
      break;
    case 'Checkout':
    case 'Check-out':
      cls = 'status-btn refused';
      icon = 'fas fa-sign-out-alt';
      label = 'Départ';
      break;
    case 'refusée':
    case 'Rejeté':
    case 'Annulé':
    case 'Annuler':
      cls = 'status-btn refused';
      icon = 'fas fa-ban';
      break;
    default:
      if (s) {
        cls = 'status-btn passe';
        icon = 'fas fa-question-circle';
      }
  }

  return (
    <span className={`${cls} ${className}`.trim()}>
      <i className={icon} /> {label}
    </span>
  );
}
