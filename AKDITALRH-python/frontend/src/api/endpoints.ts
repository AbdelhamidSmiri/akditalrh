import api from './client';
import type {
  Appartement,
  Beneficiaire,
  CarteCarburant,
  Chambre,
  DemandeVoiture,
  Hotel,
  HotelPrice,
  LoginResponse,
  MfaSetupResponse,
  ParcRequete,
  Referentiel,
  Reservation,
  Role,
  Site,
  TagJawaz,
  User,
  Ville,
  Voiture,
  VolReservation,
} from '../types';

export const authApi = {
  login: (username: string, password: string) =>
    api.post<LoginResponse>('/auth/login', { username, password }),
  mfaVerify: (mfa_token: string, code: string) =>
    api.post<LoginResponse>('/auth/mfa/verify', { mfa_token, code }),
  mfaSetup: () => api.post<MfaSetupResponse>('/auth/mfa/setup', {}),
  mfaEnable: (code: string) => api.post('/auth/mfa/enable', { code }),
  mfaDisable: (code: string) => api.post('/auth/mfa/disable', { code }),
  refresh: (refresh_token: string) =>
    api.post<LoginResponse>('/auth/refresh', { refresh_token }),
  logout: () => api.post('/auth/logout'),
  forgotPassword: (username: string) =>
    api.post('/auth/forgot-password', { username }),
  me: () => api.get<User>('/users/me'),
};

export const usersApi = {
  list: (params?: Record<string, unknown>) => api.get('/users', { params }),
  get: (id: number | string) => api.get<User>(`/users/${id}`),
  create: (payload: Partial<User> & { password?: string }) =>
    api.post<User>('/users', payload),
  update: (id: number | string, payload: Partial<User> & { password?: string }) =>
    api.patch<User>(`/users/${id}`, payload),
  remove: (id: number | string) => api.delete(`/users/${id}`),
};

export const rolesApi = {
  list: () => api.get('/roles'),
  get: (id: number | string) => api.get<Role>(`/roles/${id}`),
  create: (payload: Partial<Role>) => api.post<Role>('/roles', payload),
  update: (id: number | string, payload: Partial<Role>) =>
    api.patch<Role>(`/roles/${id}`, payload),
  remove: (id: number | string) => api.delete(`/roles/${id}`),
};

export const sitesApi = {
  list: (params?: Record<string, unknown>) => api.get('/sites', { params }),
  get: (id: number | string) => api.get<Site>(`/sites/${id}`),
  create: (payload: Partial<Site>) => api.post<Site>('/sites', payload),
  update: (id: number | string, payload: Partial<Site>) =>
    api.patch<Site>(`/sites/${id}`, payload),
  remove: (id: number | string) => api.delete(`/sites/${id}`),
};

export const villesApi = {
  list: () => api.get('/villes'),
  get: (id: number | string) => api.get<Ville>(`/villes/${id}`),
  create: (payload: Partial<Ville>) => api.post<Ville>('/villes', payload),
  update: (id: number | string, payload: Partial<Ville>) =>
    api.patch<Ville>(`/villes/${id}`, payload),
  remove: (id: number | string) => api.delete(`/villes/${id}`),
};

export const hotelsApi = {
  list: (params?: Record<string, unknown>) => api.get('/hotels', { params }),
  get: (id: number | string) => api.get<Hotel>(`/hotels/${id}`),
  create: (payload: Partial<Hotel>) => api.post<Hotel>('/hotels', payload),
  update: (id: number | string, payload: Partial<Hotel>) =>
    api.patch<Hotel>(`/hotels/${id}`, payload),
  remove: (id: number | string) => api.delete(`/hotels/${id}`),
  available: (params: Record<string, unknown>) =>
    api.get('/hotels/available', { params }),
  listChambres: (hotelId: number | string) =>
    api.get(`/hotels/${hotelId}/chambres`),
  createChambre: (hotelId: number | string, payload: Partial<Chambre>) =>
    api.post<Chambre>(`/hotels/${hotelId}/chambres`, payload),
  updateChambre: (id: number | string, payload: Partial<Chambre>) =>
    api.patch<Chambre>(`/hotels/chambres/${id}`, payload),
  removeChambre: (id: number | string) => api.delete(`/hotels/chambres/${id}`),
  createPrice: (chambreId: number | string, payload: Partial<HotelPrice>) =>
    api.post(`/hotels/chambres/${chambreId}/prices`, payload),
  syncPrices: (
    chambreId: number | string,
    payload: { prices: Partial<HotelPrice>[]; delete_ids: number[] },
  ) => api.put<Chambre>(`/hotels/chambres/${chambreId}/prices`, payload),
};

