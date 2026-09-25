import { useCallback, useEffect, useState, type ReactNode } from 'react';
import { useParams } from 'react-router-dom';
import { hotelsApi } from '../../api/endpoints';
import { getErrorMessage } from '../../api/client';
import type { Chambre, Hotel } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { Loading } from '../../components/ui/Loading';
import { PageHeader } from '../../components/layout/PageHeader';
import { EditChambreModal, NewChambreModal, PricesModal } from './ChambreForm';
import './HotelDetailPage.css';

function todayIso() {
  const d = new Date();
  const pad = (n: number) => String(n).padStart(2, '0');
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

function InfoCol({ label, children }: { label: string; children: ReactNode }) {
  return (
    <div className="col-md-4">
      <div className="info">
        <label>{label}</label>
        {children}
      </div>
    </div>
  );
}

export function HotelDetailPage() {
  const { id } = useParams();
  const [hotel, setHotel] = useState<Hotel | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [showNew, setShowNew] = useState(false);
  const [pricesFor, setPricesFor] = useState<Chambre | null>(null);
  const [infoFor, setInfoFor] = useState<Chambre | null>(null);

  const load = useCallback(async () => {
    if (!id) return;
    setError('');
    try {
      const { data } = await hotelsApi.get(id);
      setHotel(data);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  }, [id]);

  useEffect(() => {
    void load();
  }, [load]);

  const afterSave = () => {
    setShowNew(false);
    setPricesFor(null);
    setInfoFor(null);
    void load();
  };

  const header = (
    <PageHeader
      title="Détails de l’Hôtel"
      subtitle="Visualisez toutes les informations concernant cet hôtel"
    />
  );

  if (loading) return <Loading />;
  if (!hotel) {
    return (
      <>
        {header}
        <Alert variant="danger">{error || 'Hôtel introuvable'}</Alert>
      </>
    );
  }

  const today = todayIso();
  const mails = (hotel.mail || '').split(';').map((m) => m.trim()).filter(Boolean);
  const stars = Number(hotel.etoile) || 0;
  const chambres = hotel.chambres || [];

  return (
    <>
      {header}
      {error && <Alert variant="danger">{error}</Alert>}

      <div className="hotels view hotel-view">
        <div className="col-md-12 little-title-section">
          <span className="little-title">{hotel.hotel}</span>
          <div className="actions">
            <button type="button" className="btn btn-primary-rounded" onClick={() => setShowNew(true)}>
              Nouvelle chambre
            </button>
          </div>
        </div>

        <div className="card view-card">
          <div className="card-body">
            <div className="col-12">
              <div className="row row-gap-3">
                {hotel.region && (
                  <InfoCol label="Region">
                    <span>{hotel.region}</span>
                  </InfoCol>
                )}
                {hotel.ville?.ville && (
                  <InfoCol label="Ville">
                    <span>{hotel.ville.ville}</span>
                  </InfoCol>
                )}
                {hotel.adresse && (
                  <InfoCol label="Adresse">
                    <span>{hotel.adresse}</span>
                  </InfoCol>
                )}
                {hotel.images && (
                  <InfoCol label="Images">
                    <span>{hotel.images}</span>
                  </InfoCol>
                )}
                {stars > 0 && (
                  <InfoCol label="Étoile">
                    <div className="starts">
                      {Array.from({ length: stars }, (_, i) => (
                        <i key={i} className="fa-solid fa-star yellow m--3" />
                      ))}
                    </div>
                  </InfoCol>
                )}
                {mails.length > 0 && (
                  <InfoCol label="Email">
                    <span className="mails">
                      {mails.map((m) => (
                        <span key={m} className="mail-line">
                          <i className="fa-solid fa-envelope" /> {m}
                        </span>
                      ))}
                    </span>
                  </InfoCol>
                )}
                {hotel.telephone && (
                  <InfoCol label="Téléphone">
                    <span>{hotel.telephone}</span>
                  </InfoCol>
                )}
                {hotel.nom_responsable && (
                  <InfoCol label="Nom du responsable">
                    <span>{hotel.nom_responsable}</span>
                  </InfoCol>
                )}
                {hotel.created && (
                  <InfoCol label="Date de création">
                    <span>{hotel.created.replace('T', ' ').slice(0, 19)}</span>
                  </InfoCol>
                )}
                {hotel.reglement && (
                  <InfoCol label="Règlement">
                    <span>{hotel.reglement}</span>
                  </InfoCol>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="row mt-4 row-gap-3 hotel-view">
        {chambres.map((c) => {
          const prices = c.prices || [];
          return (
            <div key={c.id} className="col-xl-4 col-lg-6 col-md-6">
              <div className="card view-card price_card">
                <div className="card-header">
                  <div className="div_card-title">
                    <h5 className="card-title">{c.nom}</h5>
                  </div>
                  <div className="action">
                    <button
                      type="button"
                      className="btn btn-primary-rounded btn-edit-price"
                      title="Mettre à jour les prix"
                      onClick={() => setPricesFor(c)}
                    >
                      <i className="fa-solid fa-arrows-rotate" />
                    </button>
                    <button
                      type="button"
                      className="btn btn-orange-rounded"
                      title="Éditer les informations"
                      onClick={() => setInfoFor(c)}
                    >
                      <i className="fa-solid fa-pen" />
                    </button>
                  </div>
                </div>
                <div className="card-body">
                  {prices.length ? (
                    <table className="table">
                      <tbody>
                        {prices.map((p) => {
                          const active =
                            !!p.date_debut && !!p.date_fin && today >= p.date_debut && today <= p.date_fin;
                          return (
                            <tr key={p.id}>
                              <td className="debut">
                                <i className="fa-solid fa-calendar-days" /> {p.date_debut}
                              </td>
                              <td>
                                <div className="arrow_icon">
                                  <i className="fa-solid fa-arrow-right" />
                                </div>
                              </td>
                              <td className="fin">
                                <i className="fa-solid fa-calendar-check" /> {p.date_fin}
                              </td>
                              <td className="div_prix">
                                <span className={`badge ${active ? 'badge-light-success' : 'badge-light-danger'}`}>
                                  {p.prix}Dh
                                </span>
                              </td>
                            </tr>
                          );
                        })}
                      </tbody>
                    </table>
                  ) : (
                    <div className="alert alert-danger" role="alert">
                      Cette chambre n&apos;a aucun prix.
                    </div>
                  )}
                </div>
              </div>
            </div>
          );
        })}
      </div>

      <NewChambreModal
        open={showNew}
        hotelId={hotel.id}
        onClose={() => setShowNew(false)}
        onSaved={afterSave}
      />
      <PricesModal chambre={pricesFor} onClose={() => setPricesFor(null)} onSaved={afterSave} />
      <EditChambreModal chambre={infoFor} onClose={() => setInfoFor(null)} onSaved={afterSave} />
    </>
  );
}
