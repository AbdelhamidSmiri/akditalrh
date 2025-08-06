<div style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">
    <p>Bonjour,</p>

    <p>Votre demande de réservation a été <strong>acceptée</strong> par l’hôtel.</p>

    <h4>Détails de la réservation :</h4>
    <ul>
        <li><strong>Hôtel :</strong> <?php echo h($hotel['Hotel']['hotel']); ?></li>
        <li><strong>Chambre :</strong> <?php echo h($reservation['Chambre']['nom']); ?></li>
        <li><strong>Check-in :</strong> <?php echo h($reservation['Reservation']['checkin']); ?></li>
        <li><strong>Check-out :</strong> <?php echo h($reservation['Reservation']['checkout']); ?></li>
        <li><strong>Numéro de confirmation :</strong> <?php echo h($reservation['Reservation']['confirmation']); ?></li>
        <li><strong>Commentaire :</strong> <?php echo nl2br(h($reservation['Reservation']['reponse'])); ?></li>
    </ul>

    <p>Merci et bon séjour !</p>
    <p>– L’équipe Akdital</p>
</div>
