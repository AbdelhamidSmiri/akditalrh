import { useCallback, useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { hotelsApi, villesApi } from '../../api/endpoints';
import { getErrorMessage, publicFileUrl, unwrapList } from '../../api/client';
import type { Hotel, Ville } from '../../types';
import { PageHeader } from '../../components/layout/PageHeader';
import { Loading } from '../../components/ui/Loading';
import { SearchBar, matchesSearch } from '../../components/ui/SearchBar';
import { flash } from '../../components/ui/Flash';
import { HotelFormModal } from './HotelFormModal';
import './HotelsPage.css';

function splitEmails(mail?: string | null): string[] {
  return (mail || '')
    .split(';')
    .map((m) => m.trim())
    .filter(Boolean);
}

export function HotelsPage() {
  const [rows, setRows] = useState<Hotel[]>([]);
  const [villes, setVilles] = useState<Ville[]>([]);
  const [loading, setLoading] = useState(true);
  const [term, setTerm] = useState('');
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState<Hotel | null>(null);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const [h, v] = await Promise.all([hotelsApi.list(), villesApi.list()]);
      setRows(unwrapList<Hotel>(h.data));
      setVilles(unwrapList<Ville>(v.data));
    } catch (err) {
      flash.error(getErrorMessage(err, 'Impossible de charger les hôtels'));
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  const villeName = useCallback(
    (h: Hotel) => h.ville?.ville || villes.find((v) => v.id === h.ville_id)?.ville || '',
    [villes],
  );

  const filtered = useMemo(
    () =>
      rows.filter((h) =>
        matchesSearch(
          term,
          h.hotel,
          villeName(h),
          h.mail,
          h.telephone,
          h.nom_responsable,
          h.adresse,
          h.reglement,
        ),
      ),
    [rows, term, villeName],
  );

  const openCreate = () => {
    setEditing(null);
    setFormOpen(true);
  };

  const openEdit = (h: Hotel) => {
    setEditing(h);
    setFormOpen(true);
  };

  const onDelete = async (h: Hotel) => {
    if (!confirm('Voulez-vous vraiment supprimer cet hôtel ?')) return;
    try {
      await hotelsApi.remove(h.id);
      flash.success("L'hôtel a été supprimé.");
      await load();
    } catch (err) {
      flash.error(getErrorMessage(err, "L'hôtel n'a pas pu être supprimé. Veuillez réessayer."));
    }
  };

  return (
    <div className="hotels-index">
      <PageHeader
        title="Liste des Hôtels"
        subtitle="Parcourez et gérez tous les hôtels disponibles."
      />
      <div className="row">
        <SearchBar onSearch={setTerm} />
        <div className="col">
          <div className="text-end">
            <button type="button" className="btn btn-primary-rounded" onClick={openCreate}>
              <i className="fa-solid fa-plus" /> Nouveau hôtel
            </button>
          </div>
        </div>
      </div>

      {loading ? (
        <Loading />
      ) : filtered.length === 0 ? (
        <div className="empty-hotels">Aucune donnée disponible</div>
      ) : (
        <div className="hotel-list">
          {filtered.map((h) => {
            const image = publicFileUrl('hotels', h.images);
            const stars = Math.max(0, Math.min(5, parseInt(h.etoile || '0', 10) || 0));
            return (
              <div key={h.id} className="hotel-card">
                {image && <div className="image" style={{ backgroundImage: `url('${image}')` }} />}
                <div className={`content-card ${image ? '' : 'full'}`.trim()}>
                  <div className="topcart">
                    <div className="head_card">
                      <h3 className="title-card">
                        <span>{h.hotel}</span>
                        <div className="ville">
                          {villeName(h) && (
                            <span>
                              <i className="fa-solid fa-location-dot" /> {villeName(h)}
                            </span>
                          )}
                          <div className="starts">
                            {Array.from({ length: stars }, (_, i) => (
                              <i key={i} className="fa-solid fa-star yellow m--3" />
                            ))}
                          </div>
                        </div>
                      </h3>
                      <div className="info">
                        <div className="emails">
                          {splitEmails(h.mail).map((email) => (
                            <span key={email} className="email">
                              <i className="fa-regular fa-envelope" /> {email} |&nbsp;
                            </span>
                          ))}
                        </div>
                        <div>
                          <span className="tel">
                            <i className="fa-solid fa-phone" /> {h.telephone}
                          </span>
                        </div>
                      </div>
                    </div>
                    <div className="profil-info">
                      <span className="respo">
                        <i className="fa-solid fa-user-tie" /> {h.nom_responsable}
                      </span>
                    </div>
                  </div>

                  <div className="sub-content">
                    {h.adresse && (
                      <div className="adress">
                        <span>
                          <i className="fa-solid fa-location-dot" /> {h.adresse}
                        </span>
                      </div>
                    )}
                    {h.reglement && <div className="reglement">{h.reglement}</div>}
                  </div>

                  <div className="actions text-end">
                    <Link to={`/hotels/${h.id}`}>Voir</Link> /
                    <a
                      href="#modifier"
                      onClick={(e) => {
                        e.preventDefault();
                        openEdit(h);
                      }}
                    >
                      modifier
                    </a>
                    /
                    <a
                      href="#supprimer"
                      onClick={(e) => {
                        e.preventDefault();
                        void onDelete(h);
                      }}
                    >
                      Supprimer
                    </a>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}

      <HotelFormModal
        open={formOpen}
        hotel={editing}
        villes={villes}
        onClose={() => setFormOpen(false)}
        onSaved={() => {
          setFormOpen(false);
          void load();
        }}
      />
    </div>
  );
}
