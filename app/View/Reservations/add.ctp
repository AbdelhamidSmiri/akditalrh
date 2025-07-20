<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<div class="reservations form">
	<?php echo $this->Form->create('Reservation',["type"=>"file"]); ?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php
					echo $this->Form->input('ville_id', array(
						'placeholder' => '',
						'label' => 'Ville',
						'empty' => 'Choisissez une ville ',
					));
					?>
					<?php
					echo $this->Form->input('chambre_id', array(
						'placeholder' => '',
						'label' => 'Hôtel',
						'empty' => 'Choisissez un hôtel ',
					));
					?>
					<div class="message-error hotel-error">
						Veuillez choisir un hôtel?.
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
						Veuillez sélectionner la date de check-in de l’hôtel
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

					<div class="message-error depart-error">
						Veuillez sélectionner la date de check-out de l’hôtel
					</div>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('num_odm', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->file('ordre_mission', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->file('cin', array('placeholder' => '', "multiple" => true));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('message', array('placeholder' => ''));
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


<script>
	document.addEventListener("DOMContentLoaded", function () {
		flatpickr("#date_checkin ,#date_checkout", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});
</script>