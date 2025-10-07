markdown# 🚴 EcoRide – Application Complète

Application de covoiturage écoresponsable composée d'un frontend React moderne et d'une API REST Symfony robuste.

> ⚠️ **Important** : Les deux projets (Frontend React et Backend Symfony) doivent être lancés simultanément pour que l'application fonctionne correctement.

---

## 📦 Architecture du Projet

L'application EcoRide est divisée en deux parties :

- **Frontend** : Interface utilisateur développée avec React + Vite
- **Backend** : API REST développée avec Symfony + Docker

---

## 🔧 Backend – EcoRideSymfonyApi

Application back-end Symfony gérant des trajets de covoiturage écoresponsables. Elle fournit une API RESTful complète et utilise Docker et Doctrine ORM.

### ✨ Fonctionnalités Clés

- **Gestion Utilisateurs** : Inscription, connexion, profil
- **Trajets** : Création, recherche, réservation et annulation
- **Historique** : Suivi des trajets passés et futurs

### ⚙️ Prérequis

- Docker & Docker Compose
- Git

### 🚀 Installation & Lancement

#### 1. Cloner le Dépôt

```bash
git clone https://github.com/Elgauch0/EcorideSymfonyApi.git
cd EcorideSymfonyApi
2. Démarrer les Services Docker
bashdocker compose -f docker-compose.prod.local.yml --env-file .env.example up -d
Ceci construit les images et démarre les services en arrière-plan.
3. Initialisation de la Base de Données
bashdocker exec -it ecoride php bin/console doctrine:migrations:migrate
4. Création du Compte Administrateur
Remplacez <mot_de_passe> par votre mot de passe souhaité :
bashdocker exec -it ecoride php bin/console app:create-admin-user <mot_de_passe>
🌐 Accès à l'API
L'API EcoRide est accessible à l'adresse :
http://localhost:8000

💡 Note : Pour utiliser l'application complète avec l'interface utilisateur, vous devez également démarrer le frontend React. Consultez la section Frontend – EcoRide React ci-dessous pour les instructions de lancement.
   → [Dépôt Frontend : EcorideReact](https://github.com/Elgauch0/EcorideReact)
```
