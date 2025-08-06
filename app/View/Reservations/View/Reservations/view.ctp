<link href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css" rel="stylesheet" />


<div class="reservations view">

	<div class="col-md-12 little-title-section">
		<span class="little-title">Détail de réservation</span>
		<div class="actions">
		</div>
	</div>


	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<?php if (!empty($reservation['User']['nom'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Demandeur</label>
								<span><?php echo $reservation['User']['nom']; ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Chambre']['nom'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Hotel</label>
								<span><?php echo $reservation['Chambre']['nom']; ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Site']['site'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label>Site</label>
								<span><?php echo $reservation['Site']['site']; ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['checkin'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Checkin'); ?></label>
								<span><?php echo h($reservation['Reservation']['checkin']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['checkout'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Checkout'); ?></label>
								<span><?php echo h($reservation['Reservation']['checkout']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['num_odm'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Num Odm'); ?></label>
								<span><?php echo h($reservation['Reservation']['num_odm']); ?></span>
							</div>
						</div>
					<?php endif; ?>
					<!-- odm images (lightgallery) -->
					<?php if (!empty($reservation['Reservation']['ordre_mission'])): ?>
						<?php $odmImages = json_decode($reservation['Reservation']['ordre_mission'], true); ?>
						<?php if (is_array($odmImages) && count($odmImages)): ?>
							<div class="col-md-3">
								<div class="info">
									<label>Ordre Mission</label>
									<div class="lightgallery">
										<?php foreach ($odmImages as $key => $img):
											$active = 'img_deactive';
											if ($key == 0)
												$active = 'img_active';
										?>
											<a href="/akditalrh/files/reservations/<?php echo h($img); ?>" class="<?php echo $active; ?>">
												<i class="fa-regular fa-eye"></i> <?php echo h($img); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<!-- CIN images (lightgallery) -->
					<?php if (!empty($reservation['Reservation']['cin'])): ?>
						<?php $cinImages = json_decode($reservation['Reservation']['cin'], true); ?>
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
											<a href="/akditalrh/files/reservations/<?php echo h($img); ?>" class="<?php echo $active; ?>">
												<i class="fa-regular fa-eye"></i> <?php echo h($img); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['message'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Message'); ?></label>
								<span><?php echo h($reservation['Reservation']['message']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['created'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Date création'); ?></label>
								<span><?php echo h($reservation['Reservation']['created']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['confirmation'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Confirmation'); ?></label>
								<span><?php echo h($reservation['Reservation']['confirmation']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['etat'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Etat'); ?></label>
								<?php

								switch ($reservation['Reservation']['etat']) {

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

					<?php if (!empty($reservation['Reservation']['reponse'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Reponse'); ?></label>
								<span><?php echo h($reservation['Reservation']['reponse']); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($reservation['Reservation']['date_reponse'])): ?>
						<div class="col-md-3">
							<div class="info">
								<label><?php echo __('Date Reponse'); ?></label>
								<span><?php echo h($reservation['Reservation']['date_reponse']); ?></span>
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