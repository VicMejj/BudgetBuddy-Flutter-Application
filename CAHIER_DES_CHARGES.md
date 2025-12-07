# 📋 Cahier des Charges - Budget Buddy

## 1. Description du Projet

**Budget Buddy** est une application mobile de gestion budgétaire développée avec Flutter pour le frontend et Laravel pour le backend. L'application permet aux utilisateurs de suivre leurs revenus et dépenses, de gérer leurs transactions financières, de convertir des devises, et offre aux administrateurs des outils de gestion des utilisateurs.

### 1.1 Contexte
L'application répond au besoin de gestion financière personnelle en offrant une solution moderne, intuitive et sécurisée pour suivre les transactions quotidiennes et analyser les habitudes de dépenses.

### 1.2 Portée du Projet
- Application mobile multiplateforme (Android/iOS)
- API REST sécurisée avec authentification JWT
- Gestion des transactions financières multi-devises
- Tableau de bord administrateur
- Système de conversion de devises en temps réel

---

## 2. Objectifs Fonctionnels

### 2.1 Authentification et Gestion des Utilisateurs
- ✅ Inscription de nouveaux utilisateurs avec validation
- ✅ Connexion sécurisée avec JWT
- ✅ Gestion de session utilisateur
- ✅ Déconnexion
- ✅ Rôles utilisateur (User/Admin)

### 2.2 Gestion des Transactions
- ✅ Création de transactions (revenus/dépenses)
- ✅ Affichage de la liste des transactions
- ✅ Catégorisation des transactions
- ✅ Filtrage par type (revenu/dépense)
- ✅ Affichage du solde total
- ✅ Support multi-devises (USD, EUR, TND, etc.)

### 2.3 Gestion des Catégories
- ✅ Liste des catégories disponibles
- ✅ Catégories par type (revenu/dépense)
- ✅ Sélection de catégorie lors de l'ajout de transaction

### 2.4 Conversion de Devises
- ✅ Conversion entre différentes devises
- ✅ Affichage du taux de change
- ✅ Liste des devises disponibles
- ✅ Interface intuitive pour la conversion

### 2.5 Tableau de Bord Utilisateur
- ✅ Affichage du solde total
- ✅ Statistiques de revenus et dépenses
- ✅ Liste des transactions récentes
- ✅ Navigation vers les fonctionnalités principales

### 2.6 Tableau de Bord Administrateur
- ✅ Vue d'ensemble des utilisateurs
- ✅ Statistiques (total utilisateurs, actifs, mutés, bannis)
- ✅ Gestion des utilisateurs (mute, ban, activation, suppression)
- ✅ Interface de modération

---

## 3. Objectifs Non Fonctionnels

### 3.1 Performance
- Temps de chargement des écrans < 2 secondes
- Réponses API < 500ms pour les opérations standards
- Support de 100+ transactions par utilisateur sans dégradation

### 3.2 Sécurité
- Authentification JWT sécurisée
- Validation des données côté serveur et client
- Protection CSRF
- Hashage des mots de passe (bcrypt)
- Gestion des rôles et permissions

### 3.3 Utilisabilité
- Interface intuitive et moderne
- Navigation fluide entre les écrans
- Messages d'erreur clairs et informatifs
- Design responsive et adaptatif
- Feedback visuel pour toutes les actions

### 3.4 Maintenabilité
- Code structuré et modulaire
- Séparation claire frontend/backend
- Documentation du code
- Gestion d'erreurs robuste
- Logs pour le débogage

### 3.5 Compatibilité
- Support Android (API 21+)
- Support iOS (iOS 12+)
- Compatibilité avec différentes tailles d'écran
- Support de plusieurs langues de devises

### 3.6 Fiabilité
- Gestion des erreurs réseau
- Retry automatique pour les requêtes échouées
- Validation des données avant envoi
- Sauvegarde locale des préférences utilisateur

---

## 4. Technologies Utilisées

### 4.1 Frontend (Flutter)
- **Framework**: Flutter 3.x
- **Langage**: Dart
- **State Management**: Provider
- **HTTP Client**: http package
- **Local Storage**: shared_preferences
- **Date Picker**: intl package
- **UI Components**: Material Design 3

### 4.2 Backend (Laravel)
- **Framework**: Laravel 10.x
- **Langage**: PHP 8.1+
- **Base de données**: MySQL
- **Authentification**: JWT (tymon/jwt-auth)
- **API**: RESTful API
- **Validation**: Laravel Validator
- **Service**: CurrencyService pour conversion de devises

### 4.3 Outils de Développement
- **Version Control**: Git
- **IDE**: VS Code / Android Studio
- **API Testing**: Postman
- **Database**: MySQL / phpMyAdmin

### 4.4 Architecture
- **Pattern**: MVC (Model-View-Controller)
- **API Architecture**: REST
- **State Management**: Provider Pattern
- **Authentication**: JWT Tokens

---

## 5. Planning Initial

### Phase 1 : Setup et Configuration (Semaine 1)
- [x] Configuration de l'environnement de développement
- [x] Initialisation du projet Flutter
- [x] Initialisation du projet Laravel
- [x] Configuration de la base de données
- [x] Configuration de l'authentification JWT

### Phase 2 : Authentification (Semaine 1-2)
- [x] Backend : Routes et contrôleurs d'authentification
- [x] Frontend : Écrans de connexion et inscription
- [x] Gestion des tokens JWT
- [x] Protection des routes API

### Phase 3 : Gestion des Transactions (Semaine 2-3)
- [x] Modèles de données (Transaction, Category)
- [x] CRUD des transactions
- [x] Interface d'ajout de transaction
- [x] Liste des transactions
- [x] Calcul du solde

### Phase 4 : Catégories et Devises (Semaine 3)
- [x] Gestion des catégories
- [x] Service de conversion de devises
- [x] Interface de conversion
- [x] Support multi-devises dans les transactions

### Phase 5 : Tableau de Bord (Semaine 4)
- [x] Dashboard utilisateur
- [x] Statistiques et graphiques
- [x] Dashboard administrateur
- [x] Gestion des utilisateurs

### Phase 6 : Tests et Finalisation (Semaine 4-5)
- [x] Tests des fonctionnalités
- [x] Correction des bugs
- [x] Amélioration de l'UI/UX
- [x] Documentation

---

## 6. Contraintes et Hypothèses

### 6.1 Contraintes Techniques
- Nécessite une connexion Internet pour la synchronisation
- Les taux de change sont basés sur des taux fixes (peut être étendu avec une API externe)
- Base de données MySQL requise pour le backend

### 6.2 Hypothèses
- Les utilisateurs ont accès à Internet
- Les utilisateurs comprennent les concepts de base de gestion budgétaire
- Les administrateurs sont formés à l'utilisation du panel d'administration

### 6.3 Limitations Actuelles
- Conversion de devises avec taux fixes (non en temps réel)
- Pas de synchronisation offline
- Pas d'export de données
- Pas de notifications push

---

## 7. Critères de Réussite

✅ Application fonctionnelle avec toutes les fonctionnalités de base
✅ Interface utilisateur moderne et intuitive
✅ Authentification sécurisée opérationnelle
✅ Gestion complète des transactions
✅ Conversion de devises fonctionnelle
✅ Panel administrateur opérationnel
✅ Code propre et bien structuré
✅ Documentation complète

---

**Date de création**: Décembre 2025
**Version**: 1.0
**Statut**: ✅ Projet Terminé

