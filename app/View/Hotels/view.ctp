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

	.step1.active,
	.step2.active {
		display: block;
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
									<button type="button" class="btn btn-danger-rounded remove-row-btn" onclick="removeRowPrice(0)" style="display: none;">
										<i class="fa-regular fa-trash"></i>
									</button>
								</div>
							</div>
							<div class="col-12">
								<div class="text-end">
									<button type="button" class="btn btn-primary-rounded" onclick="addrowprice()">
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
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Etoile'); ?></label>
							<span><?php echo h($hotel['Hotel']['etoile']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Region'); ?></label>
							<span><?php echo h($hotel['Hotel']['region']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Ville'); ?></label>
							<span><?php echo h($hotel['Hotel']['ville']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Adresse'); ?></label>
							<span><?php echo h($hotel['Hotel']['adresse']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Images'); ?></label>
							<span><?php echo h($hotel['Hotel']['images']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Mail'); ?></label>
							<span><?php echo h($hotel['Hotel']['mail']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Telephone'); ?></label>
							<span><?php echo h($hotel['Hotel']['telephone']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Nom Responsable'); ?></label>
							<span><?php echo h($hotel['Hotel']['nom_responsable']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Created'); ?></label>
							<span><?php echo h($hotel['Hotel']['created']); ?></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="info">
							<label><?php echo __('Reglement'); ?></label>
							<span><?php echo h($hotel['Hotel']['reglement']); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<?php
// debug($hotel);
foreach ($hotel['Chambre'] as $val): ?>
	<?php $hotelprices = $val["Hotelprice"]; ?>
	<h2><?php echo $val["nom"]; ?></h2>
	<?php echo $this->Html->link(__('Ajouter un prix'), array("controller" => "hotelprices", 'action' => 'add', $val['Hotel']['id']), array('class' => 'btn btn-primary'));
	?>
	<div class="content-table">
		<table class="table table-akdital">
			<thead>
				<tr>
					<th>date debut</th>
					<th>date fin</th>
					<th>prix</th>
					<th>Etat</th>
					<th class="actions">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($hotelprices as $hotelprice): ?>
					<tr>
						<td><?php echo $hotelprice['date_debut']; ?></td>
						<td><?php echo $hotelprice['date_fin']; ?></td>
						<td><?php echo $hotelprice['prix']; ?></td>
						<td>
							<?= (date('Y-m-d') >= $hotelprice['date_debut'] && date('Y-m-d') <= $hotelprice['date_fin']) ? '<span style="color:green">prix actif</span>' : '<span style="color:red">prix inactif</span>'; ?>
						</td>

						<td class="actions">
							<?php echo $this->Html->link(__('Edit'), array("controller" => "hotelprices", 'action' => 'edit', $hotelprice['id'])); ?>
							/
							<?php echo $this->Form->postLink(__('Delete'), array("controller" => "hotelprices", 'action' => 'delete', $hotelprice['id']), array('confirm' => __('Are you sure you want to delete # %s?', $hotelprice['id']))); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endforeach; ?>


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

		// Reset price rows - keep only the first one
		const step2 = document.querySelector('.step2');
		const allPriceRows = step2.querySelectorAll('.row[class*="row_price_"]');
		allPriceRows.forEach((row, index) => {
			if (index > 0) {
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

	function addrowprice() {
		priceRowIndex++;

		const step2 = document.querySelector('.step2');
		const addButtonContainer = step2.querySelector('.col-12:last-child');

		const newRow = document.createElement('div');
		newRow.className = `row row_price_${priceRowIndex}`;
		newRow.innerHTML = `
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
            <button type="button" class="btn btn-danger-rounded remove-row-btn" onclick="removeRowPrice(${priceRowIndex})">
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


	function removeRowPrice(index) {
		const rowToRemove = document.querySelector(`.row_price_${index}`);
		if (rowToRemove) {
			rowToRemove.remove();
			updateRemoveButtons();
		}
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
</script>