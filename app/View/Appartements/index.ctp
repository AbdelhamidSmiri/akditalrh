<h1 class="little-title">Tableau de bord des Appartements
</h1>
<div class="row">
    <div class="col-md-4">
        <div class="custom-card card-info">
            <div class="card-left">
                <div class="card-number"><?= $global['total_appartements'] ?></div>
                <div class="card-label">Total Appartements</div>
            </div>
            <div class="card-icon">
                <div class="icon-circle">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="custom-card card-success">
            <div class="card-left">
                <div class="card-number"><?= $global['total_ocupes'] ?></div>
                <div class="card-label">Appartements Pleins</div>
            </div>
            <div class="card-icon">
                <div class="icon-circle">
                    <i class="fas fa-door-closed"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="custom-card card-warning">
            <div class="card-left">
                <div class="card-number"><?= $global['total_places_dispo'] ?></div>
                <div class="card-label">Places Disponibles</div>
            </div>
            <div class="card-icon">
                <div class="icon-circle">
                    <i class="fas fa-door-open"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<h1 class="little-title">Statistiques par Ville et Sexe</h1>
<table class="table table-akdital mb-lg-5">
    <thead>
        <tr>
            <th>Ville</th>
            <th>Sexe</th>
            <th>Total d’appartements</th>
            <th>Appartements pleins</th>
            <th>Places disponibles</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats as $ville => $bySexe): ?>
            <?php foreach ($bySexe as $sexe => $info): ?>
                <tr>
                    <td><?= strtolower(h($ville)) ?></td>
                    <td><?= h($sexe) ?></td>
                    <td><?= $info['total'] ?></td>
                    <td><?= $info['ocupes'] ?></td>
                    <td><?= $info['places_dispo'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>



<h1 class="little-title">Détails par Appartement
</h1>
<table class="table table-akdital mb-lg-5">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Ville</th>
            <th>Sexe</th>
            <th>Capacité</th>
            <th>Occupants</th>
            <th>Places disponibles</th>
            <th>Actions</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($appart_info as $a): ?>
            <tr>
                <td><?= h($a['nom']) ?></td>
                <td><?= strtolower(h($a['ville'])) ?></td>
                <td><?= h($a['sexe']) ?></td>
                <td><?= $a['capacite'] ?></td>
                <td><?= $a['ocupants'] ?></td>
                <td><?= $a['places_dispo'] ?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('Voir'), array('action' => 'view', $a['id'])); ?>
                    /
                    <?php echo $this->Html->link(__('Modifier'), array('action' => 'edit', $a['id'])); ?>
                    /
                    <?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $a['id']), array('confirm' => __('Are you sure you want to delete # %s?', $a['id']))); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
