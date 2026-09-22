<div class="users form">
    <?php echo $this->Form->create('User'); ?>

    <div class="row">
        <div class="col"></div>
        <div class="col-8">

            <div class="row">
                <div class="col-12">
                    <?php
                    echo $this->Form->input('role_id', [
                        'label' => 'Rôle',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error role-error">
                        Sélectionnez un rôle pour l'utilisateur.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('username', [
                        'label' => "Nom d'utilisateur",
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error username-error">
                        Le nom d'utilisateur est requis.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('password', [
                        'label' => 'Mot de passe',
                        'placeholder' => '',
                        'autocomplete' => 'new-password',
                    ]);
                    ?>
                    <div id="password-feedback" class="password-feedback" aria-live="polite">
                        <div class="password-feedback-title">Exigences du mot de passe :</div>
                        <ul class="password-rules">
                            <li data-rule="length" class="rule-pending">Au moins 8 caractères</li>
                            <li data-rule="letter" class="rule-pending">Au moins une lettre</li>
                            <li data-rule="digit" class="rule-pending">Au moins un chiffre</li>
                        </ul>
                        <div id="password-status" class="password-status"></div>
                    </div>
                    <div class="message-error password-error">
                        Mot de passe requis : 8 caractères min., lettre + chiffre.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('nom', [
                        'label' => 'Nom',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error nom-error">
                        Le nom de l'utilisateur est requis.
                    </div>
                </div>
                <div class="col-12">
                    <?php
                    echo $this->Form->input('prenom', [
                        'label' => 'Prénom',
                        'placeholder' => ''
                    ]);
                    ?>
                    <div class="message-error prenom-error">
                        Le prénom de l'utilisateur est requis.
                    </div>
                </div>

                <div class="submit-section">
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

<style>
    .password-feedback {
        margin-top: -12px;
        margin-bottom: 16px;
        padding: 10px 12px;
        border-radius: 8px;
        background: #f7f9fc;
        border: 1px solid #e3e8ef;
        font-size: 13px;
    }
    .password-feedback-title {
        font-weight: 600;
        margin-bottom: 6px;
        color: #424242;
    }
    .password-rules {
        list-style: none;
        padding: 0;
        margin: 0 0 8px 0;
    }
    .password-rules li {
        margin: 4px 0;
        padding-left: 22px;
        position: relative;
    }
    .password-rules li::before {
        content: '○';
        position: absolute;
        left: 0;
        font-weight: bold;
    }
    .password-rules li.rule-ok {
        color: #1b7a3d;
    }
    .password-rules li.rule-ok::before {
        content: '✓';
    }
    .password-rules li.rule-bad {
        color: #b80000;
    }
    .password-rules li.rule-bad::before {
        content: '✗';
    }
    .password-rules li.rule-pending {
        color: #6c757d;
    }
    .password-status {
        font-weight: 600;
        min-height: 18px;
    }
    .password-status.is-good {
        color: #1b7a3d;
    }
    .password-status.is-bad {
        color: #b80000;
    }
    input.password-input-good {
        border-color: #1b7a3d !important;
        box-shadow: 0 0 0 0.15rem rgba(27, 122, 61, 0.15);
    }
    input.password-input-bad {
        border-color: #b80000 !important;
        box-shadow: 0 0 0 0.15rem rgba(184, 0, 0, 0.12);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('.users form form');
        const password = document.querySelector('[name="data[User][password]"]');
        const statusEl = document.getElementById('password-status');
        const passwordError = document.querySelector('.password-error');

        function evaluatePassword(value) {
            return {
                length: value.length >= 8,
                letter: /[A-Za-z]/.test(value),
                digit: /[0-9]/.test(value),
            };
        }

        function isPasswordValid(value) {
            const rules = evaluatePassword(value);
            return value.length > 0 && rules.length && rules.letter && rules.digit;
        }

        function updatePasswordFeedback() {
            if (!password) return false;
            const value = password.value;
            const rules = evaluatePassword(value);
            const hasTyped = value.length > 0;

            ['length', 'letter', 'digit'].forEach(function(rule) {
                const li = document.querySelector('.password-rules [data-rule="' + rule + '"]');
                if (!li) return;
                li.classList.remove('rule-ok', 'rule-bad', 'rule-pending');
                if (!hasTyped) {
                    li.classList.add('rule-pending');
                } else if (rules[rule]) {
                    li.classList.add('rule-ok');
                } else {
                    li.classList.add('rule-bad');
                }
            });

            password.classList.remove('password-input-good', 'password-input-bad');
            statusEl.classList.remove('is-good', 'is-bad');

            if (!hasTyped) {
                statusEl.textContent = '';
                passwordError.style.display = 'none';
                return false;
            }

            const valid = rules.length && rules.letter && rules.digit;
            if (valid) {
                password.classList.add('password-input-good');
                statusEl.classList.add('is-good');
                statusEl.textContent = 'Mot de passe conforme.';
                passwordError.style.display = 'none';
            } else {
                password.classList.add('password-input-bad');
                statusEl.classList.add('is-bad');
                statusEl.textContent = 'Mot de passe trop faible.';
                passwordError.style.display = 'block';
                passwordError.textContent = 'Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.';
            }
            return valid;
        }

        if (password) {
            password.addEventListener('input', updatePasswordFeedback);
            password.addEventListener('blur', updatePasswordFeedback);
            updatePasswordFeedback();
        }

        form.addEventListener('submit', function(e) {
            let isValid = true;

            const role = document.querySelector('[name="data[User][role_id]"]');
            if (!role.value) {
                role.style.border = '1px solid #b80000';
                document.querySelector('.role-error').style.display = 'block';
                isValid = false;
            } else {
                role.style.border = '';
                document.querySelector('.role-error').style.display = 'none';
            }

            const username = document.querySelector('[name="data[User][username]"]');
            if (!username.value.trim()) {
                username.style.border = '1px solid #b80000';
                document.querySelector('.username-error').style.display = 'block';
                isValid = false;
            } else {
                username.style.border = '';
                document.querySelector('.username-error').style.display = 'none';
            }

            if (!isPasswordValid(password.value)) {
                updatePasswordFeedback();
                passwordError.style.display = 'block';
                isValid = false;
            }

            const nom = document.querySelector('[name="data[User][nom]"]');
            if (!nom.value.trim()) {
                nom.style.border = '1px solid #b80000';
                document.querySelector('.nom-error').style.display = 'block';
                isValid = false;
            } else {
                nom.style.border = '';
                document.querySelector('.nom-error').style.display = 'none';
            }

            const prenom = document.querySelector('[name="data[User][prenom]"]');
            if (!prenom.value.trim()) {
                prenom.style.border = '1px solid #b80000';
                document.querySelector('.prenom-error').style.display = 'block';
                isValid = false;
            } else {
                prenom.style.border = '';
                document.querySelector('.prenom-error').style.display = 'none';
            }

            if (!isValid) {
                e.preventDefault();
                const firstError = document.querySelector('.message-error[style*="display: block"], .password-status.is-bad');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    });
</script>
