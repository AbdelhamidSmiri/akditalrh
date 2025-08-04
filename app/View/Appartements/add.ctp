<div class="appartements form">
	<?php echo $this->Form->create('Appartement', array("type" => "file")); ?>
	<div class="page-header">
		<h1 class="title-page"></h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php echo $this->Form->input('nom', array('placeholder' => '')); ?>
				</div>
				<div class='col-12'>
					<?php
					$sexe = ["Homme" => "Homme", "Femme" => "Femme"];
					echo $this->Form->input('sexe', array('options' => $sexe, 'placeholder' => '')); ?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('capacite', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('ville_id', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('adresse', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12 mb-4 input-file'>
					<label for="img">Images</label>
					<div class="file-upload-wrapper">
						<div class="file-upload-area">
							<div class="upload-text">Glissez-déposez les fichiers ici</div>
							<div class="upload-subtext">Ou</div>
							<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

							<?php echo $this->Form->file('images', array(
								'name' => 'data[Appartement][images][]',
								'class' => 'file-input',
								'accept' => '.jpg, .jpeg, .png, .pdf', // Accept only image and PDF files
								'multiple' => true
							)); ?>
						</div>

						<div class="file-info">
							<div class="files-list"></div>
						</div>
					</div>

					<div class="description-text">
						Téléversez les images de l'appartement.
					</div>
				</div>
				<div class='submit-section'>
					<button type="submit" class="btn btn-submit">
						<i class="fa-solid fa-paper-plane"></i> Envoyer
					</button>
				</div><?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div class="col"></div>


	</div>
</div>


<?php echo $this->Html->script('input_file'); ?>