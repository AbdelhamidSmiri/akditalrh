<div class="parc view_requete"></div>

<?php if (empty($demande)): ?>
	<p>Requête introuvable.</p>
<?php else: ?>
	<?php
	$d = $demande['Demandevoiture'];
	$demandeur = trim(($demande['User']['nom'] ?? '') . ' ' . ($demande['User']['prenom'] ?? ''));
	?>
	<div class="content-table">
		<table class="table table-akdital">
			<tbody>
				<tr><th>ID</th><td><?php echo h($d['id']); ?></td></tr>
				<tr><th>Demandeur</th><td><?php echo h($demandeur !== '' ? $demandeur : '—'); ?></td></tr>
				<tr><th>Marque</th><td><?php echo h($d['marque']); ?></td></tr>
				<tr><th>Modèle</th><td><?php echo h($d['modele']); ?></td></tr>
				<tr><th>Durée (mois)</th><td><?php echo h($d['duree_mois']); ?></td></tr>
				<tr><th>Catégorie</th><td><?php echo h($d['categorie_vr']); ?></td></tr>
				<tr><th>Service / lien</th><td><?php echo h($d['service_link']); ?></td></tr>
				<tr><th>Livraison</th><td><?php echo h($d['livraison']); ?></td></tr>
				<tr><th>Km</th><td><?php echo h($d['km']); ?></td></tr>
				<tr><th>Nb pneus</th><td><?php echo h($d['nbr_pneus']); ?></td></tr>
				<tr><th>Nouveau plafond</th><td><?php echo h($d['nv_plafond']); ?></td></tr>
				<tr><th>Statut</th><td><?php echo h($d['status_demande']); ?></td></tr>
				<tr><th>Description</th><td><?php echo nl2br(h($d['description'])); ?></td></tr>
				<tr><th>Commentaire RH</th><td><?php echo nl2br(h($d['commentaire_rh'])); ?></td></tr>
				<tr><th>Commentaire DGD</th><td><?php echo nl2br(h($d['commentaire_dgd'])); ?></td></tr>
				<tr><th>Commentaire Achat</th><td><?php echo nl2br(h($d['commentaire_achat'])); ?></td></tr>
				<tr><th>Devis</th><td><?php echo nl2br(h($d['devis'])); ?></td></tr>
				<tr><th>Créée le</th><td><?php echo h($d['created']); ?></td></tr>
			</tbody>
		</table>
	</div>
	<p>
		<?php echo $this->Html->link(
			'← Retour aux requêtes',
			array('controller' => 'parc', 'action' => 'requetes_collaborateurs'),
			array('class' => 'btn btn-secondary-rounded')
		); ?>
	</p>
<?php endif; ?>
