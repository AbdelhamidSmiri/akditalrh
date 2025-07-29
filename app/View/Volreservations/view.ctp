<link href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css" rel="stylesheet" />

<div class="volreservations view">

	<div class="col-md-12 little-title-section">
		<span class="little-title">Informations générales</span>
		<div class="actions_right">
			<?php if (AuthComponent::user('Role.role') == 'Agence' && $volreservation["Volreservation"]["etat"] == "En cours"): ?>
				<a href="<?php echo $this->Html->url(array('controller' => 'Volreservations', 'action' => 'agence_valide', $volreservation['Volreservation']['id'])); ?>"
					class="btn btn-success-rounded">Marquer comme validée</a>
			<?php endif; ?>

			<?php if ($volreservation["Volreservation"]["etat"] == "En cours"): ?>
				<button type="button" class="btn btn-secondary-rounded" data-bs-toggle="modal" data-bs-target="#archiveModal">
					Archiver
				</button>
			<?php endif; ?>
		</div>
	</div>



	<!-- Modal -->
	<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<?php
				echo $this->Form->create('Volreservation', array(
					'url' => array('controller' => 'Volreservations', 'action' => 'agence_archive', $volreservation['Volreservation']['id']),
					'type' => 'post',
					'class' => 'modal-form'
				));
				?>
				<div class="modal-header">
					<h5 class="modal-title" id="archiveModalLabel">Archiver la demande</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
				</div>

				<div class="modal-body">
					<div class="mb-3">
						<?php echo $this->Form->input('reponse', array('placeholder' => '')); ?>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
					<button type="submit" class="btn btn-danger">Envoyer</button>
				</div>
				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>





	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<?php if (!empty($volreservation['User']['nom'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Nom d'utilisateur</label>
								<span><?php echo h($volreservation['User']['nom']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Site']['site'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Site</label>
								<span><?php echo h($volreservation['Site']['site']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['destination'] || $volreservation['Volreservation']['depart'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Destination</label>
								<div>
									<span><?php echo h($volreservation['Volreservation']['depart']); ?></span>
									<i class="fa-solid fa-arrow-right"></i>
									<span><?php echo h($volreservation['Volreservation']['destination']); ?></span>
								</div>



							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['date_retour']) && !empty($volreservation['Volreservation']['date_aller'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Dates </label>

								<span><b>Aller : </b> <?php echo h($volreservation['Volreservation']['date_retour']); ?></span>
								<span><b>Retour : </b><?php echo h($volreservation['Volreservation']['date_aller']); ?></span>

							</div>
						</div>
					<?php endif; ?>

					<?php
					if (!empty($volreservation['Volreservation']['num_odm'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Num ODM</label>
								<span><?php echo h($volreservation['Volreservation']['num_odm']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<!-- CIN images (lightgallery) -->
					<?php if (!empty($volreservation['Volreservation']['ordre_mission'])): ?>
						<?php $cinImages = json_decode($volreservation['Volreservation']['ordre_mission'], true); ?>
						<?php if (is_array($cinImages) && count($cinImages)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>Ordre mission</label>
									<div class="lightgallery">
										<?php foreach ($cinImages as $key => $img):
											$active = 'img_deactive';
											if ($key == 0)
												$active = 'img_active';
										?>
											<a href="/akditalrh/files/volreservations/<?php echo h($img); ?>" class="<?php echo $active; ?>">
												<i class="fa-regular fa-eye"></i> <?php echo h($img); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<!-- CIN images (lightgallery) -->
					<?php if (!empty($volreservation['Volreservation']['cin'])): ?>
						<?php $cinImages = json_decode($volreservation['Volreservation']['cin'], true); ?>
						<?php if (is_array($cinImages) && count($cinImages)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>CIN</label>
									<div class="lightgallery">
										<?php foreach ($cinImages as $key => $img):
											$active = 'img_deactive';
											if ($key == 0)
												$active = 'img_active';
										?>
											<a href="/akditalrh/files/volreservations/<?php echo h($img); ?>" class="<?php echo $active; ?>">
												<i class="fa-regular fa-eye"></i> <?php echo h($img); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<!-- Passport images (lightgallery) -->
					<?php if (!empty($volreservation['Volreservation']['passport'])): ?>
						<?php $passportImages = json_decode($volreservation['Volreservation']['passport'], true); ?>
						<?php if (is_array($passportImages) && count($passportImages)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>Passport</label>
									<div class="lightgallery">
										<?php foreach ($passportImages as $key => $img):
											$active = 'img_deactive';
											if ($key == 0)
												$active = 'img_active';
										?>
											<a href="/akditalrh/files/volreservations/<?php echo h($img); ?>" class="<?php echo $active; ?>">
												<i class="fa-regular fa-eye"></i> <?php echo h($img); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['message'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Message</label>
								<span><?php echo h($volreservation['Volreservation']['message']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['created'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Date de demande</label>
								<span><?php echo h($volreservation['Volreservation']['created']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['etat'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>État</label>
								<?php

								switch ($volreservation['Volreservation']['etat']) {

									case 'En cours':
										echo '<span class="status-btn in-progress"><i class="fas fa-clock"></i> En cours</span>';
										break;
									case 'Validé':
										echo '<span class="status-btn confirmed"><i class="fas fa-check-circle"></i> Validé</span>';
										break;
									case 'refusée':
										echo '<span class="status-btn refused"><i class="fas fa-times-circle"></i> Refusée</span>';
										break;
									case 'passe':
										echo '<span class="status-btn passe"><i class="fas fa-calendar-times"></i> Passé</span>';
										break;
									default:
										echo '<span class="status-btn passe"><i class="fas fa-question-circle"></i> ' . htmlspecialchars($volreservation['Volreservation']['etat']) . '</span>';
								}
								?>
							</div>
						</div>
					<?php endif; ?>
					<!-- document -->
					<?php if (!empty($volreservation['Volreservation']['documents'])): ?>
						<?php $documentFiles = json_decode($volreservation['Volreservation']['documents'], true); ?>
						<?php if (is_array($documentFiles) && count($documentFiles)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>Documents</label>
									<div class="lightgallery">
										<?php foreach ($documentFiles as $key => $doc):
											$active = ($key === 0) ? 'img_active' : 'img_deactive';
										?>
											<a href="/akditalrh/files/volreservations/<?php echo h($doc); ?>" class="<?php echo $active; ?>" target="_blank">
												<i class="fa-regular fa-eye"></i> <?php echo h($doc); ?>
											</a><br />
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>


					<?php if (!empty($volreservation['Volreservation']['reponse'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Réponse</label>
								<span><?php echo h($volreservation['Volreservation']['reponse']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['date_reponse'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Date réponse</label>
								<span><?php echo h($volreservation['Volreservation']['date_reponse']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['num_vol'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Numéro vol</label>
								<span><?php echo h($volreservation['Volreservation']['num_vol']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['file_aller'])): ?>
						<?php $allerFiles = json_decode($volreservation['Volreservation']['file_aller'], true); ?>
						<?php if (is_array($allerFiles) && count($allerFiles)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>Fichier Aller</label>
									<div class="lightgallery">
										<?php foreach ($allerFiles as $key => $file):
											$active = ($key === 0) ? 'img_active' : 'img_deactive';
										?>
											<a href="/akditalrh/files/volreservations/<?php echo h($file); ?>" class="<?php echo $active; ?>" target="_blank">
												<i class="fa-regular fa-eye"></i> <?php echo h($file); ?>
											</a><br />
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>


					<?php if (!empty($volreservation['Volreservation']['file_retour'])): ?>
						<?php $retourFiles = json_decode($volreservation['Volreservation']['file_retour'], true); ?>
						<?php if (is_array($retourFiles) && count($retourFiles)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>Fichier Retour</label>
									<div class="lightgallery">
										<?php foreach ($retourFiles as $key => $file):
											$active = ($key === 0) ? 'img_active' : 'img_deactive';
										?>
											<a href="/akditalrh/files/volreservations/<?php echo h($file); ?>" class="<?php echo $active; ?>" target="_blank">
												<i class="fa-regular fa-eye"></i> <?php echo h($file); ?>
											</a><br />
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>


					<?php if (!empty($volreservation['Volreservation']['transfer'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Transfert</label>
								<span class="badge badge-transfer">
									<?php echo ($volreservation['Volreservation']['transfer'] == "1") ? 'Oui' : 'Non';
									?>
								</span>

							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['nom_transfer'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Nom Transfert</label>
								<span><?php echo h($volreservation['Volreservation']['nom_transfer']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['date_transfer'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Date Transfert</label>
								<span><?php echo h($volreservation['Volreservation']['date_transfer']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['tel_transfer'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Téléphone Transfert</label>
								<span><?php echo h($volreservation['Volreservation']['tel_transfer']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($volreservation['Volreservation']['description_transfer'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Description Transfert</label>
								<span><?php echo h($volreservation['Volreservation']['description_transfer']); ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>


			</div>
		</div>
	</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/zoom/lg-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/thumbnail/lg-thumbnail.min.js"></script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.lightgallery').forEach(function(el) {
			lightGallery(el, {
				selector: 'a'
			});
		});
	});
</script>