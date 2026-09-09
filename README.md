# QR Amour

QR Amour est une application web qui permet de transformer des messages romantiques (texte ou URL) en QR codes téléchargeables, dans une expérience orientée “love tech”.

## Aperçu

- 🌐 **URL de démo** : https://qr.lovestoblog.com  
- 👤 **Accès** : inscription et connexion utilisateur  
- 💳 **Monétisation** : paiement unique avant l’accès au générateur de QR code  
- ❤️ **Usage principal** : partager un message d’amour de façon originale via QR code

## Fonctionnalités

- Page d’accueil de présentation du service
- Création de compte utilisateur
- Connexion sécurisée (mot de passe haché côté serveur)
- Vérification du paiement avant accès à l’outil principal
- Génération de QR code à partir d’un texte ou d’une URL
- Téléchargement du QR code en image PNG

## Stack technique

- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP
- **Base de données** : MySQL (via PDO)
- **Librairies externes** :
  - `qrcodejs` pour la génération de QR codes
  - `jQuery`
  - `Kkiapay` (widget de paiement)

## Structure du projet

- `/index.html` : page d’accueil
- `/inscription.php` : création de compte
- `/connexion.php` : authentification
- `/new.php` : page de paiement
- `/paie.php` : enregistrement du paiement
- `/code.php` : générateur de QR code (accès après paiement)
- `/css/style.css` : styles principaux

## Installation locale

### Prérequis

- PHP 8+
- MySQL
- Serveur local (XAMPP, WAMP, MAMP, ou équivalent)

### Étapes

1. Cloner le dépôt.
2. Copier le projet dans le répertoire servi par votre serveur web local.
3. Créer une base MySQL nommée `love`.
4. Créer les tables nécessaires (utilisateurs, paiement) selon la logique du code.
5. Mettre à jour les paramètres de connexion MySQL dans les fichiers PHP si nécessaire (`localhost`, `root`, mot de passe, nom de base).
6. Ouvrir `index.html` dans le navigateur via votre serveur local.

## Déploiement

Le projet est déployé sur InfinityFree :  
https://qr.lovestoblog.com

## Auteur

Projet développé par **Lm-hg**.
