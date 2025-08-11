<div class="referentiels form">
	<?php echo $this->Form->create('Referentiel'); ?>
	<div class="page-header">
		<h1 class="title-page">Referentiel</h1>
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
		echo $this->Form->input('modele', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('duree', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('kilometrage', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('loyer_ht_ald', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('loyer_ttc_ald', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('km_supp_ht_ald', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('nb_pneus_ald', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('cat_remplacement_ald', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('loyer_ht_arval', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('loyer_ttc_arval', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('km_supp_ht_arval', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('nb_pneus_arval', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('cat_remplacement_arval', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('moyenne_offres_ttc', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('valeur_actuelle_moy_ttc', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('augmentation_pourcent', array('placeholder'=>''));
	?>
	</div>
	<div class='col-12'>
	<?php
		echo $this->Form->input('categorie', array('placeholder'=>''));
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