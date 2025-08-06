<?php
// Calculs simples pour les statistiques
$total_reservations = count($reservations);
$en_cours = 0;
$valide = 0;
$refuse = 0;
$sites_stats = array();

foreach ($reservations as $reservation) {
    // Comptage par statut
    switch ($reservation['Reservation']['etat']) {
        case 'En cours':
            $en_cours++;
            break;
        case 'valide':
            $valide++;
            break;
        case 'refuse':
            $refuse++;
            break;
    }

    // Comptage par site
    $site = $reservation['Site']['site'];
    if (!isset($sites_stats[$site])) {
        $sites_stats[$site] = array('total' => 0, 'En cours' => 0, 'valide' => 0, 'refuse' => 0);
    }
    $sites_stats[$site]['total']++;
    if (!isset($sites_stats[$site][$reservation['Reservation']['etat']]))
        $sites_stats[$site][$reservation['Reservation']['etat']] = 0;
    $sites_stats[$site][$reservation['Reservation']['etat']]++;
}

// Fonction pour calculer les jours restants
function joursRestants($checkin_date)
{
    $aujourd_hui = new DateTime();
    $checkin = new DateTime($checkin_date);
    $diff = $aujourd_hui->diff($checkin);

    if ($checkin < $aujourd_hui) {
        return -1; // Déjà passé
    }
    return $diff->days;
}

// Fonction pour calculer la durée du séjour
function dureeSejour($checkin_date, $checkout_date)
{
    $checkin = new DateTime($checkin_date);
    $checkout = new DateTime($checkout_date);
    $diff = $checkin->diff($checkout);
    return $diff->days;
}
?>


