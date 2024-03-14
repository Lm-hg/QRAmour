<?php
session_start();


if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ./connexion.php");
    exit();
}
$serveur = "localhost";
$nomutilisateur = "root";
$motdepasse = "";
$basededonnees = "love";
try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$basededonnees", $nomutilisateur, $motdepasse);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}
$li = $_GET['li'];
if($li==1){
$montant = 100;
$date_paiement = date('Y-m-d');
$id_utilisateur = $_SESSION['id_utilisateur']; 

$requete = $connexion->prepare("INSERT INTO paiement (id_utilisateur, montant, date_paiement) VALUES (:id_utilisateur, :montant, :date_paiement)");
$requete->bindParam(':id_utilisateur', $id_utilisateur);
$requete->bindParam(':montant', $montant);
$requete->bindParam(':date_paiement', $date_paiement);
$requete->execute();
if ($requete->execute()) {
    header("Location:./code.php");

    exit();
} else {
    echo "Erreur lors de l'insertion dans la base de données.";
}
}else{
    header("Location:./new.php");
 
}
?>
