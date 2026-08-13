<?php
$succes = false;
$nom = "";
$email = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = strip_tags(trim($_POST["nom"] ?? ""));
    $email = strip_tags(trim($_POST["email"] ?? ""));
    $message = strip_tags(trim($_POST["message"] ?? ""));

    if (!empty($nom) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        
        $texteMessage = "--- NOUVEAU MESSAGE ---\n";
        $texteMessage .= "Date : " . date("d/m/Y H:i:s") . "\n";
        $texteMessage .= "Nom : " . $nom . "\n";
        $texteMessage .= "Email : " . $email . "\n";
        $texteMessage .= "Message : " . $message . "\n";
        $texteMessage .= "----------------------\n\n";

        file_put_contents("messages.txt", $texteMessage, FILE_APPEND);

        $succes = true;
    }
}

include 'header.php';
?>

<style>
    
    .champ-groupe {
        margin-bottom: 15px;
    }
    .champ-groupe label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .champ-groupe input,
    .champ-groupe textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        outline: none;
        font-family: inherit;
        transition: border-color 0.2s ease;
    }

    
    .champ-groupe input.input-erreur,
    .champ-groupe textarea.input-erreur {
        border: 1.5px solid #ff6b6b !important;
    }

    
    .msg-erreur {
        color: #ff6b6b;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
        min-height: 18px;
    }
</style>

<div class="contenu">
    <h1>Me contacter</h1>
    <p>Une question, une opportunité ? Écrivez-moi.</p>

    <?php if ($succes): ?>
        <p style="background:#e6f4ee; color:#1f5c47; padding:15px; border-radius:6px; max-width:500px;">
            Merci <?php echo $nom; ?> ! Votre message a bien été reçu.
        </p>
    <?php endif; ?>

    <form id="formContact" class="projet-carte" style="max-width:500px;" method="POST" action="contact.php" novalidate>
        
        <div class="champ-groupe">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>">
            <span id="erreur-nom" class="msg-erreur"></span>
        </div>

        <div class="champ-groupe">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            <span id="erreur-email" class="msg-erreur"></span>
        </div>

        <div class="champ-groupe">
            <label for="message">Message *</label>
            <textarea id="message" name="message" rows="5"><?php echo $message; ?></textarea>
            <span id="erreur-message" class="msg-erreur"></span>
        </div>

        <button type="submit" class="btn">Envoyer</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formContact");

    if (form) {
        form.addEventListener("submit", function (e) {
            let estValide = true;

            const nomEl = document.getElementById("nom");
            const emailEl = document.getElementById("email");
            const messageEl = document.getElementById("message");

            const errNom = document.getElementById("erreur-nom");
            const errEmail = document.getElementById("erreur-email");
            const errMessage = document.getElementById("erreur-message");

            
            [nomEl, emailEl, messageEl].forEach(el => el.classList.remove("input-erreur"));
            [errNom, errEmail, errMessage].forEach(el => el.textContent = "");

            
            if (nomEl.value.trim() === "") {
                nomEl.classList.add("input-erreur");
                errNom.textContent = "Le nom est requis";
                estValide = false;
            }

            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailEl.value.trim() === "") {
                emailEl.classList.add("input-erreur");
                errEmail.textContent = "L'adresse email est requise";
                estValide = false;
            } else if (!emailRegex.test(emailEl.value.trim())) {
                emailEl.classList.add("input-erreur");
                errEmail.textContent = "L'adresse email n'est pas valide";
                estValide = false;
            }

            
            if (messageEl.value.trim() === "") {
                messageEl.classList.add("input-erreur");
                errMessage.textContent = "Le message est requis";
                estValide = false;
            }

            
            if (!estValide) {
                e.preventDefault();
            }
        });
    }
});
</script>

<?php include 'footer.php'; ?>