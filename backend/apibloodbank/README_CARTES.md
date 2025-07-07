# 🗺️ Cartes Interactives des Banques de Sang

Ce document explique comment utiliser les cartes interactives pour visualiser et localiser les banques de sang.

## 📍 Pages Disponibles

### 1. Carte Interactive avec Recherche
**URL :** `http://localhost:8000/map.html`

**Fonctionnalités :**
- 🔍 Recherche par adresse ou ville
- 📏 Rayon de recherche configurable (10 à 200 km)
- 🩸 Filtrage par type de sang
- 📊 Statistiques en temps réel
- 🎯 Géocodage automatique des adresses

### 2. Carte de Toutes les Banques
**URL :** `http://localhost:8000/all-banks-map.html`

**Fonctionnalités :**
- 🏥 Affichage de toutes les banques de sang
- 🎨 Marqueurs colorés selon le statut
- 📈 Statistiques globales
- 🔍 Zoom et navigation libre

## 🚀 Comment Utiliser

### Carte Interactive avec Recherche

1. **Ouvrir la page** : Accédez à `http://localhost:8000/map.html`

2. **Entrer une adresse** : 
   - Tapez une adresse (ex: "Paris, France")
   - Ou une ville (ex: "Marseille")

3. **Configurer la recherche** :
   - **Rayon** : Choisissez la distance (10, 25, 50, 100, 200 km)
   - **Type de sang** : Optionnel, filtrez par groupe sanguin

4. **Lancer la recherche** : Cliquez sur "🔍 Rechercher"

5. **Résultats** :
   - La carte se centre sur votre position
   - Un cercle rouge montre le rayon de recherche
   - Les banques apparaissent avec des marqueurs
   - Cliquez sur un marqueur pour voir les détails

### Carte de Toutes les Banques

1. **Ouvrir la page** : Accédez à `http://localhost:8000/all-banks-map.html`

2. **Navigation** :
   - La carte s'ajuste automatiquement pour voir toutes les banques
   - Utilisez la souris pour zoomer/dézoomer
   - Cliquez et glissez pour vous déplacer

3. **Légende** :
   - **Bleu** : Banque de sang standard
   - **Vert** : Banque vérifiée
   - **Jaune** : Banque en attente de vérification

## 🎨 Fonctionnalités des Cartes

### Marqueurs Interactifs
- **Clic** : Affiche les détails de la banque
- **Informations** : Nom, adresse, téléphone, email, site web
- **Statut** : Vérifiée ou en attente
- **Niveau de partenariat** : Public, privé, etc.

### Interface Utilisateur
- **Design responsive** : Fonctionne sur mobile et desktop
- **Animations fluides** : Transitions et effets visuels
- **Messages d'état** : Feedback en temps réel
- **Gestion d'erreurs** : Messages informatifs

### Statistiques
- **Nombre de banques trouvées**
- **Pourcentage de couverture GPS**
- **Rayon de recherche actuel**

## 🔧 Configuration Technique

### Prérequis
- Serveur Laravel en cours d'exécution (`php artisan serve`)
- API accessible sur `http://localhost:8000/api`
- Connexion Internet pour les tuiles de carte

### Technologies Utilisées
- **Leaflet.js** : Bibliothèque de cartographie
- **OpenStreetMap** : Données cartographiques
- **Fetch API** : Communication avec l'API Laravel
- **CSS3** : Styles et animations

### Structure des Fichiers
```
public/
├── map.html              # Carte interactive avec recherche
├── all-banks-map.html    # Carte de toutes les banques
└── ...
```

## 📱 Compatibilité

### Navigateurs Supportés
- ✅ Chrome (recommandé)
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ⚠️ Internet Explorer (limité)

### Appareils
- ✅ Desktop (Windows, macOS, Linux)
- ✅ Tablettes
- ✅ Smartphones (responsive design)

## 🛠️ Personnalisation

### Modifier l'Apparence
1. Éditez les fichiers CSS dans les balises `<style>`
2. Changez les couleurs, polices, tailles
3. Ajustez les animations et transitions

### Ajouter des Fonctionnalités
1. Modifiez le JavaScript dans les balises `<script>`
2. Ajoutez de nouveaux endpoints API
3. Intégrez d'autres services de cartographie

### Changer l'API
1. Modifiez `API_BASE_URL` dans le JavaScript
2. Ajustez les endpoints selon votre configuration
3. Mettez à jour les tokens d'authentification

## 🔒 Sécurité

### Authentification
- Les endpoints de recherche nécessitent un token
- Le token de test est inclus dans le code
- En production, utilisez un système d'authentification approprié

### CORS
- Assurez-vous que CORS est configuré sur votre serveur Laravel
- Les requêtes viennent du même domaine (localhost)

## 📊 Données Affichées

### Informations des Banques
- **Nom** : Nom officiel de la banque
- **Adresse** : Adresse complète
- **Coordonnées GPS** : Latitude et longitude
- **Contact** : Téléphone, email, site web
- **Statut** : Vérifiée ou en attente
- **Partenariat** : Niveau de partenariat

### Calculs Automatiques
- **Distance** : Calculée avec la formule de Haversine
- **Rayon de recherche** : Cercle affiché sur la carte
- **Statistiques** : Mises à jour en temps réel

## 🚨 Dépannage

### Problèmes Courants

**La carte ne se charge pas :**
- Vérifiez que le serveur Laravel fonctionne
- Assurez-vous d'avoir une connexion Internet
- Vérifiez la console du navigateur pour les erreurs

**Aucune banque trouvée :**
- Vérifiez que l'API retourne des données
- Testez les endpoints directement
- Vérifiez les tokens d'authentification

**Erreurs de géocodage :**
- Vérifiez la connectivité à OpenStreetMap
- Respectez les limites de taux (1 requête/seconde)
- Utilisez des adresses plus spécifiques

### Logs et Debug
- Ouvrez les outils de développement du navigateur
- Vérifiez l'onglet "Console" pour les erreurs
- Utilisez l'onglet "Network" pour voir les requêtes API

## 📈 Améliorations Futures

### Fonctionnalités Suggérées
- 🧭 Géolocalisation automatique du navigateur
- 🚗 Calcul d'itinéraire vers les banques
- 📱 Application mobile native
- 🔔 Notifications de nouvelles banques
- 📊 Graphiques et analyses avancées
- 🌍 Support multi-langues

### Optimisations Techniques
- 🚀 Mise en cache des données
- 📦 Compression des ressources
- 🔄 Actualisation automatique
- 🎯 Recherche prédictive
- 📍 Marqueurs groupés pour les zones denses

## 📞 Support

Pour toute question ou problème :
1. Vérifiez cette documentation
2. Consultez les logs du serveur Laravel
3. Testez les endpoints API directement
4. Vérifiez la console du navigateur

---

**Note :** Ces cartes utilisent l'API BloodBank et nécessitent que le serveur Laravel soit en cours d'exécution pour fonctionner correctement. 
