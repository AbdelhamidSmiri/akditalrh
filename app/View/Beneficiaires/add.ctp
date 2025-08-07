<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<style>
	.title-step {
		font-size: 22px;
		font-weight: 600;
		color: #1e2c5a;
		margin-bottom: 4px;
	}

	.content-card .flex {
		display: flex;
	}

	.block-content {
		display: flex;
		flex-direction: column;
		width: fit-content;
		margin-bottom: 18px;
	}

	.step_2,
	.step_3 {
		display: none;
	}

	.appartement-card {
		margin-bottom: 20px;
		border: 2px solid #D0D9FF;
		border-radius: 15px;
		transition: all 0.3s ease;
	}

	.appartement-card:hover {
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}

	.appartement-card.selected {
		border-color: #007bff;
		box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
	}


	.btn-submit {
		background-color: #28a745;
		color: white;
		border: none;
		padding: 12px 30px;
		border-radius: 5px;
		cursor: pointer;
		font-size: 16px;
	}

	.btn-submit:hover {
		background-color: #218838;
	}

	.loading {
		text-align: center;
		padding: 20px;
		color: #666;
	}

	.no-results {
		text-align: center;
		padding: 20px;
		color: #999;
		font-style: italic;
	}

	.pagin-steps {
		font-size: 14px;
		color: #666;
	}

	.import-span {
		font-size: 12px;
		color: #999;
	}
</style>

