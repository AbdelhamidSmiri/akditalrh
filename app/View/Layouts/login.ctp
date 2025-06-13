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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <?php
    echo $this->Html->css('fontawesome');
    echo $this->Html->css('bootstrap');
    echo $this->Html->css('akdital_style'); // this is the main css file for the application our theme
    echo $this->Html->css('flash.min'); // this for flash messages https://betaweb.github.io/flashjs
    echo $this->Html->script('flash.min'); // this for flash messages https://betaweb.github.io/flashjs

    ?>




</head>

<body>
    <div id="container" class="container-fluid ">
        <!-- flash message -->

        <?php
        echo $this->Flash->render('info');
        echo $this->Flash->render('default');
        echo $this->Flash->render('success');
        echo $this->Flash->render('warning');
        echo $this->Flash->render('error');
        echo $this->fetch('content');
        ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <?php echo $this->Html->script('bootstrap.min.js'); ?>


</body>

</html>