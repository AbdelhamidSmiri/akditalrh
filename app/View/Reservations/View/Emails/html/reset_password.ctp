<p>Bonjour <?php echo h($user['User']['username']); ?>,</p>
<p>Pour réinitialiser votre mot de passe, cliquez sur le lien suivant :</p>
<p><a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'reset_password', $user['User']['id'], 'your-token'), true); ?>">Réinitialiser le mot de passe</a></p>
