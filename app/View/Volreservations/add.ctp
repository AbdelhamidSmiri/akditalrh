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
					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('depart', array('placeholder' => ''));
						?>
					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('destination', array('placeholder' => ''));
						?>
					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('date_aller', array(
							'label' => 'Date Aller',
							'type' => 'text', // important: not 'date'
							'id' => 'date_aller_input', // so we can replace it
							'placeholder' => ''
						));
						?>

					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('date_retour', array(
							'label' => 'Date Retour',
							'type' => 'text', // important: not 'date'
							'id' => 'date_retour_input', // so we can replace it
							'placeholder' => ''
						));
						?>
					</div>
					<div class='col-12 mt-5'>
						<label class="control control--checkbox">Je veux un transfert
							<input type="checkbox" name="data[Volreservation][transfer]" />
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
						<span class="pagin-steps">1 sur 2 étapes</span>
					</div>
				</div>
				<div class="step_2">
					<div class='col-12'>
						<?php
						echo $this->Form->input('num_odm', array('placeholder' => '', 'label' => 'Numéro d\'ODM'));
						?>
					</div>
					<div class='col-12 mb-4 input-file'>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('ordre_mission', array(
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
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
		<div class="col"></div>


	</div>
</div>


<script>
	document.addEventListener("DOMContentLoaded", function() {
		flatpickr("#date_aller_input, #date_retour_input", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});

	document.addEventListener('DOMContentLoaded', function() {
		// Initialize all file upload components
		const fileUploadWrappers = document.querySelectorAll('.file-upload-wrapper');

		fileUploadWrappers.forEach(function(wrapper) {
			initFileUpload(wrapper);
		});

		function initFileUpload(wrapper) {
			const fileUploadArea = wrapper.querySelector('.file-upload-area');
			const fileInput = wrapper.querySelector('.file-input');
			const fileInfo = wrapper.querySelector('.file-info');
			const filesList = wrapper.querySelector('.files-list');
			const chooseBtn = wrapper.querySelector('.choose-files-btn');

			let selectedFiles = [];

			// Add null checks with debugging
			if (!fileInput || !fileUploadArea || !filesList || !fileInfo) {
				console.error('Required elements not found:', {
					fileInput: !!fileInput,
					fileUploadArea: !!fileUploadArea,
					filesList: !!filesList,
					fileInfo: !!fileInfo
				});
				return;
			}

			// Handle file selection
			fileInput.addEventListener('change', function(e) {
				handleFileSelect(e, filesList, fileInfo);
			});


			// Handle click on upload area (improved)
			fileUploadArea.addEventListener('click', function(e) {
				if (e.target !== fileInput && !e.target.closest('.choose-files-btn')) {
					fileInput.click();
				}
			});

			// Handle button click
			if (chooseBtn) {
				chooseBtn.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					fileInput.click();
				});
			}

			function handleFileSelect(e, filesList, fileInfo) {
				const files = Array.from(e.target.files);
				selectedFiles = files;
				displayFilesList(files, filesList, fileInfo);
			}


			function displayFilesList(files, filesList, fileInfo) {
				if (!filesList || !fileInfo) return;

				filesList.innerHTML = '';

				if (files.length === 0) {
					fileInfo.classList.remove('show');
					return;
				}

				files.forEach((file, index) => {
					// Create elements safely
					const fileItem = document.createElement('div');
					fileItem.className = 'file-item';

					const fileName = document.createElement('div');
					fileName.className = 'file-name';
					fileName.textContent = file.name;

					const fileSize = document.createElement('div');
					fileSize.className = 'file-size';
					fileSize.textContent = formatFileSize(file.size);

					const fileDetails = document.createElement('div');
					fileDetails.className = 'file-details';
					fileDetails.appendChild(fileName);
					fileDetails.appendChild(fileSize);

					const removeBtn = document.createElement('button');
					removeBtn.type = 'button';
					removeBtn.className = 'remove-file';
					removeBtn.textContent = '×';
					removeBtn.setAttribute('data-index', index);

					fileItem.appendChild(fileDetails);
					fileItem.appendChild(removeBtn);
					filesList.appendChild(fileItem);

					// Add event listener for remove button
					removeBtn.addEventListener('click', function() {
						const index = parseInt(this.getAttribute('data-index'));
						removeFile(index, fileInput, filesList, fileInfo);
					});
				});

				fileInfo.classList.add('show');
			}

			function removeFile(index, fileInput, filesList, fileInfo) {
				selectedFiles.splice(index, 1);

				// Update the file input
				try {
					const dt = new DataTransfer();
					selectedFiles.forEach(file => dt.items.add(file));
					fileInput.files = dt.files;
				} catch (error) {
					console.warn('Could not update file input:', error);
				}

				displayFilesList(selectedFiles, filesList, fileInfo);
			}
		}

		function formatFileSize(bytes) {
			if (bytes === 0) return '0 Bytes';
			const k = 1024;
			const sizes = ['Bytes', 'KB', 'MB', 'GB'];
			const i = Math.floor(Math.log(bytes) / Math.log(k));
			return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
		}
	});


	// Handle the "Next" button click
	document.getElementById('btn-next').addEventListener('click', function() {
		document.querySelector('.step_1').style.display = 'none';
		document.querySelector('.step_2').style.display = 'block';
	});
</script>