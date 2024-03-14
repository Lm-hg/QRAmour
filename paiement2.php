<?php
session_start();
define('STRIPE_SECRET_KEY', 'secret');

require_once './vendor/autoload.php'; 

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
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentMethodId = $_POST['payment_method'];
    try {
       

        
        $return_url = 'http://localhost/tests/code-d-amour/code.php';

        $paymentIntent = \Stripe\PaymentIntent::create([
            'payment_method' => $paymentMethodId,
            'amount' => 50, 
            'currency' => 'eur',
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => $return_url,
        ]);

        if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
            header("Location:./paiement.php");

            exit();
        } elseif ($paymentIntent->status === 'succeeded') {
            $montant = 0.5;
            $date_paiement = date('Y-m-d');
            $id_utilisateur = $_SESSION['id_utilisateur']; 

            $requete = $connexion->prepare("INSERT INTO paiement (id_utilisateur, montant, date_paiement) VALUES (:id_utilisateur, :montant, :date_paiement)");
            $requete->bindParam(':id_utilisateur', $id_utilisateur);
            $requete->bindParam(':montant', $montant);
            $requete->bindParam(':date_paiement', $date_paiement);
            $requete->execute();
            if ($requete->execute()) {
                //header("Location:" . $return_url . "?payment_intent_id=" . $paymentIntent->id);
                header("Location:./code.php");

                exit();
            } else {
                echo "Erreur lors de l'insertion dans la base de données.";
            }
        } else {
            header("Location:./paiement.php");
            exit();
        }

       

    } catch (\Stripe\Exception\CardException $e) {
        echo "Erreur de carte : " . $e->getMessage();
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        echo "Erreur de requête : " . $e->getMessage();
    } catch (\Stripe\Exception\AuthenticationException $e) {
        echo "Erreur d'authentification : " . $e->getMessage();
    } catch (\Stripe\Exception\ApiConnectionException $e) {
        echo "Erreur de connexion API : " . $e->getMessage();
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Erreur API : " . $e->getMessage();
    } catch (Exception $e) {
         echo "Erreur inattendue : " . $e->getMessage();
     }
   
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    animation: fadeIn 0.8s ease-out;
}

h2 {
    color: #333;
    animation: fadeInUp 0.8s ease-out;
}

form {
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    animation: fadeIn 1s ease-out;
}

select,
#card-element,
#submit-button {
    width: 100%;
    padding: 10px;
    margin-bottom: 16px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 4px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease-out forwards;
}

#card-errors {
    color: #ff0000;
    margin-bottom: 16px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease-out forwards;
}

#submit-button {
    background-color: #007BFF;
    color: #fff;
    cursor: pointer;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease-out forwards;
}

#submit-button:hover {
    background-color: #0056b3;
}


@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>Page de paiement</h2>
    <form action="" method="post" id="payment-form">
        <div class="form-row">
            <label for="card-element">
                Entrez les détails de votre carte de crédit
            </label>
            <div id="card-element">
            </div>

            <div id="card-errors" role="alert"></div>
        </div>

        <button type="submit">Payer 0.5 Euro</button>
    </form>


    <script>
        var stripe = Stripe('secret'); 
        var elements = stripe.elements();
        var cardElement = elements.create('card');

        cardElement.mount('#card-element');

        var cardErrors = document.getElementById('card-errors');

        cardElement.addEventListener('change', function(event) {
            if (event.error) {
                cardErrors.textContent = event.error.message;
            } else {
                cardErrors.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                // billing_details:{
                //     name:cardholder,
                //     email:email
                // }
            }).then(function(result) {
                if (result.error) {
                    cardErrors.textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.setAttribute('value', result.paymentMethod.id);
                    form.appendChild(hiddenInput);

                    form.submit();
                }
            });
        });
    </script>

    
</body>
</html>
