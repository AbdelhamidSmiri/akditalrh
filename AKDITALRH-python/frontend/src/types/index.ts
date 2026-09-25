export type RoleName =
  | 'Admin'
  | 'Agence'
  | 'Achat'
  | 'Directeur de projet'
  | 'DRH'
  | 'RH'
  | 'DRACHAT'
  | 'DGD'
  | string;

export interface Role {
  id: number;
  role: RoleName;
  plafond_hotel?: string | null;
}

export interface Ville {
  id: number;
  ville: string;
}

export interface Site {
  id: number;
  ville_id: number;
  site: string;
  adresse?: string | null;
  telephone?: string | null;
  mail?: string | null;
  created?: string | null;
  ville?: Ville | null;
}

export interface User {
  id: number;
  role_id: number;
  site_id: number;
  username: string;
  etat?: string | null;
  nom?: string | null;
  prenom?: string | null;
  poste?: string | null;
  direction?: string | null;
  frequence_mobilite?: string | null;
  code?: string | null;
  tel?: string | null;
  statut?: string | null;
  totp_enabled?: boolean;
  mfa_required?: boolean;
  mfa_enrollment_required?: boolean;
  created?: string | null;
  role?: Role | null;
  site?: Site | null;
}

export interface AuthUser extends User {
  role_name?: string;
}

export interface LoginResponse {
  access_token?: string;
  refresh_token?: string;
  mfa_required?: boolean;
  mfa_token?: string;
  mfa_setup_required?: boolean;
  user?: AuthUser;
}

export interface MfaSetupResponse {
  secret: string;
  qr_data_url?: string;
  otpauth_uri?: string;
  qr_code?: string;
  otpauth_url?: string;
}

export interface Hotel {
  id: number;
  hotel: string;
  nom?: string | null;
  etoile?: string | null;
  region?: string | null;
  ville_id: number;
  adresse?: string | null;
  images?: string | null;
  mail?: string | null;
  telephone?: string | null;
  nom_responsable?: string | null;
  reglement?: string | null;
  created?: string | null;
  ville?: Ville | null;
  chambres?: Chambre[];
}

export interface Chambre {
  id: number;
  hotel_id: number;
  nom?: string | null;
  type?: string | null;
  type_chambre?: string | null;
  designation?: string | null;
  capacite?: string | null;
  etat?: string | null;
  images?: string | null;
  hotel?: Hotel | null;
  prices?: HotelPrice[];
}

export interface HotelPrice {
  id: number;
  chambre_id: number;
  prix?: string | null;
  devise?: string | null;
  date_debut?: string | null;
  date_fin?: string | null;
}

export interface Reservation {
  id: number;
  user_id?: number;
  hotel_id?: number;
  chambre_id?: number;
  site_id?: number;
  ville_id?: number;
  checkin?: string | null;
  checkout?: string | null;
  date_arrivee?: string | null;
  date_depart?: string | null;
  num_odm?: string | null;
  odm?: string | null;
  ordre_mission?: string | null;
  cin?: string | null;
  statut?: string | null;
  etat?: string | null;
  commentaire?: string | null;
  message?: string | null;
  created?: string | null;
  hotel_nom?: string | null;
  hotel?: Hotel | null;
  chambre?: Chambre | null;
  user?: User | null;
  site?: Site | null;
  ville?: Ville | null;
}

export interface VolReservation {
  id: number;
  user_id?: number;
  site_id?: number;
  nom?: string | null;
  prenom?: string | null;
  depart?: string | null;
  destination?: string | null;
  origine?: string | null;
  date_aller?: string | null;
  date_retour?: string | null;
  date_depart?: string | null;
  num_odm?: string | null;
  message?: string | null;
  cin?: string | null;
  motif?: string | null;
  statut?: string | null;
  etat?: string | null;
  commentaire?: string | null;
  reponse?: string | null;
  date_reponse?: string | null;
  num_vol?: string | null;
  archived?: boolean | null;
  created?: string | null;
  site_nom?: string | null;
  transfer?: string | null;
  nom_transfer?: string | null;
  date_transfer?: string | null;
  tel_transfer?: string | null;
  description_transfer?: string | null;
  user?: User | null;
  site?: Site | null;
}

export interface Appartement {
  id: number;
  nom?: string | null;
  designation?: string | null;
  adresse?: string | null;
  ville_id?: number | null;
  sexe?: string | null;
  capacite?: string | number | null;
  etat?: string | null;
  images?: string | null;
  ville?: Ville | null;
  created?: string | null;
  ocupants?: number;
  places_dispo?: number;
  nb_occupants?: number;
}

export interface Beneficiaire {
  id: number;
  site_id?: number | null;
  user_id?: number | null;
  appartement_id?: number | null;
  nom?: string | null;
  prenom?: string | null;
  sexe?: string | null;
  mail?: string | null;
  telephone?: string | null;
  status?: string | null;
  etat?: string | null;
  statut?: string | null;
  date_debut?: string | null;
  date_fin?: string | null;
  date_entree?: string | null;
  date_sortie?: string | null;
  note?: string | null;
  created?: string | null;
  site?: Pick<Site, 'id' | 'site'> | null;
  user?: Pick<User, 'id' | 'nom' | 'prenom' | 'username'> | null;
  appartement?: Pick<Appartement, 'id' | 'nom' | 'sexe' | 'capacite'> | null;
}

