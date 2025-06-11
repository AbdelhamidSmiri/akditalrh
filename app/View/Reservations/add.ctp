<div class="reservations form">
	<?php echo $this->Form->create('Reservation'); ?>
	<div class="page-header">
		<h1 class="title-page">Reservation</h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
					<div class='col-12'>
	<?php
		echo $this->Form->input('user_id', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('hotel_id', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('site_id', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('checkin', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('checkout', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('num_odm', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('ordre_mission', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('cin', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('message', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('confirmation', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('etat', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('reponse', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('date_reponse', array('placeholder'=>''));
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