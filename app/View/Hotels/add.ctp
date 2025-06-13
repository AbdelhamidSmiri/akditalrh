<div class="hotels form">
	<?php echo $this->Form->create('Hotel'); ?>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
					<div class='col-12'>
	<?php
		echo $this->Form->input('nom', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('etoile', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('region', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('ville', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('adresse', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('images', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('mail', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('telephone', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('nom_responsable', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('reglement', array('placeholder'=>''));
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