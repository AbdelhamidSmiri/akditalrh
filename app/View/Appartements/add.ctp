<div class="appartements form">
    <?php echo $this->Form->create('Appartement', array("type" => "file")); ?>
    <div class="row">
        <div class="col"></div>
        <div class="col-8">

            <div class="row">
                <div class='col-12'>
                    <?php echo $this->Form->input('nom', array('placeholder' => '')); ?>
                    <div class="message-error nom-error">
                        Veuillez saisir le nom de l'appartement.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    $sexe = ["Homme" => "Homme", "Femme" => "Femme"];
                    echo $this->Form->input('sexe', array('options' => $sexe, 'placeholder' => '')); ?>
                    <div class="message-error sexe-error">
                        Veuillez sélectionner le sexe.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('capacite', array('placeholder' => '','type' => 'text'));
                    ?>
                    <div class="message-error capacite-error">
                        Veuillez saisir la capacité.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('ville_id', array('placeholder' => ''));
                    ?>
                    <div class="message-error ville-error">
                        Veuillez sélectionner une ville.
                    </div>
                </div>
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('adresse', array('placeholder' => ''));
                    ?>
                    <div class="message-error adresse-error">
                        Veuillez saisir l'adresse.
                    </div>
                </div>
                <div class='col-12 mb-4 input-file'>
                    <label for="img">Images</label>
                    <div class="file-upload-wrapper">
                        <div class="file-upload-area" id="images-area">
                            <div class="upload-text">Glissez-déposez les fichiers ici</div>
                            <div class="upload-subtext">Ou</div>
                            <button type="button" class="choose-files-btn">Choisir des fichiers <i class="fa-light fa-cloud-arrow-up"></i></button>

                            <?php echo $this->Form->file('images', array(
                                'name' => 'data[Appartement][images][]',
                                'class' => 'file-input',
                                'accept' => '.jpg, .jpeg, .png, .pdf',
                                'multiple' => true
                            )); ?>
                        </div>

                        <div class="file-info">
                            <div class="files-list"></div>
                        </div>
                    </div>

                    <div class="description-text">
                        Téléversez les images de l'appartement.
                    </div>
                    <div class="message-error images-error">
                        Veuillez téléverser au moins une image.
                    </div>
                </div>
                <div class='submit-section'>
                    <button type="submit" class="btn btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Envoyer
                    </button>
                </div><?php echo $this->Form->end(); ?>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>

<?php echo $this->Html->script('input_file'); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;

        // Validate nom
        const nom = document.querySelector('[name="data[Appartement][nom]"]');
        if (!nom.value.trim()) {
            nom.style.border = '1px solid #b80000';
            document.querySelector('.nom-error').style.display = 'block';
            isValid = false;
        } else {
            nom.style.border = '';
            document.querySelector('.nom-error').style.display = 'none';
        }

        // Validate sexe
        const sexe = document.querySelector('[name="data[Appartement][sexe]"]');
        if (!sexe.value) {
            sexe.style.border = '1px solid #b80000';
            document.querySelector('.sexe-error').style.display = 'block';
            isValid = false;
        } else {
            sexe.style.border = '';
            document.querySelector('.sexe-error').style.display = 'none';
        }

        // Validate capacite
        const capacite = document.querySelector('[name="data[Appartement][capacite]"]');
        if (!capacite.value.trim()) {
            capacite.style.border = '1px solid #b80000';
            document.querySelector('.capacite-error').style.display = 'block';
            isValid = false;
        } else {
            capacite.style.border = '';
            document.querySelector('.capacite-error').style.display = 'none';
        }

        // Validate ville_id
        const ville = document.querySelector('[name="data[Appartement][ville_id]"]');
        if (!ville.value) {
            ville.style.border = '1px solid #b80000';
            document.querySelector('.ville-error').style.display = 'block';
            isValid = false;
        } else {
            ville.style.border = '';
            document.querySelector('.ville-error').style.display = 'none';
        }

        // Validate adresse
        const adresse = document.querySelector('[name="data[Appartement][adresse]"]');
        if (!adresse.value.trim()) {
            adresse.style.border = '1px solid #b80000';
            document.querySelector('.adresse-error').style.display = 'block';
            isValid = false;
        } else {
            adresse.style.border = '';
            document.querySelector('.adresse-error').style.display = 'none';
        }

        // Validate images
        const images = document.querySelector('[name="data[Appartement][images][]"]');
        if (images.files.length === 0) {
            document.getElementById('images-area').style.border = '2px dashed #b80000';
            document.querySelector('.images-error').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('images-area').style.border = '';
            document.querySelector('.images-error').style.display = 'none';
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.message-error[style*="display: block"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>