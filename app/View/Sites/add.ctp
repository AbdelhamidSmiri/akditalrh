<div class="sites form">
	<?php echo $this->Form->create('Site'); ?>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php
					echo $this->Form->input('ville_id', [
						'label' => 'Ville',
						'placeholder' => ''
					]);
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('site', [
						'label' => 'Site',
						'placeholder' => ''
					]);
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('adresse', [
						'label' => 'Adresse',
						'placeholder' => ''
					]);
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('telephone', [
						'label' => 'Téléphone',
						'placeholder' => ''
					]);
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('mail', [
						'label' => 'Mail',
						'placeholder' => ''
					]);
					?>
				</div>
				<div class='submit-section'>
					<button type="submit" class="btn btn-submit">
						<i class="fa-solid fa-paper-plane"></i> Envoyer
					</button>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div class="col"></div>
	</div>
</div>
