<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de la valeur actuelle de $compteur depuis la session
    $compteur = 0;

    // Intégration du widget de paiement Kkiapay avec JavaScript
    $script = '
        document.getElementById("forme").addEventListener("submit", function(event) {
            event.preventDefault();
            var amount = 100;
            var compteur = ' . $compteur . ';
            
            openKkiapayWidget({
                amount: amount,
                position: "center",
                theme: "#0095ff",
                sandbox: true,
                key: "66b5bd0011c511eea310b7029dff9cca"
            });

            addSuccessListener(response => {
                console.log(response);
                compteur++;
                var li=1;
                window.location.href = "./paie.php?li=" + li;
            });

            addFailedListener(error => {
                console.log(error);
            });
        });
    ';

    
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.kkiapay.me/k.js"></script>
</head>
<body>
<form action="" method="POST" id="forme">
   <input type="text" name="" id="  " value="100FCFA">
    <button type="submit">Payer</button>
</form>
</body>
<script><?php echo $script; ?></script>

</html>
