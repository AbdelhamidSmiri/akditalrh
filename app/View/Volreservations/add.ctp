<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<style>
	.step_2 {
		display: none;
	}
</style>
<div class="volreservations form">
	<?php echo $this->Form->create('Volreservation', array('type' => 'file')); ?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class="step_1">
					<div class='col-12'>
						<?php
						echo $this->Form->input('site_id', array(
							'placeholder' => '',
							'label' => 'Site',
							'empty' => 'Choisissez le site de la réservation',
						));
						?>
						<div class="message-error site-error">
							Veuillez choisir un site.
						</div>
					</div>

					<div class='col-12'>
						<?php
						echo $this->Form->input('depart', array('placeholder' => ''));
						?>
						<div class="message-error depart-error">
							Veuillez choisir un lieu de départ.
						</div>
					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('destination', array('placeholder' => ''));
						?>
						<div class="message-error destination-error">
							Veuillez choisir une destination.
						</div>
					</div>
					<div class='col-12'>
						<div class="has-calendar-icon input text">
							<?php
							echo $this->Form->input('date_aller', array(
								'label' => 'Date Aller',
								'type' => 'text', // important: not 'date'
								'id' => 'date_aller_input', // so we can replace it
								'placeholder' => '',
								'div' => false,
							));
							?>
						</div>
						<div class="message-error dateAller-error">
							Veuillez choisir une date aller.
						</div>
					</div>
					<div class='col-12'>
						<div class="has-calendar-icon input text">
							<?php
							echo $this->Form->input('date_retour', array(
								'label' => 'Date Retour',
								'type' => 'text', // important: not 'date'
								'id' => 'date_retour_input', // so we can replace it
								'placeholder' => '',
								'div' => false,
							));
							?>
						</div>
						<div class="message-error dateRetour-error">
							Veuillez choisir une date retour.
						</div>
					</div>
					<div class='col-12 mt-5'>
						<label class="control control--checkbox">Je veux un transfert
							<input type="hidden" name="data[Volreservation][transfer]" id="transferValue" value="" />
							<input type="checkbox" id="transferCheckbox" />
							<div class="control__indicator"></div>
						</label>
					</div>
					<div class='col-12 text-end mt-4'>
						<button type="button" id="btn-next" class="btn btn-primary-rounded">
							<span class="rounded_icon"><i class="fa-solid fa-arrow-right"></i></span> Suivant
						</button>
					</div>
					<div class="col-12 d-flex justify-content-between mt-3">
						<span class="import-span">Tous les champs sont obligatoires.</span>
						<span class="pagin-steps"></span>
					</div>
				</div>
				<div class="step_2">
					<div class='col-12'>
						<?php
						echo $this->Form->input('num_odm', array('placeholder' => '', 'label' => 'Numéro d\'ODM'));
						?>
						<div class="message-error num-odm-error">
							Veuillez entrer le numéro d'ODM.
						</div>
					</div>
					<div class='col-12 mb-4 input-file'>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('ordre_mission', array(
									'name' => 'data[Volreservation][ordre_mission][]',
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
							Téléversez les fichiers de votre ordre de mission signé.
						</div>
					</div>

					<div class='col-12 mb-4 input-file'>
						<label for="cin">CIN</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('cin', array(
									'name' => 'data[Volreservation][cin][]',
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
							Téléversez les fichiers de votre CIN.
						</div>
					</div>
					<div class='col-12 mb-4 input-file'>
						<label for="passport">Passeport</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('passport', array(
									'name' => 'data[Volreservation][passport][]',
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
							Téléversez les fichiers de votre CIN.
						</div>
					</div>

					<div class='col-12'>
						<?php
						echo $this->Form->input('message', array('placeholder' => 'Motif du voyage', 'type' => 'textarea', 'label' => 'Motif du voyage'));
						?>
						<div class="description-text">
							Expliquez brièvement la raison de votre déplacement
						</div>
						<div class="message-error motif-error">
							Veuillez entrer le motif du voyage
						</div>
					</div>
					<div class='submit-section'>
						<button type="submit" class="btn btn-submit">
							<i class="fa-solid fa-paper-plane"></i> Envoyer
						</button>


					</div>
					<div class="col-12 d-flex justify-content-between mt-3">
						<span class="import-span">Tous les champs sont obligatoires.</span>
						<span class="pagin-steps"></span>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
		<div class="col"></div>


	</div>
</div>

<?php echo $this->Html->script('input_file'); ?>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		flatpickr("#date_aller_input, #date_retour_input", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});


	// Handle the "Next" button click
	document.getElementById('btn-next').addEventListener('click', function() {
		// Validate the first step inputs
		const siteId = document.querySelector('select[name="data[Volreservation][site_id]"]');
		const depart = document.querySelector('input[name="data[Volreservation][depart]"]');
		const destination = document.querySelector('input[name="data[Volreservation][destination]"]');
		const dateAller = document.querySelector('input[name="data[Volreservation][date_aller]"]');
		const dateRetour = document.querySelector('input[name="data[Volreservation][date_retour]"]');
		// Validate the inputs
		if (!siteId.value || !depart.value || !destination.value || !dateAller.value || !dateRetour.value) {
			// If any field is empty, show border red around the input and show message error
			if (!siteId.value) {
				siteId.style.border = '1px solid #b80000';
				document.querySelector('.site-error').style.display = 'block';
			} else {
				siteId.style.border = '';
				document.querySelector('.site-error').style.display = 'none';
			}


			if (!depart.value) {
				depart.style.border = '1px solid #b80000';
				document.querySelector('.depart-error').style.display = 'block';
			} else {
				depart.style.border = '';
				document.querySelector('.depart-error').style.display = 'none';
			}
			if (!destination.value) {
				destination.style.border = '1px solid #b80000';
				document.querySelector('.destination-error').style.display = 'block';
			} else {
				destination.style.border = '';
				document.querySelector('.destination-error').style.display = 'none';
			}
			if (!dateAller.value) {
				dateAller.style.border = '1px solid #b80000';
				document.querySelector('.dateAller-error').style.display = 'block';
			} else {
				dateAller.style.border = '';
				document.querySelector('.dateAller-error').style.display = 'none';
			}
			if (!dateRetour.value) {
				dateRetour.style.border = '1px solid #b80000';
				document.querySelector('.dateRetour-error').style.display = 'block';
			} else {
				dateRetour.style.border = '';
				document.querySelector('.dateRetour-error').style.display = 'none';
			}
			return;
		}
		// If validation passes, hide the first step and show the second step
		document.querySelector('.step_1').style.display = 'none';
		document.querySelector('.step_2').style.display = 'block';
		// Update the pagination step text
		document.querySelector('.pagin-steps').textContent = '2 sur 2 étapes';
		// Scroll to the top of the form
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	});


	document.getElementById('transferCheckbox').addEventListener('change', function() {
		document.getElementById('transferValue').value = this.checked ? '1' : '';
	});

	// Handle the form submission
	document.querySelector('.volreservations form').addEventListener('submit', function(event) {
		event.preventDefault(); // Prevent the default form submission
		//validation step 2 
		const numOdm = document.querySelector('input[name="data[Volreservation][num_odm]"]');
		const ordreMissionFiles = document.querySelector('input[name="data[Volreservation][ordre_mission][]"]');
		const cinFiles = document.querySelector('input[name="data[Volreservation][cin][]"]');
		const passportFiles = document.querySelector('input[name="data[Volreservation][passport][]"]');
		const message = document.querySelector('textarea[name="data[Volreservation][message]"]');
		// Validate the inputs
		if (!numOdm.value || !ordreMissionFiles.files.length || !cinFiles.files.length || !passportFiles.files.length || !message.value) {
			// If any field is empty, show border red around the input and show message error
			if (!numOdm.value) {
				numOdm.style.border = '1px solid #b80000';
				document.querySelector('.num-odm-error').style.display = 'block';
			} else {
				numOdm.style.border = '';
				document.querySelector('.num-odm-error').style.display = 'none';
			}
			if (ordreMissionFiles.files.length === 0) {
				const parent = ordreMissionFiles.closest('.file-upload-area');
				if (parent) {
					parent.style.border = '2px dashed #b80000';
				}
			} else {
				const parent = ordreMissionFiles.closest('.file-upload-area');
				if (parent) {
					parent.style.border = '';
				}
			}
			// CIN
			if (cinFiles.files.length === 0) {
				const parent = cinFiles.closest('.file-upload-area');
				if (parent) {
					parent.style.border = '2px dashed #b80000';
				}
			} else {
				const parent = cinFiles.closest('.file-upload-area');
				if (parent) {
					parent.style.border = '';
				}
			}

			// PASSPORT
			if (passportFiles.files.length === 0) {
				const parent = passportFiles.closest('.file-upload-area');
				if (parent) {
					parent.style.border = '2px dashed #b80000';
				}
			} else {
				const parent = passportFiles.closest('.file-upload-area');
				if (parent) {
					parent.style.border = '';
				}
			}

			if (!message.value) {
				message.style.border = '1px solid #b80000';
				document.querySelector('.motif-error').style.display = 'block';
			} else {
				message.style.border = '';
				document.querySelector('.motif-error').style.display = 'none';
			}
		} else {
			// If validation passes, submit the form
			this.submit();
		}

	});
</script>