<style>
    .stat-card {
        border-radius: 12px;
        height: 120px;
    }

    .stat-card.dark {
        background-color: #2d3748;
        color: white;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 600;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.95rem;
        font-weight: 400;
        line-height: 1.3;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        font-size: 1.2rem;
        background-color: rgb(214 234 248);
        color: #3498db;
    }

    .table-akdital td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .main-title {
        color: #2c3e50;
        font-size: 22px;
        font-weight: 500;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .subtitle {
        color: #6c757d;
        font-size: 1rem;
        font-weight: 400;
        margin-bottom: 0;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .main-title {
            font-size: 1.5rem;
        }


    }

    .bg-info {
        background-color: rgb(214 234 248) !important;
    }
</style>


<div class="container-fluid py-4">
    <!-- Statistiques Principales -->
    <div class="row g-3 justify-content-center mb-5">
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card view-card border-1 h-100">
                <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex flex-column">
                        <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                            <?php echo $total_reservations; ?>
                        </div>
                        <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                            Total Réservations
                        </div>
                    </div>
                    <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card view-card border-1 h-100">
                <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex flex-column">
                        <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                            <?php echo $en_cours; ?>
                        </div>
                        <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                            En Cours
                        </div>
                    </div>
                    <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6 - Validées -->
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card view-card border-1 h-100">
                <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex flex-column">
                        <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                            <?php echo $valide; ?>
                        </div>
                        <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                            Validées
                        </div>
                    </div>
                    <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 7 - Refusées -->
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card view-card border-1 h-100">
                <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                    <div class="d-flex flex-column">
                        <div class="stats-number  mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                            <?php echo $refuse; ?>
                        </div>
                        <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                            Refusées
                        </div>
                    </div>
                    <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 ">
            <h1 class="main-title">Répartition par Site</h1>
            <div class="row">
                <?php foreach ($sites_stats as $site => $stats): ?>
                    <div class="col-md-6">
                        <div class="card view-card border-1 h-100">
                            <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                                <div class="d-flex flex-column">
                                    <div class="stats-number mb-1" style="font-size: 17px;; font-weight: 600; line-height: 1;">
                                        <?php echo $site; ?>
                                    </div>
                                    <div class="stats-label mb-0" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                                        <strong style="font-size: 1.2rem;"><?php echo $stats['total']; ?></strong> <span class="stats-label mb-0 text-muted"> total</span><br>
                                        <span class="badge stats-label mb-0 text-muted"><?php echo $stats['valide']; ?> validées</span>
                                        <span class="badge stats-label mb-0 text-muted"><?php echo $stats['En cours']; ?> en cours</span>
                                        <span class=" badge stats-label mb-0 text-muted"><?php echo $stats['refuse']; ?> refusées</span>
                                    </div>
                                </div>
                                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Section de Recherche (votre code original) -->
    <div class="col-md-5">
        <div class="search-section">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-primary-rounded search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
            </div>
        </div>
    </div>

    <!-- Tableau des Réservations (votre code amélioré) -->
    <div class="col-md-12 filter-section"></div>
    <div class="content-table">
        <table class="table table-akdital">
            <thead>
                <tr>
                    <th>Demandeur</th>
                    <th>Hotel</th>
                    <th>Chambre</th>
                    <th>Site</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Jours Restants</th>
                    <th>Durée Séjour</th>
                    <th>N° ODM</th>
                    <th>Etat</th>
                    <th>Date de demande</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <?php
                    // Calculs pour cette réservation
                    $jours_restants = joursRestants($reservation['Reservation']['checkin']);
                    $duree_sejour = dureeSejour($reservation['Reservation']['checkin'], $reservation['Reservation']['checkout']);
                    ?>
                    <tr>
                        <td><strong><?php echo $reservation['User']['nom']; ?></strong></td>
                        <td>
                            <?php
                            $hotelId = $reservation['Chambre']['hotel_id'];
                            echo isset($hotels[$hotelId]) ? $hotels[$hotelId] : '';
                            ?>
                        </td>
                        <td><?php echo $reservation['Chambre']['nom']; ?></td>
                        <td><?php echo $reservation['Site']['site']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reservation['Reservation']['checkin'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reservation['Reservation']['checkout'])); ?></td>
                        <td>
                            <?php if ($jours_restants == -1): ?>
                                <span class="status-btn passe">
                                    <i class="fas fa-calendar-times"></i> Passé
                                </span>
                            <?php elseif ($jours_restants <= 3): ?>
                                <span class="jours-badge jours-urgent"><?php echo $jours_restants; ?> jour<?php echo $jours_restants > 1 ? 's' : ''; ?></span>
                            <?php else: ?>
                                <span class="jours-badge"><?php echo $jours_restants; ?> jour<?php echo $jours_restants > 1 ? 's' : ''; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark"><?php echo $duree_sejour; ?> jour<?php echo $duree_sejour > 1 ? 's' : ''; ?></span>
                        </td>
                        <td> <span class="badge bg-info text-dark"><?php echo $reservation['Reservation']['num_odm']; ?></span></td>
                        <td>
                            <?php
                            switch ($reservation['Reservation']['etat']) {

                                case 'En cours':
                                    echo '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
                                    break;
                                case 'acceptée':
                                    echo '<span class="status-btn confirmed"><i class="fas fa-check-circle"></i> Confirmé</span>';
                                    break;
                                case 'refusée':
                                    echo '<span class="status-btn refused"><i class="fas fa-times-circle"></i> Refusée</span>';
                                    break;
                                case 'passe':
                                    echo '<span class="status-btn passe"><i class="fas fa-calendar-times"></i> Passé</span>';
                                    break;
                                default:
                                    echo '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . htmlspecialchars($reservation['Reservation']['etat']) . '</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($reservation['Reservation']['created'])); ?></td>
                        <td class="actions">
                            <?php echo $this->Html->link(__('Voir'), array('action' => 'view', $reservation['Reservation']['id'])); ?>
                            /
                            <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $reservation['Reservation']['id'])); ?>
                            /
                            <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $reservation['Reservation']['id']), array('confirm' => __('Êtes-vous sûr de vouloir supprimer # %s?', $reservation['Reservation']['id']))); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript simple pour la recherche -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction de recherche simple
        const searchInput = document.getElementById('search_input');
        const searchBtn = document.querySelector('.search-btn');

        function rechercher() {
            const terme = searchInput.value.toLowerCase();
            const lignes = document.querySelectorAll('tbody tr');

            lignes.forEach(function(ligne) {
                const texte = ligne.textContent.toLowerCase();
                if (texte.includes(terme)) {
                    ligne.style.display = '';
                } else {
                    ligne.style.display = 'none';
                }
            });
        }

        searchBtn.addEventListener('click', rechercher);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                rechercher();
            }
        });
    });
</script>