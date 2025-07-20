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
					<div class="col-md-3">
						<div class="info">
							<label>Demandeur</label>
							<span>
								<?php echo $reservation['User']['nom']; ?>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label>Hotel</label>
							<span>
								<?php echo $reservation['Chambre']['nom']; ?>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label>Site</label>
							<span>
								<?php echo $reservation['Site']['site']; ?>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Checkin'); ?></label>
							<span><?php echo h($reservation['Reservation']['checkin']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Checkout'); ?></label>
							<span><?php echo h($reservation['Reservation']['checkout']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Num Odm'); ?></label>
							<span><?php echo h($reservation['Reservation']['num_odm']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Ordre Mission'); ?></label>
							<span><?php echo h($reservation['Reservation']['ordre_mission']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Cin'); ?></label>
							<span><?php echo h($reservation['Reservation']['cin']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Message'); ?></label>
							<span><?php echo h($reservation['Reservation']['message']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Created'); ?></label>
							<span><?php echo h($reservation['Reservation']['created']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Confirmation'); ?></label>
							<span><?php echo h($reservation['Reservation']['confirmation']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Etat'); ?></label>
							<span><?php echo h($reservation['Reservation']['etat']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Reponse'); ?></label>
							<span><?php echo h($reservation['Reservation']['reponse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Reponse'); ?></label>
							<span><?php echo h($reservation['Reservation']['date_reponse']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>