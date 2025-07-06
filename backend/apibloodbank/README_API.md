# 📚 Documentation API BloodBank

## 🚀 Vue d'ensemble

Cette API permet de gérer complètement une plateforme de dons de sang avec authentification, géolocalisation, gestion des urgences et notifications automatiques.

## 📋 Fonctionnalités principales

- ✅ **Authentification** avec Laravel Sanctum
- ✅ **Gestion des rôles** (Admin, Blood Bank, Donor, Doctor)
- ✅ **Types de sang** et compatibilités
- ✅ **Banques de sang** avec géolocalisation
- ✅ **Dons de sang** et suivi des stocks
- ✅ **Patients** et demandes de sang
- ✅ **Urgences** avec notifications automatiques
- ✅ **Géolocalisation** avancée
- ✅ **Partenariats** entre banques
- ✅ **Système de contact** et notifications
- ✅ **Statistiques** complètes

## 🛠️ Installation et configuration

### Prérequis
- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Laravel 10+

### Installation
```bash
# Cloner le projet
git clone <repository-url>
cd apibloodbank

# Installer les dépendances
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bloodbank
DB_USERNAME=root
DB_PASSWORD=

# Générer la clé d'application
php artisan key:generate

# Migrer la base de données
php artisan migrate

# Peupler avec les données de base
php artisan db:seed

# Démarrer le serveur
php artisan serve
```

## 🔐 Authentification

L'API utilise Laravel Sanctum. Voici le flux d'authentification :

1. **Inscription** : `POST /api/auth/register`
2. **Connexion** : `POST /api/auth/login` → Retourne un token
3. **Utilisation** : Inclure `Authorization: Bearer {token}` dans les headers

### Exemple de connexion
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@bloodbank.com",
    "password": "password"
  }'
```

## 📖 Documentation

### Formats disponibles

1. **Documentation Markdown** : `API_DOCUMENTATION.md`
2. **Collection Postman** : `BloodBank_API.postman_collection.json`
3. **Documentation interactive** : `http://localhost:8000/docs` (si Scribe installé)

### Utilisation de la collection Postman

1. Importer `BloodBank_API.postman_collection.json` dans Postman
2. Configurer les variables d'environnement :
   - `base_url` : `http://localhost:8000/api`
   - `auth_token` : (sera automatiquement rempli après connexion)
3. Exécuter "Connexion" pour obtenir un token
4. Tester les autres endpoints

## 🧪 Tests

### Données de test
```bash
# Créer des données d'exemple pour la documentation
php artisan db:seed --class=DocumentationSeeder
```

### Comptes de test
- **Admin** : `admin@bloodbank.com` / `password`
- **Donor** : `donor@example.com` / `password`
- **Doctor** : `doctor@example.com` / `password`

## 🔧 Configuration avancée

### Variables d'environnement importantes
```env
# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bloodbank
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:8000
SESSION_DOMAIN=localhost

# Géolocalisation (OpenStreetMap)
NOMINATIM_URL=https://nominatim.openstreetmap.org
```

### Middleware de rôles
L'API utilise un middleware personnalisé pour gérer les rôles :
- `role:admin` : Administrateurs uniquement
- `role:blood_bank` : Banques de sang
- `role:donor` : Donneurs
- `role:doctor` : Médecins

## 📊 Endpoints principaux

### 🔐 Authentification
- `POST /auth/register` - Inscription
- `POST /auth/login` - Connexion
- `POST /auth/logout` - Déconnexion
- `GET /auth/me` - Profil utilisateur

### 🩸 Types de sang
- `GET /blood-types` - Liste des types
- `GET /blood-types/{id}/compatible` - Types compatibles

### 🏥 Banques de sang
- `GET /blood-banks` - Liste des banques
- `POST /blood-banks/search/nearby` - Recherche par proximité

### 💉 Dons de sang
- `GET /donations` - Liste des dons
- `POST /donations` - Créer un don
- `POST /donations/{id}/complete` - Compléter un don

### 📦 Gestion du stock
- `GET /stocks` - Liste des stocks
- `POST /stocks/{id}/adjust` - Ajuster un stock
- `GET /stocks/alerts/low-stock` - Alertes de stock faible

### 🚨 Urgences
- `POST /emergencies/declare` - Déclarer une urgence
- `GET /emergencies/active` - Urgences actives
- `POST /emergencies/find-banks` - Rechercher banques disponibles

### 🗺️ Géolocalisation
- `POST /geolocation/geocode` - Géocoder une adresse
- `POST /geolocation/nearby-banks` - Banques proches
- `POST /geolocation/nearby-donors` - Donneurs proches

### 🤝 Partenariats
- `GET /partnerships` - Liste des partenariats
- `POST /partnerships/request` - Demander un partenariat

### 📞 Contact
- `POST /contact/send` - Envoyer un message
- `GET /contacts` - Liste des messages (Admin)

## 🔔 Notifications

Le système de notifications est automatique pour :
- ✅ Stock faible
- ✅ Demandes d'urgence
- ✅ Nouveaux partenariats
- ✅ Messages de contact

## 🗺️ Géolocalisation

L'API utilise OpenStreetMap/Nominatim pour :
- Géocodage d'adresses
- Calcul de distances
- Recherche par proximité
- Optimisation des urgences

## 📈 Statistiques

Chaque module fournit des statistiques détaillées :
- Dons par période
- Stock par banque/type
- Demandes par urgence
- Couverture géographique

## 🚀 Déploiement

### Production
```bash
# Optimiser pour la production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurer le serveur web (Apache/Nginx)
# Pointer vers public/
```

### Variables d'environnement de production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Base de données de production
DB_HOST=production-db-host
DB_DATABASE=bloodbank_prod
DB_USERNAME=prod_user
DB_PASSWORD=prod_password

# Cache et sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🆘 Support

Pour toute question ou problème :
1. Consulter la documentation
2. Vérifier les issues existantes
3. Créer une nouvelle issue avec les détails

---

**API BloodBank** - Gestion complète des dons de sang avec géolocalisation et notifications automatiques. 
