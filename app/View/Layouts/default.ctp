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
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
		rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">
	<?php
	echo $this->Html->css('fontawesome');
	echo $this->Html->css('bootstrap');
	echo $this->Html->css('akdital_style');
	echo $this->Html->css('flash.min');
	echo $this->Html->script('flash.min');
	echo $this->Html->script('flash.min');
	echo $this->Html->css('sidebar_styles');
	?>


</head>

<body>
	<!-- get all icons svg  -->
	<?php
	$dashboard_icon = '<svg width="16" height="17" class="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M14.6667 7.5C14.6667 6.69333 14.1267 5.66 13.4667 5.2L9.34667 2.31333C8.41333 1.66 6.91333 1.69333 6.01333 2.39333L2.42 5.19333C1.82 5.66 1.33333 6.65333 1.33333 7.40666V12.3467C1.33333 13.8933 2.59333 15.16 4.14 15.16H11.86C13.4067 15.16 14.6667 13.8933 14.6667 12.3533V10.2867" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M8 12.4933V10.4933" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>';
	$reservations_icon = '<svg width="14" height="15" class="me-2" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_195_9649)"><path d="M6.125 4V4.875H3.5V4H6.125ZM3.5 6.625V5.75H6.125V6.625H3.5ZM3.5 8.375V7.5H5.25V8.375H3.5ZM2.625 4V4.875H1.75V4H2.625ZM2.625 5.75V6.625H1.75V5.75H2.625ZM1.75 8.375V7.5H2.625V8.375H1.75ZM0.875 1.375V13.625H5.25V14.5H0V0.5H7.62207L11.375 4.25293V5.75H10.5V4.875H7V1.375H0.875ZM7.875 1.99707V4H9.87793L7.875 1.99707ZM12.25 7.5H14V14.5H6.125V7.5H7.875V6.625H8.75V7.5H11.375V6.625H12.25V7.5ZM13.125 13.625V10.125H7V13.625H13.125ZM13.125 9.25V8.375H7V9.25H13.125Z" fill="white" /></g> <defs> <clipPath id="clip0_195_9649"> <rect width="14" height="14" fill="white" transform="translate(0 0.5)" /> </clipPath> </defs> </svg>';
	$demande_billet_icon = '<svg width="16" height="17" class="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M10.578 3.02C11.1953 2.52134 11.8873 2.49067 12.6427 2.50134C13.034 2.50734 13.2293 2.51 13.386 2.57067C13.6347 2.66734 13.8327 2.86534 13.9293 3.114C13.99 3.27067 13.9927 3.466 13.9987 3.85734C14.0093 4.61267 13.9787 5.304 13.48 5.922C13.06 6.442 12.3373 6.714 11.99 7.28867C11.7247 7.726 11.848 8.18 11.962 8.64734L12.8153 12.144C12.9853 12.8413 12.8553 13.2873 12.3687 13.7747C12.1093 14.0347 11.8933 14.0213 11.6833 13.686L9.10867 9.58334L7.87867 10.5607C7.43267 10.9153 7.21 11.0927 7.09267 11.342C6.81867 11.926 6.99267 12.8033 7.00133 13.4367C7.00667 13.7867 6.704 14.4767 6.276 14.4993C6.012 14.514 5.922 14.198 5.836 14.004L5.01467 12.136C4.81867 11.6893 4.81067 11.6813 4.364 11.4853L2.496 10.6633C2.30267 10.578 1.986 10.488 2.00067 10.224C2.024 9.796 2.714 9.49334 3.06333 9.49867C3.69667 9.50734 4.574 9.68134 5.158 9.40734C5.40733 9.29 5.58467 9.06734 5.93933 8.62067L6.91667 7.39134L2.814 4.81667C2.47867 4.606 2.466 4.39067 2.72533 4.13134C3.212 3.64467 3.65867 3.51467 4.356 3.68467L7.85267 4.538C8.31933 4.652 8.774 4.77534 9.212 4.51C9.786 4.16334 10.058 3.44067 10.578 3.02Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/> </svg>';
	$demande_hotel_icon = '<svg width="16" height="17" class="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1.33333 13.1667C1.14445 13.1667 0.986223 13.1027 0.858668 12.9747C0.731112 12.8467 0.667112 12.6885 0.666668 12.5V3.83334C0.666668 3.64445 0.730668 3.48623 0.858668 3.35867C0.986668 3.23112 1.14489 3.16712 1.33333 3.16667C1.52178 3.16623 1.68022 3.23023 1.80867 3.35867C1.93711 3.48712 2.00089 3.64534 2 3.83334V9.83334H7.33333V5.83334C7.33333 5.46667 7.464 5.1529 7.72534 4.89201C7.98667 4.63112 8.30045 4.50045 8.66667 4.50001H12.6667C13.4 4.50001 14.0278 4.76112 14.55 5.28334C15.0722 5.80556 15.3333 6.43334 15.3333 7.16667V12.5C15.3333 12.6889 15.2693 12.8473 15.1413 12.9753C15.0133 13.1033 14.8551 13.1671 14.6667 13.1667C14.4782 13.1662 14.32 13.1022 14.192 12.9747C14.064 12.8471 14 12.6889 14 12.5V11.1667H2V12.5C2 12.6889 1.936 12.8473 1.808 12.9753C1.68 13.1033 1.52178 13.1671 1.33333 13.1667ZM4.66667 9.16667C4.11111 9.16667 3.63889 8.97223 3.25 8.58334C2.86111 8.19445 2.66667 7.72223 2.66667 7.16667C2.66667 6.61112 2.86111 6.1389 3.25 5.75001C3.63889 5.36112 4.11111 5.16667 4.66667 5.16667C5.22222 5.16667 5.69445 5.36112 6.08333 5.75001C6.47222 6.1389 6.66667 6.61112 6.66667 7.16667C6.66667 7.72223 6.47222 8.19445 6.08333 8.58334C5.69445 8.97223 5.22222 9.16667 4.66667 9.16667ZM8.66667 9.83334H14V7.16667C14 6.80001 13.8696 6.48623 13.6087 6.22534C13.3478 5.96445 13.0338 5.83379 12.6667 5.83334H8.66667V9.83334ZM4.66667 7.83334C4.85556 7.83334 5.014 7.76934 5.142 7.64134C5.27 7.51334 5.33378 7.35512 5.33333 7.16667C5.33289 6.97823 5.26889 6.82001 5.14133 6.69201C5.01378 6.56401 4.85556 6.50001 4.66667 6.50001C4.47778 6.50001 4.31956 6.56401 4.192 6.69201C4.06445 6.82001 4.00045 6.97823 4 7.16667C3.99956 7.35512 4.06356 7.51356 4.192 7.64201C4.32045 7.77045 4.47867 7.83423 4.66667 7.83334Z" fill="white"/> </svg>';
	$bons_commande_icon = '<svg width="14" height="15" class="me-2" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_195_9664)"> <path d="M6.125 4V4.875H3.5V4H6.125ZM3.5 6.625V5.75H6.125V6.625H3.5ZM3.5 8.375V7.5H5.25V8.375H3.5ZM2.625 4V4.875H1.75V4H2.625ZM2.625 5.75V6.625H1.75V5.75H2.625ZM1.75 8.375V7.5H2.625V8.375H1.75ZM0.875 1.375V13.625H5.25V14.5H0V0.5H7.62207L11.375 4.25293V5.75H10.5V4.875H7V1.375H0.875ZM7.875 1.99707V4H9.87793L7.875 1.99707ZM12.25 7.5H14V14.5H6.125V7.5H7.875V6.625H8.75V7.5H11.375V6.625H12.25V7.5ZM13.125 13.625V10.125H7V13.625H13.125ZM13.125 9.25V8.375H7V9.25H13.125Z" fill="white" /> </g> <defs> <clipPath id="clip0_195_9664"> <rect width="14" height="14" fill="white" transform="translate(0 0.5)" /> </clipPath> </defs> </svg>';
	$billetterie_icon = '<svg width="16" height="17" class="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M10.578 3.01999C11.1953 2.52132 11.8873 2.49065 12.6427 2.50132C13.034 2.50732 13.2293 2.50999 13.386 2.57065C13.6347 2.66732 13.8327 2.86532 13.9293 3.11399C13.99 3.27065 13.9927 3.46599 13.9987 3.85732C14.0093 4.61265 13.9787 5.30399 13.48 5.92199C13.06 6.44199 12.3373 6.71399 11.99 7.28865C11.7247 7.72599 11.848 8.17999 11.962 8.64732L12.8153 12.144C12.9853 12.8413 12.8553 13.2873 12.3687 13.7747C12.1093 14.0347 11.8933 14.0213 11.6833 13.686L9.10867 9.58332L7.87867 10.5607C7.43267 10.9153 7.21 11.0927 7.09267 11.342C6.81867 11.926 6.99267 12.8033 7.00133 13.4367C7.00667 13.7867 6.704 14.4767 6.276 14.4993C6.012 14.514 5.922 14.198 5.836 14.004L5.01467 12.136C4.81867 11.6893 4.81067 11.6813 4.364 11.4853L2.496 10.6633C2.30267 10.578 1.986 10.488 2.00067 10.224C2.024 9.79599 2.714 9.49332 3.06333 9.49865C3.69667 9.50732 4.574 9.68132 5.158 9.40732C5.40733 9.28999 5.58467 9.06732 5.93933 8.62065L6.91667 7.39132L2.814 4.81665C2.47867 4.60599 2.466 4.39065 2.72533 4.13132C3.212 3.64465 3.65867 3.51465 4.356 3.68465L7.85267 4.53799C8.31933 4.65199 8.774 4.77532 9.212 4.50999C9.786 4.16332 10.058 3.44065 10.578 3.01999Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/> </svg>';
	$hebergement_icon = '<svg width="16" height="17" class="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1.33333 13.1667C1.14445 13.1667 0.986223 13.1027 0.858668 12.9747C0.731112 12.8467 0.667112 12.6884 0.666668 12.5V3.83333C0.666668 3.64444 0.730668 3.48621 0.858668 3.35866C0.986668 3.2311 1.14489 3.1671 1.33333 3.16666C1.52178 3.16621 1.68022 3.23021 1.80867 3.35866C1.93711 3.4871 2.00089 3.64533 2 3.83333V9.83333H7.33333V5.83333C7.33333 5.46666 7.464 5.15288 7.72534 4.89199C7.98667 4.6311 8.30045 4.50044 8.66667 4.49999H12.6667C13.4 4.49999 14.0278 4.7611 14.55 5.28333C15.0722 5.80555 15.3333 6.43333 15.3333 7.16666V12.5C15.3333 12.6889 15.2693 12.8473 15.1413 12.9753C15.0133 13.1033 14.8551 13.1671 14.6667 13.1667C14.4782 13.1662 14.32 13.1022 14.192 12.9747C14.064 12.8471 14 12.6889 14 12.5V11.1667H2V12.5C2 12.6889 1.936 12.8473 1.808 12.9753C1.68 13.1033 1.52178 13.1671 1.33333 13.1667ZM4.66667 9.16666C4.11111 9.16666 3.63889 8.97221 3.25 8.58333C2.86111 8.19444 2.66667 7.72221 2.66667 7.16666C2.66667 6.6111 2.86111 6.13888 3.25 5.74999C3.63889 5.3611 4.11111 5.16666 4.66667 5.16666C5.22222 5.16666 5.69445 5.3611 6.08333 5.74999C6.47222 6.13888 6.66667 6.6111 6.66667 7.16666C6.66667 7.72221 6.47222 8.19444 6.08333 8.58333C5.69445 8.97221 5.22222 9.16666 4.66667 9.16666ZM8.66667 9.83333H14V7.16666C14 6.79999 13.8696 6.48621 13.6087 6.22533C13.3478 5.96444 13.0338 5.83377 12.6667 5.83333H8.66667V9.83333ZM4.66667 7.83333C4.85556 7.83333 5.014 7.76933 5.142 7.64133C5.27 7.51333 5.33378 7.3551 5.33333 7.16666C5.33289 6.97821 5.26889 6.81999 5.14133 6.69199C5.01378 6.56399 4.85556 6.49999 4.66667 6.49999C4.47778 6.49999 4.31956 6.56399 4.192 6.69199C4.06445 6.81999 4.00045 6.97821 4 7.16666C3.99956 7.3551 4.06356 7.51355 4.192 7.64199C4.32045 7.77044 4.47867 7.83421 4.66667 7.83333Z" fill="white"/> </svg>';
	$parametres_icon = '<svg width="16" height="17" class="me-2" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M8 11C6.62 11 5.5 9.88 5.5 8.5C5.5 7.12 6.62 6 8 6C9.38 6 10.5 7.12 10.5 8.5C10.5 9.88 9.38 11 8 11ZM8 7C7.17333 7 6.5 7.67333 6.5 8.5C6.5 9.32667 7.17333 10 8 10C8.82667 10 9.5 9.32667 9.5 8.5C9.5 7.67333 8.82667 7 8 7Z" fill="white"/> <path d="M10.14 15.2933C10 15.2933 9.86004 15.2733 9.72004 15.24C9.30671 15.1267 8.96004 14.8667 8.74004 14.5L8.66004 14.3667C8.26671 13.6867 7.72671 13.6867 7.33337 14.3667L7.26004 14.4933C7.04004 14.8667 6.69337 15.1333 6.28004 15.24C5.86004 15.3533 5.42671 15.2933 5.06004 15.0733L3.91337 14.4133C3.50671 14.18 3.21337 13.8 3.08671 13.34C2.96671 12.88 3.02671 12.4067 3.26004 12C3.45337 11.66 3.50671 11.3533 3.39337 11.16C3.28004 10.9667 2.99337 10.8533 2.60004 10.8533C1.62671 10.8533 0.833374 10.06 0.833374 9.08665V7.91332C0.833374 6.93999 1.62671 6.14665 2.60004 6.14665C2.99337 6.14665 3.28004 6.03332 3.39337 5.83999C3.50671 5.64665 3.46004 5.33999 3.26004 4.99999C3.02671 4.59332 2.96671 4.11332 3.08671 3.65999C3.20671 3.19999 3.50004 2.81999 3.91337 2.58665L5.06671 1.92665C5.82004 1.47999 6.81337 1.73999 7.26671 2.50665L7.34671 2.63999C7.74004 3.31999 8.28004 3.31999 8.67337 2.63999L8.74671 2.51332C9.20004 1.73999 10.1934 1.47999 10.9534 1.93332L12.1 2.59332C12.5067 2.82665 12.8 3.20665 12.9267 3.66665C13.0467 4.12665 12.9867 4.59999 12.7534 5.00665C12.56 5.34665 12.5067 5.65332 12.62 5.84665C12.7334 6.03999 13.02 6.15332 13.4134 6.15332C14.3867 6.15332 15.18 6.94665 15.18 7.91999V9.09332C15.18 10.0667 14.3867 10.86 13.4134 10.86C13.02 10.86 12.7334 10.9733 12.62 11.1667C12.5067 11.36 12.5534 11.6667 12.7534 12.0067C12.9867 12.4133 13.0534 12.8933 12.9267 13.3467C12.8067 13.8067 12.5134 14.1867 12.1 14.42L10.9467 15.08C10.6934 15.22 10.42 15.2933 10.14 15.2933ZM8.00004 12.8267C8.59337 12.8267 9.14671 13.2 9.52671 13.86L9.60004 13.9867C9.68004 14.1267 9.81337 14.2267 9.97337 14.2667C10.1334 14.3067 10.2934 14.2867 10.4267 14.2067L11.58 13.54C11.7534 13.44 11.8867 13.2733 11.94 13.0733C11.9934 12.8733 11.9667 12.6667 11.8667 12.4933C11.4867 11.84 11.44 11.1667 11.7334 10.6533C12.0267 10.14 12.6334 9.84665 13.3934 9.84665C13.82 9.84665 14.16 9.50665 14.16 9.07999V7.90665C14.16 7.48665 13.82 7.13999 13.3934 7.13999C12.6334 7.13999 12.0267 6.84665 11.7334 6.33332C11.44 5.81999 11.4867 5.14665 11.8667 4.49332C11.9667 4.31999 11.9934 4.11332 11.94 3.91332C11.8867 3.71332 11.76 3.55332 11.5867 3.44665L10.4334 2.78665C10.1467 2.61332 9.76671 2.71332 9.59337 3.00665L9.52004 3.13332C9.14004 3.79332 8.58671 4.16665 7.99337 4.16665C7.40004 4.16665 6.84671 3.79332 6.46671 3.13332L6.39337 2.99999C6.22671 2.71999 5.85337 2.61999 5.56671 2.78665L4.41337 3.45332C4.24004 3.55332 4.10671 3.71999 4.05337 3.91999C4.00004 4.11999 4.02671 4.32665 4.12671 4.49999C4.50671 5.15332 4.55337 5.82665 4.26004 6.33999C3.96671 6.85332 3.36004 7.14665 2.60004 7.14665C2.17337 7.14665 1.83337 7.48665 1.83337 7.91332V9.08665C1.83337 9.50665 2.17337 9.85332 2.60004 9.85332C3.36004 9.85332 3.96671 10.1467 4.26004 10.66C4.55337 11.1733 4.50671 11.8467 4.12671 12.5C4.02671 12.6733 4.00004 12.88 4.05337 13.08C4.10671 13.28 4.23337 13.44 4.40671 13.5467L5.56004 14.2067C5.70004 14.2933 5.86671 14.3133 6.02004 14.2733C6.18004 14.2333 6.31337 14.1267 6.40004 13.9867L6.47337 13.86C6.85337 13.2067 7.40671 12.8267 8.00004 12.8267Z" fill="white"/> </svg>';

	?>
	<!-- Sidebar -->
	<nav class="sidebar">
		<!-- Logo -->
		<div class="sidebar-logo">
			<div class="logo-circle">
				<?php echo $this->Html->image('svg/logo.svg', [
					'alt' => 'Logo',
					'class' => 'akdital_logo',
				]); ?>
			</div>
		</div>


		<!-- Navigation -->
		<div class="sidebar-nav">
			<ul class="nav flex-column">

				<?php
				if (AuthComponent::user("Role.role") === 'Agence'): ?>
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#agence" aria-expanded="false">
							<?php echo $bons_commande_icon; ?> Gestion des vols
						</button>
						<div class="collapse" id="agence">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									$billetterie_icon .
										' Vols demander',
									array('controller' => 'volreservations', 'action' => 'agence_index'),
									array('class' => 'dropdown-item', 'escape' => false)
								);
								echo $this->Html->link(
									$hebergement_icon .
										' Vols términer',
									array('controller' => 'volreservations', 'action' => 'agence_valider'),
									array('class' => 'dropdown-item', 'escape' => false)
								);
								echo $this->Html->link(
									$hebergement_icon .
										' Vols Annuler',
									array('controller' => 'volreservations', 'action' => 'agence_annuler'),
									array('class' => 'dropdown-item', 'escape' => false)
								);

								?>
							</div>
						</div>
					</li>
				<?php endif; ?>

				<?php
				if (!in_array(AuthComponent::user("Role.role"), ['Agence', 'Admin'])): ?>
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#reservationsDropdown" aria-expanded="false">
							<?php echo $reservations_icon; ?>
							Mes billets de vol
						</button>
						<div class="collapse" id="reservationsDropdown">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									'Créé une demande',
									array('controller' => 'volreservations', 'action' => 'add'),
									array('class' => 'dropdown-item', 'escape' => false)

								); ?>
								<?php echo $this->Html->link(
									$demande_hotel_icon . 'Mes demandes',
									array('controller' => 'volreservations', 'action' => 'agent_index'),
									array('class' => 'dropdown-item', 'escape' => false)
								); ?>
							</div>
						</div>
					</li>

					<!-- Bons de commande Dropdown -->
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#commandesDropdown" aria-expanded="false">
							<?php echo $bons_commande_icon; ?>
							Bons de commande
						</button>
						<div class="collapse" id="commandesDropdown">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									'Demandes de billets',
									array('controller' => 'volreservations', 'action' => 'add'),
									array('class' => 'dropdown-item', 'escape' => false)

								);
								echo $this->Html->link(
									$demande_hotel_icon . ' Demandes d\'hôtel',
									array('controller' => 'demandes', 'action' => 'add'),
									array('class' => 'dropdown-item', 'escape' => false)
								); ?>
							</div>
						</div>
					</li>
				<?php endif; ?>


				<?php if (AuthComponent::user("Role.role") === 'Admin'): ?>
					<!-- Dashboard -->
					<li class="nav-item">
						<?php
						echo $this->Html->link(
							$dashboard_icon . ' Dashboard',
							array('controller' => 'dashboard', 'action' => 'index'),
							array('class' => 'nav-link', 'escape' => false)
						); ?>
					</li>

					<!-- Réservations Dropdown -->
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#reservationsDropdown" aria-expanded="false">
							<?php echo $reservations_icon; ?>
							Réservations
						</button>
						<div class="collapse" id="reservationsDropdown">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									'Demandes de billets',
									array('controller' => 'volreservations', 'action' => 'add'),
									array('class' => 'dropdown-item', 'escape' => false)

								); ?>
								<?php echo $this->Html->link(
									$demande_hotel_icon . ' Demandes d\'hôtel',
									array('controller' => 'demandes', 'action' => 'add'),
									array('class' => 'dropdown-item', 'escape' => false)
								); ?>
							</div>
						</div>
					</li>

					<!-- Bons de commande Dropdown -->
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#commandesDropdown" aria-expanded="false">
							<?php echo $bons_commande_icon; ?>
							Bons de commande
						</button>
						<div class="collapse" id="commandesDropdown">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									$billetterie_icon .
										' Billetterie',
									array('controller' => 'bons', 'action' => 'billetterie'),
									array('class' => 'dropdown-item', 'escape' => false)
								); ?>
								<?php echo $this->Html->link(
									$hebergement_icon .
										' Hébergement',
									array('controller' => 'bons', 'action' => 'hebergement'),
									array('class' => 'dropdown-item', 'escape' => false)
								); ?>
							</div>
						</div>
					</li>

					<!-- Parc automobile Dropdown -->
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#parcDropdown" aria-expanded="false">
							<i class="fas fa-car"></i>
							Parc automobile
						</button>
						<div class="collapse" id="parcDropdown">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									'Liste des véhicules',
									array('controller' => 'parc', 'action' => 'liste_vehicules'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Requêtes collaborateurs',
									array('controller' => 'parc', 'action' => 'requetes_collaborateurs'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Contrats',
									array('controller' => 'parc', 'action' => 'contrats'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Suivi des cartes carburant',
									array('controller' => 'parc', 'action' => 'suivi_cartes_carburant'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Suivi des Tag Jawaz',
									array('controller' => 'parc', 'action' => 'suivi_tag_jawaz'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Référentiel de prix',
									array('controller' => 'parc', 'action' => 'referentiel_prix'),
									array('class' => 'dropdown-item')
								); ?>
							</div>
						</div>
					</li>

					<!-- Appartements Dropdown -->
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#appartementsDropdown" aria-expanded="false">
							<i class="fas fa-building"></i>
							Appartements
						</button>
						<div class="collapse" id="appartementsDropdown">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									'Disponibilités',
									array('controller' => 'appartements', 'action' => 'disponibilites'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Affectation',
									array('controller' => 'beneficiaires', 'action' => 'recherche'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Affectations',
									array('controller' => 'beneficiaires', 'action' => 'add'),
									array('class' => 'dropdown-item')
								); ?>
								<?php echo $this->Html->link(
									'Historique logements',
									array('controller' => 'appartements', 'action' => 'historique_logements'),
									array('class' => 'dropdown-item')
								); ?>
							</div>
						</div>
					</li>

					<!-- Paramètres -->
					<li class="nav-item dropdown">
						<button class="nav-link dropdown-toggle" type="button" data-bs-toggle="collapse"
							data-bs-target="#parametres" aria-expanded="false">
							<i class="fas fa-building"></i>
							Paramètres
						</button>
						<div class="collapse" id="parametres">
							<div class="dropdown-menu show">
								<?php echo $this->Html->link(
									'Gestion des utilisateurs',
									array('controller' => 'users', 'action' => 'index'),
									array('class' => 'dropdown-item')
								);
								echo $this->Html->link(
									'Gestion des rôles',
									array('controller' => 'roles', 'action' => 'index'),
									array('class' => 'dropdown-item')
								);
								echo $this->Html->link(
									'Gestion des sites',
									array('controller' => 'sites', 'action' => 'index'),
									array('class' => 'dropdown-item')
								);
								echo $this->Html->link(
									'Gestion des hotels',
									array('controller' => 'hotels', 'action' => 'index'),
									array('class' => 'dropdown-item')
								);
								echo $this->Html->link(
									'Gestion des chambres',
									array('controller' => 'chambres', 'action' => 'index'),
									array('class' => 'dropdown-item')
								);
								echo $this->Html->link(
									'Gestion des appartements',
									array('controller' => 'appartements', 'action' => 'index'),
									array('class' => 'dropdown-item')
								);

								?>
							</div>
						</div>
					</li>
				<?php endif; ?>

			</ul>
		</div>

		<!-- Logout Button -->
		<div class="sidebar-footer">
			<?php echo $this->Html->link(
				'<i class="fas fa-sign-out-alt"></i> Se déconnecter',
				array('controller' => 'users', 'action' => 'logout'),
				array('class' => 'logout-btn', 'escape' => false)
			); ?>
		</div>
	</nav>

	<!-- Main Content -->
	<div class="main-content">
		<div id="container">
			<!-- Profile Section -->

			<div class="page-header">
				<div class="profile-header">
					<div>
						<h2 class="title-page"><?php echo $this->fetch('title'); ?></h2>
						<p class="slogan">
							<?php echo isset($pageSubtitle) ? $pageSubtitle : 'Consultez et gérez les informations du système.'; ?>
						</p>
					</div>
					<!-- User Profile -->
					<div class="profile-card">
						<div class="d-flex align-items-center">
							<div class="profile-avatar me-2">
								<?php
								$userInitials = 'ZD'; // Default fallback
								if (AuthComponent::user('nom')) {
									$userName = AuthComponent::user('nom');
									$userLastname = AuthComponent::user('prenom');
									$userInitials = strtoupper(substr($userName, 0, 1)) . strtoupper(substr($userLastname, 0, 1));
								}
								echo $userInitials;
								?>
							</div>
							<div>
								<small class="name-profile">
									<?php
									echo AuthComponent::user('nom') . ' ' . AuthComponent::user('prenom');
									?>
								</small><br>
								<small class="role-profile">
									<?php
									echo AuthComponent::user('Role.role');
									?>
								</small>
							</div>

						</div>
					</div>
				</div>
			</div>

			<!-- Flash Messages -->
			<div id="flash-messages">
				<?php
				echo $this->Flash->render('info');
				echo $this->Flash->render('default');
				echo $this->Flash->render('success');
				echo $this->Flash->render('warning');
				echo $this->Flash->render('error');
				?>
			</div>

			<!-- Content -->
			<div id="content">
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
	</div>

	<!-- Mobile Menu Toggle (for responsive) -->
	<button class="btn btn-primary d-md-none" id="sidebarToggle"
		style="position: fixed; top: 1rem; left: 1rem; z-index: 1001;">
		<i class="fas fa-bars"></i>
	</button>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
		integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
		crossorigin="anonymous"></script>
	<?php echo $this->Html->script('bootstrap.min'); ?>
	<?php echo $this->Html->script('search_table'); ?>


	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Handle active states based on current URL
			const currentController = '<?php echo $this->request->controller; ?>';
			const currentAction = '<?php echo $this->request->action; ?>';

			// Remove active class from all nav links
			document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
				link.classList.remove('active');
			});

			// Add active class based on controller/action
			document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
				const href = link.getAttribute('href');
				const isCurrent =
					href &&
					href.includes(currentController) &&
					(
						href.includes(currentAction) ||
						(currentAction === 'index' &&
							(
								href.endsWith('/' + currentController) ||
								href.includes('/' + currentController + '/index')
							)
						)
					);
				if (isCurrent) {
					link.classList.add('active');

					// If it's a dropdown item, expand the parent dropdown
					const dropdown = link.closest('.nav-item');
					if (dropdown && link.classList.contains('dropdown-item')) {
						const collapseElement = dropdown.querySelector('.collapse');
						if (collapseElement) {
							collapseElement.classList.add('show');
							const toggleButton = dropdown.querySelector('[data-bs-toggle="collapse"]');
							if (toggleButton) {
								toggleButton.setAttribute('aria-expanded', 'true');
							}
						}
					}
				}
			});

			// Mobile sidebar toggle
			const sidebarToggle = document.getElementById('sidebarToggle');
			const sidebar = document.querySelector('.sidebar');

			if (sidebarToggle && sidebar) {
				sidebarToggle.addEventListener('click', function() {
					sidebar.classList.toggle('show');
				});

				document.addEventListener('click', function(e) {
					if (window.innerWidth <= 768) {
						if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
							sidebar.classList.remove('show');
						}
					}
				});
			}
		});
	</script>
</body>

</html>