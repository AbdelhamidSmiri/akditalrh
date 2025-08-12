<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<style>
	.step_2,
	.step_3,
	.step_4 {
		display: none;
	}

	/* Hotel Cards Styles */
	.content-table {
		padding: 20px;
	}

	.image {
		height: 100%;
		width: 42%;
		background-size: cover;
		background-position: center;
		border-top-left-radius: 16px;
		border-bottom-left-radius: 16px;
	}

	.content-card {
		padding: 12px 18px;
		width: 58%;
		height: fit-content;
	}

	.title-card {
		font-size: 22px;
	}

	.sub-content {
		font-size: 14px;
		height: 65%;
		display: flex;
		flex-direction: column;
		justify-content: flex-end;
		row-gap: 3px;
	}

	.topcart {
		justify-content: space-between;
	}

	.respo {
		background-color: #eaf1fe;
		padding: 7px 16px;
		border-radius: 20px;
		font-weight: 600;
		font-size: 12px;
		width: max-content;
		display: inline-block;
	}

	.reglement {
		color: #7C7C7C;
		font-size: 14px;
	}

	.hotel-card {
		border: 2px solid #D9D9D9;
		box-shadow: 0px 0px 9px 0px #d9d9d9b0;
		border-radius: 16px;
		transition: 0.2s;
		position: relative;
		cursor: pointer;
	}

	.hotel-card:hover {
		border: 2px solid #152259;
	}

	.hotel-card.selected {
		border: 2px solid #152259;
		background-color: #f8f9ff;
	}



	.hotel-card .radio-selection {
		position: absolute;
		top: 14px;
		right: 14px;
	}

	.emails {
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
	}

	.info {
		display: flex;
		flex-direction: column;
		flex-wrap: wrap;
		flex-direction: row;
		word-break: break-all;
		/* Forces breaking inside words */
		overflow-wrap: anywhere;
		/* Modern way for breaking long strings */
	}

	.info .email,
	.info .tel {
		font-size: 14px;
		font-weight: 400;
	}

	.starts {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: flex-start;
		column-gap: 7px;
		max-width: 20%;
	}

	.starts i {
		font-size: 14px;
	}

	.ville {
		display: flex;
		column-gap: 12px;
		padding: 6px 0 16px;
	}

	.ville span {
		font-size: 16px;
		font-weight: 400;
		color: #5d5d5d;
	}

	/* Chambre Cards Styles */
	.view-card {
		border: 2px solid #D9D9D9;
		box-shadow: 0px 0px 9px 0px #d9d9d9b0;
		border-radius: 16px;
		transition: 0.2s;
		cursor: pointer;
		margin-bottom: 20px;
	}

	.view-card:hover {
		border: 2px solid #152259;
	}

	.view-card.selected {
		border: 2px solid #152259;
		background-color: #f8f9ff;
	}

	.view-card .radio-selection {
		position: absolute;
		top: 14px;
		right: 14px;
	}

	.appartement-card .card-body {
		position: relative;
	}

	.block-content {
		margin-bottom: 15px;
	}

	.block-content label {
		display: block;
		font-weight: 600;
		font-size: 12px;
		color: #666;
		margin-bottom: 5px;
	}

	.block-content span {
		font-size: 14px;
		color: #333;
	}

	.flex {
		display: flex;
	}

	.justify-content-between {
		justify-content: space-between;
	}

	.go_to_id {
		color: #007bff;
		text-decoration: none;
		font-size: 12px;
	}

	.loading {
		opacity: 0.5;
		pointer-events: none;
		position: relative;
	}

	.loading::after {
		content: "Chargement...";
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		font-size: 14px;
		color: #666;
		background: white;
		padding: 10px;
		border-radius: 5px;
	}

	.step-header {
		text-align: center;
		margin-bottom: 30px;
	}

	.step-header h2 {
		color: #333;
		font-size: 24px;
		margin-bottom: 10px;
	}

	.step-header p {
		color: #666;
		font-size: 14px;
	}

	.content-card-chambre {
		width: 100%;
	}

	.view-card .card-body {
		padding: 10px 18px;
	}

	.form-check-input:checked {
		background-color: #3780CB;
		border-color: #3780CB;
	}

	#hotels-container,
	#chambres-container {
		height: 404px;
		overflow: auto;
	}

	@media only screen and (max-width: 600px) {
		.hotel-card .radio-selection {
			display: none;
		}

		.image {
			width: 40%;
			align-items: center;

		}

		.title-card {
			font-size: 16px;
		}

		.ville span {
			font-size: 12px;
		}

		.starts i {
			font-size: 12px;
		}

		.info .email,
		.info .tel {
			font-size: 12px;
		}

		.content-card {
			padding: 10px 14px;
			width: 60% !important;
			padding-bottom: 11px;
		}

		.content-card-chambre {
			width: 100% !important;
		}

	}
