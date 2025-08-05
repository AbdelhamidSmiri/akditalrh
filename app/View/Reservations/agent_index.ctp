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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Réservations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .table-akdital {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table-akdital thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table-akdital thead th {
            border: none;
            padding: 15px 12px;
            font-weight: 600;
        }
        
        .table-akdital tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table-akdital tbody td {
            padding: 12px;
            vertical-align: middle;
            border: none;
        }
        
        .btn-primary-rounded {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .badge-en-cours {
            background-color: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .badge-valide {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .badge-refuse {
            background-color: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .jours-badge {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
        }
        
        .jours-urgent {
            background: #dc3545;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        .site-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>


<div class="container-fluid py-4">
    <!-- Titre -->
    <div class="header-section">

        <div class="row">
            <div class="col-12">
                <h1 class="main-title">Dashboard Réservations</h1>
            </div>
        </div>

    </div>

    <!-- Statistiques Principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-primary"><?php echo $total_reservations; ?></div>
                <div class="stats-label">Total Réservations</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-warning"><?php echo $en_cours; ?></div>
                <div class="stats-label">En Cours</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-success"><?php echo $valide; ?></div>
                <div class="stats-label">Validées</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number text-danger"><?php echo $refuse; ?></div>
                <div class="stats-label">Refusées</div>
            </div>
        </div>
    </div>

    <!-- Statistiques par Site -->
    <div class="row mb-4">
        <div class="col-12 ">
            <h1 class="main-title">Répartition par Site</h1>
            <div class="row">
                <?php foreach ($sites_stats as $site => $stats): ?>
                <div class="col-md-4">
                    <div class="site-card">
                        <h6 style="color: #667eea; margin-bottom: 10px;">
                            <i class="fas fa-building me-2"></i><?php echo $site; ?>
                        </h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <strong style="font-size: 1.5rem;"><?php echo $stats['total']; ?></strong><br>
                                <small class="text-muted">Total</small>
                            </div>
                            <div class="col-6">
                                <div style="font-size: 0.9rem;">
                                    <span class="text-success"><?php echo $stats['valide']; ?> validées</span><br>
                                    <span class="text-warning"><?php echo $stats['En cours']; ?> en cours</span><br>
                                    <span class="text-danger"><?php echo $stats['refuse']; ?> refusées</span>
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
                            switch($reservation['Reservation']['etat']) {
                                case 'en-cours':
                                    echo '<span class="badge-en-cours">⏳ En Cours</span>';
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
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript no simple pour la recherche -->
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