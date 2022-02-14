# Créations d'un controller : 
$ symfony console make:controller

Un controller c'est quoi ? 
Un controller permet d'afficher nos pages , on peut donc faire passer des variables et les intégrer à la vue .
Ces donc dans ce fichier qu'on réalise nos routes .  
Créer egalement dans le dossier 'template' la vue associer

# Créations d'une entité : 
$ symfony console make:entity 

Une entité c'est quoi ? 
Une entités represente ma table dans notre exemple "user" 
Que l'on devra migrer vers notre base de données 

vous pouvez également mettre a jour l'entités User par exemple pour ajouter
un "firstname" en utilisant la meme commande et le nom de classe a mettre a jour
et ne pas oublier de migrer la mise a jour de l'entité 

--------------------------------------------

# Le dossier "Repository" :
Centralise tous ce qui touche a la récupération des entités 

--------------------------------------------

--------------------------------------------
# Configuration de notre base de données Migration 

La connexion à la base de données s'effectue dans le fichier '.env' pour notre exemple nous utiliserons mysql : 
*DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"*

# Création de notre base de données a partir de symfony 
* /!\ Pour effectuer cette commande assurez-vous de bien configurer le ficher '.env' pour la connexion à la base de données  /!\ **

$  symfony console doctrine:database:create

# Migrer vers la base de données : 
$  symfony console make:migration

Cette commande nous a créer un fichier "Version20220124135022.php" qui est dans notre dossier "Migrations" qui contient notre requetes et qui schematise notre entités, une fois le fichier créer, executer cette commande : 

$ php bin/console doctrine:migrations:migrate

Et la magie opérent regarder dans votre base de données la table User a etait creer ! 
--------------------------------------------
|
--------------------------------------------
# Créer un formulaire d'inscription pour nos utilisateur : 

On crée un controller 'register' pour la vue du formulaire ainsi que la route : 
$ symfony console make:controller 

On crée ensuite notre formulaire avec la commande suivante : 

$ symfony console make:form 

Il faudra rentrer le nom de la class  'Register' 
Ensuite de quel entity nous voulons nous enregistrez donc l'entity 'User'

Un dossier a était générer portant le nom de "form" avec un fichier 'registerType'
Qui contient alors notre formulaire . 


Pour ajouter du style a nos formulaire rendez vous dans "config/packages/twig.yaml"
et copier  : 
*form_themes: ['bootstrap_5_layout.html.twig']*

Pour mettre symfony en francais (Peut etre utile si vous avez des erreurs de saisie aupres des formulaire) : 
Allez dans le dossier config/package/translation.yaml
--------------------------------------------
|
--------------------------------------------
# Création de formulaire de connexion :

symfony console make:auth

--------------------------------------------
# CREATION DE L'administration 

$ composer require easycorp/easyadmin-bundle

Mettre en place le "dashboard" 
-
$ symfony console make:admin:dashboard 
--------------------------------------------
|
--------------------------------------------
DEBUG SYMFONY : 
Permet de tcheker les routes associer a notre projet . 
$  symfony console debug:router
--------------------------------------------
|
--------------------------------------------
 Controller l'accés a des routes ou l'utilisateur doit etre connecter 

Les roles pour chaque utilisateur s'effectue dans le dossier "security.yaml" 
acces_controle:
        - {path:/^admin roles}
        - etc...


--------------------------------------------
|
--------------------------------------------
Installer "Stripe" qui est un moyen de paiement qui sera installer partir de "composer" : 

Installation de la librairie : 
composer require stripe/stripe-php

On copie notre clé Api dan 'order' pour la récuperation des données donc du panier : 
sk_test_51KO0jGKxPRXSB4TZ95ESqBjUDLkBV0EHxyb5txchSNwu6cfJ7tj23NvVQNmGpD43KQw56n7NiTbMd6kqT0XyqeLQ00BBUZHEaK

--------------------------------------------
|
--------------------------------------------

Envoie de mail avec "Mailjet" qui est un moyen d'envoyer des mails a partir de notre site . 

Installation de la libraire a partir de composer :
$ composer require mailjet/mailjet-apiv3-php 

--------------------------------------------
|
--------------------------------------------
Gerer les erreurs symfony (Exception,404 etc ...)

Allez dans le fichier '.env' de l'application et modifier "APP_ENV = dev" en "APP_ENV=prod "

On installe un package composer : 
composer.phar require symfony/twig-pack

Creation d'un dossier Bundle ainsi qu'un dossier Exception avec un fichier "error.html.twig"


Saissisez cet commande pour vider le cahce de la prod :
$ symfony console cache:clear
















