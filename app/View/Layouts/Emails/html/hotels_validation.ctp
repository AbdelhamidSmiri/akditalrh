<div style="font-family: Arial, sans-serif; color: #333; font-size: 16px;">
    <p>Bonjour,</p>

    <p>Une nouvelle demande de réservation a été effectuée par un collaborateur Akdital. Voici les détails :</p>

    <ul>
        <li><strong>Demandeur :</strong> <?php echo AuthComponent::user("nom"); ?></li>
        <li><strong>Hôtel :</strong> <?php echo h($hotel["Hotel"]["hotel"]); ?></li>
        <li><strong>Chambre :</strong> <?php echo h($chambre["Chambre"]["nom"]); ?></li>
        <li><strong>Date d'arrivée :</strong> <?php echo h($reservation["checkin"]); ?></li>
        <li><strong>Date de départ :</strong> <?php echo h($reservation["checkout"]); ?></li>
        <li><strong>Message :</strong> <?php echo nl2br(h($reservation["message"])); ?></li>
    </ul>

    <p>Merci de valider ou refuser cette demande :</p>

    <div style="margin: 20px 0;">
        <a href="<?php 
        $encryptedId = urlencode(base64_encode($id ^ 19051983));
        echo Router::url(array('controller' => 'reservations', 'action' => 'accepte',$encryptedId ), true); ?>" 
           style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">
            ✅ Accepter
        </a>

        <a href="<?php 
        $encryptedId = urlencode(base64_encode($id ^ 19051983));
        echo Router::url(array('controller' => 'reservations', 'action' => 'reject',$encryptedId ), true); ?>" 
           style="padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px;">
            ❌ Refuser
        </a>
    </div>

    <p>La pièce jointe (CIN) est incluse avec cet e-mail.</p>

    <p>Cordialement,<br>L’équipe Akdital</p>
</div>
