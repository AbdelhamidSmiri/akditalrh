<div class="appartements form">
	<?php echo $this->Form->create('Appartement', array('type' => 'file')); ?>
	<div class="page-header">
		<h1 class="title-page">Modifier Appartement</h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">
			<div class="card">
				<div class="card-body">
					<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo $this->Form->input('nom', array(
									'class' => 'form-control',
									'label' => 'Nom de l\'appartement',
									'placeholder' => 'Entrez le nom de l\'appartement'
								)); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php
								$sexe=["Homme"=>"Homme","Femme"=>"Femme"];
								 echo $this->Form->input('sexe', array(
									'class' => 'form-control',
									'label' => 'Nom de l\'appartement',
									"options" => $sexe,
									'placeholder' => 'Entrez le nom de l\'appartement'
								)); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo $this->Form->input('capacite', array(
									'class' => 'form-control',
									'label' => 'Capacité',
									'type' => 'number',
									'placeholder' => 'Nombre de personnes'
								)); ?>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<?php echo $this->Form->input('ville_id', array(
									'class' => 'form-control',
									'label' => 'Ville',
									'placeholder' => 'Entrez la ville'
								)); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<?php echo $this->Form->input('adresse', array(
									'class' => 'form-control',
									'label' => 'Adresse',
									'placeholder' => 'Entrez l\'adresse complète'
								)); ?>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Images actuelles:</label>
								<?php if (!empty($this->request->data['Appartement']['images'])): ?>
									<div class="current-images">
										<?php 
										$images = explode(';', $this->request->data['Appartement']['images']);
										foreach ($images as $key=>$image):
											if (!empty($image)):
										?>
											<div class="image-container" style="display: inline-block; position: relative; margin: 5px;">
												<img src="<?php echo $this->webroot . 'files/appartements/' . $image; ?>" 
													 alt="Image appartement" 
													 style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; padding: 3px;">
												<?php echo $this->Form->postLink(
													'<i class="fa-solid fa-trash"></i>',
													array('action' => 'deleteImage', $this->request->data['Appartement']['id'], $key),
													array(
														'class' => 'btn btn-danger btn-sm',
														'style' => 'position: absolute; top: -5px; right: -5px; border-radius: 50%; padding: 2px 6px;',
														'escape' => false,
														'confirm' => 'Êtes-vous sûr de vouloir supprimer cette image ?'
													)
												); ?>
											</div>
										<?php 
											endif;
										endforeach; 
										?>
									</div>
								<?php else: ?>
									<p class="text-muted">Aucune image actuellement</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<?php echo $this->Form->input('image.', array(
									'type' => 'file',
									'multiple' => true,
									'label' => 'Nouvelles images',
									'class' => 'form-control-file'
								)); ?>
								<small class="form-text text-muted">
									Sélectionnez une ou plusieurs images pour remplacer les images actuelles
								</small>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="submit-section text-center">
								<?php echo $this->Form->button('<i class="fa-solid fa-save"></i> Enregistrer', array(
									'type' => 'submit',
									'class' => 'btn btn-primary btn-lg'
								)); ?>
								<?php echo $this->Html->link('<i class="fa-solid fa-arrow-left"></i> Annuler', 
									array('action' => 'index'), 
									array('class' => 'btn btn-secondary btn-lg ml-2', 'escape' => false)
								); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col"></div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'); ?>
