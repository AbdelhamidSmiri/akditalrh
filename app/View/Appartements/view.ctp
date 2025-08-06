<?php
// Calculs des statistiques
$beneficiaires = $appartement["Beneficiaire"];
$stats = [
    'total' => count($beneficiaires),
    'en_cours' => 0,
    'valider' => 0,
    'checkin' => 0,
    'checkout' => 0,
    'annuler' => 0,
    'capacite' => $appartement['Appartement']['capacite'],
    'occupation_actuelle' => 0,
    'revenus_estimés' => 0,
    'jours_total_occupation' => 0
];

$beneficiaires_actifs = [];
$beneficiaires_historique = [];

foreach ($beneficiaires as $beneficiaire) {
    switch ($beneficiaire['etat']) {
        case 'En cours':
            $stats['en_cours']++;
            $beneficiaires_actifs[] = $beneficiaire;
            break;
        case 'Valider':
            $stats['valider']++;
            $beneficiaires_actifs[] = $beneficiaire;
            break;
        case 'Checkin':
            $stats['checkin']++;
            $stats['occupation_actuelle']++;
            $beneficiaires_actifs[] = $beneficiaire;
            break;
        case 'Checkout':
            $stats['checkout']++;
            $beneficiaires_historique[] = $beneficiaire;
            break;
        case 'Annuler':
            $stats['annuler']++;
            $beneficiaires_historique[] = $beneficiaire;
            break;
    }
}

// Calcul du taux d'occupation
$taux_occupation = $stats['capacite'] > 0 ? round(($stats['occupation_actuelle'] / $stats['capacite']) * 100, 1) : 0;

// Fonction pour calculer les jours restants
function calculerJoursRestants($dateDebut, $dateFin)
{
    if (!$dateDebut || !$dateFin) return 0;
    $debut = new DateTime($dateDebut);
    $fin = new DateTime($dateFin);
    $aujourd_hui = new DateTime();

    if ($aujourd_hui > $fin) return 0;
    if ($aujourd_hui < $debut) return $debut->diff($fin)->days;

    return $aujourd_hui->diff($fin)->days;
}

function calculerDureeTotal($dateDebut, $dateFin)
{
    if (!$dateDebut || !$dateFin) return 0;
    $debut = new DateTime($dateDebut);
    $fin = new DateTime($dateFin);
    return $debut->diff($fin)->days;
}
?>