export const reservationsApi = {
  list: (params?: Record<string, unknown>) =>
    api.get('/reservations', { params }),
  get: (id: number | string) => api.get<Reservation>(`/reservations/${id}`),
  create: (payload: Partial<Reservation> | FormData) =>
    api.post<Reservation>('/reservations', payload, {
      headers:
        payload instanceof FormData
          ? { 'Content-Type': 'multipart/form-data' }
          : undefined,
    }),
  update: (id: number | string, payload: Partial<Reservation>) =>
    api.patch<Reservation>(`/reservations/${id}`, payload),
  remove: (id: number | string) => api.delete(`/reservations/${id}`),
  publicAcceptGet: (token: string) =>
    api.get(`/public/reservations/${token}/accept`),
  publicAccept: (token: string, payload?: Record<string, unknown>) =>
    api.post(`/public/reservations/${token}/accept`, payload ?? {}),
  publicRejectGet: (token: string) =>
    api.get(`/public/reservations/${token}/reject`),
  publicReject: (token: string, payload?: Record<string, unknown>) =>
    api.post(`/public/reservations/${token}/reject`, payload ?? {}),
};

export const volsApi = {
  list: (params?: Record<string, unknown>) => api.get('/vols', { params }),
  agence: (params?: Record<string, unknown>) =>
    api.get('/vols/agence', { params }),
  get: (id: number | string) => api.get<VolReservation>(`/vols/${id}`),
  create: (payload: Partial<VolReservation>) =>
    api.post<VolReservation>('/vols', payload),
  update: (id: number | string, payload: Partial<VolReservation>) =>
    api.patch<VolReservation>(`/vols/${id}`, payload),
  remove: (id: number | string) => api.delete(`/vols/${id}`),
  valider: (id: number | string, payload?: Record<string, unknown>) =>
    api.post(`/vols/${id}/valider`, payload ?? {}),
  annuler: (id: number | string, payload?: Record<string, unknown>) =>
    api.post(`/vols/${id}/annuler`, payload ?? {}),
  archive: (id: number | string) => api.post(`/vols/${id}/archive`),
};

export const logementApi = {
  listAppartements: (params?: Record<string, unknown>) =>
    api.get('/appartements', { params }),
  disponibilites: () => api.get('/appartements/disponibilites'),
  recherche: (payload: {
    date_debut: string;
    date_fin: string;
    sexe: string;
    ville_id: number;
  }) => api.post('/appartements/recherche', payload),
  getAppartement: (id: number | string) =>
    api.get<Appartement>(`/appartements/${id}`),
  createAppartement: (payload: Partial<Appartement>) =>
    api.post<Appartement>('/appartements', payload),
  updateAppartement: (id: number | string, payload: Partial<Appartement>) =>
    api.patch<Appartement>(`/appartements/${id}`, payload),
  removeAppartement: (id: number | string) =>
    api.delete(`/appartements/${id}`),
  listBeneficiaires: (params?: Record<string, unknown>) =>
    api.get('/beneficiaires', { params }),
  getBeneficiaire: (id: number | string) =>
    api.get<Beneficiaire>(`/beneficiaires/${id}`),
  createBeneficiaire: (payload: Partial<Beneficiaire>) =>
    api.post('/beneficiaires', payload),
  updateBeneficiaire: (id: number | string, payload: Partial<Beneficiaire>) =>
    api.patch<Beneficiaire>(`/beneficiaires/${id}`, payload),
  removeBeneficiaire: (id: number | string) =>
    api.delete(`/beneficiaires/${id}`),
  transitionBeneficiaire: (id: number | string, etat: string) =>
    api.post(`/beneficiaires/${id}/transition`, { etat }),
};

