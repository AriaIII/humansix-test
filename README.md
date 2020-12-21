# humansix-test

J'ai utilisé Symfony 5.2 et MySQL pour ce projet.

## installation du projet

- cloner le repo
- créer et configurer le .env.local avec les identifiants de votre base de données
- ouvrir un terminal dans le dossier du projet
- taper les commandes suivantes :
  - composer install
  - php bin/console doctrine:database:create
  - php bin/console doctrine:migrations:migrate
  - php bin/console doctrine:fixtures:load (crée l'utilisateur admin)
  - php bin/console app:fill-database-with-xml (charge les données de orders.xml en base)

## les pages

- La page de connexion est sur la route : /login
- Elle donne accès après la connexion à la liste des commandes sur la page /order
- On accède au détail d'une commande : /order/{orderId}
- On crée une nouvelle commande : /order/create
Les pages order sont accessibles seulement si on est connecté

## API

J'ai utilisé API Platform - Elle est accessible sur /api. On peut la tester avec Postman par exemple
