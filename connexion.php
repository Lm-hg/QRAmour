<?php
session_start();

// Informations de connexion à la base de données
$serveur = "localhost";
$nomutilisateur = "root";
$motdepasse = "";
$basededonnees = "love";
// Connexion à la base de données via PDO
try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$basededonnees", $nomutilisateur, $motdepasse);
    // Configuration pour afficher les erreurs PDO
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête pour récupérer le mot de passe associé à l'utilisateur
    $requete = $connexion->prepare("SELECT id, mot_de_passe FROM utilisateurs WHERE nom = :nom_utilisateur");
    $requete->bindParam(':nom_utilisateur', $nom_utilisateur);
    $requete->execute();
    $resultat = $requete->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe et si le mot de passe est correct
    if ($resultat && password_verify($mot_de_passe, $resultat['mot_de_passe'])) {
        session_start();
        $_SESSION['id_utilisateur'] = $resultat['id'];
        // Redirection vers la page de profil ou toute autre page après connexion réussie
        header("Location: ./code.php");
        exit();
    } else {
        echo "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h2 {
            color: #3498db;
            text-align: center;
        }

        form {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 12px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nom_utilisateur">Nom d'utilisateur:</label><br>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required><br>
        <label for="mot_de_passe">Mot de passe:</label><br>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
    <p>Vous n'avez pas de compte ? <a href="./inscription.php">Inscrivez-vous ici</a>.</p>

</body>
</html>
