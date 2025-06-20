<div class="volreservations form">
	<?php echo $this->Form->create('Volreservation', ["type" => "file"]);
	?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">

				<div class='col-12'>
					<?php
					echo $this->Form->input('reponse', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('num_vol', array('placeholder' => ''));
					?>
				</div>
				file_aller
				<div class='col-12'>
					<?php
					echo $this->Form->file('file_aller', array('placeholder' => ''));
					?>
				</div>
				file_retour
				<div class='col-12'>
					<?php
					echo $this->Form->file('file_retour', array('placeholder' => ''));
					?>
				</div>
				<?php if ($vol["Volreservation"]["transfer"] == 1): ?>
					<div class='col-12'>
						<?php
						echo $this->Form->input('nom_transfer', array('placeholder' => ''));
						?>
					</div>
					<div class='col-12'>
						<?php
						echo $this->Form->input('date_transfer', array('placeholder' => ''));
						?>
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
				<?php endif; ?>
				documents  données la possibilité de joindre des documents (bzaf)
				<div class='col-12'>
					<?php
					echo $this->Form->file('documents', array('placeholder' => ''));
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