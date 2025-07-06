# 📚 Documentation API BloodBank

## 🌐 Base URL
```
http://localhost:8000/api
```

## 🔐 Authentification

L'API utilise **Laravel Sanctum** pour l'authentification. Incluez le token Bearer dans l'en-tête `Authorization`.

```
Authorization: Bearer {YOUR_TOKEN}
```

## 👥 Rôles utilisateurs

- **Admin** : Accès complet à toutes les fonctionnalités
- **Blood Bank** : Gestion de sa banque de sang
- **Donor** : Donneur de sang
- **Doctor** : Médecin pouvant faire des demandes

---

## 🔑 Authentification

### Inscription
```http
POST /auth/register
```

**Corps de la requête :**
```json
{
  "name": "Jean Dupont",
  "email": "jean.dupont@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+33123456789",
  "address": "123 Rue de la Paix",
  "city": "Paris",
  "postal_code": "75001",
  "country": "France",
  "latitude": 48.8566,
  "longitude": 2.3522,
  "blood_type_id": 1,
  "role": "donor"
}
```

### Connexion
```http
POST /auth/login
```

**Corps de la requête :**
```json
{
  "email": "jean.dupont@example.com",
  "password": "password123"
}
```

### Déconnexion
```http
POST /auth/logout
```
*Authentification requise*

### Profil utilisateur
```http
GET /auth/me
```
*Authentification requise*

### Rafraîchir token
```http
POST /auth/refresh
```
*Authentification requise*

---

## 🩸 Types de sang

### Liste des types de sang
```http
GET /blood-types
```

### Détails d'un type de sang
```http
GET /blood-types/{id}
```

### Types compatibles
```http
GET /blood-types/{id}/compatible
```

### Créer un type de sang (Admin)
```http
POST /blood-types
```
*Authentification + rôle Admin requis*

### Modifier un type de sang (Admin)
```http
PUT /blood-types/{id}
```
*Authentification + rôle Admin requis*

### Supprimer un type de sang (Admin)
```http
DELETE /blood-types/{id}
```
*Authentification + rôle Admin requis*

---

## 🏥 Banques de sang

### Liste des banques de sang
```http
GET /blood-banks
```

### Détails d'une banque
```http
GET /blood-banks/{id}
```

### Stock d'une banque
```http
GET /blood-banks/{id}/stock
```

### Recherche par proximité
```http
POST /blood-banks/search/nearby
```

**Corps de la requête :**
```json
{
  "latitude": 48.8566,
  "longitude": 2.3522,
  "radius_km": 50
}
```

### Créer une banque (Admin)
```http
POST /blood-banks
```
*Authentification + rôle Admin requis*

### Modifier une banque
```http
PUT /blood-banks/{id}
```
*Authentification + rôle Blood Bank/Admin requis*

### Vérifier une banque (Admin)
```http
POST /blood-banks/{id}/verify
```
*Authentification + rôle Admin requis*

---

## 💉 Dons de sang

### Liste des dons
```http
GET /donations
```
*Authentification requise*

### Détails d'un don
```http
GET /donations/{id}
```
*Authentification requise*

### Créer un don
```http
POST /donations
```
*Authentification requise*

**Corps de la requête :**
```json
{
  "donor_id": 2,
  "blood_bank_id": 1,
  "blood_type_id": 1,
  "donation_date": "2025-07-07",
  "quantity_ml": 450,
  "notes": "Don régulier"
}
```

### Modifier un don
```http
PUT /donations/{id}
```
*Authentification requise*

### Supprimer un don
```http
DELETE /donations/{id}
```
*Authentification requise*

### Compléter un don
```http
POST /donations/{id}/complete
```
*Authentification requise*

### Annuler un don
```http
POST /donations/{id}/cancel
```
*Authentification requise*

### Historique d'un donneur
```http
GET /donations/donor/{donorId}/history
```
*Authentification requise*

### Statistiques des dons
```http
GET /donations/statistics
```
*Authentification requise*

---

## 📦 Gestion du stock

### Liste des stocks
```http
GET /stocks
```
*Authentification + rôle Blood Bank/Admin requis*

### Détails d'un stock
```http
GET /stocks/{id}
```
*Authentification + rôle Blood Bank/Admin requis*

### Créer un stock
```http
POST /stocks
```
*Authentification + rôle Blood Bank/Admin requis*

### Modifier un stock
```http
PUT /stocks/{id}
```
*Authentification + rôle Blood Bank/Admin requis*

### Ajuster un stock
```http
POST /stocks/{id}/adjust
```
*Authentification + rôle Blood Bank/Admin requis*

