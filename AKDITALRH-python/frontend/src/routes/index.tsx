import { Navigate, Route, Routes, useParams } from 'react-router-dom';
import { AppShell } from '../components/layout/AppShell';
import { RequireAuth } from '../auth/RequireAuth';
import { RequireRole } from '../auth/RequireRole';
import { MfaGate } from '../auth/MfaGate';
import { MfaEnrollmentGate, MFA_SETUP_PATH } from '../auth/MfaEnrollmentGate';
import { LoginPage } from '../modules/auth/LoginPage';
import { MfaSetupPage } from '../modules/auth/MfaSetupPage';
import { MfaDisablePage } from '../modules/auth/MfaDisablePage';
import { MfaVerifyPage } from '../modules/auth/MfaVerifyPage';
import { ForgotPasswordPage } from '../modules/auth/ForgotPasswordPage';
import { DashboardPage } from '../modules/dashboard/DashboardPage';
import { UsersPage } from '../modules/users/UsersPage';
import { UserFormPage } from '../modules/users/UserFormPage';
import { UserDetailPage } from '../modules/users/UserDetailPage';
import { RolesPage } from '../modules/roles/RolesPage';
import { SitesPage } from '../modules/sites/SitesPage';
import { SiteDetailPage } from '../modules/sites/SiteDetailPage';
import { VillesPage } from '../modules/villes/VillesPage';
import { HotelsPage } from '../modules/hotels/HotelsPage';
import { HotelDetailPage } from '../modules/hotels/HotelDetailPage';
import { ReservationsPage } from '../modules/reservations/ReservationsPage';
import { ReservationFormPage } from '../modules/reservations/ReservationFormPage';
import { ReservationDetailPage } from '../modules/reservations/ReservationDetailPage';
import { PublicAcceptPage } from '../modules/reservations/PublicAcceptPage';
import { PublicRejectPage } from '../modules/reservations/PublicRejectPage';
import { VolsPage } from '../modules/vols/VolsPage';
import { VolFormPage } from '../modules/vols/VolFormPage';
import { VolDetailPage } from '../modules/vols/VolDetailPage';
import { VolEditPage } from '../modules/vols/VolEditPage';
import { AgenceVolsPage } from '../modules/vols/AgenceVolsPage';
import { AppartementsPage } from '../modules/logement/AppartementsPage';
import { BeneficiairesPage } from '../modules/logement/BeneficiairesPage';
import { AffectationsPage } from '../modules/logement/AffectationsPage';
import { VoituresPage } from '../modules/parc/VoituresPage';
import { CalendrierPage } from '../modules/parc/CalendrierPage';
import { DemandesVoituresPage } from '../modules/parc/DemandesVoituresPage';
import { ReferentielsPage } from '../modules/parc/ReferentielsPage';
import { RequetesPage } from '../modules/parc/RequetesPage';
import { CartesPage } from '../modules/carburant/CartesPage';
import { TagJawazPage } from '../modules/jawaz/TagJawazPage';

function ProtectedLayout() {
  return (
    <RequireAuth>
      <MfaGate>
        <MfaEnrollmentGate>
          <AppShell />
        </MfaEnrollmentGate>
      </MfaGate>
    </RequireAuth>
  );
}

function RedirectAdminUserToView() {
  const { id } = useParams();
  return <Navigate to={`/users/${id}`} replace />;
}

export function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<LoginPage />} />
      <Route path="/mfa/setup" element={<Navigate to={MFA_SETUP_PATH} replace />} />
      <Route path="/mfa/verify" element={<MfaVerifyPage />} />
      <Route path="/forgot-password" element={<ForgotPasswordPage />} />
      <Route path="/public/reservation/:token/accept" element={<PublicAcceptPage />} />
      <Route path="/public/reservation/:token/reject" element={<PublicRejectPage />} />

      <Route element={<ProtectedLayout />}>
        <Route path="/" element={<Navigate to="/dashboard" replace />} />
        <Route path="/dashboard" element={<DashboardPage />} />
        <Route path="/reservations" element={<ReservationsPage />} />
        <Route path="/reservations/new" element={<ReservationFormPage />} />
        <Route path="/reservations/:id" element={<ReservationDetailPage />} />
        <Route path="/vols" element={<VolsPage />} />
        <Route path="/vols/new" element={<VolFormPage />} />
        <Route path="/vols/:id/edit" element={<VolEditPage />} />
        <Route path="/vols/:id" element={<VolDetailPage />} />
        <Route
          path="/agence/vols"
          element={
            <RequireRole roles={['Agence', 'Admin']}>
              <AgenceVolsPage />
            </RequireRole>
          }
        />
        <Route path="/hotels" element={<HotelsPage />} />
        <Route path="/hotels/:id" element={<HotelDetailPage />} />
        <Route path="/logement/appartements" element={<AppartementsPage />} />
        <Route path="/logement/affectations" element={<AffectationsPage />} />
        <Route path="/logement/beneficiaires" element={<BeneficiairesPage />} />
        <Route path="/parc/voitures" element={<VoituresPage />} />
        <Route path="/parc/calendrier" element={<CalendrierPage />} />
        <Route path="/parc/demandes" element={<DemandesVoituresPage />} />
        <Route path="/parc/referentiels" element={<ReferentielsPage />} />
        <Route path="/parc/requetes" element={<RequetesPage />} />
        <Route path="/carburant" element={<CartesPage />} />
        <Route path="/jawaz" element={<TagJawazPage />} />

        <Route path={MFA_SETUP_PATH} element={<MfaSetupPage />} />
        <Route path="/users/mfa-disable" element={<MfaDisablePage />} />
        <Route path="/users/:id" element={<UserDetailPage />} />
        <Route path="/sites/:id" element={<SiteDetailPage />} />

        <Route
          path="/admin/users"
          element={
            <RequireRole roles={['Admin']}>
              <UsersPage />
            </RequireRole>
          }
        />
        <Route
          path="/admin/users/new"
          element={
            <RequireRole roles={['Admin']}>
              <UserFormPage />
            </RequireRole>
          }
        />
        <Route
          path="/admin/users/:id/edit"
          element={
            <RequireRole roles={['Admin']}>
              <UserFormPage />
            </RequireRole>
          }
        />
        <Route path="/admin/users/:id" element={<RedirectAdminUserToView />} />
        <Route
          path="/admin/roles"
          element={
            <RequireRole roles={['Admin']}>
              <RolesPage />
            </RequireRole>
          }
        />
        <Route
          path="/admin/sites"
          element={
            <RequireRole roles={['Admin']}>
              <SitesPage />
            </RequireRole>
          }
        />
        <Route
          path="/admin/villes"
          element={
            <RequireRole roles={['Admin']}>
              <VillesPage />
            </RequireRole>
          }
        />
      </Route>

      <Route path="*" element={<Navigate to="/dashboard" replace />} />
    </Routes>
  );
}
