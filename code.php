<?php
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ./connexion.php");
    exit();
}

// Informations de connexion à la base de données
$serveur = "localhost";
$nomutilisateur = "root";
$motdepasse = "";
$basededonnees = "love";

// Connexion à la base de données 
try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$basededonnees", $nomutilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}

// Vérification du paiement de l'utilisateur
$id_utilisateur = $_SESSION['id_utilisateur'];
$requete = $connexion->prepare("SELECT * FROM paiement WHERE id_utilisateur = :id_utilisateur");
$requete->bindParam(':id_utilisateur', $id_utilisateur);
$requete->execute();
$paiement = $requete->fetch(PDO::FETCH_ASSOC);

if (!$paiement) {
    // Rediriger vers la page de paiement si aucun paiement n'a été effectué
    header("Location: ./new.php");
    exit();
}

// Récupération du nom de l'utilisateur
$requete = $connexion->prepare("SELECT nom, prenoms FROM utilisateurs WHERE id = :id_utilisateur");
$requete->bindParam(':id_utilisateur', $id_utilisateur);
$requete->execute();
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de QR Code</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <style>
       

        #qrcode img {
            width: 100%;
            height: auto;
        }

        #qrcode:after {
            padding: 10px;
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30px; 
        }


        body {
           

            font-family: Arial, sans-serif;
    background-color: #f7f7f7; 
    margin: 0;
    padding: 0;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
    background-image: url('./moi.jpeg'); 
    background-size: cover;
    background-position: center;
        }
        h2 {
            color: white;
            text-align: center;
        }
        #qrcode-container {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            max-width: 300px; 
    margin: 0 auto;
        }
        #qrcode {
    width: 100%;
    max-width: 300px;
    max-height: 300px;
    margin: 20px auto;
}
        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        button:hover {
            background-color: #c0392b;
        }
        #download-btn {
            margin-top: 10px;
        }
        .heart {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('./coeur.jpeg');
            background-size: contain;
            animation: heartAnimation 1s infinite;
            z-index: 0;
        }
        @keyframes heartAnimation {
    0% {
        transform: scale(1) rotate(0deg);
    }
    10% {
        transform: scale(3) rotate(0deg);
    }
    20% {
        transform: scale(1) rotate(0deg);
    }
    30% {
        transform: scale(3) rotate(0deg);
    }
    40% {
        transform: scale(1) rotate(0deg);
    }
    60% {
        transform: scale(1) rotate(0deg);
    }
    70% {
        transform: scale(3) rotate(0deg);
    }
    80% {
        transform: scale(1) rotate(0deg);
    }
    90% {
        transform: scale(3) rotate(deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
    }
}
    </style>
</head>
<body>
    <h2>Bonjour <?php echo $utilisateur['nom'] . ' ' . $utilisateur['prenoms']; ?>,</h2>
    <div id="qrcode-container">
        <form id="qr-form">
            <label for="message">Entrez votre URL ou texte :</label><br>
            <input type="text" id="message"><br>
            
            <button type="submit">Générer QR Code</button>
        </form>
        <div id="qrcode"></div>
        <button id="download-btn" style="display: none; position: absolute; left: 0; width: 100%; z-index: 999;">Télécharger QR Code</button>
    </div>
    <div class="heart" style="top: 4%; left: 0%;"></div>
    <div class="heart" style="top: 0%; left: 90%;"></div>
    <div class="heart" style="top: 90%; left: 4%;"></div>
    <div class="heart" style="top: 80%; left: 90%;"></div>
    <script>
        document.querySelector('#qr-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var qrcode = new QRCode("qrcode");
            function makeCode(){
                var text = document.getElementById("message").value;
                if(!text){
                    alert("Erreur : veuillez entrer une URL ou un texte.");
                    document.getElementById("message").focus();
                    return;
                }
                qrcode.makeCode(text);
                document.getElementById("download-btn").style.display = "block"; 
            }
            makeCode();
            $("#message").on("blur", function(){
                makeCode();
            }).on("keydown", function(e){
                if(e.key === "Enter"){
                    makeCode();
                }
            });
        });

        document.getElementById("download-btn").addEventListener("click", function() {
            var canvas = document.querySelector("canvas");
            var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
            var link = document.createElement("a");
            link.setAttribute("href", image);
            link.setAttribute("download", "qrcode.png");
            link.click();
        });
    </script>
</body>
</html>
