<div class="hotels form">
    <?php echo $this->Form->create('Hotel', ["type" => "file", 'id' => 'HotelAddForm']); ?>

    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="row">
                <!-- Nom de l'hôtel -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('hotel', [
                        'placeholder' => '',
                        'label' => 'Nom de l\'hôtel',
                        'id' => 'HotelHotel'
                    ]);
                    ?>
                    <div class="message-error hotel-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Le nom de l'hôtel est obligatoire (min. 3 caractères)
                    </div>
                </div>
                
                <!-- Étoiles -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('etoile', [
                        'placeholder' => '',
                        'label' => 'Nombre d\'étoiles',
                        'id' => 'HotelEtoile'
                    ]);
                    ?>
                    <div class="message-error etoile-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Veuillez indiquer le nombre d'étoiles (1-5)
                    </div>
                </div>
                
                <!-- Ville -->
                <div class='col-12'>
                    <?php
                    $villes_hotel['__autre__'] = 'Autre';
                    echo $this->Form->input('ville_select', [
                        'label' => 'Ville',
                        'type' => 'select',
                        'options' => $villes_hotel,
                        'empty' => 'Sélectionner une ville',
                        'class' => 'form-control',
                        'id' => 'ville-select'
                    ]);
                    ?>
                    <div class="message-error ville-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Sélectionnez une ville ou spécifiez "Autre"
                    </div>
                    
                    <?php
                    echo $this->Form->input('ville_autre', [
                        'label' => 'Autre ville',
                        'type' => 'text',
                        'id' => 'ville-autre',
                        'style' => 'display: none;',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error ville-autre-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Le nom de la ville est obligatoire
                    </div>
                </div>

                <!-- Adresse -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('adresse', [
                        'placeholder' => '',
                        'id' => 'HotelAdresse'
                    ]);
                    ?>
                    <div class="message-error adresse-error" style="display: none; color: #b80000; margin-top: 5px;">
                        L'adresse complète est requise
                    </div>
                </div>
                
                <!-- Images -->
                <div class='col-12 mb-4 input-file'>
                    <label>Images de l'hôtel</label>
                    <div class="file-upload-wrapper">
                        <div class="file-upload-area" id="images-area">
                            <div class="upload-text">Glissez-déposez les images ici</div>
                            <div class="upload-subtext">Ou</div>
                            <button type="button" class="choose-files-btn">Choisir des images <i class="fa-light fa-cloud-arrow-up"></i></button>
                            <?php echo $this->Form->file('images', [
                                'name' => 'data[Hotel][images][]',
                                'class' => 'file-input',
                                'accept' => '.jpg, .jpeg, .png',
                                'multiple' => true,
                                'id' => 'HotelImages'
                            ]); ?>
                        </div>
                        <div class="file-info">
                            <div class="files-list"></div>
                        </div>
                    </div>
                    <div class="message-error images-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Au moins une image est requise
                    </div>
                </div>
                
                <!-- Email -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('mail', [
                        'placeholder' => '',
                        'id' => 'HotelMail'
                    ]);
                    ?>
                    <div class="message-error mail-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Une adresse email valide est requise
                    </div>
                </div>
                
                <!-- Téléphone -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('telephone', [
                        'placeholder' => '',
                        'id' => 'HotelTelephone',
						'label' => 'Téléphone'
                    ]);
                    ?>
                    <div class="message-error telephone-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Un numéro valide (10 chiffres) est requis
                    </div>
                </div>
                
                <!-- Responsable -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('nom_responsable', [
                        'placeholder' => '',
                        'id' => 'HotelNomResponsable'
                    ]);
                    ?>
                    <div class="message-error responsable-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Le nom du responsable est obligatoire
                    </div>
                </div>
                
                <!-- Règlement -->
                <div class='col-12'>
                    <?php
                    echo $this->Form->input('reglement', [
                        'placeholder' => '',
                        'id' => 'HotelReglement',
                        'label' => 'Règlement
',
                    ]);
                    ?>
                    <div class="message-error reglement-error" style="display: none; color: #b80000; margin-top: 5px;">
                        Les conditions de règlement sont requises
                    </div>
                </div>

                <div class='submit-section'>
                    <button type="submit" class="btn btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Enregistrer
                    </button>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>

<?php echo $this->Html->script('input_file'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('HotelAddForm');
    const emailField = document.getElementById('HotelMail');
    const select = document.getElementById('ville-select');
    const inputAutre = document.getElementById('ville-autre');
    const villeAutreError = document.querySelector('.ville-autre-error');
    const villeError = document.querySelector('.ville-error');

    // Ville selection handler
    select.addEventListener('change', function() {
        if (this.value === '__autre__') {
            inputAutre.style.display = 'block';
            inputAutre.setAttribute('name', 'data[Hotel][ville_autre]');
            villeError.style.display = 'none';
        } else {
            inputAutre.style.display = 'none';
            inputAutre.removeAttribute('name');
            villeAutreError.style.display = 'none';
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9]{10}$/;

        // Reset all errors
        document.querySelectorAll('.message-error').forEach(el => {
            el.style.display = 'none';
        });
        document.querySelectorAll('input, select').forEach(el => {
            el.style.borderColor = '';
        });
        document.getElementById('images-area').style.border = '';

        // Hotel name validation
        const hotel = document.getElementById('HotelHotel');
        if (!hotel.value.trim() || hotel.value.trim().length < 3) {
            hotel.style.border = '1px solid #b80000';
            document.querySelector('.hotel-error').style.display = 'block';
            isValid = false;
        }

        // Stars validation
        const etoile = document.getElementById('HotelEtoile');
        if (!etoile.value || isNaN(etoile.value) || etoile.value < 1 || etoile.value > 5) {
            etoile.style.border = '1px solid #b80000';
            document.querySelector('.etoile-error').style.display = 'block';
            isValid = false;
        }

        // City validation
        if (select.value === '__autre__') {
            if (!inputAutre.value.trim() || inputAutre.value.trim().length < 2) {
                inputAutre.style.border = '1px solid #b80000';
                villeAutreError.style.display = 'block';
                isValid = false;
            }
        } else if (!select.value) {
            select.style.border = '1px solid #b80000';
            villeError.style.display = 'block';
            isValid = false;
        }

        // Address validation
        const adresse = document.getElementById('HotelAdresse');
        if (!adresse.value.trim()) {
            adresse.style.border = '1px solid #b80000';
            document.querySelector('.adresse-error').style.display = 'block';
            isValid = false;
        }

        // Images validation
        const imagesInput = document.querySelector('[name="data[Hotel][images][]"]');
        if (imagesInput.files.length === 0) {
            document.getElementById('images-area').style.border = '2px dashed #b80000';
            document.querySelector('.images-error').style.display = 'block';
            isValid = false;
        }

        // Email validation
        if (!emailField.value.trim() || !emailRegex.test(emailField.value)) {
            emailField.style.border = '1px solid #b80000';
            document.querySelector('.mail-error').style.display = 'block';
            isValid = false;
        }

        // Phone validation
        const telephone = document.getElementById('HotelTelephone');
        const phoneValue = telephone.value.replace(/\s/g, '');
        if (!phoneValue || !phoneRegex.test(phoneValue)) {
            telephone.style.border = '1px solid #b80000';
            document.querySelector('.telephone-error').style.display = 'block';
            isValid = false;
        }

        // Manager name validation
        const responsable = document.getElementById('HotelNomResponsable');
        if (!responsable.value.trim()) {
            responsable.style.border = '1px solid #b80000';
            document.querySelector('.responsable-error').style.display = 'block';
            isValid = false;
        }

        // Payment terms validation
        const reglement = document.getElementById('HotelReglement');
        if (!reglement.value.trim()) {
            reglement.style.border = '1px solid #b80000';
            document.querySelector('.reglement-error').style.display = 'block';
            isValid = false;
        }

        if (isValid) {
            // Clean up emails before submission
            const emails = emailField.value
                .split(/\r?\n/)
                .map(e => e.trim())
                .filter(e => e.length > 0)
                .join(';');
            emailField.value = emails;
            
            // Submit the form
            form.submit();
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.message-error[style*="block"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>