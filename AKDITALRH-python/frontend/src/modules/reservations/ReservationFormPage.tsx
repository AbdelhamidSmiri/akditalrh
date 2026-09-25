import { FormEvent, useEffect, useMemo, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { filesApi, hotelsApi, sitesApi, villesApi, reservationsApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import { useAuth } from '../../hooks/useAuth';
import type { Chambre, Hotel, HotelPrice, Site, Ville } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { PageHeader } from '../../components/layout/PageHeader';
import { DropZone, uploadFiles } from '../../components/ui/DropZone';
import './ReservationFormPage.css';

type Step = 1 | 2 | 3 | 4;

function currentPrix(chambre: Chambre, plafond: number): number | null {
  const prices = chambre.prices || [];
  const today = new Date().toISOString().slice(0, 10);
  for (const p of prices) {
    const debut = (p.date_debut || '').toString().slice(0, 10);
    const fin = (p.date_fin || '').toString().slice(0, 10);
    if (debut && fin && (today < debut || today > fin)) continue;
    const n = Number(String(p.prix ?? '').replace(',', '.'));
    if (Number.isNaN(n)) continue;
    if (plafond > 0 && n > plafond) continue;
    return n;
  }
  // fallback: premier prix sous plafond
  for (const p of prices) {
    const n = Number(String((p as HotelPrice).prix ?? '').replace(',', '.'));
    if (Number.isNaN(n)) continue;
    if (plafond > 0 && n > plafond) continue;
    return n;
  }
  return null;
}

function Stars({ n }: { n?: string | number | null }) {
  const count = Math.min(5, Math.max(0, Number(n) || 0));
  return (
    <div className="starts">
      {Array.from({ length: count }).map((_, i) => (
        <i key={i} className="fa-solid fa-star" />
      ))}
    </div>
  );
}

export function ReservationFormPage() {
  const navigate = useNavigate();
  const { user } = useAuth();
  const plafond = Number(user?.role?.plafond_hotel || 0) || 0;

  const [step, setStep] = useState<Step>(1);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [fieldErrors, setFieldErrors] = useState<Record<string, boolean>>({});

  const [villes, setVilles] = useState<Ville[]>([]);
  const [sites, setSites] = useState<Site[]>([]);
  const [hotels, setHotels] = useState<Hotel[]>([]);
  const [chambres, setChambres] = useState<(Chambre & { prix?: number | null })[]>([]);

  const [form, setForm] = useState({
    ville_id: '',
    site_id: user?.site_id ? String(user.site_id) : '',
    checkin: '',
    checkout: '',
    hotel_id: '',
    chambre_id: '',
    num_odm: '',
    message: '',
  });
  const [ordreFiles, setOrdreFiles] = useState<File[]>([]);
  const [cinFiles, setCinFiles] = useState<File[]>([]);

  const selectedHotel = useMemo(
    () => hotels.find((h) => String(h.id) === form.hotel_id),
    [hotels, form.hotel_id],
  );

  useEffect(() => {
    void (async () => {
      try {
        const [v, s] = await Promise.all([villesApi.list(), sitesApi.list()]);
        setVilles(unwrapList<Ville>(v.data));
        setSites(unwrapList<Site>(s.data));
      } catch (err) {
        setError(getErrorMessage(err));
      }
    })();
  }, []);

  // Cake charge les sites une fois (souvent tous rattachés à CASABLANCA en base).
  // On filtre par ville si possible, sinon on garde la liste complète.
  const sitesForSelect = useMemo(() => {
    if (!form.ville_id) return sites;
    const filtered = sites.filter((s) => String(s.ville_id) === form.ville_id);
    return filtered.length > 0 ? filtered : sites;
  }, [sites, form.ville_id]);

  const set = (k: keyof typeof form, v: string) =>
    setForm((f) => ({ ...f, [k]: v }));

  const goStep = (s: Step) => {
    setStep(s);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const searchHotels = async () => {
    const errs = {
      ville_id: !form.ville_id,
      site_id: !form.site_id,
      checkin: !form.checkin,
      checkout: !form.checkout,
    };
    setFieldErrors(errs);
    if (Object.values(errs).some(Boolean)) {
      setError('Veuillez compléter tous les champs de l’étape 1.');
      return;
    }
    setError('');
    setLoading(true);
    try {
      const { data } = await hotelsApi.list({ ville_id: form.ville_id });
      setHotels(unwrapList<Hotel>(data));
      setForm((f) => ({ ...f, hotel_id: '', chambre_id: '' }));
      goStep(2);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  const loadChambres = async () => {
    if (!form.hotel_id) return;
    setLoading(true);
    setError('');
    try {
      const { data } = await hotelsApi.listChambres(form.hotel_id);
      const list = unwrapList<Chambre>(data);
      const withPrix = list
        .map((c) => {
          const prix = currentPrix(c, plafond);
          return { ...c, prix };
        })
        .filter((c) => c.prix !== null || plafond <= 0 || !c.prices?.length);
      // Si plafond actif : ne garder que chambres avec prix OK (comme Cake)
      const filtered =
        plafond > 0
          ? withPrix.filter((c) => c.prix !== null)
          : withPrix.map((c) => ({
              ...c,
              prix: c.prix ?? currentPrix(c, 0),
            }));
      setChambres(filtered);
      setForm((f) => ({ ...f, chambre_id: '' }));
      goStep(3);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (step !== 4) return;

    const errs = {
      num_odm: !form.num_odm.trim(),
      ordre: !ordreFiles?.length,
      cin: !cinFiles?.length,
      message: !form.message.trim(),
    };
    setFieldErrors(errs);
    if (Object.values(errs).some(Boolean)) {
      setError('Tous les champs et documents sont obligatoires.');
      return;
    }

    setLoading(true);
    setError('');
    try {
      const [odmNames, cinNames] = await Promise.all([
        uploadFiles(ordreFiles, 'reservations', filesApi.upload),
        uploadFiles(cinFiles, 'reservations', filesApi.upload),
      ]);
      const { data } = await reservationsApi.create({
        chambre_id: Number(form.chambre_id),
        site_id: Number(form.site_id),
        checkin: form.checkin,
        checkout: form.checkout,
        num_odm: form.num_odm,
        message: form.message,
        ordre_mission: JSON.stringify(odmNames),
        cin: JSON.stringify(cinNames),
      });
      navigate(`/reservations/${data.id}`);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="reservation-form-page">
      <PageHeader
        title="Demande d’hôtel"
        subtitle="Soumettez une nouvelle demande de réservation d’hôtel."
      />
      {error && <Alert variant="danger">{error}</Alert>}

      <div className="reservations form">
        <div className="row">
          <div className="col" />
          <div className="col-md-10 p-4">
            <form onSubmit={onSubmit}>
              {step === 1 && (
                <div className="step_1">
                  <div className="step-header">
                    <h2>Informations de base</h2>
                    <p>Sélectionnez votre ville, site et dates de séjour</p>
                  </div>

                  <div className="row">
                    <div className="col-md-6">
                      <div className="select">
                        <select
                          id="ville-select"
                          value={form.ville_id}
                          onChange={(e) => {
                            set('ville_id', e.target.value);
                          }}
                          style={
                            fieldErrors.ville_id
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        >
                          <option value="">Choisissez une ville</option>
                          {villes.map((v) => (
                            <option key={v.id} value={v.id}>
                              {v.ville}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div className="message">Veuillez sélectionner la ville</div>
                      {fieldErrors.ville_id && (
                        <div className="message-error show">Veuillez choisir une ville.</div>
                      )}
                    </div>

                    <div className="col-md-6">
                      <div className="select">
                        <select
                          value={form.site_id}
                          onChange={(e) => set('site_id', e.target.value)}
                          style={
                            fieldErrors.site_id
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        >
                          <option value="">Choisissez le site de la réservation</option>
                          {sitesForSelect.map((s) => (
                            <option key={s.id} value={s.id}>
                              {s.site}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div className="message">
                        Sélectionnez le site Akdital qui prendra en charge votre mission.
                      </div>
                      {fieldErrors.site_id && (
                        <div className="message-error show">Veuillez choisir un site.</div>
                      )}
                    </div>

                    <div className="col-md-6">
                      <label htmlFor="date_checkin" className="title-section-form">
                        Date d’arrivée
                      </label>
                      <div className="field-static has-calendar-icon">
                        <input
                          id="date_checkin"
                          type="date"
                          value={form.checkin}
                          onChange={(e) => {
                            set('checkin', e.target.value);
                            if (!form.checkout || form.checkout < e.target.value) {
                              set('checkout', e.target.value);
                            }
                          }}
                          style={
                            fieldErrors.checkin
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        />
                      </div>
                      <div className="message">Sélectionnez la date prévue d’arrivée</div>
                      {fieldErrors.checkin && (
                        <div className="message-error show">
                          Veuillez sélectionner la date de check-in.
                        </div>
                      )}
                    </div>

                    <div className="col-md-6">
                      <label htmlFor="date_checkout" className="title-section-form">
                        Date de départ
                      </label>
                      <div className="field-static has-calendar-icon">
                        <input
                          id="date_checkout"
                          type="date"
                          min={form.checkin || undefined}
                          value={form.checkout}
                          onChange={(e) => set('checkout', e.target.value)}
                          style={
                            fieldErrors.checkout
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        />
                      </div>
                      <div className="message">Sélectionnez la date prévue de départ.</div>
                      {fieldErrors.checkout && (
                        <div className="message-error show">
                          Veuillez sélectionner la date de check-out.
                        </div>
                      )}
                    </div>
                  </div>

                  <div className="text-center mt-4">
                    <button
                      type="button"
                      className="btn btn-primary-rounded btn-lg"
                      disabled={loading}
                      onClick={() => void searchHotels()}
                    >
                      <i className="fa-solid fa-search" /> Rechercher les hôtels
                    </button>
                  </div>
                  <div className="text-center mt-3">
                    <span className="pagin-steps">1 sur 4 étapes</span>
                  </div>
                </div>
              )}

              {step === 2 && (
                <div className="step_2">
                  <div className="step-header">
                    <h2>Choisissez votre hôtel</h2>
                    <p>Sélectionnez l&apos;hôtel qui vous convient</p>
                  </div>

                  <div className="hotels-container">
                    {loading ? (
                      <div className="text-center p-5">Chargement des hôtels...</div>
                    ) : hotels.length === 0 ? (
                      <div className="text-center p-5">
                        <p>Aucun hôtel disponible pour cette ville.</p>
                      </div>
                    ) : (
                      hotels.map((hotel) => {
                        const selected = form.hotel_id === String(hotel.id);
                        const hasImage = Boolean(hotel.images);
                        return (
                          <div
                            key={hotel.id}
                            className={`hotel-card ${selected ? 'selected' : ''}`}
                            onClick={() => set('hotel_id', String(hotel.id))}
                            role="button"
                            tabIndex={0}
                            onKeyDown={(e) => {
                              if (e.key === 'Enter' || e.key === ' ') {
                                set('hotel_id', String(hotel.id));
                              }
                            }}
                          >
                            <div className="radio-selection">
                              <input
                                type="radio"
                                className="form-check-input"
                                checked={selected}
                                onChange={() => set('hotel_id', String(hotel.id))}
                              />
                            </div>
                            {hasImage && (
                              <div
                                className="image"
                                style={{
                                  backgroundImage: `url(${hotel.images})`,
                                }}
                              />
                            )}
                            <div
                              className={`content-card ${hasImage ? '' : 'full'}`}
                            >
                              <h3 className="title-card">
                                <span>{hotel.hotel || hotel.nom}</span>
                                <div className="ville-row">
                                  <span>
                                    <i className="fa-solid fa-location-dot" />{' '}
                                    {hotel.region || '—'}
                                  </span>
                                  <Stars n={hotel.etoile} />
                                </div>
                              </h3>
                              <div className="info">
                                {hotel.mail &&
                                  hotel.mail.split(';').map((email) => (
                                    <span key={email} className="email">
                                      <i className="fa-regular fa-envelope" /> {email.trim()}{' '}
                                      |&nbsp;
                                    </span>
                                  ))}
                                {hotel.telephone?.trim() && (
                                  <div>
                                    <span className="tel">
                                      <i className="fa-solid fa-phone" /> {hotel.telephone}
                                    </span>
                                  </div>
                                )}
                              </div>
                              {hotel.adresse?.trim() && (
                                <div className="adress mt-2">
                                  <span>
                                    <i className="fa-solid fa-location-dot" /> {hotel.adresse}
                                  </span>
                                </div>
                              )}
                              {hotel.reglement && (
                                <div className="reglement">{hotel.reglement}</div>
                              )}
                            </div>
                          </div>
                        );
                      })
                    )}
                  </div>

                  <div className="text-center mt-4">
                    <button
                      type="button"
                      className="btn btn-secondary-rounded me-2"
                      onClick={() => goStep(1)}
                    >
                      <i className="fa-solid fa-arrow-left" /> Retour
                    </button>
                    <button
                      type="button"
                      className="btn btn-primary-rounded btn-lg"
                      disabled={!form.hotel_id || loading}
                      onClick={() => void loadChambres()}
                    >
                      Voir les chambres <i className="fa-solid fa-arrow-right" />
                    </button>
                  </div>
                  <div className="text-center mt-3">
                    <span className="pagin-steps">2 sur 4 étapes</span>
                  </div>
                </div>
              )}

              {step === 3 && (
                <div className="step_3">
                  <div className="step-header">
                    <h2>Choisissez votre chambre</h2>
                    <p>Sélectionnez la chambre qui vous convient</p>
                  </div>

                  {selectedHotel && (
                    <div className="alert alert-info mb-4">
                      <strong>Hôtel sélectionné:</strong>{' '}
                      {selectedHotel.hotel || selectedHotel.nom}
                    </div>
                  )}

                  <div className="row">
                    {loading ? (
                      <div className="col-12 text-center p-5">Chargement des chambres...</div>
                    ) : chambres.length === 0 ? (
                      <div className="col-12 text-center p-5">
                        <p>Aucune chambre disponible pour cet hôtel (plafond).</p>
                      </div>
                    ) : (
                      chambres.map((c) => {
                        const selected = form.chambre_id === String(c.id);
                        return (
                          <div className="col-md-6" key={c.id}>
                            <div
                              className={`card view-card chambre-card appartement-card ${
                                selected ? 'selected' : ''
                              }`}
                              onClick={() => set('chambre_id', String(c.id))}
                              role="button"
                              tabIndex={0}
                              onKeyDown={(e) => {
                                if (e.key === 'Enter' || e.key === ' ') {
                                  set('chambre_id', String(c.id));
                                }
                              }}
                            >
                              <div className="card-body">
                                <div className="radio-selection">
                                  <input
                                    type="radio"
                                    className="form-check-input"
                                    checked={selected}
                                    onChange={() => set('chambre_id', String(c.id))}
                                  />
                                </div>
                                <div className="d-flex justify-content-between gap-2">
                                  <div className="block-content">
                                    <label>Type de chambre</label>
                                    <span>
                                      <b>{c.nom || c.type || `Chambre #${c.id}`}</b>
                                    </span>
                                  </div>
                                  <div className="block-content">
                                    <label>Prix par nuit</label>
                                    <span>
                                      <b>
                                        {c.prix != null ? `${c.prix} DH` : '—'}
                                      </b>
                                    </span>
                                  </div>
                                </div>
                                <div className="d-flex justify-content-between gap-2 align-items-end">
                                  <div className="block-content mb-0">
                                    <label>Statut</label>
                                    <span>
                                      <b>{c.type || 'Standard'}</b>
                                    </span>
                                  </div>
                                  <button
                                    type="button"
                                    className="btn btn-primary-rounded"
                                    onClick={(e) => {
                                      e.stopPropagation();
                                      set('chambre_id', String(c.id));
                                    }}
                                  >
                                    Sélectionner
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        );
                      })
                    )}
                  </div>

                  <div className="text-center mt-4">
                    <button
                      type="button"
                      className="btn btn-secondary-rounded me-2"
                      onClick={() => goStep(2)}
                    >
                      <i className="fa-solid fa-arrow-left" /> Retour
                    </button>
                    <button
                      type="button"
                      className="btn btn-primary-rounded btn-lg"
                      disabled={!form.chambre_id}
                      onClick={() => goStep(4)}
                    >
                      Finaliser la réservation{' '}
                      <i className="fa-solid fa-arrow-right" />
                    </button>
                  </div>
                  <div className="text-center mt-3">
                    <span className="pagin-steps">3 sur 4 étapes</span>
                  </div>
                </div>
              )}

              {step === 4 && (
                <div className="step_4">
                  <div className="step-header">
                    <h2>Finaliser votre réservation</h2>
                    <p>Complétez les informations nécessaires</p>
                  </div>

                  <div className="row">
                    <div className="col-md-12">
                      <div className="text">
                        <label htmlFor="num_odm">Numéro d&apos;ODM</label>
                        <input
                          id="num_odm"
                          type="text"
                          placeholder=" "
                          value={form.num_odm}
                          onChange={(e) => set('num_odm', e.target.value)}
                          style={
                            fieldErrors.num_odm
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        />
                      </div>
                      {fieldErrors.num_odm && (
                        <div className="message-error show">
                          Veuillez entrer le numéro d&apos;ODM.
                        </div>
                      )}
                    </div>

                    <DropZone
                      label="Ordre de mission"
                      description="Téléversez les fichiers de votre ordre de mission signé."
                      files={ordreFiles}
                      onChange={setOrdreFiles}
                      error={fieldErrors.ordre}
                      className="col-md-12"
                    />
                    <DropZone
                      label="CIN"
                      description="Téléversez les fichiers de votre CIN."
                      files={cinFiles}
                      onChange={setCinFiles}
                      error={fieldErrors.cin}
                      className="col-md-12"
                    />

                    <div className="col-md-12">
                      <label className="title-section-form" htmlFor="message">
                        Motif du séjour
                      </label>
                      <textarea
                        id="message"
                        className="form-control"
                        placeholder="Motif du séjour"
                        rows={4}
                        value={form.message}
                        onChange={(e) => set('message', e.target.value)}
                        style={
                          fieldErrors.message
                            ? { border: '1px solid #b80000' }
                            : undefined
                        }
                      />
                      <div className="description-text">
                        Expliquez brièvement la raison de votre séjour
                      </div>
                      {fieldErrors.message && (
                        <div className="message-error show">
                          Veuillez entrer le motif du séjour
                        </div>
                      )}
                    </div>
                  </div>

                  <div className="text-center mt-4">
                    <button
                      type="button"
                      className="btn btn-secondary-rounded me-2"
                      onClick={() => goStep(3)}
                    >
                      <i className="fa-solid fa-arrow-left" /> Retour
                    </button>
                    <button
                      type="submit"
                      className="btn btn-success-rounded btn-lg"
                      disabled={loading}
                    >
                      <i className="fa-solid fa-paper-plane" />{' '}
                      {loading ? 'Envoi…' : 'Envoyer la réservation'}
                    </button>
                  </div>
                  <div className="text-center mt-3">
                    <span className="pagin-steps">4 sur 4 étapes</span>
                  </div>
                </div>
              )}
            </form>
          </div>
          <div className="col" />
        </div>
      </div>
    </div>
  );
}
