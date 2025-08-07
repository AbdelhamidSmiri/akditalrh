<div class="beneficiaires view">
	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
						<div class="info">
							<label>Site</label>
							<span>
								<span><?php echo h($beneficiaire['Site']['site']); ?></span>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label>Affecté par</label>
							<span>
								<span><?php echo h($beneficiaire['User']['nom']); ?></span>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label>Appartement</label>
							<span>
								<?php echo $beneficiaire['Appartement']['nom']; ?> </span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Nom'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['nom']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Email'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['mail']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Téléphone'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['telephone']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Status'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['status']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('État'); ?></label>
							<span>
									<td>
						<?php
						switch ($beneficiaire['Beneficiaire']['etat']) {

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
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Début'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['date_debut']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Fin'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['date_fin']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Note'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['note']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Created'); ?></label>
							<span><?php echo h($beneficiaire['Beneficiaire']['created']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>