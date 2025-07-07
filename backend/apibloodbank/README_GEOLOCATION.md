# Géolocalisation des Banques de Sang

Ce document explique comment utiliser les fonctionnalités de géolocalisation pour localiser les banques de sang dans l'API BloodBank.

## 🎯 Fonctionnalités Disponibles

### 1. Géocodage d'Adresses
Convertit une adresse en coordonnées GPS (latitude/longitude).

**Endpoint :** `POST /api/geolocation/geocode`

**Exemple d'utilisation :**
```bash
curl -X POST http://localhost:8000/api/geolocation/geocode \
  -H "Content-Type: application/json" \
  -d '{"address": "Paris, France"}'
```

**Réponse :**
```json
{
  "success": true,
  "data": {
    "latitude": 48.8588897,
    "longitude": 2.320041,
    "display_name": "Paris, France métropolitaine, France",
    "address": {
      "city": "Paris",
      "country": "France",
      "country_code": "fr"
    }
  }
}
```

### 2. Recherche de Banques Proches
Trouve les banques de sang dans un rayon donné autour d'un point GPS.

**Endpoint :** `POST /api/geolocation/nearby-banks` (Authentification requise)

**Exemple d'utilisation :**
```bash
curl -X POST http://localhost:8000/api/geolocation/nearby-banks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "latitude": 48.8566,
    "longitude": 2.3522,
    "radius_km": 50,
    "blood_type_id": 1
  }'
```

**Réponse :**
```json
{
  "success": true,
  "banks": [
    {
      "id": 1,
      "name": "Centre de Transfusion Sanguine de Paris",
      "distance_km": 0.04,
      "latitude": "48.85660000",
      "longitude": "2.35220000",
      "address": "6 rue Alexandre Cabanel",
      "city": "Paris",
      "phone": "01 44 49 30 00",
      "blood_stocks": []
    }
  ],
  "total_found": 1,
  "search_radius_km": 50
}
```

### 3. Recherche par Ville
Trouve toutes les banques de sang dans une ville spécifique.

**Endpoint :** `POST /api/geolocation/search-by-city`

**Exemple d'utilisation :**
```bash
curl -X POST http://localhost:8000/api/geolocation/search-by-city \
  -H "Content-Type: application/json" \
  -d '{"city": "Marseille"}'
```

### 4. Statistiques de Géolocalisation
Affiche les statistiques de couverture géographique.

**Endpoint :** `GET /api/geolocation/statistics`

**Exemple d'utilisation :**
```bash
curl -X GET http://localhost:8000/api/geolocation/statistics \
  -H "Accept: application/json"
```

**Réponse :**
```json
{
  "success": true,
  "statistics": {
    "banks": {
      "total": 5,
      "with_location": 5,
      "coverage_percentage": 100
    },
    "donors": {
      "total": 1,
      "with_location": 0,
      "coverage_percentage": 0
    }
  }
}
```

## 🔧 Workflow Complet d'Utilisation

### Étape 1 : Géocoder une adresse
```bash
# Convertir une adresse en coordonnées GPS
curl -X POST http://localhost:8000/api/geolocation/geocode \
  -H "Content-Type: application/json" \
  -d '{"address": "Centre de Transfusion Sanguine de Paris"}'
```

### Étape 2 : Rechercher les banques proches
```bash
# Utiliser les coordonnées obtenues pour trouver les banques proches
curl -X POST http://localhost:8000/api/geolocation/nearby-banks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "latitude": 48.8566,
    "longitude": 2.3522,
    "radius_km": 50
  }'
```

### Étape 3 : Filtrer par type de sang (optionnel)
```bash
# Ajouter un filtre par type de sang
curl -X POST http://localhost:8000/api/geolocation/nearby-banks \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "latitude": 48.8566,
    "longitude": 2.3522,
    "radius_km": 50,
    "blood_type_id": 1
  }'
```

## 📍 Paramètres des Endpoints

### Géocodage (`/geocode`)
- `address` (requis) : L'adresse à géocoder (max 500 caractères)

### Recherche de Banques Proches (`/nearby-banks`)
- `latitude` (requis) : Latitude du point de recherche (-90 à 90)
- `longitude` (requis) : Longitude du point de recherche (-180 à 180)
- `radius_km` (optionnel) : Rayon de recherche en km (1 à 500, défaut: 50)
- `blood_type_id` (optionnel) : ID du type de sang pour filtrer les résultats

### Recherche par Ville (`/search-by-city`)
- `city` (requis) : Nom de la ville à rechercher (max 100 caractères)

## 🚀 Fonctionnalités Avancées

### Mise à Jour Automatique des Coordonnées
Les administrateurs peuvent mettre à jour automatiquement les coordonnées GPS des banques qui n'en ont pas :

**Endpoint :** `POST /api/geolocation/update-bank-coordinates` (Admin seulement)

```bash
curl -X POST http://localhost:8000/api/geolocation/update-bank-coordinates \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

## 📊 Données Retournées

### Informations des Banques
- **Coordonnées GPS** : `latitude`, `longitude`
- **Informations de contact** : `phone`, `email`, `website`
- **Adresse complète** : `address`, `city`, `postal_code`, `country`
- **Distance** : `distance_km` (pour les recherches de proximité)
- **Stock de sang** : `blood_stocks` (si disponible)

### Calcul de Distance
La distance est calculée using la formule de Haversine, qui donne la distance en ligne droite entre deux points sur la surface de la Terre.

## 🔒 Authentification

- **Endpoints publics** : Géocodage, statistiques, recherche par ville
- **Endpoints protégés** : Recherche de banques proches, mise à jour des coordonnées

## 🌐 API Externe Utilisée

L'API utilise **OpenStreetMap Nominatim** pour le géocodage :
- Service gratuit et open source
- Limite de taux : 1 requête par seconde
- Précision : Excellente pour les adresses françaises

## 📝 Notes Importantes

1. **Limites de taux** : Respectez la limite d'1 requête par seconde pour l'API OpenStreetMap
2. **Précision** : Les coordonnées GPS sont précises à environ 10-50 mètres
3. **Données** : Toutes les banques de sang ont des coordonnées GPS (100% de couverture)
4. **Donneurs** : La géolocalisation des donneurs nécessite l'ajout de champs GPS à la table users

## 🛠️ Développement

Pour ajouter de nouvelles fonctionnalités de géolocalisation :

1. Modifier `app/Http/Controllers/GeolocationController.php`
2. Ajouter les routes dans `routes/api.php`
3. Tester avec les exemples ci-dessus
4. Mettre à jour cette documentation 