</style>

<div class="reservations form">
	<?php echo $this->Form->create('Reservation', array('type' => 'file')); ?>

	<div class="row">
		<div class="col"></div>
		<div class="col-md-10 p-4">

			<!-- Step 1: Basic Information -->
			<div class="step_1">
				<div class="step-header">
					<h2>Informations de base</h2>
					<p>Sélectionnez votre ville, site et dates de séjour</p>
				</div>

				<div class="row">
					<div class='col-md-6'>
						<?php
						echo $this->Form->input('ville_id', array(
							'placeholder' => '',
							'label' => 'Ville',
							'empty' => 'Choisissez une ville',
							'id' => 'ville-select'
						));
						?>
						<div class="message">
							Veuillez sélectionner la ville 
						</div>
						<div class="message-error ville-error" style="display: none;">
							Veuillez choisir une ville.
						</div>
					</div>

					<div class='col-md-6'>
						<?php
						echo $this->Form->input('site_id', array(
							'placeholder' => '',
							'label' => 'Site',
							'empty' => 'Choisissez le site de la réservation',
						));
						?>
						<div class="message">
							Sélectionnez le site Akdital qui prendra en charge votre mission.
						</div>
						<div class="message-error site-error" style="display: none;">
							Veuillez choisir un site.
						</div>

					</div>

					<div class='col-md-6'>
						<div class="has-calendar-icon input text">
							<?php
							echo $this->Form->input('checkin', array(
								'label' => 'Date d’arrivée',
								'type' => 'text',
								'id' => 'date_checkin',
								'placeholder' => '',
								'div' => false,
							));
							?>
						</div>
						<div class="message">
							Sélectionnez la date prévue d’arrivée
						</div>
						<div class="message-error checkin-error" style="display: none;">
							Veuillez sélectionner la date de check-in.
						</div>
					</div>

					<div class='col-md-6'>
						<div class="has-calendar-icon input text">
							<?php
							echo $this->Form->input('checkout', array(
								'label' => 'Date de départ',
								'type' => 'text',
								'id' => 'date_checkout',
								'placeholder' => '',
								'div' => false,
							));
							?>
						</div>
						<div class="message">
							Sélectionnez la date prévue de départ.
						</div>
						<div class="message-error checkout-error" style="display: none;">
							Veuillez sélectionner la date de check-out.
						</div>
					</div>
				</div>

				<div class='text-center mt-4'>
					<button type="button" id="btn-step1-next" class="btn btn-primary-rounded btn-lg">
						<i class="fa-solid fa-search"></i> Rechercher les hôtels
					</button>
				</div>
				<div class="text-center mt-3">
					<span class="pagin-steps">1 sur 4 étapes</span>
				</div>
			</div>

			<!-- Step 2: Hotel Selection -->
			<div class="step_2">
				<div class="step-header">
					<h2>Choisissez votre hôtel</h2>
					<p>Sélectionnez l'hôtel qui vous convient</p>
				</div>

				<div id="hotels-container" class="d-grid row-gap-3">
					<!-- Hotels will be loaded here via AJAX -->
				</div>

				<div class='text-center mt-4'>
					<button type="button" id="btn-step2-back" class="btn btn-secondary-rounded">
						<i class="fa-solid fa-arrow-left"></i> Retour
					</button>
					<button type="button" id="btn-step2-next" class="btn btn-primary-rounded btn-lg" disabled>
						Voir les chambres <i class="fa-solid fa-arrow-right"></i>
					</button>
				</div>
				<div class="text-center mt-3">
					<span class="pagin-steps">2 sur 4 étapes</span>
				</div>
			</div>

			<!-- Step 3: Room Selection -->
			<div class="step_3">
				<div class="step-header">
					<h2>Choisissez votre chambre</h2>
					<p>Sélectionnez la chambre qui vous convient</p>
				</div>

				<div id="selected-hotel-info" class="mb-4">
					<!-- Selected hotel info will be shown here -->
				</div>

				<div id="chambres-container" class="row">
					<!-- Chambres will be loaded here via AJAX -->
				</div>

				<div class='text-center mt-4'>
					<button type="button" id="btn-step3-back" class="btn btn-secondary-rounded">
						<i class="fa-solid fa-arrow-left"></i> Retour
					</button>
					<button type="button" id="btn-step3-next" class="btn btn-primary-rounded btn-lg" disabled>
						Finaliser la réservation <i class="fa-solid fa-arrow-right"></i>
					</button>
				</div>
				<div class="text-center mt-3">
					<span class="pagin-steps">3 sur 4 étapes</span>
				</div>
			</div>

			<!-- Step 4: Final Information -->
			<div class="step_4">
				<div class="step-header">
					<h2>Finaliser votre réservation</h2>
					<p>Complétez les informations nécessaires</p>
				</div>

				<div class="row">
					<div class='col-md-12'>
						<?php
						echo $this->Form->input('num_odm', array('placeholder' => '', 'label' => 'Numéro d\'ODM'));
						?>
						<div class="message-error num-odm-error" style="display: none;">
							Veuillez entrer le numéro d'ODM.
						</div>
					</div>

					<div class='col-md-12 mb-4 input-file'>
						<label>Ordre de mission</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('ordre_mission', array(
									'name' => 'data[Reservation][ordre_mission][]',
									'class' => 'file-input',
									'accept' => '.jpg, .jpeg, .png, .pdf',
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

					<div class='col-md-12 mb-4 input-file'>
						<label>CIN</label>
						<div class="file-upload-wrapper">
							<div class="file-upload-area">
								<div class="upload-text">Glissez-déposez les fichiers ici</div>
								<div class="upload-subtext">Ou</div>
								<button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

								<?php echo $this->Form->file('cin', array(
									'name' => 'data[Reservation][cin][]',
									'class' => 'file-input',
									'accept' => '.jpg, .jpeg, .png, .pdf',
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

					<div class='col-md-12'>
						<?php
						echo $this->Form->input('message', array('placeholder' => 'Motif du séjour', 'type' => 'textarea', 'label' => 'Motif du séjour'));
						?>
						<div class="description-text">
							Expliquez brièvement la raison de votre séjour
						</div>
						<div class="message-error motif-error" style="display: none;">
							Veuillez entrer le motif du séjour
						</div>
					</div>
				</div>

				<div class='text-center mt-4'>
					<button type="button" id="btn-step4-back" class="btn btn-secondary-rounded">
						<i class="fa-solid fa-arrow-left"></i> Retour
					</button>
					<button type="submit" class="btn btn-success-rounded btn-lg">
						<i class="fa-solid fa-paper-plane"></i> Envoyer la réservation
					</button>
				</div>
				<div class="text-center mt-3">
					<span class="pagin-steps">4 sur 4 étapes</span>
				</div>
			</div>

			<!-- Hidden fields for selected hotel and chambre -->
			<input type="hidden" name="data[Reservation][hotel_id]" id="selected-hotel-id">
			<input type="hidden" name="data[Reservation][chambre_id]" id="selected-chambre-id">

		</div>
		<div class="col"></div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('input_file'); ?>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		window.scrollTo({
			top: document.body.scrollHeight,
			behavior: "smooth" // smooth scroll animation
		});
		// Initialize date pickers
		flatpickr("#date_checkin, #date_checkout", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true,
			disableMobile: true,
			allowInput: false,
		});

		// Step 1: Search hotels
		$('#btn-step1-next').click(function() {
			console.log('clicked')
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 500); // 500ms for smooth scrolling
			const villeId = $('#ville-select').val();
			const siteId = $('select[name="data[Reservation][site_id]"]').val();
			const checkin = $('#date_checkin').val();
			const checkout = $('#date_checkout').val();

			// Validate inputs
			let hasError = false;

			if (!villeId) {
				$('#ville-select').css('border', '1px solid #b80000');
				$('.ville-error').show();
				hasError = true;
			} else {
				$('#ville-select').css('border', '');
				$('.ville-error').hide();
			}

			if (!siteId) {
				$('select[name="data[Reservation][site_id]"]').css('border', '1px solid #b80000');
				$('.site-error').show();
				hasError = true;
			} else {
				$('select[name="data[Reservation][site_id]"]').css('border', '');
				$('.site-error').hide();
			}

			if (!checkin) {
				$('#date_checkin').css('border', '1px solid #b80000');
				$('.checkin-error').show();
				hasError = true;
			} else {
				$('#date_checkin').css('border', '');
				$('.checkin-error').hide();
			}

			if (!checkout) {
				$('#date_checkout').css('border', '1px solid #b80000');
				$('.checkout-error').show();
				hasError = true;
			} else {
				$('#date_checkout').css('border', '');
				$('.checkout-error').hide();
			}

			if (hasError) return;

			// Load hotels
			loadHotels(villeId);
		});

		// Load hotels function
		function loadHotels(villeId) {
			console.log('coco');
			$('#hotels-container').addClass('loading').html('<div class="text-center p-5">Chargement des hôtels...</div>');

			$.ajax({
				url: '<?php echo $this->Html->url(array("controller" => "reservations", "action" => "fetch_hotels_with_details")); ?>',
				type: 'POST',
				data: {
					ville_id: villeId
				},
				dataType: 'json',
				success: function(response) {
					$('#hotels-container').removeClass('loading');

					if (response.success && response.hotels.length > 0) {
						let hotelsHtml = '';

						response.hotels.forEach(function(hotel) {
							let imageHtml = '';
							let contentWidth = '100%';

							if (hotel.images) {
								imageHtml = `<div class="image" style="background-image: url('<?php echo Router::url('/files/hotels/', true); ?>${hotel.images}');"></div>`;
								contentWidth = '58%';
							}

							let starsHtml = '';
							for (let i = 0; i < hotel.etoile; i++) {
								starsHtml += '<i class="fa-solid fa-star yellow m--3"></i>';
							}

							let emailsHtml = '';
							if (hotel.mail) {
								const emails = hotel.mail.split(';');
								emails.forEach(function(email) {
									emailsHtml += `<span class="email"><i class="fa-regular fa-envelope"></i> ${email} |&nbsp;</span>`;
								});
							}

							hotelsHtml += `
								<div class="d-flex hotel-card" data-hotel-id="${hotel.id}" data-hotel-name="${hotel.hotel}">
									<div class="radio-selection">
										<input type="radio" name="selected_hotel" value="${hotel.id}" class="form-check-input">
									</div>
									${imageHtml}
									<div class="content-card" style="width:${contentWidth};">
										<div class="d-flex topcart">
											<div class="head_card">
												<h3 class="title-card">
													<span>${hotel.hotel}</span>
													<div class="ville">
														<span><i class="fa-regular fa-location-dot"></i> ${hotel.ville}</span>
														<div class="starts">
															${starsHtml}
														</div>
													</div>
												</h3>
												<div class="info">
													<div class="emails">
														${emailsHtml}
													</div>
													${hotel.telephone && hotel.telephone.trim() ? `
													<div>
														<span class="tel"><i class="fa-regular fa-phone"></i> ${hotel.telephone}</span>
													</div>
												` : ''}
												</div>
											</div>
										</div>
										<div class="sub-content">
										${hotel.adresse && hotel.adresse.trim() ? `
											<div class="adress">
												<span><i class="fa-regular fa-location-dot"></i> ${hotel.adresse}</span>
											</div>
											` : ''}
											<div class="reglement">
												${hotel.reglement}
											</div>
										</div>
									</div>
								</div>
							`;
						});

						$('#hotels-container').html(hotelsHtml);

						// Add click handlers for hotel selection
						$('.hotel-card').click(function() {
							$('.hotel-card').removeClass('selected');
							$(this).addClass('selected');
							$(this).find('input[type="radio"]').prop('checked', true);
							$('#btn-step2-next').prop('disabled', false);
						});

						// Show step 2
						$('.step_1').hide();
						$('.step_2').show();
						window.scrollTo({
							top: 0,
							behavior: 'smooth'
						});

					} else {
						$('#hotels-container').html('<div class="text-center p-5"><p>Aucun hôtel disponible pour cette ville.</p></div>');
						$('.step_1').hide();
						$('.step_2').show();
					}
				},
				error: function() {
					console.log('coco error');
					$('#hotels-container').removeClass('loading').html('<div class="text-center p-5 text-danger"><p>Erreur lors du chargement des hôtels.</p></div>');
				}
			});
		}

		// Step 2: Hotel selection -> Load rooms
		$('#btn-step2-next').click(function() {
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 500); // 500ms for smooth scrolling
			const selectedHotelId = $('input[name="selected_hotel"]:checked').val();
			const selectedHotelName = $(`.hotel-card[data-hotel-id="${selectedHotelId}"]`).data('hotel-name');

			if (selectedHotelId) {
				$('#selected-hotel-id').val(selectedHotelId);
				console.log(selectedHotelId);
				loadChambres(selectedHotelId, selectedHotelName);
			}
		});

		// Load chambres function
		function loadChambres(hotelId, hotelName) {
			$('#selected-hotel-info').html(`<div class="alert alert-info"><strong>Hôtel sélectionné:</strong> ${hotelName}</div>`);
			$('#chambres-container').addClass('loading').html('<div class="text-center p-5">Chargement des chambres...</div>');


			$.ajax({
				url: '<?php echo $this->Html->url(array("controller" => "reservations", "action" => "fetch_chambres")); ?>',
				type: 'POST',
				data: {
					hotel_id: hotelId
				},
				dataType: 'json',
				success: function(response) {
					$('#chambres-container').removeClass('loading');

					if (response.success && response.chambres.length > 0) {
						let chambresHtml = '';

						response.chambres.forEach(function(chambre) {
							chambresHtml += `
								<div class="col-md-6">
									<div class="card view-card appartement-card" data-chambre-id="${chambre.id}">
										<div class="card-body">
											<div class="radio-selection">
												<input type="radio" name="selected_chambre" value="${chambre.id}" class="form-check-input">
											</div>
											<div class="content-card content-card-chambre">
												<div class="flex justify-content-between col-gap-2">
													<div class="block-content">
														<label>Type de chambre</label>
														<span><b class="nom_chambre">${chambre.nom}</b></span>
													</div>
													<div class="block-content">
														<label>Prix par nuit</label>
														<span><b class="prix_chambre">${chambre.prix} DH</b></span>
													</div>
												</div>
											</div>
											<div class="content-card content-card-chambre">
												<div class="flex justify-content-between col-gap-2">
													<div class="block-content">
														<label>Statut</label>
														<span><b class="type_chambre">${chambre.type || 'Standard'}</b></span>
													</div>
													<div class="block-content d-flex align-items-end">
														<div class="actions">
															<button class="btn btn-primary-rounded select-chambre-btn" type="button" data-chambre-id="${chambre.id}">
																Sélectionner
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

						$('#chambres-container').html(chambresHtml);

						// Add click handlers for chambre selection
						$('.appartement-card').click(function() {
							$('.appartement-card').removeClass('selected');
							$(this).addClass('selected');
							$(this).find('input[type="radio"]').prop('checked', true);
							$('#btn-step3-next').prop('disabled', false);
						});

						$('.select-chambre-btn').click(function(e) {
							e.stopPropagation();
							const chambreCard = $(this).closest('.appartement-card');
							chambreCard.click();
						});

						// Show step 3
						$('.step_2').hide();
						$('.step_3').show();
						window.scrollTo({
							top: 0,
							behavior: 'smooth'
						});

					} else {
						$('#chambres-container').html('<div class="text-center p-5"><p>Aucune chambre disponible pour cet hôtel.</p></div>');
						$('.step_2').hide();
						$('.step_3').show();
					}
				},
				error: function() {
					$('#chambres-container').removeClass('loading').html('<div class="text-center p-5 text-danger"><p>Erreur lors du chargement des chambres.</p></div>');
				}
			});
		}

		// Step 3: Room selection -> Final step
		$('#btn-step3-next').click(function() {
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 500); // 500ms for smooth scrolling
			const selectedChambreId = $('input[name="selected_chambre"]:checked').val();

			if (selectedChambreId) {
				$('#selected-chambre-id').val(selectedChambreId);
				$('.step_3').hide();
				$('.step_4').show();
				window.scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			}
		});

		// Back buttons
		$('#btn-step2-back').click(function() {
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 500); // 500ms for smooth scrolling

			$('.step_2').hide();
			$('.step_1').show();
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		});

		$('#btn-step3-back').click(function() {
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 500); // 500ms for smooth scrolling
			$('.step_3').hide();
			$('.step_2').show();
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		});

		$('#btn-step4-back').click(function() {
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 500); // 500ms for smooth scrolling
			$('.step_4').hide();
			$('.step_3').show();
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		});

		// Form submission validation
		$('.reservations form').submit(function(event) {
			event.preventDefault();

			const numOdm = $('input[name="data[Reservation][num_odm]"]').val();
			const ordreMissionFiles = $('input[name="data[Reservation][ordre_mission][]"]')[0].files;
			const cinFiles = $('input[name="data[Reservation][cin][]"]')[0].files;
			const message = $('textarea[name="data[Reservation][message]"]').val();

			let hasError = false;

			if (!numOdm) {
				$('input[name="data[Reservation][num_odm]"]').css('border', '1px solid #b80000');
				$('.num-odm-error').show();
				hasError = true;
			} else {
				$('input[name="data[Reservation][num_odm]"]').css('border', '');
				$('.num-odm-error').hide();
			}

			if (ordreMissionFiles.length === 0) {
				$('input[name="data[Reservation][ordre_mission][]"]').closest('.file-upload-area').css('border', '2px dashed #b80000');
				hasError = true;
			} else {
				$('input[name="data[Reservation][ordre_mission][]"]').closest('.file-upload-area').css('border', '');
			}

			if (cinFiles.length === 0) {
				$('input[name="data[Reservation][cin][]"]').closest('.file-upload-area').css('border', '2px dashed #b80000');
				hasError = true;
			} else {
				$('input[name="data[Reservation][cin][]"]').closest('.file-upload-area').css('border', '');
			}

			if (!message) {
				$('textarea[name="data[Reservation][message]"]').css('border', '1px solid #b80000');
				$('.motif-error').show();
				hasError = true;
			} else {
				$('textarea[name="data[Reservation][message]"]').css('border', '');
				$('.motif-error').hide();
			}

			if (!hasError) {
				this.submit();
			}
		});
	});
</script>
