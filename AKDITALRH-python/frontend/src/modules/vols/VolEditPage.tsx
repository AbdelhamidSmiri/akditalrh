import { FormEvent, useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { sitesApi, volsApi } from '../../api/endpoints';
import { getErrorMessage, unwrapList } from '../../api/client';
import type { Site, VolReservation } from '../../types';
import { Alert } from '../../components/ui/Alert';
import { Loading } from '../../components/ui/Loading';
import { PageHeader } from '../../components/layout/PageHeader';

export function VolEditPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [sites, setSites] = useState<Site[]>([]);
  const [form, setForm] = useState({
    site_id: '',
    depart: '',
    destination: '',
    date_aller: '',
    date_retour: '',
    num_odm: '',
    message: '',
    etat: 'En cours',
    reponse: '',
    num_vol: '',
    transfer: '0',
    nom_transfer: '',
    date_transfer: '',
    tel_transfer: '',
    description_transfer: '',
  });

  useEffect(() => {
    if (!id) return;
    void (async () => {
      try {
        const [{ data: vol }, { data: sitesData }] = await Promise.all([
          volsApi.get(id),
          sitesApi.list(),
        ]);
        setSites(unwrapList<Site>(sitesData));
        const v = vol as VolReservation;
        setForm({
          site_id: String(v.site_id || ''),
          depart: v.depart || '',
          destination: v.destination || '',
          date_aller: (v.date_aller || '').toString().slice(0, 10),
          date_retour: (v.date_retour || '').toString().slice(0, 10),
          num_odm: v.num_odm || '',
          message: v.message || '',
          etat: v.etat || 'En cours',
          reponse: v.reponse || '',
          num_vol: v.num_vol || '',
          transfer: v.transfer === '1' ? '1' : '0',
          nom_transfer: v.nom_transfer || '',
          date_transfer: (v.date_transfer || '').toString().slice(0, 16),
          tel_transfer: v.tel_transfer || '',
          description_transfer: v.description_transfer || '',
        });
      } catch (err) {
        setError(getErrorMessage(err));
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  const set = (k: keyof typeof form, v: string) =>
    setForm((f) => ({ ...f, [k]: v }));

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    if (!id) return;
    setSaving(true);
    setError('');
    try {
      await volsApi.update(id, {
        site_id: Number(form.site_id) || undefined,
        depart: form.depart,
        destination: form.destination,
        date_aller: form.date_aller || undefined,
        date_retour: form.date_retour || undefined,
        num_odm: form.num_odm,
        message: form.message,
        etat: form.etat,
        reponse: form.reponse,
        num_vol: form.num_vol,
        transfer: form.transfer,
        nom_transfer: form.nom_transfer,
        date_transfer: form.date_transfer || undefined,
        tel_transfer: form.tel_transfer,
        description_transfer: form.description_transfer,
      } as Partial<VolReservation>);
      navigate(`/vols/${id}`);
    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <Loading />;

  return (
    <>
      <PageHeader
        title={`Modifier la demande #${id}`}
        subtitle="Modifiez les informations de la demande de billet d’avion."
      />
      {error && <Alert variant="danger">{error}</Alert>}

      <div className="volreservations form">
        <div className="row">
          <div className="col" />
          <div className="col-8">
            <form onSubmit={onSubmit}>
              <div className="col-12 mb-3">
                <label className="title-section-form">Site</label>
                <div className="select">
                  <select
                    value={form.site_id}
                    onChange={(e) => set('site_id', e.target.value)}
                  >
                    <option value="">—</option>
                    {sites.map((s) => (
                      <option key={s.id} value={s.id}>
                        {s.site}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="row">
                <div className="col-md-6">
                  <div className="text">
                    <label htmlFor="depart">Départ</label>
                    <input
                      id="depart"
                      type="text"
                      placeholder=" "
                      value={form.depart}
                      onChange={(e) => set('depart', e.target.value)}
                    />
                  </div>
                </div>
                <div className="col-md-6">
                  <div className="text">
                    <label htmlFor="destination">Destination</label>
                    <input
                      id="destination"
                      type="text"
                      placeholder=" "
                      value={form.destination}
                      onChange={(e) => set('destination', e.target.value)}
                    />
                  </div>
                </div>
              </div>

              <div className="row">
                <div className="col-md-6">
                  <label className="title-section-form">Date aller</label>
                  <div className="field-static">
                    <input
                      type="date"
                      value={form.date_aller}
                      onChange={(e) => set('date_aller', e.target.value)}
                    />
                  </div>
                </div>
                <div className="col-md-6">
                  <label className="title-section-form">Date retour</label>
                  <div className="field-static">
                    <input
                      type="date"
                      min={form.date_aller || undefined}
                      value={form.date_retour}
                      onChange={(e) => set('date_retour', e.target.value)}
                    />
                  </div>
                </div>
              </div>

              <div className="text">
                <label htmlFor="num_odm">Num ODM</label>
                <input
                  id="num_odm"
                  type="text"
                  placeholder=" "
                  value={form.num_odm}
                  onChange={(e) => set('num_odm', e.target.value)}
                />
              </div>

              <div className="col-12 mb-3">
                <label className="title-section-form" htmlFor="message">
                  Message
                </label>
                <textarea
                  id="message"
                  className="form-control"
                  rows={3}
                  value={form.message}
                  onChange={(e) => set('message', e.target.value)}
                />
              </div>

              <div className="row">
                <div className="col-md-6">
                  <label className="title-section-form">État</label>
                  <div className="select">
                    <select
                      value={form.etat}
                      onChange={(e) => set('etat', e.target.value)}
                    >
                      <option value="En cours">En cours</option>
                      <option value="Validé">Validé (Confirmée)</option>
                      <option value="Annulé">Annulé</option>
                    </select>
                  </div>
                </div>
                <div className="col-md-6">
                  <div className="text">
                    <label htmlFor="num_vol">Numéro de vol</label>
                    <input
                      id="num_vol"
                      type="text"
                      placeholder=" "
                      value={form.num_vol}
                      onChange={(e) => set('num_vol', e.target.value)}
                    />
                  </div>
                </div>
              </div>

              <div className="col-12 mb-3">
                <label className="title-section-form" htmlFor="reponse">
                  Réponse
                </label>
                <textarea
                  id="reponse"
                  className="form-control"
                  rows={2}
                  value={form.reponse}
                  onChange={(e) => set('reponse', e.target.value)}
                />
              </div>

              <div className="col-12 mb-3">
                <label className="control control--checkbox">
                  Transfert
                  <input
                    type="checkbox"
                    checked={form.transfer === '1'}
                    onChange={(e) => set('transfer', e.target.checked ? '1' : '0')}
                  />
                  <div className="control__indicator" />
                </label>
              </div>

              {form.transfer === '1' && (
                <>
                  <div className="text">
                    <label htmlFor="nom_transfer">Nom du transfert</label>
                    <input
                      id="nom_transfer"
                      type="text"
                      placeholder=" "
                      value={form.nom_transfer}
                      onChange={(e) => set('nom_transfer', e.target.value)}
                    />
                  </div>
                  <div className="row">
                    <div className="col-md-6">
                      <label className="title-section-form">Date du transfert</label>
                      <input
                        type="datetime-local"
                        value={form.date_transfer}
                        onChange={(e) => set('date_transfer', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6">
                      <div className="text">
                        <label htmlFor="tel_transfer">Téléphone du transfert</label>
                        <input
                          id="tel_transfer"
                          type="text"
                          placeholder=" "
                          value={form.tel_transfer}
                          onChange={(e) => set('tel_transfer', e.target.value)}
                        />
                      </div>
                    </div>
                  </div>
                  <div className="col-12 mb-3">
                    <label className="title-section-form">Description du transfert</label>
                    <textarea
                      className="form-control"
                      rows={2}
                      value={form.description_transfer}
                      onChange={(e) => set('description_transfer', e.target.value)}
                    />
                  </div>
                </>
              )}

              <div className="submit-section d-flex gap-2 mt-4">
                <Link to="/vols" className="btn btn-link">
                  Retour
                </Link>
                <button type="submit" className="btn btn-submit" disabled={saving}>
                  {saving ? 'Enregistrement…' : 'Enregistrer'}
                </button>
              </div>
            </form>
          </div>
          <div className="col" />
        </div>
      </div>
    </>
  );
}
