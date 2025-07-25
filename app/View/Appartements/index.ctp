<style>
	  .main-title {
            color: #2c3e50;
            font-size: 22px;
            font-weight: 500;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }
.custom-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.card-left {
    display: flex;
    flex-direction: column;
}

.card-number {
    font-size: 24px;
    font-weight: bold;
    color: #111827;
}

.card-label {
    color: #6b7280;
    font-size: 14px;
}

.card-icon .icon-circle {
    background-color: #e0f2fe;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card-icon i {
    color: #2563eb;
    font-size: 18px;
}

/* Optional colored variants */
.card-info .icon-circle { background-color: #dbeafe; }
.card-success .icon-circle { background-color: #dcfce7; }
.card-warning .icon-circle { background-color: #fef9c3; }

.card-info i { color: #3b82f6; }
.card-success i { color: #22c55e; }
.card-warning i { color: #f59e0b; }

</style>

<h1 class="main-title">Tableau de bord des Appartements
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


<h1 class="main-title">Statistiques par Ville et Sexe</h1>
<table class="table table-akdital">
	<thead>
		<tr>
			<th>Ville</th>
			<th>Sexe</th>
			<th>Total Appartements</th>
			<th>Appartements Pleins</th>
			<th>Places Disponibles</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($stats as $ville => $bySexe): ?>
			<?php foreach ($bySexe as $sexe => $info): ?>
				<tr>
					<td><?= h($ville) ?></td>
					<td><?= h($sexe) ?></td>
					<td><?= $info['total'] ?></td>
					<td><?= $info['ocupes'] ?></td>
					<td><?= $info['places_dispo'] ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</tbody>
</table>



<h1 class="main-title">Détails par Appartement
</h1>
<table class="table table-akdital">
	<thead>
		<tr>
			<th>Nom</th>
			<th>Ville</th>
			<th>Sexe</th>
			<th>Capacité</th>
			<th>Occupants</th>
			<th>Places Dispo</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($appart_info as $a): ?>
			<tr>
				<td><?= h($a['nom']) ?></td>
				<td><?= h($a['ville']) ?></td>
				<td><?= h($a['sexe']) ?></td>
				<td><?= $a['capacite'] ?></td>
				<td><?= $a['ocupants'] ?></td>
				<td><?= $a['places_dispo'] ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $a['id'])); ?>
					/
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $a['id'])); ?>
					/
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $a['id']), array('confirm' => __('Are you sure you want to delete # %s?', $a['id']))); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>