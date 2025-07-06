# Introduction

API complète pour la gestion des dons de sang, banques de sang, urgences et géolocalisation.

<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>

# API BloodBank

Cette API permet de gérer complètement une plateforme de dons de sang avec :

- **Authentification** et gestion des rôles
- **Gestion des banques de sang** avec géolocalisation
- **Dons de sang** et suivi des stocks
- **Demandes d'urgence** avec notifications automatiques
- **Partenariats** entre banques
- **Système de contact** et notifications

## Rôles utilisateurs

- **Admin** : Accès complet à toutes les fonctionnalités
- **Blood Bank** : Gestion de sa banque de sang
- **Donor** : Donneur de sang
- **Doctor** : Médecin pouvant faire des demandes

## Authentification

L'API utilise Laravel Sanctum pour l'authentification. Incluez le token Bearer dans l'en-tête Authorization.

