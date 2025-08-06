<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<div class="hotels form">
	<?php echo $this->Form->create('Chambre', ["type" => "file"]); ?>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php
					echo $this->Form->input('hotel_id', array('placeholder' => '', 'option' => $hotels));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('nom', array('placeholder' => '', 'label' => 'Nom/N° de la chambre'));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('type', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12 mb-4 input-file'>
					<div class="file-upload-wrapper">
						<div class="file-upload-area">
							<div class="upload-text">Glissez-déposez les Images ici</div>
							<div class="upload-subtext">Ou</div>
							<button type="button" class="choose-files-btn">Choisir des images <i class="fa-light fa-cloud-arrow-up"></i></button>

							<?php echo $this->Form->file('images', array(
								'name' => 'data[Chambre][images][]',
								'class' => 'file-input',
								'accept' => '.jpg, .jpeg, .png', // Accept only image and PDF files
								'multiple' => true
							)); ?>
						</div>

						<div class="file-info">
							<div class="files-list"></div>
						</div>
					</div>

					<div class="description-text">
						Téléversez les images de la chambre.
					</div>
				</div>
				<?php $k = 0;
				for ($i = 0; $i < 5; $i++): ?>
					<div class="row" style="margin-bottom: 10px;">
						<?php for ($j = 0; $j < 2; $j++): ?>
							<div class="col-md-4">
								<div class="has-calendar-icon input text">
									<?php echo $this->Form->input("Chambre.prices.$k.date_debut", array(
										'label' => 'Date debut prix',
										'type' => 'text',
										'id' => 'date_debut_prix'.$k,
										'placeholder' => '',
										'div' => false
									)); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="has-calendar-icon input text">
									<?php echo $this->Form->input("Chambre.prices.$k.date_fin", array(
										'label' => 'Date fin prix',
										'type' => 'text',
										'id' => 'date_fin_prix'.$k,
										'placeholder' => '',
										'div' => false
									)); ?>
								</div>
							</div>
							<div class="col-md-4">
								<?php echo $this->Form->input("Chambre.prices.$k.prix", array('label' => 'Prix','placeholder' => '')); ?>
							</div>
						<?php $k++;
						endfor; ?>
					</div>
				<?php endfor; ?>

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

<script>
	document.addEventListener("DOMContentLoaded", function() {
		flatpickr(".has-calendar-icon input", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});
</script>