<div class="beneficiaires form">
	<?php echo $this->Form->create('Beneficiaire'); ?>

	<div class="row justify-content-center">
		<!-- Step 1: Search Criteria -->
		<div class="step_1 col-md-8">
			<h1 class="title-step mb-4">Recherche d'appartements</h1>


			<label class="title-section-form">Période d’hébergement</label>
			<div class="row">
				<div class='col-md-6 col-sm-12'>

					<div class="has-calendar-icon input text">
						<?php
						echo $this->Form->input('date_debut', array(
							'label' => 'Date d’arrivée prévue',
							'type' => 'text',
							'id' => 'date_debut_input',
							'placeholder' => '',
							'div' => false,
						));
						?>
					</div>
					<div class="message">
						Date estimée de début d’occupation du logement.
					</div>
					<div class="message-error date-debut-error">
						Veuillez choisir une date début d’occupation du logement.
					</div>
				</div>

				<div class='col-md-6 col-sm-12'>
					<div class="has-calendar-icon input text">
						<?php
						echo $this->Form->input('date_fin', array(
							'label' => 'Date de départ prévue',
							'type' => 'text',
							'id' => 'date_fin_input',
							'placeholder' => '',
							'div' => false,
						));
						?>
					</div>
					<div class="message">
						Date estimée de fin de l’hébergement.
					</div>
					<div class="message-error date-fin-error">
						Veuillez choisir une date fin.
					</div>
				</div>
			</div>

			<div class='col-12'>
				<?php
				$sexe = ["Homme" => "Homme", "Femme" => "Femme"];
				echo $this->Form->input('sexe', array(
					'placeholder' => '',
					"options" => $sexe,
					'empty' => 'Choisissez le sexe'
				));
				?>
				<div class="message">
					Sélectionnez le sexe de la personne à loger.
				</div>
				<div class="message-error sexe-error">
					Veuillez sélectionnez le sexe.
				</div>
			</div>

			<div class='col-12'>
				<?php
				echo $this->Form->input('ville_id', array(
					'placeholder' => '',
					"options" => $villes,
					'empty' => 'Choisissez la ville',
					'label' => 'Ville'
				));
				?>
				<div class="message">
					Sélectionnez la ville où loger.
				</div>
				<div class="message-error ville-error">
					Veuillez choisir une ville.
				</div>
			</div>

			<div class='col-12 text-end mt-4'>
				<button type="button" id="btn-rechercher" class="btn btn-primary-rounded">
					<i class="fa-solid fa-search"></i> Rechercher
				</button>
			</div>

			<div class="col-12 d-flex justify-content-between mt-3">
				<span class="import-span">Tous les champs sont obligatoires.</span>
				<span class="pagin-steps">1 sur 3 étapes</span>
			</div>
		</div>

		<!-- Step 2: Available Apartments -->
		<div class="step_2 col-md-12">
			<h1 class="title-step mb-5">Liste des appartements disponibles</h1>
			<div id="appartements-container" class="row">
				<!-- Apartments will be loaded here via AJAX -->
			</div>

			<div class="col-12 d-flex justify-content-between mt-3">
				<button type="button" id="btn-retour-step1" class="btn btn-secondary-rounded">
					<i class="fa-solid fa-arrow-left"></i> Retour
				</button>
				<span class="pagin-steps">2 sur 3 étapes</span>
			</div>
		</div>

		<!-- Step 3: Beneficiary Details -->
		<div class="step_3 col-md-8">
			<h1 class="title-step mb-4">Informations du bénéficiaire</h1>

			<?php echo $this->Form->hidden('appartement_id', array('id' => 'selected_appartement_id')); ?>
			<div class='col-12'>
				<?php
				echo $this->Form->input('site_id', array(
					'placeholder' => '',
					'label' => 'Site',
					'empty' => 'Site d’affectation'
				));
				?>
				<div class="message">
					Indiquez le site Akdital auquel le bénéficiaire est rattaché.
				</div>
				<div class="message-error site-error">
					Veuillez choisir un site.
				</div>
			</div>
			<div class='col-12'>
				<?php
				echo $this->Form->input('nom', array(
					'placeholder' => '',
					'label' => 'Nom et prénom du bénéficiaire'
				));
				?>
				<div class="message">
					Saisissez le nom complet de la personne à loger.
				</div>
				<div class="message-error nom-error">
					Veuillez entrer le nom.
				</div>
			</div>

			<div class='col-12'>
				<?php
				echo $this->Form->input('mail', array(
					'placeholder' => '',
					'label' => 'L’adresse email du bénéficiaire'
				));
				?>
				<div class="message">
					Saisissez l’adresse email de la personne à loger.
				</div>
				<div class="message-error mail-error">
					Veuillez entrer un email valide.
				</div>
			</div>

			<div class='col-12'>
				<?php
				echo $this->Form->input('telephone', array(
					'placeholder' => '',
					'label' => 'Numéro WhatsApp du bénéficiaire'
				));
				?>
				<div class="message">
					Saisissez le Numéro WhatsApp de la personne à loger.
				</div>
				<div class="message-error telephone-error">
					Veuillez entrer le numéro de téléphone.
				</div>
			</div>

			<div class='col-12'>
				<?php
				$status = [
					"Stagiaire" => "Stagiaire",
					"Employe" => "Employé",
					"Externe" => "Externe",
					"Nouvelle recrue" => "Nouvelle recrue",
					"Autre" => "Autre"
				];
				echo $this->Form->input('status', array(
					'placeholder' => '',
					"options" => $status,
					'empty' => 'Choisissez le statut',
					'label' => 'Statut du bénéficiaire'
				));
				?>
				<div class="message">
					Sélectionnez le type de profil concerné (stagiaire, nouvelle recrue, etc.).
				</div>
				<div class="message-error status-error">
					Veuillez choisir le statut.
				</div>
			</div>

			<div class='col-12'>
				<?php
				echo $this->Form->input('note', array(
					'placeholder' => 'Notes supplémentaires...',
					'type' => 'textarea',
					'label' => 'Notes / Contraintes particulières (facultatif)'
				));
				?>
				<div class="message" style="margin-top: 1px;">
					Ajoutez toute information utile pour l’affectation (préférences, conditions spécifiques, etc.).
				</div>
			</div>

			<div class='submit-section mt-4'>
				<button type="submit" class="btn btn-success-rounded">
					<i class="fa-solid fa-paper-plane"></i> Enregistrer la demande
				</button>
			</div>

			<div class="col-12 d-flex justify-content-between mt-3">
				<button type="button" id="btn-retour-step2" class="btn btn-secondary-rounded">
					<i class="fa-solid fa-arrow-left"></i> Retour
				</button>
				<span class="pagin-steps">3 sur 3 étapes</span>
			</div>
		</div>
	</div>

	<?php echo $this->Form->end(); ?>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Initialize Flatpickr
		flatpickr("#date_debut_input, #date_fin_input", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: false,
			disableMobile: true
		});

		// Handle search button click
		document.getElementById('btn-rechercher').addEventListener('click', function() {
			if (validateStep1()) {
				searchAppartements();
			}
		});

		// Handle back buttons
		document.getElementById('btn-retour-step1').addEventListener('click', function() {
			showStep(1);
		});

		document.getElementById('btn-retour-step2').addEventListener('click', function() {
			showStep(2);
		});

		// Handle form submission
		document.querySelector('.beneficiaires form').addEventListener('submit', function(event) {
			event.preventDefault();
			if (validateStep3()) {
				this.submit();
			}
		});

		function validateStep1() {
			const dateDebut = document.querySelector('input[name="data[Beneficiaire][date_debut]"]');
			const dateFin = document.querySelector('input[name="data[Beneficiaire][date_fin]"]');
			const sexe = document.querySelector('select[name="data[Beneficiaire][sexe]"]');
			const ville = document.querySelector('select[name="data[Beneficiaire][ville_id]"]');

			let isValid = true;



			// Validate date debut
			if (!dateDebut.value) {
				showError(dateDebut, '.date-debut-error');
				isValid = false;
			} else {
				hideError(dateDebut, '.date-debut-error');
			}

			// Validate date fin
			if (!dateFin.value) {
				showError(dateFin, '.date-fin-error');
				isValid = false;
			} else {
				hideError(dateFin, '.date-fin-error');
			}

			// Validate sexe
			if (!sexe.value) {
				showError(sexe, '.sexe-error');
				isValid = false;
			} else {
				hideError(sexe, '.sexe-error');
			}

			// Validate ville
			if (!ville.value) {
				showError(ville, '.ville-error');
				isValid = false;
			} else {
				hideError(ville, '.ville-error');
			}

			return isValid;
		}

		function validateStep3() {
			const nom = document.querySelector('input[name="data[Beneficiaire][nom]"]');
			const mail = document.querySelector('input[name="data[Beneficiaire][mail]"]');
			const telephone = document.querySelector('input[name="data[Beneficiaire][telephone]"]');
			const status = document.querySelector('select[name="data[Beneficiaire][status]"]');

			let isValid = true;

			// Validate nom
			if (!nom.value.trim()) {
				showError(nom, '.nom-error');
				isValid = false;
			} else {
				hideError(nom, '.nom-error');
			}

			// Validate email
			if (!mail.value.trim() || !isValidEmail(mail.value)) {
				showError(mail, '.mail-error');
				isValid = false;
			} else {
				hideError(mail, '.mail-error');
			}

			// Validate telephone
			if (!telephone.value.trim()) {
				showError(telephone, '.telephone-error');
				isValid = false;
			} else {
				hideError(telephone, '.telephone-error');
			}

			// Validate status
			if (!status.value) {
				showError(status, '.status-error');
				isValid = false;
			} else {
				hideError(status, '.status-error');
			}

			return isValid;
		}
		const mainurl = getMainUrlSimple();
		function searchAppartements() {
			const formData = new FormData();
			formData.append('data[Beneficiaire][date_debut]', document.querySelector('input[name="data[Beneficiaire][date_debut]"]').value);
			formData.append('data[Beneficiaire][date_fin]', document.querySelector('input[name="data[Beneficiaire][date_fin]"]').value);
			formData.append('data[Beneficiaire][sexe]', document.querySelector('select[name="data[Beneficiaire][sexe]"]').value);
			formData.append('data[Beneficiaire][ville_id]', document.querySelector('select[name="data[Beneficiaire][ville_id]"]').value);

			console.log('Sending form data'); // Debug log

			// Show loading
			document.getElementById('appartements-container').innerHTML = '<div class="loading"><i class="fa-solid fa-spinner fa-spin"></i> Recherche en cours...</div>';
			showStep(2);

			// AJAX call to search apartments
			fetch(`${mainurl}//beneficiaires/recherche`, {
					method: 'POST',
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					},
					body: formData
				})
				.then(response => {
					console.log('Response status:', response.status); // Debug log
					if (!response.ok) {
						throw new Error(`HTTP error! status: ${response.status}`);
					}
					return response.json();
				})
				.then(data => {
					console.log('Received data:', data); // Debug log
					displayAppartements(data);
				})
				.catch(error => {
					console.error('Error:', error);
					document.getElementById('appartements-container').innerHTML = '<div class="no-results">Erreur lors de la recherche: ' + error.message + '</div>';
				});
		}

		function displayAppartements(appartements) {
			const container = document.getElementById('appartements-container');


			if (!appartements || appartements.length === 0) {
				container.innerHTML = '<div class="no-results">Aucun appartement disponible pour ces critères.</div>';
				return;
			}

			let html = '';
			appartements.forEach(function(item) {
				const appartement = item.Appartement;
				const ville = item.Ville;
				const capaciteRestante = appartement.capacite - appartement.nb_occupants;

				html += `
				<div class="col-md-6">
					<div class="card view-card appartement-card" data-appartement-id="${appartement.id}">
						<div class="card-body">
							<div class="content-card">
								<div class="flex justify-content-between">
									<div class="block-content">
										<label>Nom ou identifiant de l'appartement</label>
										<span><b class="nom_app">${appartement.nom}</b></span>
									</div>
									<div class="block-content">
										<div class="actions">
											<a href="${mainurl}/appartements/view/${appartement.id}" class="go_to_id">Voir les détails</a>
										</div>
									</div>
								</div>
							</div>
							<div class="content-card">
								<div class="flex justify-content-between">
									<div class="block-content">
										<label>Adresse / Ville</label>
										<span><b class="ville_app">${appartement.adresse}, ${ville.ville}</b></span>
									</div>
									<div class="block-content">
										<label>Chambres libres</label>
										<span><b class="capacite_rest_app">${capaciteRestante} chambre(s) disponible(s)</b></span>
									</div>
								</div>
							</div>
							<div class="content-card">
								<div class="flex justify-content-between">
									<div class="block-content">
										<label>Occupants actuels</label>
										<span><b class="nb_occupants_app">${appartement.nb_occupants} personne(s)</b></span>
									</div>
									<div class="block-content">
										<div class="actions">
											<button class="btn btn-primary-rounded affecter-btn" type="button" data-appartement-id="${appartement.id}">
												Affecter ce logement
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			`;
			});

			container.innerHTML = html;

			// Add event listeners to "Affecter" buttons
			document.querySelectorAll('.affecter-btn').forEach(function(btn) {
				btn.addEventListener('click', function() {
					const appartementId = this.getAttribute('data-appartement-id');
					selectAppartement(appartementId);
				});
			});
		}

		function selectAppartement(appartementId) {
			// Remove previous selection
			document.querySelectorAll('.appartement-card').forEach(function(card) {
				card.classList.remove('selected');
			});

			// Add selection to current card
			document.querySelector(`[data-appartement-id="${appartementId}"]`).classList.add('selected');

			// Set the appartement_id in hidden field
			document.getElementById('selected_appartement_id').value = appartementId;

			// Move to step 3
			showStep(3);
		}

		function showStep(stepNumber) {
			// Hide all steps
			document.querySelectorAll('.step_1, .step_2, .step_3').forEach(function(step) {
				step.style.display = 'none';
			});

			// Show the requested step
			document.querySelector(`.step_${stepNumber}`).style.display = 'block';

			// Scroll to top
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		}

		function showError(element, errorSelector) {
			element.style.border = '1px solid #b80000';
			document.querySelector(errorSelector).style.display = 'block';
		}

		function hideError(element, errorSelector) {
			element.style.border = '';
			document.querySelector(errorSelector).style.display = 'none';
		}

		function isValidEmail(email) {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailRegex.test(email);
		}


		// Alternative simpler approach if you know the pattern
		function getMainUrlSimple() {
			const currentUrl = window.location.href;
			const url = new URL(currentUrl);

			// Extract the controller/subdomain part from the path
			const pathParts = url.pathname.split('/').filter(part => part !== '');
			const controller = pathParts[0] || '';

			if (url.hostname === 'localhost' || url.hostname === '127.0.0.1') {
				return `${url.protocol}//${url.host}/${controller}`;
			} else {
				return `https://akditalrh.icozdev.com`;
			}
		}
	});
</script>