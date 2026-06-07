# ViveParis 🗼

**ViveParis** est un site web interactif de découverte de Paris. Il propose une carte de la ville permettant d'explorer différents lieux remarquables, d'y lire des anecdotes historiques ou insolites, et de visionner les photos associées.

---

## 🛠️ Stack Technique

* **Serveur web & Logique :** PHP 8.x
* **Base de données :** MySQL 8.0 (stockant les lieux, les médias, et les types de lieux)
* **Design & Layout :** HTML5, CSS3 personnalisé (`nouveau.css`, `styles.css`)
* **Carte Interactive :** Moteur de rendu cartographique personnalisé écrit en JavaScript natif (Vanilla JS) avec transitions de zoom fluides et support du déplacement au glisser (pan).
* **Conteneurisation :** Docker & Docker Compose

---

## 🚀 Lancement Rapide (Docker)

Le projet est entièrement conteneurisé. Assurez-vous d'avoir Docker installé sur votre machine.

1. **Démarrer les conteneurs :**
   Lancez la commande suivante à la racine du projet :
   ```bash
   docker compose up -d
   ```

2. **Accéder à l'application :**
   * **Front-Office (Plan de Paris) :** [http://localhost:8000/](http://localhost:8000/)
   * **Base de données :** MySQL tourne sur le port `3306` (initialisée automatiquement avec `database_complet.sql`).

3. **Arrêter les conteneurs :**
   ```bash
   docker compose down
   ```

---

## 📂 Structure des Fichiers Clés

* `index.php` : Page d'accueil principale avec le plan interactif de Paris.
* `afficherLieu.php` : Fiche détaillée d'un lieu (titre, anecdote, carrousel d'images, et bouton de retour).
* `styles/` : Feuilles de style globales et spécifiques.
* `medias/` : Répertoire contenant les photos et leurs imagettes (thumbnails) associées aux lieux.
* `backOffice/` : Espace d'administration pour ajouter/modifier des lieux et des médias.
* `database_complet.sql` : Script d'initialisation de la base de données MySQL.
* `docker-compose.yml` : Configuration des conteneurs pour l'environnement local.
