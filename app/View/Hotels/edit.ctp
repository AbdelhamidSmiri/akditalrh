<div class="hotels form">
	<?php echo $this->Form->create('Hotel', ["type" => "file"]); ?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php
					echo $this->Form->input('id', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('hotel', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('etoile', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('region', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					$villes_hotel['__autre__'] = 'Autre';

					// Determine selected ville (id)
					$selectedVilleId = isset($this->request->data['Hotel']['ville_id']) ? $this->request->data['Hotel']['ville_id'] : null;

					// If the current ville_id is not in the list, set to '__autre__'
					$villeExists = array_key_exists($selectedVilleId, $villes_hotel);
					$selected = $villeExists ? $selectedVilleId : '__autre__';

					// Set default value for ville_autre (only if ville not found in list)
					$ville_autre_value = '';
					if (!$villeExists && isset($this->request->data['Hotel']['ville']['ville'])) {
						$ville_autre_value = $this->request->data['Hotel']['ville']['ville'];
					}

					echo $this->Form->input('ville_select', array(
						'label' => 'Ville',
						'type' => 'select',
						'options' => $villes_hotel,
						'empty' => 'Sélectionner une ville',
						'class' => 'form-control',
						'id' => 'ville-select',
						'default' => $selected
					));

					echo $this->Form->input('ville_autre', array(
						'label' => 'Autre ville',
						'type' => 'text',
						'id' => 'ville-autre',
						'style' => !$villeExists ? 'display:block;' : 'display:none;',
						'value' => $ville_autre_value,
						'placeholder' => ''
					));
					?>

				</div>

				<div class='col-12'>
					<?php
					echo $this->Form->input('adresse', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12 mb-4 input-file'>
					<div class="file-upload-wrapper"
						data-existing-images="<?php echo isset($this->request->data['Hotel']['images']) ? htmlspecialchars($this->request->data['Hotel']['images']) : ''; ?>"
						data-images-path="/akditalrh/files/hotels/">
						<div class="file-upload-area">
							<div class="upload-text">Glissez-déposez les Images ici</div>
							<div class="upload-subtext">Ou</div>
							<button type="button" class="choose-files-btn">Choisir des images <i class="fa-light fa-cloud-arrow-up"></i></button>

							<?php echo $this->Form->file('images', array(
								'name' => 'data[Hotel][images][]',
								'class' => 'file-input',
								'accept' => '.jpg, .jpeg, .png', // Accept only image files
								'multiple' => true
							)); ?>
						</div>

						<div class="file-info">
							<div class="files-list"></div>
						</div>
					</div>

					<div class="description-text">
						Téléversez les images de l'hôtel.
					</div>
				</div>
				<div class='col-12'>
					<?php
					if (!empty($this->request->data['Hotel']['mail'])) {
						$this->request->data['Hotel']['mail'] = str_replace(';', "\n", $this->request->data['Hotel']['mail']);
					}
					echo $this->Form->input('mail', array(
						'type' => 'textarea',
						'placeholder' => '',
						'rows' => 5,
					));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('telephone', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('nom_responsable', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('reglement', array('placeholder' => ''));
					?>
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

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const form = document.getElementById('HotelEditForm');
		const emailField = document.getElementById('HotelMail');

		form.addEventListener('submit', function(e) {
			// Clean up emails: trim and join with semicolons
			const emails = emailField.value
				.split(/\r?\n/) // Split by new lines
				.map(e => e.trim()) // Remove extra spaces
				.filter(e => e.length > 0) // Remove empty lines
				.join(';'); // Join with ;

			emailField.value = emails;
		});

		// for ville autre

		const select = document.getElementById('ville-select');
		const inputAutre = document.getElementById('ville-autre');

		function toggleAutreField() {
			if (select.value === '__autre__') {
				inputAutre.style.display = 'block';
				inputAutre.name = 'Hotel[ville_autre]';
			} else {
				inputAutre.style.display = 'none';
				inputAutre.name = '';
			}
		}

		toggleAutreField(); // run on page load
		select.addEventListener('change', toggleAutreField);
	});
</script>