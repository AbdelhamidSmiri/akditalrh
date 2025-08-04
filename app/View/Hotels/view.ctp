<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<style>
	.step1,
	.step2 {
		display: none;
	}

	.step3 {
		display: block;
	}

	.step1.active,
	.step2.active {
		display: block;
	}


	/* style card chambres */
	.card-header {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		background: transparent;
		padding: 24px 14px;
	}

	.card-title {
		font-size: 16px;
		margin: 0;
		font-weight: 700;
	}

	.prices {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
	}

	.price_card .card-body {
		padding: 27px 12px;

	}

	.price_card .card-body td {
		font-size: 12px;

	}

	.price_card .card-body .fin,
	.price_card .card-body .debut {
		font-weight: 700;

	}

	.badge {
		display: inline-flex;
		align-items: center;
		font-size: 12px;
	}

	.badge-light-danger {
		background-color: #ffeef3 !important;
		color: #f8285a;
	}

	.badge-light-success {
		background-color: #dfffea;
		color: #17c653;
	}

	.price_card .table> :not(caption)>*>* {
		padding: 12px 4px;
		text-align: center;
	}

	.mails {
		font-size: 14px !important;
	}

	.price_card .alert {
		padding: 12px 1rem;
		margin-bottom: 0px;
	}

	.div_card-title {
		width: 70%;
	}

	.arrow_icon {
		background: #d0d9ff;
		border-radius: 25px;
		padding: 1px 5px;
	}
</style>


