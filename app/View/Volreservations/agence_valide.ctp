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
</style>

<div class="volreservations form">
	<?php echo $this->Form->create('Volreservation', ["type" => "file"]);
	?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class="step step_1">
					<div class='col-12'> <?php echo $this->Form->input('reponse', array('placeholder' => '')); ?></div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('num_vol', array('placeholder' => ''));
						?>
					</div>
					<div class='col-12'>
						<?php echo $this->Form->input('prix_vol', array('placeholder' => '')); ?>
					</div>
					<div class='col-12 mb-4 input-file'>
						<label for="file_aller">Les fichiers d’aller</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
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
							Téléversez les fichiers d’aller.
						</div>
					</div>
					<div class='col-12 mb-4 input-file'>
						<label for="cin">Les fichiers de retour</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
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
					</div>

				</div>
				<div class="step step_2">
					<?php if ($vol["Volreservation"]["transfer"] == 1): ?>
						<div class='col-12'>
							<?php
							echo $this->Form->input('nom_transfer', array('placeholder' => ''));
							?>
						</div>
						<div class='col-12'>
							<div class="has-calendar-icon input text">
								<?php
								echo $this->Form->input('date_transfer', array(
									'label' => 'Date Transfer',
									'type' => 'text', // important: not 'date'
									'id' => 'date_transfer', // so we can replace it
									'placeholder' => '',
									'div' => false,
								));
								?>
							</div>
							<div class="message-error dateRetour-error">
								Veuillez choisir une date de transfer.
							</div>
						</div>

						<div class='col-12'>
							<?php
							echo $this->Form->input('tel_transfer', array('placeholder' => ''));
							?>
						</div>
						<div class='col-12'>
							<?php
							echo $this->Form->input('description_transfer', array('placeholder' => ''));
							?>
						</div>
						<div class='col-12'>
							<?php
							echo $this->Form->input('prix_transfert', array('placeholder' => ''));
							?>
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
					</div>
				</div>
				<div class="col-12">
					<div class='col-12 text-end mt-4 btns-step'>
						<button type="button" id="btn-next" class="btn btn-primary-rounded">
							<span class="rounded_icon"><i class="fa-solid fa-arrow-right"></i></span> Suivant
						</button>
					</div>

					<div class='submit-section btns-submit' style="display:none">
						<button type="submit" class="btn btn-submit">
							<i class="fa-solid fa-paper-plane"></i> Envoyer
						</button>
					</div>

					<div class="col-12 d-flex justify-content-between mt-3">
						<span class="import-span">Tous les champs sont obligatoires.</span>
						<span class="pagin-steps">1 sur X étapes</span>
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
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});
	// Handle the "Next" button click

	const steps = document.querySelectorAll('.step');
	const nextBtn = document.getElementById('btn-next');
	const submitSection = document.querySelector('.btns-submit');
	const paginSteps = document.querySelector('.pagin-steps');
	let currentStep = 0;

	function showStep(index) {
		steps.forEach((step, i) => {
			step.style.display = i === index ? 'block' : 'none';
		});

		paginSteps.textContent = `${index + 1} sur ${steps.length} étapes`;

		if (index === steps.length - 1) {
			nextBtn.style.display = 'none';
			submitSection.style.display = 'flex';
		} else {
			nextBtn.style.display = 'inline-block';
			submitSection.style.display = 'none';
		}

		// Scroll to top smoothly
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	}

	nextBtn.addEventListener('click', () => {
		if (currentStep < steps.length - 1) {
			currentStep++;
			showStep(currentStep);
		}
	});

	// Initial display
	showStep(currentStep);
</script>