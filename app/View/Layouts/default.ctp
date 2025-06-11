<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">


	<?php
	echo $this->Html->css('fontawesome');
	echo $this->Html->css('bootstrap');
	echo $this->Html->css('akdital_style');

	?>
</head>

<body>
	<div id="container" class="container">
		<div class="profile-section">
			<div class="row">
				<div class="col-9"></div>
				<div class="col">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title mb-0">Empty card</h5>
						</div>
						<div class="card-body">
						</div>
					</div>
				</div>

			</div>
		</div>
		<div id="content">

			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<?php echo $this->Html->script('bootstrap.min.js'); ?>
</body>

</html>