<!-- Modal -->
<div class="modal fade" id="chambremodal" tabindex="-1" aria-labelledby="chambremodalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<?php echo $this->Form->create('Chambre', ["type" => "file"]); ?>
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="chambremodalLabel">Ajouter une nouvelle chambre</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-add-chambre">
					<div class="row">
						<div class="step1 active">
							<div class='col-12'>
								<?php
								echo $this->Form->hidden('hotel_id', array('value' => $hotel['Hotel']['id']));
								?>
							</div>
							<div class='col-12'>
								<?php
								echo $this->Form->input('nom', array('placeholder' => '', 'label' => 'Nom/N° de la chambre'));
								?>
							</div>
							<div class='col-12'>
								<?php
								echo $this->Form->input('type', array('placeholder' => '', 'label' => 'Catégorie'));
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
						</div>
						<div class="step2">
							<div class="row row_price_0">
								<div class="col-md-4">
									<div class="has-calendar-icon input text">
										<label for="date_debut_prix0">Date debut prix</label>
										<input name="data[Chambre][prices][0][date_debut]" id="date_debut_prix0" placeholder="" type="date">
									</div>
								</div>
								<div class="col-md-4">
									<div class="has-calendar-icon input text">
										<label for="date_fin_prix0">Date fin prix</label>
										<input name="data[Chambre][prices][0][date_fin]" id="date_fin_prix0" placeholder="" type="date">
									</div>
								</div>
								<div class="col-md-3">
									<div class="input text">
										<label for="ChambrePrices0Prix">Prix</label>
										<input name="data[Chambre][prices][0][prix]" placeholder="" type="text" id="ChambrePrices0Prix">
									</div>
								</div>
								<div class="col-md-1">
									<button type="button" class="btn btn-danger-rounded remove-row-btn" data-model_index="2" onclick="removeRowPrice(0,2)" style="display: none;">
										<i class="fa-regular fa-trash"></i>
									</button>
								</div>
							</div>
							<div class="col-12">
								<div class="text-end">
									<button type="button" class="btn btn-primary-rounded  " data-model_index="2" onclick="addrowprice(this)">
										<i class="fa-regular fa-plus"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary-rounded" data-bs-dismiss="modal">Fermer</button>
				<button type="button" class="btn btn-secondary-rounded" id="retourBtn" onclick="previousStep()" style="display: none;">Retour</button>
				<button type="button" class="btn btn-primary-rounded" id="suivantBtn" onclick="nextStep()">Suivant</button>
				<button type="submit" class="btn btn-primary-rounded" id="enregistrerBtn" onclick="saveForm(e)" style="display: none;">Enregistrer</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="add_price_modal" tabindex="-1" aria-labelledby="add_price_modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<?php echo $this->Form->create('Hotelprice', array(
				'url' => array('controller' => 'hotelprices', 'action' => 'edit', $hotel['Hotel']['id'])
			)); ?>
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="add_price_modalLabel">Mettre à jour les prix<span class="chambre_name"></span></h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-add-chambre">
					<div class="row">
						<div class="step3">
							<input name="data[Chambre][delete_ids]" id="delete_ids" class="delete_ids" type="hidden" value="">


							<div class="col-12">
								<div class="text-end">
									<button type="button" class="btn btn-primary-rounded add_btn" data-chambre_id="" data-model_index="3" onclick="addrowprice(this)">
										<i class="fa-regular fa-plus"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary-rounded" data-bs-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-primary-rounded">Enregistrer</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="edit_info_modal" tabindex="-1" aria-labelledby="edit_info_modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<?php echo $this->Form->create('chambres', array(
				'url' => array('controller' => 'chambres', 'action' => 'edit', $hotel['Hotel']['id'])
			)); ?>
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="edit_info_modalLabel">Éditer les informations de la chambre<span class="chambre_name"></span></h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-add-chambre">
					<div class="row">
						<div class="step4">
							<div class="row">
								<div class="col-md-6">
									<div class="input text">
										<input name="data[Chambre][id]" id="Chambreidedite" type="hidden">
										<label for="ChambreNomedite">Nom/N° de la chambre</label>
										<input name="data[Chambre][nom]" id="ChambreNomedite" placeholder="" type="text">
									</div>
								</div>
								<div class="col-md-6">
									<div class="input text">
										<label for="Chambretypeedite">Catégorie</label>
										<input name="data[Chambre][type]" id="Chambretypeedite" placeholder="" type="text">
									</div>
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
							</div>


						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary-rounded" data-bs-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-primary-rounded">Enregistrer</button>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<div class="hotels view">

	<div class="col-md-12 little-title-section">
		<span class="little-title"><?php echo $hotel['Hotel']['hotel'] ?></span>
		<div class="actions">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary-rounded" data-bs-toggle="modal" data-bs-target="#chambremodal">
				Nouvelle chambre
			</button>


		</div>
	</div>

	<div class="card view-card">
		<div class="card-body">
			<div class="col-12">
				<div class="row row-gap-3">
					<?php if (!empty($hotel['Hotel']['region'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Region'); ?></label>
								<span><?php echo h($hotel['Hotel']['region']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['ville'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Ville'); ?></label>
								<span><?php echo h($hotel['Hotel']['ville']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['adresse'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Adresse'); ?></label>
								<span><?php echo h($hotel['Hotel']['adresse']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['images'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Images'); ?></label>
								<span><?php echo h($hotel['Hotel']['images']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['etoile'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Etoile'); ?></label>
								<div class="starts">
									<?php for ($i = 0; $i < $hotel['Hotel']['etoile']; $i++) { ?>
										<i class="fa-solid fa-star yellow m--3"></i>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['mail'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Mail'); ?></label>
								<span class="mails">
									<?php
									$mailArray = explode(';', $hotel['Hotel']['mail']);
									?>
									<?php foreach ($mailArray as $mail): ?>
										<i class="fa-regular fa-envelope"></i> <?php echo h(trim($mail)); ?><br>
									<?php endforeach; ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['telephone'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Telephone'); ?></label>
								<span><?php echo h($hotel['Hotel']['telephone']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['nom_responsable'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Nom Responsable'); ?></label>
								<span><?php echo h($hotel['Hotel']['nom_responsable']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['created'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Date Création'); ?></label>
								<span><?php echo h($hotel['Hotel']['created']); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if (!empty($hotel['Hotel']['reglement'])) { ?>
						<div class="col-md-4">
							<div class="info">
								<label><?php echo __('Reglement'); ?></label>
								<span><?php echo h($hotel['Hotel']['reglement']); ?></span>
							</div>
						</div>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>

</div>
<div class="row mt-4 row-gap-3">
	<?php
	// debug($hotel);
	foreach ($hotel['Chambre'] as $val): ?>
		<?php $hotelprices = $val["Hotelprice"];
		$jsonPrices = htmlspecialchars(json_encode($hotelprices), ENT_QUOTES, 'UTF-8'); ?>

		<div class="col-xl-4 col-lg-6 col-md-6  ">
			<div class="card view-card price_card">
				<div class="card-header">
					<div class="div_card-title">
						<h5 class="card-title">
							<?php echo $val["nom"]; ?>
						</h5>
					</div>
					<div class="action">
						<button
							type="button"
							class="btn btn-primary-rounded btn-edit-price"
							data-bs-toggle="modal"
							data-bs-target="#add_price_modal"
							data-prices="<?php echo $jsonPrices; ?>"
							data-name="<?php echo htmlspecialchars($val["nom"], ENT_QUOTES, 'UTF-8'); ?>"
							data-chambre_id="<?php echo $val["id"]; ?>"
							onclick="edit_prices(this)">
							<i class="fa-solid fa-arrows-rotate"></i>
						</button>
						<button
							type="button"
							class="btn btn-orange-rounded"
							data-bs-toggle="modal"
							data-bs-target="#edit_info_modal"
							data-type="<?php echo $val["type"]; ?>"
							data-images="<?php echo $val["images"]; ?>"
							data-name="<?php echo htmlspecialchars($val["nom"], ENT_QUOTES, 'UTF-8'); ?>"
							data-chambre_id="<?php echo $val["id"]; ?>"
							onclick="edit_info_chambre(this)">
							<i class="fa-regular fa-pen"></i>
						</button>
					</div>
				</div>
				<div class="card-body">
					<table class="table">
						<tbody>
							<?php
							if (!empty($hotelprices)) {
								foreach ($hotelprices as $hotelprice): ?>
									<tr>
										<?php (date('Y-m-d') >= $hotelprice['date_debut'] && date('Y-m-d') <= $hotelprice['date_fin']) ?  $badge_color = "badge-light-success" : $badge_color = "badge-light-danger"; ?>
										<td class="debut">
											<i class="fa-light fa-calendar-arrow-down"></i>
											<?php echo $hotelprice['date_debut']; ?>
										</td>
										<td>
											<div class="arrow_icon">
												<i class="fa-light fa-arrow-right"></i>
											</div>
										</td>
										<td class="fin">
											<i class="fa-light fa-calendar-arrow-up"></i>
											<?php echo $hotelprice['date_fin']; ?>
										</td>
										<td class="div_prix">
											<span class="badge <?= $badge_color; ?>"><?php echo $hotelprice['prix']; ?>Dh</span>
										</td>
									</tr>

								<?php endforeach;
							} else { ?>
								<div class="alert alert-danger" role="alert">
									Cette chambre n'a aucun prix.
								</div>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>


	<?php endforeach; ?>
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


	let currentStep = 1;
	let priceRowIndex = 0;

	// Initialize modal
	document.getElementById('chambremodal').addEventListener('show.bs.modal', function() {
		resetModal();
		reset(2);
	});
	document.getElementById('add_price_modal').addEventListener('show.bs.modal', function() {
		reset(3);
	});

	function resetModal() {
		currentStep = 1;
		priceRowIndex = 0;

		// Show step 1, hide step 2
		document.querySelector('.step1').classList.add('active');
		document.querySelector('.step2').classList.remove('active');

		// Reset buttons
		document.getElementById('suivantBtn').style.display = 'inline-block';
		document.getElementById('enregistrerBtn').style.display = 'none';
		document.getElementById('retourBtn').style.display = 'none';


	}

	function reset(index) {
		// Get the container for step
		const step2 = document.querySelector('.step' + index);
		const allPriceRows = step2.querySelectorAll('.row[class*="row_price_"]');

		// Remove all rows except the first one
		if (index == 3) {
			arraytoremove = [];
			step2.querySelector('.delete_ids').value = '';
		}

		allPriceRows.forEach((row, idx) => {
			if (index == 3 || (index == 2 && idx !== 0)) {
				row.remove();
			}
		});

		// Reset first row values
		const firstRow = step2.querySelector('.row_price_0');
		if (firstRow) {
			const inputs = firstRow.querySelectorAll('input');
			inputs.forEach(input => input.value = '');

			const removeBtn = firstRow.querySelector('.remove-row-btn');
			if (removeBtn) {
				removeBtn.style.display = 'none';
			}
		}
	}


	function nextStep() {
		if (currentStep === 1) {
			// Validate step 1 if needed
			if (!validateStep1()) {
				return;
			}

			currentStep = 2;
			document.querySelector('.step1').classList.remove('active');
			document.querySelector('.step2').classList.add('active');

			// Update buttons
			document.getElementById('suivantBtn').style.display = 'none';
			document.getElementById('enregistrerBtn').style.display = 'inline-block';
			document.getElementById('retourBtn').style.display = 'inline-block';
		}
	}

	function previousStep() {
		if (currentStep === 2) {
			currentStep = 1;
			document.querySelector('.step2').classList.remove('active');
			document.querySelector('.step1').classList.add('active');

			// Update buttons
			document.getElementById('suivantBtn').style.display = 'inline-block';
			document.getElementById('enregistrerBtn').style.display = 'none';
			document.getElementById('retourBtn').style.display = 'none';
		}
	}

	function validateStep1() {
		const nom = document.getElementById('ChambreNom').value;
		const type = document.getElementById('ChambreType').value;

		if (!nom || !type) {
			alert('Veuillez remplir tous les champs obligatoires');
			return false;
		}

		return true;
	}

	function addrowprice(element) {
		priceRowIndex++;
		const model_index = element.dataset.model_index;
		const chambre_id = element.dataset.chambre_id;

		const step2 = document.querySelector('.step' + model_index);


		const addButtonContainer = step2.querySelector('.col-12:last-child');

		const newRow = document.createElement('div');
		newRow.className = `row row_price_${priceRowIndex}`;
		newRow.innerHTML = `
		<input name="data[Chambre][prices][${priceRowIndex}][chambre_id]" id="chambre_id${priceRowIndex}" class="chambre_id" type="hidden" value="${chambre_id}">
        <div class="col-md-4">
            <div class="has-calendar-icon input text">
                <label for="date_debut_prix${priceRowIndex}">Date debut prix</label>
                <input name="data[Chambre][prices][${priceRowIndex}][date_debut]" id="date_debut_prix${priceRowIndex}" placeholder="" type="text">
            </div>
        </div>
        <div class="col-md-4">
            <div class="has-calendar-icon input text">
                <label for="date_fin_prix${priceRowIndex}">Date fin prix</label>
                <input name="data[Chambre][prices][${priceRowIndex}][date_fin]" id="date_fin_prix${priceRowIndex}" placeholder="" type="text">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input text">
                <label for="ChambrePrices${priceRowIndex}Prix">Prix</label>
                <input name="data[Chambre][prices][${priceRowIndex}][prix]" placeholder="" type="text" id="ChambrePrices${priceRowIndex}Prix">
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger-rounded remove-row-btn" data-model_index=${model_index} onclick="removeRowPrice(${priceRowIndex}, ${model_index})">
                <i class="fa-regular fa-trash"></i>
            </button>
        </div>
    `;

		step2.insertBefore(newRow, addButtonContainer);

		// ✅ Re-initialize flatpickr for only the two new inputs
		flatpickr(`#date_debut_prix${priceRowIndex}`, {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
		flatpickr(`#date_fin_prix${priceRowIndex}`, {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});

		updateRemoveButtons();
	}

	let arraytoremove = [];

	function removeRowPrice(index, modal_i) {
		const step = document.querySelector('.step' + modal_i);
		const rowToRemove = step.querySelector(`.row_price_${index}`);

		console.log('Step:', step);

		if (!rowToRemove || !step) return; // Stop if any required element is missing

		const hotelPriceInput = rowToRemove.querySelector('.hotelprice_id');

		// If hotelprice_id exists, add it to arraytoremove
		if (hotelPriceInput && hotelPriceInput.value) {
			arraytoremove.push(hotelPriceInput.value);
			const deleteInput = step.querySelector('.delete_ids');
			if (deleteInput) {
				deleteInput.value = arraytoremove.join(',');
			}
		}

		rowToRemove.remove();
		updateRemoveButtons();
	}



	function updateRemoveButtons() {
		const allPriceRows = document.querySelectorAll('.row[class*="row_price_"]');

		allPriceRows.forEach((row, index) => {
			const removeBtn = row.querySelector('.remove-row-btn');
			if (removeBtn) {
				// Show remove button only if there's more than one row
				if (allPriceRows.length > 1) {
					removeBtn.style.display = 'inline-block';
				} else {
					removeBtn.style.display = 'none';
				}
			}
		});
	}

	function saveForm(e) {
		e.preventDefault(); // ✅ Corrigé ici

		if (!validateStep2()) {
			return false;
		}

		// ✅ Fermer le modal AVANT le return
		const modal = bootstrap.Modal.getInstance(document.getElementById('chambremodal'));
		modal.hide();

		// Ici, tu peux éventuellement soumettre le formulaire avec AJAX ou autre
		return true;
	}


	function validateStep2() {
		const priceRows = document.querySelectorAll('.row[class*="row_price_"]');

		for (let row of priceRows) {
			const dateDebut = row.querySelector('input[name*="[date_debut]"]').value;
			const dateFin = row.querySelector('input[name*="[date_fin]"]').value;
			const prix = row.querySelector('input[name*="[prix]"]').value;

			if (!dateDebut || !dateFin || !prix) {
				alert('Veuillez remplir tous les champs de prix');
				return false;
			}
		}

		return true;
	}

	async function edit_prices(button) {
		await reset(3);
		priceRowIndex = 0;

		const pricesJson = button.getAttribute('data-prices');
		const name = button.getAttribute('data-name');
		const chambre_id = button.getAttribute('data-chambre_id');

		let prices = [];
		try {
			prices = JSON.parse(pricesJson);
		} catch (e) {
			console.error("Invalid JSON in data-prices", e);
			// Even if JSON parsing fails, prices will remain empty and we handle it below
		}

		const step2 = document.querySelector('.step3');
		const addButtonContainer = step2.querySelector('.col-12:last-child');

		const addBtn = step2.querySelector('.add_btn');
		if (addBtn) {
			addBtn.setAttribute('data-chambre_id', chambre_id);
		}

		// ✅ Ensure at least one row exists
		if (prices.length === 0) {
			prices.push({
				id: "",
				chambre_id: chambre_id,
				date_debut: "",
				date_fin: "",
				prix: ""
			});
		}

		prices.forEach((element, index) => {
			const newRow = document.createElement('div');
			newRow.className = `row row_price_${index}`;
			newRow.innerHTML = `
			<input name="data[Chambre][prices][${index}][id]" id="id_${index}" class="hotelprice_id" type="hidden" value="${element.id}">
			<input name="data[Chambre][prices][${index}][chambre_id]" id="chambre_id${index}" class="chambre_id" type="hidden" value="${chambre_id}">
			<div class="col-md-4">
				<div class="has-calendar-icon input text">
					<label for="date_debut_prix${index}">Date debut prix</label>
					<input name="data[Chambre][prices][${index}][date_debut]" id="date_debut_prix${index}" placeholder="" type="text" value="${element.date_debut}">
				</div>
			</div>
			<div class="col-md-4">
				<div class="has-calendar-icon input text">
					<label for="date_fin_prix${index}">Date fin prix</label>
					<input name="data[Chambre][prices][${index}][date_fin]" id="date_fin_prix${index}" placeholder="" type="text" value="${element.date_fin}">
				</div>
			</div>
			<div class="col-md-3">
				<div class="input text">
					<label for="ChambrePrices${index}Prix">Prix</label>
					<input name="data[Chambre][prices][${index}][prix]" placeholder="" type="text" id="ChambrePrices${index}Prix" value="${element.prix}">
				</div>
			</div>
			<div class="col-md-1">
				<button type="button" class="btn btn-danger-rounded remove-row-btn" onclick="removeRowPrice(${index},3)">
					<i class="fa-regular fa-trash"></i>
				</button>
			</div>
		`;

			step2.insertBefore(newRow, addButtonContainer);

			flatpickr(`#date_debut_prix${index}`, {
				dateFormat: "Y-m-d",
				locale: "fr",
				allowInput: true
			});
			flatpickr(`#date_fin_prix${index}`, {
				dateFormat: "Y-m-d",
				locale: "fr",
				allowInput: true
			});

			priceRowIndex = index;
		});

		updateRemoveButtons();
	}

	async function edit_info_chambre(button) {

		const name = button.getAttribute('data-name');
		const type = button.getAttribute('data-type');
		const images = button.getAttribute('data-images');
		const chambre_id = button.getAttribute('data-chambre_id');

		document.getElementById("Chambreidedite").value = chambre_id;
		document.getElementById("ChambreNomedite").value = name;
		document.getElementById("Chambretypeedite").value = type;

	}
</script>