**Corps de la requête :**
```json
{
  "quantity_ml": 100,
  "movement_type": "in",
  "reason": "Don reçu"
}
```

### Mouvements de stock
```http
GET /stocks/movements
```
*Authentification + rôle Blood Bank/Admin requis*

### Alertes de stock faible
```http
GET /stocks/alerts/low-stock
```
*Authentification + rôle Blood Bank/Admin requis*

### Statistiques du stock
```http
GET /stocks/statistics
```
*Authentification + rôle Blood Bank/Admin requis*

---

## 👨‍⚕️ Patients

### Liste des patients
```http
GET /patients
```
*Authentification + rôle Doctor/Admin requis*

### Détails d'un patient
```http
GET /patients/{id}
```
*Authentification + rôle Doctor/Admin requis*

### Créer un patient
```http
POST /patients
```
*Authentification + rôle Doctor/Admin requis*

**Corps de la requête :**
```json
{
  "name": "Marie Dupont",
  "email": "marie.dupont@example.com",
  "phone": "+33123456789",
  "blood_type_id": 1,
  "date_of_birth": "1985-03-15",
  "gender": "female",
  "hospital_name": "Hôpital de la Pitié-Salpêtrière",
  "hospital_address": "47-83 Boulevard de l'Hôpital, Paris",
  "latitude": 48.8361,
  "longitude": 2.3614,
  "urgency_level": "medium",
  "medical_notes": "Patient nécessitant des transfusions régulières"
}
```

### Modifier un patient
```http
PUT /patients/{id}
```
*Authentification + rôle Doctor/Admin requis*

### Supprimer un patient
```http
DELETE /patients/{id}
```
*Authentification + rôle Doctor/Admin requis*

### Rechercher des patients
```http
POST /patients/search
```
*Authentification + rôle Doctor/Admin requis*

### Statistiques des patients
```http
GET /patients/statistics
```
*Authentification + rôle Doctor/Admin requis*

---

## 🚨 Demandes de sang

### Liste des demandes
```http
GET /blood-requests
```
*Authentification + rôle Doctor/Admin requis*

### Détails d'une demande
```http
GET /blood-requests/{id}
```
*Authentification + rôle Doctor/Admin requis*

### Créer une demande
```http
POST /blood-requests
```
*Authentification + rôle Doctor/Admin requis*

**Corps de la requête :**
```json
{
  "patient_id": 1,
  "blood_type_id": 1,
  "required_quantity_ml": 500,
  "urgency_level": "medium",
  "contact_phone": "+33123456789",
  "notes": "Transfusion programmée"
}
```

### Modifier une demande
```http
PUT /blood-requests/{id}
```
*Authentification + rôle Doctor/Admin requis*

### Supprimer une demande
```http
DELETE /blood-requests/{id}
```
*Authentification + rôle Doctor/Admin requis*

### Rechercher disponibilités
```http
GET /blood-requests/{id}/availability
```
*Authentification + rôle Doctor/Admin requis*

### Approuver une demande
```http
POST /blood-requests/{id}/approve
```
*Authentification + rôle Doctor/Admin requis*

### Annuler une demande
```http
POST /blood-requests/{id}/cancel
```
*Authentification + rôle Doctor/Admin requis*

### Satisfaire une demande
```http
POST /blood-requests/{id}/fulfill
```
*Authentification + rôle Doctor/Admin requis*

### Statistiques des demandes
```http
GET /blood-requests/statistics
```
*Authentification + rôle Doctor/Admin requis*

---

## 🔔 Notifications

### Liste des notifications
```http
GET /notifications
```
*Authentification requise*

### Nombre de notifications non lues
```http
GET /notifications/unread-count
```
*Authentification requise*

### Marquer comme lue
```http
PATCH /notifications/{id}/read
```
*Authentification requise*

### Marquer toutes comme lues
```http
PATCH /notifications/mark-all-read
```
*Authentification requise*

### Supprimer une notification
```http
DELETE /notifications/{id}
```
*Authentification requise*

---

## 🚨 Urgences

### Déclarer une urgence
```http
POST /emergencies/declare
```
*Authentification requise*

**Corps de la requête :**
```json
{
  "patient_id": 1,
  "blood_type_id": 1,
  "required_quantity_ml": 1000,
  "urgency_level": "critical",
  "hospital_name": "Hôpital de la Pitié-Salpêtrière",
  "hospital_address": "47-83 Boulevard de l'Hôpital, Paris",
  "latitude": 48.8361,
  "longitude": 2.3614,
  "contact_phone": "+33123456789",
  "notes": "URGENCE - Chirurgie cardiaque"
}
```

