# 📝 Backlog - Budget Buddy

## Vue d'Ensemble

Ce backlog présente toutes les fonctionnalités du projet Budget Buddy, organisées par priorité et avec leurs user stories associées.

---

## 🔴 Priorité Haute (Must Have)

### US-001 : Authentification Utilisateur
**En tant que** utilisateur,  
**Je veux** m'inscrire et me connecter à l'application,  
**Afin de** sécuriser mes données financières personnelles.

**Tâches**:
- [x] Backend : Route POST /api/auth/register
- [x] Backend : Route POST /api/auth/login
- [x] Backend : Route POST /api/auth/logout
- [x] Backend : Route GET /api/auth/me
- [x] Frontend : Écran d'inscription
- [x] Frontend : Écran de connexion
- [x] Frontend : Gestion des tokens JWT
- [x] Frontend : Protection des routes

**Critères d'acceptation**:
- L'utilisateur peut s'inscrire avec nom, email et mot de passe
- L'utilisateur peut se connecter avec email/mot de passe
- Le token JWT est stocké et utilisé pour les requêtes
- La session persiste après fermeture de l'app

---

### US-002 : Création de Transaction
**En tant que** utilisateur,  
**Je veux** ajouter des transactions (revenus/dépenses),  
**Afin de** suivre mes finances personnelles.

**Tâches**:
- [x] Backend : Route POST /api/transactions
- [x] Backend : Validation des données
- [x] Frontend : Écran d'ajout de transaction
- [x] Frontend : Sélection de catégorie
- [x] Frontend : Sélection de devise
- [x] Frontend : Validation du formulaire

**Critères d'acceptation**:
- L'utilisateur peut ajouter une transaction avec montant, catégorie, date
- La transaction est sauvegardée dans la base de données
- Le solde est mis à jour automatiquement
- Les erreurs sont affichées clairement

---

### US-003 : Affichage des Transactions
**En tant que** utilisateur,  
**Je veux** voir la liste de mes transactions,  
**Afin de** consulter mon historique financier.

**Tâches**:
- [x] Backend : Route GET /api/transactions
- [x] Backend : Filtrage par utilisateur
- [x] Frontend : Liste des transactions
- [x] Frontend : Affichage du solde total
- [x] Frontend : Distinction revenus/dépenses

**Critères d'acceptation**:
- Toutes les transactions de l'utilisateur sont affichées
- Le solde total est calculé et affiché
- Les transactions sont triées par date
- L'interface est claire et lisible

---

### US-004 : Gestion des Catégories
**En tant que** utilisateur,  
**Je veux** sélectionner une catégorie pour mes transactions,  
**Afin de** organiser mes finances par type.

**Tâches**:
- [x] Backend : Route GET /api/categories
- [x] Backend : Seed des catégories par défaut
- [x] Frontend : Dropdown de sélection de catégorie
- [x] Frontend : Filtrage par type (revenu/dépense)

**Critères d'acceptation**:
- Les catégories sont chargées depuis l'API
- Le dropdown fonctionne correctement
- Les catégories sont filtrées par type de transaction
- Les catégories par défaut sont disponibles

---

## 🟡 Priorité Moyenne (Should Have)

### US-005 : Conversion de Devises
**En tant que** utilisateur,  
**Je veux** convertir des montants entre différentes devises,  
**Afin de** gérer mes finances en devises multiples.

**Tâches**:
- [x] Backend : Route POST /api/currency/convert
- [x] Backend : Route GET /api/currency/list
- [x] Backend : Service de conversion de devises
- [x] Frontend : Écran de conversion
- [x] Frontend : Sélection des devises source et cible
- [x] Frontend : Affichage du résultat et du taux

**Critères d'acceptation**:
- L'utilisateur peut convertir entre différentes devises
- Le taux de change est affiché
- L'interface est intuitive
- Les erreurs sont gérées proprement

---

### US-006 : Tableau de Bord Utilisateur
**En tant que** utilisateur,  
**Je veux** voir un résumé de mes finances,  
**Afin de** avoir une vue d'ensemble rapide.

**Tâches**:
- [x] Frontend : Écran dashboard
- [x] Frontend : Carte de solde total
- [x] Frontend : Statistiques revenus/dépenses
- [x] Frontend : Liste des transactions récentes
- [x] Frontend : Navigation vers les fonctionnalités

**Critères d'acceptation**:
- Le solde total est affiché de manière claire
- Les statistiques sont calculées correctement
- Les 5 dernières transactions sont visibles
- Le design est moderne et attrayant

---

### US-007 : Support Multi-Devises dans les Transactions
**En tant que** utilisateur,  
**Je veux** enregistrer mes transactions dans différentes devises,  
**Afin de** gérer mes finances internationales.

**Tâches**:
- [x] Backend : Champ currency dans les transactions
- [x] Frontend : Sélection de devise lors de l'ajout
- [x] Frontend : Affichage de la devise dans les transactions

