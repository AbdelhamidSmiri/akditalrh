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
	}

	.hotel-card:hover {
		scale: 1.01;
	}
</style>

<div class="hotels index"></div>

<div class="col-md-5">
	<div class="search-section">
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
			<input type="text" class="form-control" id="search_input" placeholder="Rechercher" aria-label="Search">
			<button class="btn btn-primary-rounded search-btn" type="button"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
		</div>
	</div>
</div>
<div class="col-md-12 filter-section"></div>
<div class="content-table">
	<?php foreach ($hotels as $hotel): ?>
		<div class="d-flex hotel-card">
			<div class="image" style="background-image: url('<?php echo Router::url('/files/hotels/' . $hotel['Hotel']['images'], true); ?>');">
			</div>

			<div class="content-card">
				<div class="d-flex topcart">
					<div class="head_card">
						<h3 class="title-card">
							<?php
							echo $hotel['Hotel']['nom'];
							for ($i = 0; $i < $hotel['Hotel']['etoile']; $i++) {
							?>
								<i class="fa-solid fa-star yellow m--3"></i>
							<?php } ?>
						</h3>
						<div class="info">
							<?php echo $hotel['Hotel']['mail']; ?>
							<?php echo $hotel['Hotel']['telephone']; ?>
						</div>
					</div>

					<div>

						<span class="respo">
							<i class="fa-regular fa-user-tie"></i>
							<?php echo $hotel['Hotel']['nom_responsable']; ?></span>
					</div>
				</div>


				<div class="sub-content">
					<div class="adress">
						<?php echo $hotel['Hotel']['region']; ?>
						<?php echo $hotel['Hotel']['ville']; ?>
						<?php echo $hotel['Hotel']['adresse']; ?>
					</div>


					<div class="reglement">
						<?php echo $hotel['Hotel']['reglement']; ?>
					</div>
					<div class="actions text-end">

						<?php echo $this->Html->link(__('View'), array('action' => 'view', $hotel['Hotel']['id'])); ?> /
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $hotel['Hotel']['id'])); ?> /
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $hotel['Hotel']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $hotel['Hotel']['id']))); ?>

					</div>
				</div>

			</div>


		</div>
	<?php endforeach; ?>
</div>

</div>