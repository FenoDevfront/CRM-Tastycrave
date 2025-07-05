# GEMINI - Application CRM de Gestion de Produits

## 🌟 Objectif

Développer une application web CRM simple, moderne et sécurisée pour la gestion de produits GM et PM. Elle sera utilisée en local via XAMPP, avec possibilité d'accès à plusieurs utilisateurs via l’IP locale. L’authentification Google sera requise pour accéder à toutes les routes.

---

## 📦 Produits à gérer

L'application doit permettre la gestion de 2 **familles de produits** :
- **GM**
- **PM**

Chaque produit peut avoir un **type** parmi les suivants :
- **Épicée**
- **Pimentée**
- **Nature**

---

## 👥 Rôles

- **Administrateur** : 2 comptes admin maximum.
  - Accès complet à l'application.
  - Gestion de tous les produits.

Aucun rôle public ou intermédiaire n'est prévu.

---

## 💻 Technologies

- **Framework backend** : Laravel (PHP)
- **Frontend** : Bootstrap (design moderne, premium, épuré)
- **Base de données** : MySQL via XAMPP (en local)
- **Authentification** : Google OAuth2 via Laravel Socialite
- **Hébergement local** : accès multi-utilisateurs via IP locale

---

## 🛡️ Sécurité

- Toutes les routes sont **protégées par authentification Google**.
- Aucune partie de l'application n'est accessible sans connexion.
- Possibilité de restreindre à certains comptes Gmail si nécessaire.

---

## 🎨 Design & UX

- UI moderne, premium et épurée
- Interface fluide, responsive, adaptée aux différents écrans
- Utilisation soignée de Bootstrap (boutons, tableaux, formulaires)

---

## 🗂️ Fonctionnalités attendues

### 1. Authentification
- Connexion uniquement via Google
- Redirection automatique vers le tableau de bord après connexion

### 2. Tableau de bord
- Vue globale des produits GM et PM
- Statistiques par type : Épicée / Pimentée / Nature

### 3. Gestion des produits
- Ajouter, modifier, supprimer un produit GM ou PM
- Attribuer un type (Épicée, Pimentée, Nature)
- Rechercher et filtrer par famille ou type

### 4. Configuration réseau
- Affichage de l’adresse IP locale de la machine
- Instructions pour que d'autres utilisateurs sur le même réseau puissent y accéder via cette IP

---

## ⚙️ Déploiement local

1. Cloner le projet Laravel.
2. Installer les dépendances avec Composer et NPM.
3. Créer la base de données `gemini_db` via phpMyAdmin (XAMPP).
4. Configurer le fichier `.env` :
   ```env
   DB_DATABASE=gemini_db
   DB_USERNAME=root
   DB_PASSWORD=

   GOOGLE_CLIENT_ID=568227708389-a7fu2j0rf4hi5iu4oh5gi2kbvd4g0kos.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=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
