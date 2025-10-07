🚴 EcoRideSymfonyApi
Application back-end Symfony gérant des trajets de covoiturage écoresponsables. Elle fournit une API RESTful complète et utilise Docker et Doctrine ORM.

✨ Fonctionnalités Clés
Gestion Utilisateurs : Inscription, connexion, profil.

Trajets : Création, recherche, réservation, et annulation.

Historique : Suivi des trajets passés et futurs.

⚙️ Prérequis
Pour lancer le projet, vous devez disposer des outils suivants :

Docker & Docker Compose

Git

🚀 Démarrage et Configuration
Suivez ces étapes pour rendre l'API opérationnelle :

1. Cloner le Dépôt
   Ouvrez votre terminal et clonez le projet :

Bash

git clone https://github.com/Elgauch0/EcorideSymfonyApi.git
cd EcorideSymfonyApi 2. Démarrer les Services Docker
Lancez les conteneurs de l'API et de la base de données :

Bash

docker compose -f docker-compose.prod.local --env-file .env.example up -d
Ceci construit les images et démarre les services en arrière-plan.

---

3. Initialisation de la Base de Données
   Exécutez les migrations Doctrine dans le conteneur ecoride pour configurer la base de données :

Bash

docker exec -it ecoride php bin/console doctrine:migrations:migrate 4. Création du Compte Administrateur
Créez l'utilisateur administrateur par défaut. N'oubliez pas de remplacer <mot_de_passe> :

Bash

docker exec -it ecoride php bin/console app:create-admin-user <mot_de_passe>
🌐 Accès à l'API
L'API EcoRide est désormais accessible à l'adresse suivante :

http://localhost:8000
