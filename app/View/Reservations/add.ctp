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

<div class="reservations form">
	<?php echo $this->Form->create('Reservation', array('type' => 'file')); ?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class="step_1">
					<div class='col-12'>
						<?php
						echo $this->Form->input('ville_id', array(
							'placeholder' => '',
							'label' => 'Ville',
							'empty' => 'Choisissez une ville',
						));
						?>
						<div class="message-error ville-error">
							Veuillez choisir une ville.
						</div>
					</div>

					<div class='col-12'>
						<?php
						echo $this->Form->input('chambre_id', array(
							'placeholder' => '',
							'label' => 'Hôtel',
							'empty' => 'Choisissez un hôtel',
						));
						?>
						<div class="message-error hotel-error">
							Veuillez choisir un hôtel.
						</div>
					</div>

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
						<div class="has-calendar-icon input text">
							<?php
							echo $this->Form->input('checkin', array(
								'label' => 'Check-in',
								'type' => 'text', // important: not 'date'
								'id' => 'date_checkin', // so we can replace it
								'placeholder' => '',
								'div' => false,
							));
							?>
						</div>
						<div class="message-error checkin-error">
							Veuillez sélectionner la date de check-in de l'hôtel.
						</div>
					</div>

					<div class='col-12'>
						<div class="has-calendar-icon input text">
							<?php
							echo $this->Form->input('checkout', array(
								'label' => 'Check-out',
								'type' => 'text', // important: not 'date'
								'id' => 'date_checkout', // so we can replace it
								'placeholder' => '',
								'div' => false,
							));
							?>
						</div>
						<div class="message-error checkout-error">
							Veuillez sélectionner la date de check-out de l'hôtel.
						</div>
					</div>

					<div class='col-12 text-end mt-4'>
						<button type="button" id="btn-next" class="btn btn-primary-rounded">
							<span class="rounded_icon"><i class="fa-solid fa-arrow-right"></i></span> Suivant
						</button>
					</div>
					<div class="col-12 d-flex justify-content-between mt-3">
						<span class="import-span">Tous les champs sont obligatoires.</span>
						<span class="pagin-steps">1 sur 2 étapes</span>
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
									'name' => 'data[Reservation][ordre_mission][]',
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
									'name' => 'data[Reservation][cin][]',
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
						echo $this->Form->input('message', array('placeholder' => 'Motif du séjour', 'type' => 'textarea', 'label' => 'Motif du séjour'));
						?>
						<div class="description-text">
							Expliquez brièvement la raison de votre séjour
						</div>
						<div class="message-error motif-error">
							Veuillez entrer le motif du séjour
						</div>
					</div>

					<div class='submit-section'>
						<button type="submit" class="btn btn-submit">
							<i class="fa-solid fa-paper-plane"></i> Envoyer
						</button>
					</div>

					<div class="col-12 d-flex justify-content-between mt-3">
						<span class="import-span">Tous les champs sont obligatoires.</span>
						<span class="pagin-steps">2 sur 2 étapes</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col"></div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('input_file'); ?>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		flatpickr("#date_checkin, #date_checkout", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});

	// Handle the "Next" button click
	document.getElementById('btn-next').addEventListener('click', function() {
		// Validate the first step inputs
		const villeId = document.querySelector('select[name="data[Reservation][ville_id]"]');
		const chambreId = document.querySelector('select[name="data[Reservation][chambre_id]"]');
		const siteId = document.querySelector('select[name="data[Reservation][site_id]"]');
		const checkin = document.querySelector('input[name="data[Reservation][checkin]"]');
		const checkout = document.querySelector('input[name="data[Reservation][checkout]"]');

		// Validate the inputs
		if (!villeId.value || !chambreId.value || !siteId.value || !checkin.value || !checkout.value) {
			// If any field is empty, show border red around the input and show message error
			if (!villeId.value) {
				villeId.style.border = '1px solid #b80000';
				document.querySelector('.ville-error').style.display = 'block';
			} else {
				villeId.style.border = '';
				document.querySelector('.ville-error').style.display = 'none';
			}

			if (!chambreId.value) {
				chambreId.style.border = '1px solid #b80000';
				document.querySelector('.hotel-error').style.display = 'block';
			} else {
				chambreId.style.border = '';
				document.querySelector('.hotel-error').style.display = 'none';
			}

			if (!siteId.value) {
				siteId.style.border = '1px solid #b80000';
				document.querySelector('.site-error').style.display = 'block';
			} else {
				siteId.style.border = '';
				document.querySelector('.site-error').style.display = 'none';
			}

			if (!checkin.value) {
				checkin.style.border = '1px solid #b80000';
				document.querySelector('.checkin-error').style.display = 'block';
			} else {
				checkin.style.border = '';
				document.querySelector('.checkin-error').style.display = 'none';
			}

			if (!checkout.value) {
				checkout.style.border = '1px solid #b80000';
				document.querySelector('.checkout-error').style.display = 'block';
			} else {
				checkout.style.border = '';
				document.querySelector('.checkout-error').style.display = 'none';
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

	// Handle the form submission
	document.querySelector('.reservations form').addEventListener('submit', function(event) {
		event.preventDefault(); // Prevent the default form submission
		
		//validation step 2 
		const numOdm = document.querySelector('input[name="data[Reservation][num_odm]"]');
		const ordreMissionFiles = document.querySelector('input[name="data[Reservation][ordre_mission][]"]');
		const cinFiles = document.querySelector('input[name="data[Reservation][cin][]"]');
		const message = document.querySelector('textarea[name="data[Reservation][message]"]');

		// Validate the inputs
		if (!numOdm.value || !ordreMissionFiles.files.length || !cinFiles.files.length || !message.value) {
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