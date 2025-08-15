<!-- this agence_valide -->
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<style>
	.step_2,
	.btns-step2 {
		display: none;
	}

	.message-error {
		color: #b80000;
		font-size: 0.875rem;
		margin-top: 5px;
		display: none;
	}

	.btns-step {
		display: flex;
	}

	.btns-submit {
		display: none;
	}
</style>

<div class="volreservations form">
	<?php echo $this->Form->create('Volreservation', ["type" => "file"]);
	?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class="step step_1">
					<div class='col-12'>
						<?php echo $this->Form->input('reponse', array('placeholder' => '')); ?>
						<div class="message-error reponse-error">
							Veuillez saisir une réponse.
						</div>
					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('num_vol', array('placeholder' => '', 'label' => 'Numéro du vol'));
						?>
						<div class="message-error num-vol-error">
							Veuillez saisir le numéro du vol.
						</div>
					</div>
					<div class='col-12'>
						<?php echo $this->Form->input('prix_vol', array('placeholder' => '', 'label' => 'Prix du billet (ttc)')); ?>
						<div class="message-error prix-vol-error">
							Veuillez saisir le prix du billet.
						</div>
					</div>
					<div class='col-12 mb-4 input-file'>
						<label for="file_aller">Les fichiers d'aller</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area" id="file-aller-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('file_aller', array(
									'name' => 'data[Volreservation][file_aller][]',
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
							Téléversez les fichiers d'aller.
						</div>
						<div class="message-error file-aller-error">
							Veuillez téléverser au moins un fichier d'aller.
						</div>
					</div>
					<div class='col-12 mb-4 input-file'>
						<label for="cin">Les fichiers de retour</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area" id="file-retour-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('file_retour', array(
									'name' => 'data[Volreservation][file_retour][]',
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
							Téléversez les fichiers de retour.
						</div>
						<div class="message-error file-retour-error">
							Veuillez téléverser au moins un fichier de retour.
						</div>
					</div>

				</div>
				<div class="step step_2">
					<?php if ($vol["Volreservation"]["transfer"] == 1): ?>
						<div class='col-12'>
							<?php
							echo $this->Form->input('nom_transfer', array('placeholder' => '', 'label' => 'Nom de chauffeur'));
							?>
							<div class="message-error nom-transfer-error">
								Veuillez saisir le nom du chauffeur.
							</div>
						</div>
						<div class='col-12'>
							<div class="has-calendar-icon input text">
								<?php
								echo $this->Form->input('date_transfer', array(
									'label' => 'Date et heure du transfer',
									'type' => 'text', // important: not 'date'
									'id' => 'date_transfer', // so we can replace it
									'placeholder' => '',
									'div' => false,
								));
								?>
							</div>
							<div class="message-error date-transfer-error">
								Veuillez choisir une date de transfer.
							</div>
						</div>

						<div class='col-12'>
							<?php
							echo $this->Form->input('tel_transfer', array('placeholder' => '', 'label' => 'Téléphone du chauffeur'));
							?>
							<div class="message-error tel-transfer-error">
								Veuillez saisir le téléphone du chauffeur.
							</div>
						</div>
						<div class='col-12 mb-3'>
							<?php
							echo $this->Form->input('description_transfer', array('placeholder' => '', 'label' => 'Description du transfert'));
							?>
							<div class="message-error description-transfer-error">
								Veuillez saisir une description du transfert.
							</div>
						</div>
						<div class='col-12'>
							<?php
							echo $this->Form->input('pick_up', array('placeholder' => '', 'label' => 'Pick-up'));
							?>
							<div class="message-error pick-up-error">
								Veuillez saisir le point de pick-up.
							</div>
						</div>
						<div class='col-12'>
							<?php
							echo $this->Form->input('prix_transfert', array('placeholder' => '', 'label' => 'Prix du transfert(TTC)'));
							?>
							<div class="message-error prix-transfert-error">
								Veuillez saisir le prix du transfert.
							</div>
						</div>
					<?php endif; ?>
					<div class=""></div>
					<div class='col-12 mb-4 input-file'>
						<label for="cin">Autre documents</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('file_retour', array(
									'name' => 'data[Volreservation][documents][]',
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
							Téléversez les autre documents.
						</div>
						<div class="message-error autre-documents-error">
							Veuillez téléverser au moins un autre document.
						</div>
					</div>
				</div>
				<div class="col-12">
					<div class='col-12 d-flex justify-content-between mt-4 btns-step'>
						<button type="button" id="btn-back" class="btn btn-secondary-rounded" style="display:none">
							<i class="fa-solid fa-arrow-left me-2"></i> Retour
						</button>
						<div></div>
						<button type="button" id="btn-next" class="btn btn-primary-rounded">
							<i class="fa-solid fa-arrow-right me-2"></i> Suivant
						</button>
					</div>

					<div class='submit-section btns-submit'>
						<button type="button" id="btn-back-final" class="btn btn-secondary-rounded">
							<i class="fa-solid fa-arrow-left me-2"></i> Retour
						</button>
						<button type="submit" class="btn btn-submit">
							<i class="fa-solid fa-paper-plane me-2"></i> Valider et envoyer au demandeur
						</button>
					</div>

					<div class="col-12 d-flex justify-content-between mt-3">
						<span class="import-span">Tous les champs sont obligatoires.</span>
						<span class="pagin-steps">1 sur 2 étapes</span>
					</div>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div class="col"></div>


	</div>
</div>

<?php echo $this->Html->script('input_file'); ?>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		flatpickr("#date_transfer", {
			enableTime: true, // allow time selection
			dateFormat: "Y-m-d H:i", // format with date and time
			time_24hr: true, // 24-hour format instead of AM/PM
			locale: "fr",
			allowInput: true,
			minDate: "today"
		});

		// Add submit button validation
		const submitBtn = document.querySelector('button[type="submit"]');
		if (submitBtn) {
			submitBtn.addEventListener('click', function(e) {
				e.preventDefault(); // Prevent form submission initially
				
				// Validate step 2 before submitting
				if (!validateStep2()) {
					// Scroll to top to show errors
					window.scrollTo({
						top: 0,
						behavior: 'smooth'
					});
					return; // Don't submit if validation fails
				}
				
				// If validation passes, submit the form
				document.querySelector('form').submit();
			});
		}
	});

	// Validation function for step 1
	function validateStep1() {
		let isValid = true;

		// Get form elements using multiple possible selectors (CakePHP generates different IDs)
		const reponse = document.getElementById('VolreservationReponse') ||
			document.querySelector('input[name="data[Volreservation][reponse]"]') ||
			document.querySelector('#reponse');

		const numVol = document.getElementById('VolreservationNumVol') ||
			document.querySelector('input[name="data[Volreservation][num_vol]"]') ||
			document.querySelector('#num_vol');

		const prixVol = document.getElementById('VolreservationPrixVol') ||
			document.querySelector('input[name="data[Volreservation][prix_vol]"]') ||
			document.querySelector('#prix_vol');

		const fileAller = document.querySelector('input[name="data[Volreservation][file_aller][]"]') ||
			document.getElementById('VolreservationFileAller');

		const fileRetour = document.querySelector('input[name="data[Volreservation][file_retour][]"]') ||
			document.getElementById('VolreservationFileRetour');

	

		// Validate reponse
		if (!reponse || !reponse.value.trim()) {
			if (reponse) reponse.style.border = '1px solid #b80000';
			document.querySelector('.reponse-error').style.display = 'block';
			isValid = false;
		} else {
			reponse.style.border = '';
			document.querySelector('.reponse-error').style.display = 'none';
		}

		// Validate num_vol
		if (!numVol || !numVol.value.trim()) {
			if (numVol) numVol.style.border = '1px solid #b80000';
			document.querySelector('.num-vol-error').style.display = 'block';
			isValid = false;
		} else {
			numVol.style.border = '';
			document.querySelector('.num-vol-error').style.display = 'none';
		}

		// Validate prix_vol
		if (!prixVol || !prixVol.value.trim()) {
			if (prixVol) prixVol.style.border = '1px solid #b80000';
			document.querySelector('.prix-vol-error').style.display = 'block';
			isValid = false;
		} else {
			prixVol.style.border = '';
			document.querySelector('.prix-vol-error').style.display = 'none';
		}

		// Validate file_aller
		if (!fileAller || fileAller.files.length === 0) {
			const parent = document.getElementById('file-aller-area');
			if (parent) parent.style.border = '2px dashed #b80000';
			document.querySelector('.file-aller-error').style.display = 'block';
			isValid = false;
		} else {
			const parent = document.getElementById('file-aller-area');
			if (parent) parent.style.border = '';
			document.querySelector('.file-aller-error').style.display = 'none';
		}

		// Validate file_retour
		if (!fileRetour || fileRetour.files.length === 0) {
			const parent = document.getElementById('file-retour-area');
			if (parent) parent.style.border = '2px dashed #b80000';
			document.querySelector('.file-retour-error').style.display = 'block';
			isValid = false;
		} else {
			const parent = document.getElementById('file-retour-area');
			if (parent) parent.style.border = '';
			document.querySelector('.file-retour-error').style.display = 'none';
		}

		return isValid;
	}

	// Validation function for step 2
	function validateStep2() {
		let isValid = true;

		// Check if transfer is enabled by looking for the transfer fields
		const nomTransfer = document.getElementById('VolreservationNomTransfer') ||
			document.querySelector('input[name="data[Volreservation][nom_transfer]"]');
		
		// If transfer fields don't exist, step 2 is valid (no transfer required)
		if (!nomTransfer) {
			return true;
		}

		// Get all transfer-related form elements
		const dateTransfer = document.getElementById('VolreservationDateTransfer') ||
			document.querySelector('input[name="data[Volreservation][date_transfer]"]') ||
			document.getElementById('date_transfer');

		const telTransfer = document.getElementById('VolreservationTelTransfer') ||
			document.querySelector('input[name="data[Volreservation][tel_transfer]"]');

		const descriptionTransfer = document.getElementById('VolreservationDescriptionTransfer') ||
			document.querySelector('textarea[name="data[Volreservation][description_transfer]"]') ||
			document.querySelector('input[name="data[Volreservation][description_transfer]"]');

		const pickUp = document.getElementById('VolreservationPickUp') ||
			document.querySelector('input[name="data[Volreservation][pick_up]"]');

		const prixTransfert = document.getElementById('VolreservationPrixTransfert') ||
			document.querySelector('input[name="data[Volreservation][prix_transfert]"]');

		// Validate nom_transfer
		if (!nomTransfer || !nomTransfer.value.trim()) {
			if (nomTransfer) nomTransfer.style.border = '1px solid #b80000';
			const errorElement = document.querySelector('.nom-transfer-error');
			if (errorElement) errorElement.style.display = 'block';
			isValid = false;
		} else {
			nomTransfer.style.border = '';
			const errorElement = document.querySelector('.nom-transfer-error');
			if (errorElement) errorElement.style.display = 'none';
		}

		// Validate date_transfer
		if (!dateTransfer || !dateTransfer.value.trim()) {
			if (dateTransfer) dateTransfer.style.border = '1px solid #b80000';
			const errorElement = document.querySelector('.date-transfer-error');
			if (errorElement) errorElement.style.display = 'block';
			isValid = false;
		} else {
			dateTransfer.style.border = '';
			const errorElement = document.querySelector('.date-transfer-error');
			if (errorElement) errorElement.style.display = 'none';
		}

		// Validate tel_transfer
		if (!telTransfer || !telTransfer.value.trim()) {
			if (telTransfer) telTransfer.style.border = '1px solid #b80000';
			const errorElement = document.querySelector('.tel-transfer-error');
			if (errorElement) errorElement.style.display = 'block';
			isValid = false;
		} else {
			telTransfer.style.border = '';
			const errorElement = document.querySelector('.tel-transfer-error');
			if (errorElement) errorElement.style.display = 'none';
		}

		// Validate description_transfer
		if (!descriptionTransfer || !descriptionTransfer.value.trim()) {
			if (descriptionTransfer) descriptionTransfer.style.border = '1px solid #b80000';
			const errorElement = document.querySelector('.description-transfer-error');
			if (errorElement) errorElement.style.display = 'block';
			isValid = false;
		} else {
			descriptionTransfer.style.border = '';
			const errorElement = document.querySelector('.description-transfer-error');
			if (errorElement) errorElement.style.display = 'none';
		}

		// Validate pick_up
		if (!pickUp || !pickUp.value.trim()) {
			if (pickUp) pickUp.style.border = '1px solid #b80000';
			const errorElement = document.querySelector('.pick-up-error');
			if (errorElement) errorElement.style.display = 'block';
			isValid = false;
		} else {
			pickUp.style.border = '';
			const errorElement = document.querySelector('.pick-up-error');
			if (errorElement) errorElement.style.display = 'none';
		}

		// Validate prix_transfert
		if (!prixTransfert || !prixTransfert.value.trim()) {
			if (prixTransfert) prixTransfert.style.border = '1px solid #b80000';
			const errorElement = document.querySelector('.prix-transfert-error');
			if (errorElement) errorElement.style.display = 'block';
			isValid = false;
		} else {
			prixTransfert.style.border = '';
			const errorElement = document.querySelector('.prix-transfert-error');
			if (errorElement) errorElement.style.display = 'none';
		}
	
	

		return isValid;
	}

	// Handle the "Next" button click
	const steps = document.querySelectorAll('.step');
	const nextBtn = document.getElementById('btn-next');
	const backBtn = document.getElementById('btn-back');
	const backBtnFinal = document.getElementById('btn-back-final');
	const submitSection = document.querySelector('.btns-submit');
	const btnsStep = document.querySelector('.btns-step');
	const paginSteps = document.querySelector('.pagin-steps');
	let currentStep = 0;

	function showStep(index) {
		steps.forEach((step, i) => {
			step.style.display = i === index ? 'block' : 'none';
		});

		paginSteps.textContent = `${index + 1} sur ${steps.length} étapes`;

		// Show/hide buttons based on current step
		if (index === steps.length - 1) {
			// Last step - hide navigation buttons, show submit section
			btnsStep.style.display = 'none';
			nextBtn.style.display = 'none';
			submitSection.style.display = 'flex';
			submitSection.style.justifyContent = 'space-between';
		} else {
			// Not last step - show navigation buttons, hide submit section
			btnsStep.style.display = 'flex';
			submitSection.style.display = 'none';

			// Show/hide back button based on step
			if (index === 0) {
				backBtn.style.display = 'none';
				btnsStep.style.justifyContent = 'flex-end';
				nextBtn.style.display = 'inline-block';

			} else {
				nextBtn.style.display = 'none';
				backBtn.style.display = 'inline-block';
				btnsStep.style.justifyContent = 'space-between';
			}
		}

		// Scroll to top smoothly
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	}

	nextBtn.addEventListener('click', () => {
		// Validate current step before proceeding
		if (currentStep === 0) {
			if (!validateStep1()) {
				return; // Don't proceed if validation fails
			}
		}

		if (currentStep < steps.length - 1) {
			currentStep++;
			showStep(currentStep);
		}
	});

	// Handle back button clicks
	backBtn.addEventListener('click', () => {
		if (currentStep > 0) {
			currentStep--;
			showStep(currentStep);
		}
	});

	backBtnFinal.addEventListener('click', () => {
		if (currentStep > 0) {
			currentStep--;
			showStep(currentStep);
		}
	});

	// Initial display
	showStep(currentStep);
</script>