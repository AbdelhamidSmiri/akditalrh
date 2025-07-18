<div class="appartements form">
	<?php echo $this->Form->create('Appartement',array("type"=>"file")); ?>
	<div class="page-header">
		<h1 class="title-page">Appartement</h1>
		<span class="slogan"></span>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php echo $this->Form->input('nom', array('placeholder' => '')); ?>
				</div>
				<div class='col-12'>
					<?php 
					$sexe = ["Homme" => "Homme", "Femme" => "Femme"];
					echo $this->Form->input('sexe', array('options'=>$sexe,'placeholder' => '')); ?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('capacite', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('ville_id', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('adresse', array('placeholder' => ''));
					?>
				</div>
				<?php for($i=0; $i<3; $i++): ?>
					<div class='col-12'>
						<?php
						echo $this->Form->file("image.$i", array('placeholder' => ''));
						?>
					</div>
				<?php endfor; ?>
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