### Rechercher banques disponibles
```http
POST /emergencies/find-banks
```
*Authentification requise*

**Corps de la requête :**
```json
{
  "blood_type_id": 1,
  "required_quantity_ml": 1000,
  "latitude": 48.8566,
  "longitude": 2.3522,
  "radius_km": 100
}
```

### Urgences actives
```http
GET /emergencies/active
```
*Authentification requise*

### Mettre à jour statut urgence
```http
PATCH /emergencies/{id}/status
```
*Authentification requise*

---

## 🗺️ Géolocalisation

### Géocoder une adresse
```http
POST /geolocation/geocode
```

**Corps de la requête :**
```json
{
  "address": "Paris, France"
}
```

### Banques proches
```http
POST /geolocation/nearby-banks
```
*Authentification requise*

**Corps de la requête :**
```json
{
  "latitude": 48.8566,
  "longitude": 2.3522,
  "radius_km": 50,
  "blood_type_id": 1
}
```

### Donneurs proches
```http
POST /geolocation/nearby-donors
```
*Authentification requise*

### Statistiques géographiques
```http
GET /geolocation/statistics
```

---

## 🤝 Partenariats

### Liste des partenariats
```http
GET /partnerships
```
*Authentification requise*

### Demander un partenariat
```http
POST /partnerships/request
```
*Authentification + rôle Blood Bank requis*

**Corps de la requête :**
```json
{
  "responding_bank_id": 2,
  "partnership_type": "sharing",
  "description": "Partage de stocks en cas d'urgence",
  "terms": "Accord de partage mutuel des stocks"
}
```

### Répondre à une demande
```http
PATCH /partnerships/{id}/respond
```
*Authentification + rôle Blood Bank requis*

**Corps de la requête :**
```json
{
  "status": "accepted",
  "response_notes": "Partenariat accepté avec enthousiasme"
}
```

### Terminer un partenariat
```http
PATCH /partnerships/{id}/terminate
```
*Authentification + rôle Blood Bank requis*

### Statistiques des partenariats
```http
GET /partnerships/statistics
```
*Authentification requise*

---

## 📞 Contact

### Envoyer un message
```http
POST /contact/send
```

**Corps de la requête :**
```json
{
  "name": "Sophie Contact",
  "email": "sophie.contact@example.com",
  "subject": "Demande d'information",
  "message": "Bonjour, j'aimerais avoir des informations sur les dons de sang.",
  "phone": "+33123456789",
  "contact_type": "general"
}
```

### Liste des messages (Admin)
```http
GET /contacts
```
*Authentification + rôle Admin requis*

### Marquer comme traité
```http
PATCH /contacts/{id}/process
```
*Authentification + rôle Admin requis*

### Marquer en cours
```http
PATCH /contacts/{id}/progress
```
*Authentification + rôle Admin requis*

### Répondre à un message
```http
POST /contacts/{id}/respond
```
*Authentification + rôle Admin requis*

### Statistiques des contacts
```http
GET /contacts/statistics
```
*Authentification + rôle Admin requis*

---

## 📊 Codes de réponse

| Code | Description |
|------|-------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 400 | Requête invalide |
| 401 | Non authentifié |
| 403 | Non autorisé |
| 404 | Ressource non trouvée |
| 422 | Erreur de validation |
| 500 | Erreur serveur |

---

## 🔧 Exemples d'utilisation

### Connexion et récupération du token
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@bloodbank.com",
    "password": "password"
  }'
```

### Utilisation du token
```bash
curl -X GET http://localhost:8000/api/blood-types \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Créer un don avec authentification
```bash
curl -X POST http://localhost:8000/api/donations \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "donor_id": 2,
    "blood_bank_id": 1,
    "blood_type_id": 1,
    "donation_date": "2025-07-07",
    "quantity_ml": 450,
    "notes": "Don régulier"
  }'
```

---

## 🚀 Démarrage rapide

1. **Installer les dépendances :**
   ```bash
   composer install
   ```

2. **Configurer la base de données :**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

3. **Démarrer le serveur :**
   ```bash
   php artisan serve
   ```

4. **Tester l'API :**
   ```bash
   curl http://localhost:8000/api/blood-types
   ```

---

## 📝 Notes importantes

- Toutes les dates sont au format `YYYY-MM-DD`
- Les coordonnées géographiques utilisent le système WGS84
- Les quantités de sang sont en millilitres (ml)
- Les tokens d'authentification expirent après 24h par défaut
- Les notifications sont envoyées automatiquement pour les événements importants

---

*Documentation générée le 6 juillet 2025* 
