import { FormEvent, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { filesApi, sitesApi, volsApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import { useAuth } from '../../hooks/useAuth';
import type { Site } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { PageHeader } from '../../components/layout/PageHeader';
import { DropZone, uploadFiles } from '../../components/ui/DropZone';

type Step = 1 | 2;

export function VolFormPage() {
  const navigate = useNavigate();
  const { user } = useAuth();
  const [step, setStep] = useState<Step>(1);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [sites, setSites] = useState<Site[]>([]);
  const [fieldErrors, setFieldErrors] = useState<Record<string, boolean>>({});

  const [form, setForm] = useState({
    site_id: '',
    depart: '',
    destination: '',
    date_aller: '',
    date_retour: '',
    transfer: false,
    num_odm: '',
    message: '',
  });
  const [ordreMission, setOrdreMission] = useState<File[]>([]);
  const [cinFiles, setCinFiles] = useState<File[]>([]);
  const [passportFiles, setPassportFiles] = useState<File[]>([]);

  useEffect(() => {
    void (async () => {
      try {
        const { data } = await sitesApi.list();
        setSites(unwrapList<Site>(data));
        if (user?.site_id) {
          setForm((f) => ({ ...f, site_id: String(user.site_id) }));
        }
      } catch (err) {
        setError(getErrorMessage(err));
      }
    })();
  }, [user?.site_id]);

  const set = (k: keyof typeof form, v: string | boolean) =>
    setForm((f) => ({ ...f, [k]: v }));

  const goStep2 = () => {
    const nextErrors: Record<string, boolean> = {
      site_id: !form.site_id,
      depart: !form.depart,
      destination: !form.destination,
      date_aller: !form.date_aller,
      date_retour: !form.date_retour,
    };
    setFieldErrors(nextErrors);
    if (Object.values(nextErrors).some(Boolean)) {
      setError('Veuillez compléter tous les champs de l’étape 1.');
      return;
    }
    setError('');
    setStep(2);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (step === 1) {
      goStep2();
      return;
    }

    const step2Errors: Record<string, boolean> = {
      num_odm: !form.num_odm,
      ordre_mission: !ordreMission?.length,
      cin: !cinFiles?.length,
      passport: !passportFiles?.length,
      message: !form.message.trim(),
    };
    setFieldErrors(step2Errors);
    if (Object.values(step2Errors).some(Boolean)) {
      setError('Tous les champs et documents sont obligatoires.');
      return;
    }

    setLoading(true);
    setError('');
    try {
      const [odmNames, cinNames, passportNames] = await Promise.all([
        uploadFiles(ordreMission, 'volreservations', filesApi.upload),
        uploadFiles(cinFiles, 'volreservations', filesApi.upload),
        uploadFiles(passportFiles, 'volreservations', filesApi.upload),
      ]);

      const { data } = await volsApi.create({
        site_id: Number(form.site_id),
        depart: form.depart,
        destination: form.destination,
        date_aller: form.date_aller,
        date_retour: form.date_retour,
        transfer: form.transfer ? '1' : '0',
        num_odm: form.num_odm,
        message: form.message,
        ordre_mission: JSON.stringify(odmNames),
        cin: JSON.stringify(cinNames),
        passport: JSON.stringify(passportNames),
      } as Parameters<typeof volsApi.create>[0]);

      navigate(`/vols/${data.id}`);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <PageHeader
        title="Demandes de billets d’avion"
        subtitle="Veuillez compléter ce formulaire et fournir les pièces justificatives requises pour enregistrer votre demande."
      />
      {error && <Alert variant="danger">{error}</Alert>}

      <div className="volreservations form">
        <div className="row">
          <div className="col" />
          <div className="col-md-8 col-sm-9 p-4">
            <form onSubmit={onSubmit}>
              {step === 1 && (
                <div className="step_1">
                  <div className="col-12">
                    <div className="select">
                      <select
                        id="site_id"
                        value={form.site_id}
                        onChange={(e) => set('site_id', e.target.value)}
                        style={fieldErrors.site_id ? { border: '1px solid #b80000' } : undefined}
                      >
                        <option value="">Choisissez le site de prise en charge</option>
                        {sites.map((s) => (
                          <option key={s.id} value={s.id}>
                            {s.site}
                          </option>
                        ))}
                      </select>
                    </div>
                    <div className="message">Veuillez sélectionner un site</div>
                    {fieldErrors.site_id && (
                      <div className="message-error" style={{ display: 'block' }}>
                        Veuillez choisir un site.
                      </div>
                    )}
                  </div>

                  <div className="row">
                    <div className="col-md-6 col-sm-12">
                      <div className="text">
                        <label htmlFor="depart">Lieu de départ</label>
                        <input
                          id="depart"
                          type="text"
                          placeholder=" "
                          value={form.depart}
                          onChange={(e) => set('depart', e.target.value)}
                          style={fieldErrors.depart ? { border: '1px solid #b80000' } : undefined}
                        />
                      </div>
                      <div className="message">D’où partez-vous ?</div>
                      {fieldErrors.depart && (
                        <div className="message-error" style={{ display: 'block' }}>
                          Veuillez choisir un lieu de départ.
                        </div>
                      )}
                    </div>
                    <div className="col-md-6 col-sm-12">
                      <div className="text">
                        <label htmlFor="destination">Destination</label>
                        <input
                          id="destination"
                          type="text"
                          placeholder=" "
                          value={form.destination}
                          onChange={(e) => set('destination', e.target.value)}
                          style={
                            fieldErrors.destination
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        />
                      </div>
                      <div className="message">Où souhaitez-vous aller ?</div>
                      {fieldErrors.destination && (
                        <div className="message-error" style={{ display: 'block' }}>
                          Veuillez choisir une destination.
                        </div>
                      )}
                    </div>
                  </div>

                  <div className="row">
                    <div className="col-md-6 col-sm-12">
                      <div className="has-calendar-icon input text">
                        <label htmlFor="date_aller">Date de départ</label>
                        <input
                          id="date_aller"
                          type="date"
                          value={form.date_aller}
                          onChange={(e) => {
                            set('date_aller', e.target.value);
                            if (
                              !form.date_retour ||
                              form.date_retour < e.target.value
                            ) {
                              set('date_retour', e.target.value);
                            }
                          }}
                          style={
                            fieldErrors.date_aller
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        />
                      </div>
                      <div className="message">Sélectionnez la date prévue de départ</div>
                      {fieldErrors.date_aller && (
                        <div className="message-error" style={{ display: 'block' }}>
                          Veuillez choisir une date aller.
                        </div>
                      )}
                    </div>
                    <div className="col-md-6 col-sm-12">
                      <div className="has-calendar-icon input text">
                        <label htmlFor="date_retour">Date de retour</label>
                        <input
                          id="date_retour"
                          type="date"
                          min={form.date_aller || undefined}
                          value={form.date_retour}
                          onChange={(e) => set('date_retour', e.target.value)}
                          style={
                            fieldErrors.date_retour
                              ? { border: '1px solid #b80000' }
                              : undefined
                          }
                        />
                      </div>
                      <div className="message">Sélectionnez la date prévue de retour</div>
                      {fieldErrors.date_retour && (
                        <div className="message-error" style={{ display: 'block' }}>
                          Veuillez choisir une date retour.
                        </div>
                      )}
                    </div>
                  </div>

                  <div className="col-12 mt-5">
                    <label className="control control--checkbox">
                      Je souhaite réserver un transfert
                      <input
                        type="checkbox"
                        checked={form.transfer}
                        onChange={(e) => set('transfer', e.target.checked)}
                      />
                      <div className="control__indicator" />
                    </label>
                  </div>

                  <div className="col-12 text-end mt-4">
                    <button type="button" className="btn btn-primary-rounded" onClick={goStep2}>
                      <span className="rounded_icon">
                        <i className="fa-solid fa-arrow-right" />
                      </span>{' '}
                      Suivant
                    </button>
                  </div>
                  <div className="col-md-9 col-sm-12 d-flex justify-content-between mt-3">
                    <span className="import-span">
                      Tous les champs et documents sont obligatoires pour la validation de
                      la demande.
                    </span>
                    <span className="pagin-steps">1 sur 2 étapes</span>
                  </div>
                </div>
              )}

              {step === 2 && (
                <div className="step_2">
                  <div className="col-12">
                    <div className="text">
                      <label htmlFor="num_odm">Numéro d&apos;ODM</label>
                      <input
                        id="num_odm"
                        type="text"
                        placeholder=" "
                        value={form.num_odm}
                        onChange={(e) => set('num_odm', e.target.value)}
                        style={
                          fieldErrors.num_odm ? { border: '1px solid #b80000' } : undefined
                        }
                      />
                    </div>
                    {fieldErrors.num_odm && (
                      <div className="message-error" style={{ display: 'block' }}>
                        Veuillez entrer le numéro d&apos;ODM.
                      </div>
                    )}
                  </div>

                  <DropZone
                    description="Téléversez les fichiers de votre ordre de mission signé."
                    files={ordreMission}
                    onChange={setOrdreMission}
                    error={fieldErrors.ordre_mission}
                  />
                  <DropZone
                    label="CIN"
                    description="Téléversez les fichiers de votre CIN."
                    files={cinFiles}
                    onChange={setCinFiles}
                    error={fieldErrors.cin}
                  />
                  <DropZone
                    label="Passeport"
                    description="Téléversez les fichiers de votre passeport."
                    files={passportFiles}
                    onChange={setPassportFiles}
                    error={fieldErrors.passport}
                  />

                  <div className="col-12">
                    <label htmlFor="message" className="title-section-form">
                      Motif du voyage
                    </label>
                    <textarea
                      id="message"
                      className="form-control"
                      placeholder="Motif du voyage"
                      rows={4}
                      value={form.message}
                      onChange={(e) => set('message', e.target.value)}
                      style={
                        fieldErrors.message ? { border: '1px solid #b80000' } : undefined
                      }
                    />
                    <div className="description-text">
                      Expliquez brièvement la raison de votre déplacement
                    </div>
                    {fieldErrors.message && (
                      <div className="message-error" style={{ display: 'block' }}>
                        Veuillez entrer le motif du voyage
                      </div>
                    )}
                  </div>

                  <div className="submit-section mt-4">
                    <button type="button" className="btn btn-link" onClick={() => setStep(1)}>
                      Retour
                    </button>
                    <button type="submit" className="btn btn-submit" disabled={loading}>
                      <i className="fa-solid fa-paper-plane mx-2" />
                      {loading ? 'Envoi…' : 'Soumettre la demande'}
                    </button>
                  </div>
                  <div className="col-12 d-flex justify-content-between mt-3">
                    <span className="import-span">
                      Tous les champs et documents sont obligatoires pour la validation de
                      la demande.
                    </span>
                    <span className="pagin-steps">2 sur 2 étapes</span>
                  </div>
                </div>
              )}
            </form>
          </div>
          <div className="col" />
        </div>
      </div>
    </>
  );
}
