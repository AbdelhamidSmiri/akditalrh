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
    .tech-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid #eee;
        border-radius: 10px;
        width: 300px;
    }

    .icon-box {
        background-color: #6f42c1;
        color: white;
        border-radius: 10px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
    }

    .progress-container {
        flex-grow: 1;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
    }

    .label-text {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .progress-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-left: 5px;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
    }

    .progress-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 5px;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
        flex-grow: 1;
        margin-right: 10px;
    }

    element.style {
        width: 44%;
    }

    .bg-primary-progress {
        color: rgb(27, 132, 255);
        background-color: #1B84FF !important;
    }

    .bg-light-primary {
        background-color: #E9F3FF !important;
    }

    .progress-wrapper {
        display: flex;
        align-items: center;
        width: 200px;
        /* ou 100% selon besoin */
    }

    .percent-text {
        font-size: 0.875rem;
        color: #6c757d;
        white-space: nowrap;
    }
</style>
<div class="row g-3 mb-5">
    <div class="col-12 col-md-4 col-lg-3">
        <div class="card view-card border-1 h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                <div class="d-flex flex-column">
                    <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?php echo $stats['occupation_actuelle']; ?>/<?php echo $stats['capacite']; ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                        Occupation Actuelle
                    </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3">
        <div class="card view-card border-1 h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                <div class="d-flex flex-column">
                    <div class="stats-number mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?php echo $stats['en_cours']; ?>
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
                        <?php echo $stats['valider']; ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                        Validés
                    </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 col-lg-3">
        <div class="card view-card border-1 h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                <div class="d-flex flex-column">
                    <div class="stats-number  mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?php echo $stats['checkin']; ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                        Check-in
                    </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-3">
        <div class="card view-card border-1 h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                <div class="d-flex flex-column">
                    <div class="stats-number  mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?php echo $stats['checkout']; ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                        Check-out </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 col-lg-3">
        <div class="card view-card border-1 h-100">
            <div class="stats-card card-body d-flex align-items-center justify-content-between p-3">
                <div class="d-flex flex-column">
                    <div class="stats-number  mb-1" style="font-size: 2.5rem; font-weight: 600; line-height: 1;">
                        <?php echo $stats['annuler']; ?>
                    </div>
                    <div class="stats-label mb-0 text-muted" style="font-size: 0.95rem; font-weight: 400; line-height: 1.3;">
                        Annulés </div>
                </div>
                <div class="stat-icon d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Statistiques principales -->

<div class="users view mb-5">

    <div class="col-md-12 mb-4">
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

<div class="col-md-12 mb-4">
    <span class="little-title">Affectations en cours</span>
    <div class="actions">
    </div>
</div>
<div class="content-table">
    <table class="table table-akdital mb-5">
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
                        <?php
                        switch ($beneficiaire['etat']) {

                            case 'En cours':
                                echo '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
                                break;
                            case 'Checkin':
                                echo '<span class="status-btn checked"><i class="fa-regular fa-right-to-bracket"></i>Arrivée</span>';
                                break;
                            case 'Valider':
                                echo '<span class="status-btn confirmed"><i class="fa-solid fa-circle-check"></i>Confirmée</span>';
                                break;
                            case 'Checkout':
                                echo '<span class="status-btn refused"><i class="fas fa-sign-out-alt"></i>Départ</span>';
                                break;

                            default:
                                echo '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . htmlspecialchars($beneficiaire['etat']) . '</span>';
                        }
                        ?>
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
                        $joursPasses = $dureeTotal - $joursRestants;

                        // Calculate the percentage of days passed
                        $pourcentage = $dureeTotal > 0 ? ($joursPasses / $dureeTotal) * 100 : 0;
                        ?>
                        <div class="progress-wrapper d-flex align-items-center" style="width: 200px;">
                            <div class="progress bg-light me-2 flex-grow-1" style="height: 6px; border-radius: 3px;">
                                <div class="progress-bar bg-primary" style="width: <?= round($pourcentage, 2) ?>%;"></div>
                            </div>
                            <div class="percent-text text-muted small"><?= $dureeTotal ?>J</div>
                        </div>
                    </td>

                    <td><?php echo h($beneficiaire['note']); ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('Voir', array('controller' => 'beneficiaires', 'action' => 'view', $beneficiaire['id']), array('class' => 'btn-view')); ?>
                        /

                        <?php echo $this->Form->postLink('Supprimer', array('controller' => 'beneficiaires', 'action' => 'delete', $beneficiaire['id']), array('class' => 'btn-delete', 'confirm' => 'Êtes-vous sûr de vouloir supprimer ce bénéficiaire ?')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>




<div class="col-md-12 mb-4 mt-5">
    <span class="little-title">Historique des Affectations</span>
    <div class="actions">
    </div>
</div>
<div class="content-table">
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
                        <?php
                        switch ($beneficiaire['etat']) {

                            case 'En cours':
                                echo '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
                                break;
                            case 'Checkin':
                                echo '<span class="status-btn checked"><i class="fa-regular fa-right-to-bracket"></i>Arrivée</span>';
                                break;
                            case 'Valider':
                                echo '<span class="status-btn confirmed"><i class="fa-solid fa-circle-check"></i> Confirmée</span>';
                                break;
                            case 'Checkout':
                                echo '<span class="status-btn refused"><i class="fas fa-sign-out-alt"></i>Départ</span>';
                                break;

                            default:
                                echo '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . htmlspecialchars($beneficiaire['etat']) . '</span>';
                        }
                        ?>
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
                        <?php echo $this->Html->link('Voir', array('controller' => 'beneficiaires','action' => 'view', $beneficiaire['id']), array('class' => 'btn-view')); ?>
                        /<?php echo $this->Html->link('Modifier', array('controller' => 'beneficiaires','action' => 'edit', $beneficiaire['id']), array('class' => 'btn-edit')); ?>
                        /<?php echo $this->Form->postLink('Supprimer', array('controller' => 'beneficiaires','action' => 'delete', $beneficiaire['id']), array('class' => 'btn-delete', 'confirm' => 'Êtes-vous sûr de vouloir supprimer ce bénéficiaire ?')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>