export interface LogementDisponibilites {
  global: {
    total_appartements: number;
    total_ocupes: number;
    total_places_dispo: number;
  };
  stats: Array<{
    ville: string;
    sexe: string;
    total: number;
    ocupes: number;
    places_dispo: number;
  }>;
  appart_info: Array<{
    id: number;
    nom?: string | null;
    ville?: string | null;
    sexe?: string | null;
    capacite: number;
    ocupants: number;
    places_dispo: number;
  }>;
}

export interface Voiture {
  id: number;
  user_id?: number | null;
  ste_location?: string | null;
  immatriculation?: string | null;
  marque?: string | null;
  modele?: string | null;
  valeur_locative_ht?: number | null;
  duree_mois?: number | null;
  km?: number | null;
  cout_km_suplm_ttc?: number | null;
  nbr_pneus?: number | null;
  service_link?: string | null;
  categorie_vr?: string | null;
  livraison?: string | null;
  fin_contrat?: string | null;
  km_actuel?: number | null;
  created?: string | null;
  /** legacy aliases */
  categorie?: string | null;
  fournisseur?: string | null;
  site_id?: number | null;
  etat?: string | null;
  statut?: string | null;
  site?: Site | string | null;
  user?: (Pick<User, 'id' | 'nom' | 'prenom' | 'username'> & { site?: string | null }) | null;
}

export interface DemandeVoiture {
  id: number;
  user_id?: number;
  marque?: string | null;
  modele?: string | null;
  duree_mois?: number | null;
  categorie_vr?: string | null;
  livraison?: string | null;
  status_demande?: string | null;
  created?: string | null;
  description?: string | null;
  /** legacy aliases */
  voiture_id?: number | null;
  date_debut?: string | null;
  date_fin?: string | null;
  motif?: string | null;
  statut?: string | null;
  etat?: string | null;
  user?: Pick<User, 'id' | 'nom' | 'prenom' | 'username'> | null;
  voiture?: Voiture | null;
}

export interface Referentiel {
  id: number;
  modele?: string | null;
  duree?: string | null;
  kilometrage?: string | null;
  loyer_ht_ald?: number | null;
  loyer_ttc_ald?: number | null;
  km_supp_ht_ald?: number | null;
  nb_pneus_ald?: number | null;
  cat_remplacement_ald?: string | null;
  loyer_ht_arval?: number | null;
  loyer_ttc_arval?: number | null;
  km_supp_ht_arval?: number | null;
  nb_pneus_arval?: number | null;
  cat_remplacement_arval?: string | null;
  moyenne_offres_ttc?: number | null;
  valeur_actuelle_moy_ttc?: number | null;
  augmentation_pourcent?: string | null;
  categorie?: string | null;
}

export interface CarteCarburant {
  id: number;
  user_id?: number | null;
  site_id?: number | null;
  prestataire?: string | null;
  identifiant?: string | null;
  carte_labelle?: string | null;
  utilisateur?: string | null;
  nv_plafond?: number | string | null;
  type_mt?: string | null;
  status?: string | null;
  created?: string | null;
  user?: Pick<User, 'id' | 'nom' | 'prenom' | 'username'> | null;
  site?: Pick<Site, 'id' | 'site'> | null;
  /** legacy aliases */
  numero?: string | null;
  fournisseur?: string | null;
  plafond?: string | null;
  statut?: string | null;
  etat?: string | null;
}

export interface TagJawaz {
  id: number;
  user_id?: number | null;
  site_id?: number | null;
  ref_jawaz?: string | null;
  user?: Pick<User, 'id' | 'nom' | 'prenom' | 'username'> | null;
  site?: Pick<Site, 'id' | 'site'> | null;
  /** legacy aliases */
  numero?: string | null;
  immatriculation?: string | null;
  statut?: string | null;
  etat?: string | null;
}

/** Alias DemandeVoiture pour la vue « Requêtes collaborateurs ». */
export type ParcRequete = DemandeVoiture;

export interface CalendrierContrat {
  id: number;
  immatriculation?: string | null;
  marque?: string | null;
  modele?: string | null;
  livraison?: string | null;
  fin_contrat?: string | null;
  date_debut?: string | null;
  date_fin?: string | null;
  ste_location?: string | null;
  km_actuel?: number | null;
  valeur_locative_ht?: number | null;
  duree_mois?: number | null;
  km?: number | null;
  cout_km_suplm_ttc?: number | null;
  demandeur?: string | null;
  site?: string | null;
  user?: Pick<User, 'id' | 'nom' | 'prenom'> & { site?: string | null } | null;
}

export interface Paginated<T> {
  items: T[];
  total: number;
  page: number;
  per_page: number;
  pages?: number;
}

export interface ApiError {
  message?: string;
  error?: string;
  errors?: Record<string, string[]>;
}

export type ListResponse<T> = T[] | Paginated<T>;