**Critères d'acceptation**:
- L'utilisateur peut sélectionner une devise
- La devise est sauvegardée avec la transaction
- La devise est affichée dans la liste

---

## 🟢 Priorité Basse (Nice to Have)

### US-008 : Tableau de Bord Administrateur
**En tant qu'** administrateur,  
**Je veux** gérer les utilisateurs de l'application,  
**Afin de** modérer la plateforme.

**Tâches**:
- [x] Backend : Routes de gestion utilisateurs
- [x] Backend : Système de statuts (actif, muté, banni)
- [x] Frontend : Dashboard administrateur
- [x] Frontend : Liste des utilisateurs
- [x] Frontend : Actions (mute, ban, activate, delete)
- [x] Frontend : Statistiques utilisateurs

**Critères d'acceptation**:
- L'admin peut voir tous les utilisateurs
- L'admin peut modifier le statut des utilisateurs
- Les statistiques sont affichées
- L'interface est claire et fonctionnelle

---

### US-009 : Gestion des Statuts Utilisateurs
**En tant qu'** administrateur,  
**Je veux** pouvoir muter, bannir ou activer des utilisateurs,  
**Afin de** maintenir la qualité de la plateforme.

**Tâches**:
- [x] Backend : Migration pour le champ status
- [x] Backend : Routes POST /api/users/{id}/mute
- [x] Backend : Routes POST /api/users/{id}/ban
- [x] Backend : Routes POST /api/users/{id}/activate
- [x] Frontend : Boutons d'action dans le dashboard admin
- [x] Frontend : Confirmation avant actions destructives

**Critères d'acceptation**:
- Les statuts sont correctement sauvegardés
- Les actions sont réversibles
- Les confirmations sont affichées
- Les erreurs sont gérées

---

### US-010 : Amélioration de l'UI/UX
**En tant qu'** utilisateur,  
**Je veux** une interface moderne et intuitive,  
**Afin de** utiliser l'application facilement.

**Tâches**:
- [x] Design moderne avec Material Design 3
- [x] Thème cohérent (couleur violette)
- [x] Animations et transitions fluides
- [x] Messages d'erreur clairs
- [x] Feedback visuel pour toutes les actions
- [x] Cards et gradients pour un design attrayant

**Critères d'acceptation**:
- L'interface est moderne et professionnelle
- La navigation est intuitive
- Les erreurs sont clairement indiquées
- Le design est cohérent dans toute l'application

---

## 📊 Résumé des Fonctionnalités

### ✅ Fonctionnalités Complétées (10/10)

| ID | Fonctionnalité | Priorité | Statut |
|----|----------------|----------|--------|
| US-001 | Authentification Utilisateur | Haute | ✅ Terminé |
| US-002 | Création de Transaction | Haute | ✅ Terminé |
| US-003 | Affichage des Transactions | Haute | ✅ Terminé |
| US-004 | Gestion des Catégories | Haute | ✅ Terminé |
| US-005 | Conversion de Devises | Moyenne | ✅ Terminé |
| US-006 | Tableau de Bord Utilisateur | Moyenne | ✅ Terminé |
| US-007 | Support Multi-Devises | Moyenne | ✅ Terminé |
| US-008 | Tableau de Bord Administrateur | Basse | ✅ Terminé |
| US-009 | Gestion des Statuts Utilisateurs | Basse | ✅ Terminé |
| US-010 | Amélioration UI/UX | Basse | ✅ Terminé |

---

## 🔮 Fonctionnalités Futures (Non Implémentées)

### US-011 : Export de Données
**En tant qu'** utilisateur,  
**Je veux** exporter mes transactions en PDF/CSV,  
**Afin de** les analyser dans d'autres outils.

### US-012 : Graphiques et Statistiques Avancées
**En tant qu'** utilisateur,  
**Je veux** voir des graphiques de mes dépenses par catégorie,  
**Afin de** mieux comprendre mes habitudes de consommation.

### US-013 : Notifications
**En tant qu'** utilisateur,  
**Je veux** recevoir des notifications pour les transactions importantes,  
**Afin de** rester informé de mes finances.

### US-014 : Mode Offline
**En tant qu'** utilisateur,  
**Je veux** pouvoir ajouter des transactions sans connexion,  
**Afin de** utiliser l'app partout.

### US-015 : Budgets et Alertes
**En tant qu'** utilisateur,  
**Je veux** définir des budgets par catégorie,  
**Afin de** contrôler mes dépenses.

---

## 📈 Métriques de Projet

- **Total User Stories**: 10 complétées
- **Taux de Complétion**: 100% des fonctionnalités prioritaires
- **Temps de Développement**: ~4-5 semaines
- **Lignes de Code**: ~3000+ (Frontend + Backend)

---

**Date de dernière mise à jour**: Décembre 2025  
**Version**: 1.0  
**Statut**: ✅ Projet Terminé

