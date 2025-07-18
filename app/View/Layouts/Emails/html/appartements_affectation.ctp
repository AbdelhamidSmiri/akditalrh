<p>Bonjour <?php echo h($nom); ?>,</p>

<p>
    Vous avez été affecté à un appartement dans le cadre de votre mission avec Akdital.<br>
    Avant de recevoir les informations complètes sur le logement, nous vous invitons à consulter les conditions
    d’occupation et à valider votre engagement.
</p>

<p>👉 Cliquez sur le lien ci-dessous pour lire les conditions :</p>

<p>
    <a
        href="<?php 
        $encryptedId = urlencode(base64_encode($id ^ 19051983));
        echo Router::url(array('controller' => 'beneficiaires', 'action' => 'conditions',$encryptedId ), true); ?>">
        Lire les conditions et signer
    </a>
</p>


<p>koko
    Une fois votre engagement validé, les informations de votre hébergement vous seront automatiquement envoyées.
</p>

<p>Cordialement,<br>
    L’équipe Akdital</p>