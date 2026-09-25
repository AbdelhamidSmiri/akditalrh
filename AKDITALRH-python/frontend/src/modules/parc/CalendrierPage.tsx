import {
  addMonths,
  eachDayOfInterval,
  endOfMonth,
  endOfWeek,
  format,
  isSameDay,
  isSameMonth,
  parseISO,
  startOfMonth,
  startOfWeek,
  subMonths,
} from 'date-fns';
import { fr } from 'date-fns/locale';
import { useEffect, useMemo, useState } from 'react';
import { parcApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { CalendrierContrat } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { Button } from '../../components/ui/Button';
import { Loading } from '../../components/ui/Loading';
import { Modal } from '../../components/ui/Modal';
import { PageHeader } from '../../components/layout/PageHeader';
import './CalendrierPage.css';

function eventDate(ev: CalendrierContrat): Date | null {
  const raw = ev.fin_contrat || ev.date_fin;
  if (!raw) return null;
  try {
    return parseISO(raw.slice(0, 10));
  } catch {
    return null;
  }
}

function eventTitle(ev: CalendrierContrat) {
  return [ev.marque, ev.modele].filter(Boolean).join(' ') || ev.immatriculation || `Véhicule #${ev.id}`;
}

export function CalendrierPage() {
  const [events, setEvents] = useState<CalendrierContrat[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [cursor, setCursor] = useState(() => startOfMonth(new Date()));
  const [selected, setSelected] = useState<CalendrierContrat | null>(null);

  useEffect(() => {
    void (async () => {
      setLoading(true);
      setError('');
      try {
        const { data } = await parcApi.calendrier();
        setEvents(unwrapList<CalendrierContrat>(data));
      } catch (err) {
        setError(getErrorMessage(err));
        setEvents([]);
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const days = useMemo(() => {
    const start = startOfWeek(startOfMonth(cursor), { weekStartsOn: 1 });
    const end = endOfWeek(endOfMonth(cursor), { weekStartsOn: 1 });
    return eachDayOfInterval({ start, end });
  }, [cursor]);

  const byDay = useMemo(() => {
    const map = new Map<string, CalendrierContrat[]>();
    for (const ev of events) {
      const d = eventDate(ev);
      if (!d) continue;
      const key = format(d, 'yyyy-MM-dd');
      const list = map.get(key) || [];
      list.push(ev);
      map.set(key, list);
    }
    return map;
  }, [events]);

  const weekdays = ['lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.', 'dim.'];

  return (
    <>
      <PageHeader
        title="Calendrier contrats"
        subtitle="Visualisez les fins de contrat véhicules."
      />
      {error && <Alert variant="warning">{error}</Alert>}
      {loading ? (
        <Loading />
      ) : (
        <div className="fc-wrap">
          <div className="fc-toolbar">
            <div className="fc-toolbar-left">
              <Button variant="secondary" size="sm" onClick={() => setCursor(subMonths(cursor, 1))}>
                ‹
              </Button>
              <Button variant="secondary" size="sm" onClick={() => setCursor(startOfMonth(new Date()))}>
                Aujourd&apos;hui
              </Button>
              <Button variant="secondary" size="sm" onClick={() => setCursor(addMonths(cursor, 1))}>
                ›
              </Button>
            </div>
            <h2 className="fc-title">{format(cursor, 'MMMM yyyy', { locale: fr })}</h2>
          </div>

          <div className="fc-grid">
            {weekdays.map((d) => (
              <div key={d} className="fc-dow">
                {d}
              </div>
            ))}
            {days.map((day) => {
              const key = format(day, 'yyyy-MM-dd');
              const dayEvents = byDay.get(key) || [];
              const inMonth = isSameMonth(day, cursor);
              const today = isSameDay(day, new Date());
              return (
                <div
                  key={key}
                  className={`fc-day${!inMonth ? ' fc-other' : ''}${today ? ' fc-today' : ''}`}
                >
                  <div className="fc-day-num">{format(day, 'd')}</div>
                  <div className="fc-events">
                    {dayEvents.map((ev) => (
                      <button
                        key={ev.id}
                        type="button"
                        className="fc-event"
                        title={eventTitle(ev)}
                        onClick={() => setSelected(ev)}
                      >
                        {eventTitle(ev)}
                      </button>
                    ))}
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      )}

      <Modal
        open={!!selected}
        title="Détails du contrat"
        onClose={() => setSelected(null)}
        size="lg"
        footer={
          <Button variant="secondary" onClick={() => setSelected(null)}>
            Fermer
          </Button>
        }
      >
        {selected && (
          <table className="table table-striped">
            <tbody>
              <tr>
                <th>Marque</th>
                <td>{selected.marque || '—'}</td>
              </tr>
              <tr>
                <th>Modèle</th>
                <td>{selected.modele || '—'}</td>
              </tr>
              <tr>
                <th>Immatriculation</th>
                <td>{selected.immatriculation || '—'}</td>
              </tr>
              <tr>
                <th>Fin contrat</th>
                <td>{(selected.fin_contrat || selected.date_fin)?.slice(0, 10) || '—'}</td>
              </tr>
              <tr>
                <th>Utilisateur</th>
                <td>
                  {selected.demandeur ||
                    [selected.user?.nom, selected.user?.prenom].filter(Boolean).join(' ') ||
                    '—'}
                </td>
              </tr>
              <tr>
                <th>Site</th>
                <td>{selected.site || selected.user?.site || '—'}</td>
              </tr>
              <tr>
                <th>Kilométrage actuel</th>
                <td>{selected.km_actuel ?? '—'}</td>
              </tr>
              <tr>
                <th>Valeur locative HT</th>
                <td>{selected.valeur_locative_ht ?? '—'}</td>
              </tr>
              <tr>
                <th>Durée (mois)</th>
                <td>{selected.duree_mois ?? '—'}</td>
              </tr>
              <tr>
                <th>Km prévus</th>
                <td>{selected.km ?? '—'}</td>
              </tr>
              <tr>
                <th>Cout km suppl. TTC</th>
                <td>{selected.cout_km_suplm_ttc ?? '—'}</td>
              </tr>
            </tbody>
          </table>
        )}
      </Modal>
    </>
  );
}
