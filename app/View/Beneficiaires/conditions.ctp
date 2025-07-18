<div class="container" style="max-width: 600px; margin: auto;">
    <h3 style="color: #1a237e;">Conditions d’occupation du logement – Akdital</h3>
    <p>
        Veuillez lire et valider les conditions ci-dessous pour accéder aux informations de votre hébergement.
    </p>

    <?php echo $this->Form->create('Appartement'); ?>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je m'engage à respecter les lieux, le mobilier et les règles de vie de l’appartement.</label>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je suis responsable des clés et du matériel mis à disposition.</label>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je m'engage à informer l’agence en cas de problème ou dégradation.</label>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je quitterai le logement à la date prévue ou après accord de prolongation.</label>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je m'engage à restituer les lieux dans un état propre et conforme.</label>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je ne suis pas autorisé à héberger d’autres personnes sans validation préalable.</label>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" checked disabled> Je m'engage à signaler mon départ via WhatsApp ou à l’agence.</label>
        </div>

        <hr>

        <div class="checkbox" style="margin-bottom: 20px;">
            <label>
                <input type="checkbox" name="valide" required>
                <strong>J’ai compris et j’accepte les conditions</strong>
            </label>
        </div>

        <button type="submit" class="btn btn-success btn-block">
            ✅ Confirmer la validation
        </button>
    </form>
</div>
