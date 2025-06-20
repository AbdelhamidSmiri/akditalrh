<div class="volreservations form">
	<?php echo $this->Form->create('Volreservation', array('type' => 'file')); ?>

	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">

				<div class='col-12'>
					<?php
					echo $this->Form->input('site_id', array('placeholder' => ''));
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
					echo $this->Form->input('date_aller', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('date_retour', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('num_odm', array('placeholder' => ''));
					?>
				</div>
				ordre_mission
				<div class='col-12'>
					<?php
					echo $this->Form->file('ordre_mission', array('placeholder' => ''));
					?>
				</div>
				cin
				<div class='col-12'>
					<?php
					echo $this->Form->file('cin', array('placeholder' => ''));
					?>
				</div>
				passport
				<div class='col-12'>
					<?php
					echo $this->Form->file('passport', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('message', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('transfert', [
						'type' => 'checkbox',
						'label' => 'Je veux un transfert'
					]);
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