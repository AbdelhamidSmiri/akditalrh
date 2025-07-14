<div class="hotels form">
	<?php echo $this->Form->create('Hotel', ["type" => "file"]); ?>
	<div class="row">
		<div class="col"></div>
		<div class="col-8">

			<div class="row">
				<div class='col-12'>
					<?php
					echo $this->Form->input('hotel', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('etoile', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('region', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('ville', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('adresse', array('placeholder' => ''));
					?>
				</div>
				images du hotels
				<div class='col-12'>
					<?php
					echo $this->Form->file('images', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('mail', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('telephone', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('nom_responsable', array('placeholder' => ''));
					?>
				</div>
				<div class='col-12'>
					<?php
					echo $this->Form->input('reglement', array('placeholder' => ''));
					?>
				</div>
				<hr>
				<hr>
				<hr>
				<?php $k=0; for ($i = 0; $i < 5; $i++): ?>
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-md-12">
							<?php echo $this->Form->input("chambre.$i.nom", array('label' => 'Type de la chambre')); ?>
						</div>
						<?php for($j=0;$j<2;$j++): ?>
							<div class="col-md-4">
								<?php echo $this->Form->input("chambre.$i.prix.$k.date_debut", array('label' => 'Date debut prix')); ?>
							</div>
							<div class="col-md-4">
								<?php echo $this->Form->input("chambre.$i.prix.$k.date_fin", array('label' => 'Date fin prix')); ?>
							</div>
							<div class="col-md-4">
								<?php echo $this->Form->input("chambre.$i.prix.$k.prix", array('label' => 'Prix')); ?>
							</div>
						<?php $k++; endfor; ?>
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