# Veloura - Boutique de chocolats artisanaux

## Description
Veloura est une application web PHP/MySQL pour la vente de chocolats artisanaux et de box personnalisées.
Le projet propose un espace client (navigation, boutique, commande) et un espace administrateur (gestion des produits, box et commandes).

## Fonctionnalités principales
- Authentification des utilisateurs (inscription, connexion, déconnexion)
- Gestion des rôles (`admin` et `client`)
- Accès client: accueil, boutique, box, formulaire de commande
- Accès admin: tableau de bord, gestion des produits, gestion des box, suivi des commandes
- Protection CSRF sur les formulaires sensibles
- Initialisation automatique de données de démonstration

## Stack technique
- Backend: PHP (procédural)
- Base de données: MySQL (MariaDB via XAMPP)
- Frontend: HTML, CSS, JavaScript

## Prérequis
- XAMPP (Apache + MySQL)
- PHP 8.x recommandé
- Navigateur web récent

## Installation locale
1. Copier le projet dans le dossier `htdocs` de XAMPP:
   - `C:\xampp\htdocs\Projet_veloura`
2. Démarrer `Apache` et `MySQL` depuis le panneau XAMPP.
3. Créer la base de données:
   - Importer le fichier `database.sql` dans phpMyAdmin
   - Ou laisser l'initialisation automatique créer les tables au premier chargement
4. Ouvrir le projet dans le navigateur:
   - `http://localhost/Projet_veloura/veloura.php`

## Compte administrateur (démo)
- Email: `admin@veloura.tn`
- Mot de passe: `admin123`

## Arborescence du projet
```text
Projet_veloura/
├── veloura.php
├── accueil.php
├── inscription.php
├── login.php
├── logout.php
├── auth.php
├── btq.php
├── box.php
├── commande.php
├── cmdT.php
├── shop.js
├── commande.js
├── admin.php
├── admin_produits.php
├── admin_box.php
├── admin_commandes.php
├── admin.css
├── admin.js
├── style.css
├── db.php
├── database.sql
└── (images *.jpeg, *.jpg)
```

## Arborescence utile
- `veloura.php`: page d'entrée (connexion)
- `inscription.php`: création de compte
- `accueil.php`: accueil client
- `btq.php`: boutique produits
- `box.php`: section box
- `commande.php` / `cmdT.php`: passage et enregistrement des commandes
- `admin.php`: dashboard administrateur
- `admin_produits.php`: gestion produits
- `admin_box.php`: gestion box
- `admin_commandes.php`: gestion commandes
- `db.php`: connexion MySQL + seed de données
- `auth.php`: fonctions d'authentification et protections
- `database.sql`: schéma de base de données

## Base de données
Tables principales:
- `users`
- `produits`
- `box`
- `commandes`

## Auteur
Projet réalisé pour la plateforme Veloura.
