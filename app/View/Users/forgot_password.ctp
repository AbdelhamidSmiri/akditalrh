<h2>Mot de passe oublié ?</h2>
<p>Entrez votre adresse e-mail pour réinitialiser votre mot de passe.</p>

<?php
echo $this->Form->create('User');
echo $this->Form->input('username', array(
    'label' => 'Adresse e-mail',
    'placeholder' => ''
));
echo $this->Form->submit('Envoyer', array('class' => 'btn btn-primary'));
echo $this->Form->end();
?>

<style>
    h2 {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #333;
    }

    p {
        font-family: 'Poppins', sans-serif;
        color: #666;
    }

    .btn {
        background-color: #3780CB;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    .btn:hover {
        background-color: #000000;
        color: #ffffff;
    }