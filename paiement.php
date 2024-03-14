<?php
// Démarre la session PHP
session_start();

// Informations de connexion à la base de données
$servername = 'Localhost';
$username = 'Lissan';
$password = '';
$database = 'madei';
try {
    // Crée une connexion à la base de données en utilisant PDO
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Définir le mode d'erreur de PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_adhesion = $_POST["type_adhesion"];
    $_SESSION["type_adhesion"] = $type_adhesion;
    $montant = ($type_adhesion == "paiement_annuel") ? 5000 : 150; // En francs CFA


       
    }
    

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: url("vegan.jpg");
        }
        button.kkiapay-button {
            background-color: #0095ff;
            color: white;
            padding: 10px 20px;
            border:none;
            border-radius: 4px;
            cursor: pointer;
        }
        button.kkiapay-button:hover {
            background-color: #0077cc;
        }
    </style>
</head>
<body>
<form action="" method="post" id="payment-form">
        <label for="type_adhesion">Type d'adhésion :</label>
        <select name="type_adhesion">
            <option value="paiement_article">Paiement par article (1,50 €)</option>
            <option value="paiement_annuel">Paiement annuel (50,00 €)</option>
        
        
        </select>
        <button type="submit">OK</button>
        
</form>
    <button id="pay-button" class="kkiapay-button">Payer 1300FCFA</button>
    <script amount="<?php echo $montant; ?>"
            callback="part4.html"
            position="right"
            theme="#0095ff"
            sandbox="true"    
            key="66b5bd0011c511eea310b7029dff9cca"
            src="https://cdn.kkiapay.me/k.js">
        
        

        </script>
</body>
</html>