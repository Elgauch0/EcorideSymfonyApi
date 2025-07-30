# 🚴 EcoRideSymfonyApi

Application back-end Symfony pour la gestion de trajets écoresponsables (covoiturage). Ce projet utilise Docker et Doctrine ORM pour la persistance des données.

---

## ✨ Fonctionnalités Principales

- Gestion des utilisateurs (inscription, connexion, profil)
- Création et recherche de trajets de covoiturage
- Réservation et annulation de places sur un trajet
- Historique des trajets pour les utilisateurs
- API RESTful pour l'intégration avec des applications front-end ou mobiles

---

## ⚙️ Prérequis

- **Docker & Docker Compose** : Essentiels pour lancer l'environnement de développement.
- **Git** : Pour cloner le dépôt.
- **Composer** : Pour installer les dépendances PHP.

---

## 🚀 Installation Rapide

Suivez ces étapes pour démarrer l'API EcoRide :

1.  **Cloner le dépôt** :

    ```bash
    git clone [https://github.com/Elgauch0/EcorideSymfonyApi.git](https://github.com/Elgauch0/EcorideSymfonyApi.git)
    cd EcorideSymfonyApi
    ```

2.  **Configuration de l'environnement** :

    ```bash
    cp .env.example .env
    ```

    _Vérifiez le fichier `.env` pour ajuster les configurations de base de données si nécessaire, bien que les valeurs par défaut soient prévues pour fonctionner avec Docker._

3.  **Installer les dépendances Composer** :

    ```bash
    composer install
    ```

4.  **Démarrer les services Docker** :
    ```bash
    docker-compose up -d
    ```
    _Cela construira les images Docker et démarrera les conteneurs ._

---

## 💾 Configuration de la Base de Données

Une fois les conteneurs Docker démarrés, exécutez les commandes suivantes pour configurer la base de données :

1.  **Créer la base de données** :

    ```bash
    docker exec -it Ecoride php bin/console doctrine:database:create
    ```

2.  **Exécuter les migrations** :

    ```bash
    docker exec -it Ecoride php bin/console doctrine:migrations:migrate
    ```

3.  **Charger les données de test (fixtures)** :
    ```bash
    docker exec -it Ecoride php bin/console doctrine:fixtures:load
    ```
    _Cette commande va peupler la base de données avec des données d'exemple pour faciliter le développement et les tests._

---

## 🌐 Accès à l'API

L'API sera accessible via `http://localhost:8000` .

---

## 🧪 Exécuter les Tests

Pour lancer les tests PHPUnit du projet :

```bash
docker exec -it Ecoride   APP_ENV=test vendor/bin/phpunit --testdox
```
