<div class="hotelprices form">
	<?php echo $this->Form->create('Hotelprice'); ?>
	<div class="page-header">
		<h1 class="title-page">Hotelprice</h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
					<div class='col-12'>
	<?php
		echo $this->Form->input('id', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('hotel_id', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('date_debut', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('date_fin', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('prix', array('placeholder'=>''));
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