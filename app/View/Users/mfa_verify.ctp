<style>
    #container { height: 100vh; padding: 0; margin: 0; }
    .mfa-box {
        max-width: 420px;
        margin: 10vh auto;
        padding: 32px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 8px 30px rgba(0,0,0,.08);
        font-family: 'Poppins', sans-serif;
    }
    .mfa-box h1 { font-size: 1.4rem; color: #1E2C5A; margin-bottom: 8px; }
    .mfa-box p { color: #6c757d; margin-bottom: 20px; }
    input[type="text"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        letter-spacing: 4px;
        font-size: 1.2rem;
        text-align: center;
    }
    input[type="submit"], .btn-mfa {
        width: 100%;
        margin-top: 16px;
        background: #3780CB;
        color: #fff;
        border: 0;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
    }
    .logout-link { display:block; text-align:center; margin-top: 14px; color:#9F9D9D; }
</style>

<div class="mfa-box">
    <h1>Vérification en deux étapes</h1>
    <p>Saisissez le code à 6 chiffres de votre application d’authentification.</p>

    <?php echo $this->Form->create('User'); ?>
    <?php echo $this->Form->input('code', array(
        'label' => false,
        'placeholder' => '123456',
        'autocomplete' => 'one-time-code',
        'maxlength' => 6,
        'type' => 'text',
    )); ?>
    <?php echo $this->Form->submit('Valider', array('class' => 'btn-mfa')); ?>
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Html->link('Se déconnecter', array('action' => 'logout'), array('class' => 'logout-link')); ?>
</div>