export const parcApi = {
  listVoitures: (params?: Record<string, unknown>) =>
    api.get('/voitures', { params }),
  getVoiture: (id: number | string) => api.get<Voiture>(`/voitures/${id}`),
  createVoiture: (payload: Partial<Voiture>) =>
    api.post<Voiture>('/voitures', payload),
  updateVoiture: (id: number | string, payload: Partial<Voiture>) =>
    api.patch<Voiture>(`/voitures/${id}`, payload),
  removeVoiture: (id: number | string) => api.delete(`/voitures/${id}`),
  calendrier: (params?: Record<string, unknown>) =>
    api.get('/voitures/calendrier', { params }),
  importVoitures: (file: FormData) =>
    api.post('/voitures/import', file, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  listDemandes: (params?: Record<string, unknown>) =>
    api.get('/demande-voitures', { params }),
  getDemande: (id: number | string) =>
    api.get<DemandeVoiture>(`/demande-voitures/${id}`),
  createDemande: (payload: Partial<DemandeVoiture>) =>
    api.post<DemandeVoiture>('/demande-voitures', payload),
  updateDemande: (id: number | string, payload: Partial<DemandeVoiture>) =>
    api.patch<DemandeVoiture>(`/demande-voitures/${id}`, payload),
  removeDemande: (id: number | string) =>
    api.delete(`/demande-voitures/${id}`),
  transitionDemande: (id: number | string, etat: string) =>
    api.post(`/demande-voitures/${id}/transition`, { etat }),
  listReferentiels: (params?: Record<string, unknown>) =>
    api.get('/referentiels', { params }),
  createReferentiel: (payload: Partial<Referentiel>) =>
    api.post<Referentiel>('/referentiels', payload),
  updateReferentiel: (id: number | string, payload: Partial<Referentiel>) =>
    api.patch<Referentiel>(`/referentiels/${id}`, payload),
  removeReferentiel: (id: number | string) =>
    api.delete(`/referentiels/${id}`),
  importReferentiels: (file: FormData) =>
    api.post('/referentiels/import', file, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  listRequetes: (params?: Record<string, unknown>) =>
    api.get('/parc/requetes', { params }),
  createRequete: (payload: Partial<ParcRequete>) =>
    api.post<ParcRequete>('/parc/requetes', payload),
  updateRequete: (id: number | string, payload: Partial<ParcRequete>) =>
    api.patch<ParcRequete>(`/parc/requetes/${id}`, payload),
  removeRequete: (id: number | string) => api.delete(`/parc/requetes/${id}`),
};

export const carburantApi = {
  list: (params?: Record<string, unknown>) =>
    api.get('/cartes-carburant', { params }),
  get: (id: number | string) =>
    api.get<CarteCarburant>(`/cartes-carburant/${id}`),
  create: (payload: Partial<CarteCarburant>) =>
    api.post<CarteCarburant>('/cartes-carburant', payload),
  update: (id: number | string, payload: Partial<CarteCarburant>) =>
    api.patch<CarteCarburant>(`/cartes-carburant/${id}`, payload),
  remove: (id: number | string) => api.delete(`/cartes-carburant/${id}`),
  import: (file: FormData) =>
    api.post('/cartes-carburant/import', file, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
};

export const jawazApi = {
  list: (params?: Record<string, unknown>) => api.get('/tag-jawaz', { params }),
  get: (id: number | string) => api.get<TagJawaz>(`/tag-jawaz/${id}`),
  create: (payload: Partial<TagJawaz>) =>
    api.post<TagJawaz>('/tag-jawaz', payload),
  update: (id: number | string, payload: Partial<TagJawaz>) =>
    api.patch<TagJawaz>(`/tag-jawaz/${id}`, payload),
  remove: (id: number | string) => api.delete(`/tag-jawaz/${id}`),
  import: (file: FormData) =>
    api.post('/tag-jawaz/import', file, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
};

export const filesApi = {
  upload: (file: FormData) =>
    api.post('/files/upload', file, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
};

export const dashboardApi = {
  stats: () => api.get('/dashboard/stats'),
};
