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
    $nom = $_POST['nom'];

    // Vérification si l'utilisateur existe déjà
    $requete_verification = $connexion->prepare("SELECT COUNT(*) AS count FROM utilisateurs WHERE nom = :nom");
    $requete_verification->bindParam(':nom', $nom);
    $requete_verification->execute();
    $resultat = $requete_verification->fetch(PDO::FETCH_ASSOC);

    if ($resultat['count'] > 0) {
        echo "Ce nom d'utilisateur est déjà pris. Veuillez choisir un autre.";
    }else{
    $nom = $_POST['nom'];
    $prenoms = $_POST['prenoms'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

    // Vérification que les mots de passe correspondent
    if ($mot_de_passe === $confirmation_mot_de_passe) {
        // Hachage du mot de passe
        $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Requête d'insertion dans la table utilisateurs
        $requete = $connexion->prepare("INSERT INTO utilisateurs (nom, prenoms, mot_de_passe, confirmation_mot_de_passe) VALUES (:nom, :prenoms, :mot_de_passe, :confirmation_mot_de_passe)");

        // Liaison des paramètres
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':prenoms', $prenoms);
        $requete->bindParam(':mot_de_passe', $mot_de_passe_hache);
        $requete->bindParam(':confirmation_mot_de_passe', $confirmation_mot_de_passe);

        // Exécution de la requête
        try {
            $requete->execute();
            echo "Inscription réussie !";
            header("Location: connexion.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'inscription: " . $e->getMessage();
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
            color: #e74c3c;
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
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nom">Nom:</label><br>
        <input type="text" id="nom" name="nom" required><br>
        <label for="prenoms">Prénoms:</label><br>
        <input type="text" id="prenoms" name="prenoms" required><br>
        <label for="mot_de_passe">Mot de passe:</label><br>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>
        <label for="confirmation_mot_de_passe">Confirmation du mot de passe:</label><br>
        <input type="password" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
