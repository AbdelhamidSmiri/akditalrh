<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo h($this->fetch('title') ?: 'Erreur'); ?> | MGR Akdital</title>
	<link rel="icon" type="image/png" href="<?php echo $this->webroot; ?>img/favicon.png">
	<style>
		* { box-sizing: border-box; }
		body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
			font-family: "Segoe UI", Roboto, Arial, sans-serif; background: #f4f6fb; color: #1E2C5A; }
		.box { width: 100%; max-width: 480px; margin: 24px; padding: 40px 32px; background: #fff;
			border-radius: 16px; box-shadow: 0 10px 30px rgba(30, 44, 90, .08); text-align: center; }
		.box img { height: 44px; margin-bottom: 28px; }
		.code { font-size: 56px; font-weight: 700; line-height: 1; color: #3780CB; margin: 0 0 12px; }
		h1 { font-size: 22px; margin: 0 0 10px; }
		p { margin: 0 0 24px; color: #5b6581; line-height: 1.5; }
		a.btn { display: inline-block; padding: 10px 22px; border-radius: 8px; background: #1E2C5A;
			color: #fff; text-decoration: none; font-weight: 600; }
		a.btn:hover { background: #3780CB; }
		.debug { margin-top: 24px; text-align: left; font-size: 12px; overflow: auto; }
	</style>
</head>
<body>
	<div class="box">
		<img src="<?php echo $this->webroot; ?>img/svg/logo_colored.svg" alt="Akdital">
		<?php echo $this->fetch('content'); ?>
		<a class="btn" href="<?php echo $this->webroot; ?>">Retour à l'accueil</a>
	</div>
</body>
</html>
