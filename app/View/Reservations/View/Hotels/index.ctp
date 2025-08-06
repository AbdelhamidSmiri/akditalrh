<style>
	.content-table {
		padding: 20px;
	}

	.image {
		height: 181px;
		width: 42%;
		background-size: cover;
		background-position: center;
		border-top-left-radius: 16px;
		border-bottom-left-radius: 16px;
	}

	.content-card {
		padding: 12px 27px;
		width: 58%;
		height: fit-content;
		padding-bottom: 32px;
	}

	.title-card {
		font-size: 22px;
	}

	.sub-content {
		font-size: 14px;
		height: 65%;
		display: flex;
		flex-direction: column;
		justify-content: flex-end;
		row-gap: 3px;
	}

	.topcart {
		justify-content: space-between;
	}

	.respo {
		background-color: #eaf1fe;
		padding: 7px 16px;
		border-radius: 20px;
		font-weight: 600;
		font-size: 12px;
		width: max-content;
		display: inline-block;
	}

	.reglement {
		color: #7C7C7C;
		font-size: 14px;
	}

	.hotel-card {
		border: 1px solid #D9D9D9;
		box-shadow: 0px 0px 9px 0px #d9d9d9b0;
		border-radius: 16px;
		transition: 0.2s;
		position: relative;
	}

	.profil-info {
		width: fit-content;
	}

	.hotel-card .actions {
		position: absolute;
		bottom: 14px;
		right: 6px;
	}

	.emails {
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
	}

	.info .email,
	.info .tel {
		font-size: 14px;
		font-weight: 400;

	}

	.starts {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: flex-start;
		column-gap: 7px;
		max-width: 20%;
	}

	.starts i {
		font-size: 14px;
	}

	.ville {
		display: flex;
		column-gap: 12px;
		padding: 6px 0 16px;
	}

	.ville span {
		font-size: 16px;
		font-weight: 400;
		color: #5d5d5d;

	}
</style>

<div class="hotels index"></div>
<div class="row">
	<div class="col-md-5">
		<div class="search-section">
			<div class="input-group mb-3">
				<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
				<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
				<button class="btn btn-primary-rounded search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="text-end">
			<!-- Button trigger modal -->
			<?php
			echo $this->Html->link(
				__('<i class="fa-regular fa-plus"></i> Nouveau hotel'),
				array('action' => 'add'),
				array(
					'escape' => false,
					'class' => 'btn btn-primary-rounded' // ✅ move it here
				)
			);
			?>


		</div>
	</div>
</div>

<div class="col-md-12 filter-section"></div>
<div class="d-grid row-gap-3">
	<?php foreach ($hotels as $hotel): ?>
		<div class="d-flex hotel-card">
			<?php
			$width = "100%";
			if (!empty($hotel['Hotel']['images'])) { ?>
				<div class="image" style="background-image: url('<?php echo Router::url('/files/hotels/' . $hotel['Hotel']['images'], true); ?>');">
				</div>

			<?php
				$width = "58%";
			} ?>
			<div class="content-card" style="width:<?php echo $width; ?>;">
				<div class="d-flex topcart">
					<div class="head_card">
						<h3 class="title-card">

							<span> <?php echo $hotel['Hotel']['hotel']; ?></span>
							<div class="ville">
								<?php if (!empty($hotel['Hotel']['ville'])): ?>
									<span><i class="fa-regular fa-location-dot"></i> <?php echo $hotel['Hotel']['ville']; ?></span>
								<?php endif; ?>
								<div class="starts">
									<?php for ($i = 0; $i < $hotel['Hotel']['etoile']; $i++) {
									?>
										<i class="fa-solid fa-star yellow m--3"></i>
									<?php } ?>
								</div>
							</div>

						</h3>
						<div class="info">
							<div class="emails">
								<?php
								$emails = explode(';', $hotel['Hotel']['mail']);
								foreach ($emails as $email) { ?>
									<span class="email"> <i class="fa-regular fa-envelope"></i> <?php echo $email; ?> |&nbsp; </span>
								<?php } ?>
							</div>
							<div>
								<span class="tel"> <i class="fa-regular fa-phone"></i> <?php echo $hotel['Hotel']['telephone']; ?></span>
							</div>
						</div>
					</div>

					<div class="profil-info">

						<span class="respo">
							<i class="fa-regular fa-user-tie"></i>
							<?php echo $hotel['Hotel']['nom_responsable']; ?></span>
					</div>
				</div>


				<div class="sub-content">
					<div class="adress">
						<?php if (!empty($hotel['Hotel']['adresse'])): ?>
							<span><i class="fa-regular fa-location-dot"></i> <?php echo $hotel['Hotel']['adresse']; ?></span>
						<?php endif; ?>
					</div>



					<div class="reglement">
						<?php echo $hotel['Hotel']['reglement']; ?>
					</div>

				</div>
				<div class="actions text-end">

					<?php echo $this->Html->link(__('Voir'), array('action' => 'view', $hotel['Hotel']['id'])); ?> /
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $hotel['Hotel']['id'])); ?> /
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $hotel['Hotel']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $hotel['Hotel']['id']))); ?>

				</div>

			</div>


		</div>
	<?php endforeach; ?>
</div>

</div>