<style>
    /* Styles généraux */
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f8f9fa;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2.5em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9em;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-en-cours {
        color: #ffc107;
    }

    .stat-valider {
        color: #17a2b8;
    }

    .stat-checkin {
        color: #28a745;
    }

    .stat-checkout {
        color: #6c757d;
    }

    .stat-annuler {
        color: #dc3545;
    }

    .stat-occupation {
        color: #007bff;
    }

    /* Styles pour les tableaux */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .table-header {
        background: #343a40;
        color: white;
        padding: 20px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .table-akdital {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table-akdital th {
        background-color: #f8f9fa;
        padding: 15px 10px;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .table-akdital td {
        padding: 12px 10px;
        border-bottom: 1px solid #dee2e6;
    }

    .table-akdital tr:hover {
        background-color: #f5f5f5;
    }

    /* Badges pour les états */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-en-cours {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .badge-valider {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #b8daff;
    }

    .badge-checkin {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .badge-checkout {
        background-color: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }

    .badge-annuler {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Barre de progression */
    .progress-container {
        width: 100px;
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin: 5px auto;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-warning {
        background: linear-gradient(90deg, #ffc107, #fd7e14);
    }

    .progress-danger {
        background: linear-gradient(90deg, #dc3545, #e83e8c);
    }

    /* Infos appartement */
    .appartement-info {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    .info-label {
        font-weight: bold;
        color: #495057;
        margin-bottom: 5px;
    }

    .info-value {
        color: #6c757d;
        font-size: 1.1em;
    }

    /* Actions */
    .actions a {
        display: inline-block;
        padding: 6px 12px;
        margin: 2px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #17a2b8;
        color: white;
    }

    .btn-edit {
        background: #ffc107;
        color: #212529;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .actions a:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    /* Métriques détaillées */
    .metrics-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .metric-box {
        background: #f8f9fa;
        padding: 8px;
        border-radius: 6px;
        text-align: center;
        font-size: 12px;
    }

    .metric-urgent {
        background: #f8d7da;
        color: #721c24;
    }

    .metric-warning {
        background: #fff3cd;
        color: #856404;
    }

    .metric-success {
        background: #d4edda;
        color: #155724;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="users view">

    <div class="col-md-12 little-title-section">
        <span class="little-title">Informations générales</span>
        <div class="actions">
        </div>
    </div>


    <div class="card view-card">
        <div class="card-body">
            <div class="col-12">
                <div class="row row-gap-3">
                    <div class="col-md-4">
                        <div class="info">
                            <label>Nom ou identifiant de l’appartement</label>
                            <div class="little-title">
                                <?php echo h($appartement['Appartement']['nom']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info">
                            <label>Capacité totale</label>
                            <span><?php echo h($appartement['Appartement']['capacite']); ?> personnes</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info">
                            <label>Occupants actuels</label>
                            <span><?php echo $stats['occupation_actuelle']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info">
                            <label>Chambres libres</label>
                            <span>
                                <?php
                                $disponible = $stats['capacite'] - $stats['occupation_actuelle'];
                                echo $disponible;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info">
                            <label>Adresse / Ville</label>
                            <span>
                                <?php echo h(ucwords(strtolower($appartement['Appartement']['adresse']))); ?> , <?php echo h(ucwords(strtolower($appartement['Ville']['ville']))); ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="affecter"> <button class="btn btn-primary-rounded affecter-btn" type="button">
                                Affecter ce logement
                            </button></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="dashboard-container">


    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number stat-occupation"><?php echo $stats['occupation_actuelle']; ?>/<?php echo $stats['capacite']; ?></div>
            <div class="stat-label">Occupation Actuelle</div>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $taux_occupation; ?>%"></div>
            </div>
            <small><?php echo $taux_occupation; ?>% occupé</small>
        </div>

        <div class="stat-card">
            <div class="stat-number stat-en-cours"><?php echo $stats['en_cours']; ?></div>
            <div class="stat-label">En Cours</div>
            <small>Nouveaux dossiers</small>
        </div>

        <div class="stat-card">
            <div class="stat-number stat-valider"><?php echo $stats['valider']; ?></div>
            <div class="stat-label">Validés</div>
            <small>Conditions OK</small>
        </div>

        <div class="stat-card">
            <div class="stat-number stat-checkin"><?php echo $stats['checkin']; ?></div>
            <div class="stat-label">Check-in</div>
            <small>Dans l'appartement</small>
        </div>

        <div class="stat-card">
            <div class="stat-number stat-checkout"><?php echo $stats['checkout']; ?></div>
            <div class="stat-label">Check-out</div>
            <small>Séjours terminés</small>
        </div>

        <div class="stat-card">
            <div class="stat-number stat-annuler"><?php echo $stats['annuler']; ?></div>
            <div class="stat-label">Annulés</div>
            <small>Non réalisés</small>
        </div>
    </div>
    <!-- Tableau des bénéficiaires actifs -->
    <div class="table-container">
        <div class="table-header">
            🏠 Bénéficiaires Actifs (<?php echo count($beneficiaires_actifs); ?>)
        </div>
        <table class="table table-akdital">
            <thead>
                <tr>
                    <th>Site</th>
                    <th>Affecté par</th>
                    <th>Nom</th>
                    <th>État</th>
                    <th>Période</th>
                    <th>Analyse Temporelle</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($beneficiaires_actifs as $beneficiaire): ?>
                    <tr>
                        <td><?php echo isset($sites[$beneficiaire['site_id']]) ? $sites[$beneficiaire['site_id']] : 'N/A'; ?></td>
                        <td><?php echo isset($users[$beneficiaire['user_id']]) ? $users[$beneficiaire['user_id']] : 'N/A'; ?></td>
                        <td><strong><?php echo h($beneficiaire['nom']); ?></strong></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $beneficiaire['etat'])); ?>">
                                <?php echo $beneficiaire['etat']; ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 12px;">
                                <div><strong>Début:</strong> <?php echo date('d/m/Y', strtotime($beneficiaire['date_debut'])); ?></div>
                                <div><strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($beneficiaire['date_fin'])); ?></div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $joursRestants = calculerJoursRestants($beneficiaire['date_debut'], $beneficiaire['date_fin']);
                            $dureeTotal = calculerDureeTotal($beneficiaire['date_debut'], $beneficiaire['date_fin']);
                            ?>
                            <div class="metrics-row">
                                <div class="metric-box <?php echo $joursRestants <= 7 ? 'metric-urgent' : ($joursRestants <= 30 ? 'metric-warning' : 'metric-success'); ?>">
                                    <div><strong><?php echo $joursRestants; ?></strong></div>
                                    <div>jours restants</div>
                                </div>
                                <div class="metric-box">
                                    <div><strong><?php echo $dureeTotal; ?></strong></div>
                                    <div>jours total</div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo h($beneficiaire['note']); ?></td>
                        <td class="actions">
                            <?php echo $this->Html->link('Voir', array('action' => 'view', $beneficiaire['id']), array('class' => 'btn-view')); ?>
                            <?php echo $this->Html->link('Modifier', array('action' => 'edit', $beneficiaire['id']), array('class' => 'btn-edit')); ?>
                            <?php echo $this->Form->postLink('Supprimer', array('action' => 'delete', $beneficiaire['id']), array('class' => 'btn-delete', 'confirm' => 'Êtes-vous sûr de vouloir supprimer ce bénéficiaire ?')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Tableau de l'historique -->
    <div class="table-container">
        <div class="table-header">
            📊 Historique des Affectations (<?php echo count($beneficiaires_historique); ?>)
        </div>
        <table class="table table-akdital">
            <thead>
                <tr>
                    <th>Site</th>
                    <th>Affecté par</th>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>État</th>
                    <th>Période</th>
                    <th>Durée</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($beneficiaires_historique as $beneficiaire): ?>
                    <tr>
                        <td><?php echo isset($sites[$beneficiaire['site_id']]) ? $sites[$beneficiaire['site_id']] : 'N/A'; ?></td>
                        <td><?php echo isset($users[$beneficiaire['user_id']]) ? $users[$beneficiaire['user_id']] : 'N/A'; ?></td>
                        <td><strong><?php echo h($beneficiaire['nom']); ?></strong></td>
                        <td>
                            <div style="font-size: 12px;">
                                <div><?php echo h($beneficiaire['mail']); ?></div>
                                <div><?php echo h($beneficiaire['telephone']); ?></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo strtolower(str_replace(' ', '-', $beneficiaire['etat'])); ?>">
                                <?php echo $beneficiaire['etat']; ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 12px;">
                                <div><?php echo date('d/m/Y', strtotime($beneficiaire['date_debut'])); ?></div>
                                <div><?php echo date('d/m/Y', strtotime($beneficiaire['date_fin'])); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="metric-box">
                                <strong><?php echo calculerDureeTotal($beneficiaire['date_debut'], $beneficiaire['date_fin']); ?></strong> jours
                            </div>
                        </td>
                        <td><?php echo h($beneficiaire['note']); ?></td>
                        <td class="actions">
                            <?php echo $this->Html->link('Voir', array('action' => 'view', $beneficiaire['id']), array('class' => 'btn-view')); ?>
                            <?php echo $this->Html->link('Modifier', array('action' => 'edit', $beneficiaire['id']), array('class' => 'btn-edit')); ?>
                            <?php echo $this->Form->postLink('Supprimer', array('action' => 'delete', $beneficiaire['id']), array('class' => 'btn-delete', 'confirm' => 'Êtes-vous sûr de vouloir supprimer ce bénéficiaire ?')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Animation des statistiques au chargement
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des barres de progression
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });

        // Animation des chiffres
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent.split('/')[0]);
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 20);

            const interval = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(interval);
                }
                stat.textContent = stat.textContent.includes('/') ?
                    currentValue + '/' + stat.textContent.split('/')[1] :
                    currentValue;
            }, 50);
        });
    });
</script>