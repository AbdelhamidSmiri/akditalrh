<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- French Locale -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<div class="beneficiaires form">
	<?php echo $this->Form->create('Beneficiaire'); ?>
	<div class="page-header">
		<h1 class="title-page">Beneficiaire</h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">

				<div class='col-12'>
					<div class="has-calendar-icon input text">
						<?php
						echo $this->Form->input('date_debut', array(
							'label' => 'Date Aller',
							'type' => 'text', // important: not 'date'
							'id' => 'date_aller_input', // so we can replace it
							'placeholder' => '',
							'div' => false,
						));
						?>
					</div>
					<div class="message-error dateAller-error">
						Veuillez choisir une date debut.
					</div>
				</div>
				<div class='col-12'>
					<div class="has-calendar-icon input text">
						<?php
						echo $this->Form->input('date_fin', array(
							'label' => 'Date Aller',
							'type' => 'text', // important: not 'date'
							'id' => 'date_retour_input', // so we can replace it
							'placeholder' => '',
							'div' => false,
						));
						?>
					</div>
					<div class="message-error dateAller-error">
						Veuillez choisir une date fin.
					</div>
				</div>
				<div class='col-12'>
					<?php
					$sexe = ["Homme" => "Homme", "Femme" => "Femme"];
					echo $this->Form->input('sexe', array('placeholder' => '', "options" => $sexe));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('ville_id', array('placeholder' => '', "options" => $villes));
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
		flatpickr("#date_aller_input, #date_retour_input", {
			dateFormat: "Y-m-d",
			locale: "fr",
			allowInput: true
		});
	});
</script>