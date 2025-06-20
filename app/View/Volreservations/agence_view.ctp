<div class="volreservations view">

	<div class="col-md-12 little-title-section">
		<span class="little-title">Détail de reservation de vol</span>
		<div class="actions">
		</div>
	</div>

	<a href="<?php echo $this->Html->url(array('controller' => 'Volreservations', 'action' => 'agence_valide',$volreservation['Volreservation']['id'])); ?>" class="btn btn-primary"> Valider</a>

	//met un button quant je clique j'affiche un model pour envoie le tout agence_archive
	<a href="<?php echo $this->Html->url(array('controller' => 'Volreservations', 'action' => 'agence_archive',$volreservation['Volreservation']['id'])); ?>" class="btn btn-secondary"> Archiver</a>




	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<div class="col-md-3">
						<div class="info">
							<label>User</label>
							<span>
								<?php echo $volreservation['User']['nom'] ?>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label>Site</label>
							<span>
								<?php echo $volreservation['Site']['nom']; ?>
							</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Depart'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['depart']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Destination'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['destination']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Aller'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['date_aller']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Retour'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['date_retour']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Num Odm'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['num_odm']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Cin'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['cin']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Passport'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['passport']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Message'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['message']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label>Date de demande</label>
							<span><?php echo h($volreservation['Volreservation']['created']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Etat'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['etat']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Documents'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['documents']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Reponse'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['reponse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Reponse'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['date_reponse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Num Vol'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['num_vol']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('File Aller'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['file_aller']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('File Retour'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['file_retour']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Transfer'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['transfer']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Nom Transfer'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['nom_transfer']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Date Transfer'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['date_transfer']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Tel Transfer'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['tel_transfer']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Description Transfer'); ?></label>
							<span><?php echo h($volreservation['Volreservation']['description_transfer']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>