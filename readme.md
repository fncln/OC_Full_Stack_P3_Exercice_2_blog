## Blog d'Emilie Forteroche

## Pour utiliser ce projet :

- Clonez le projet.
- Installez le projet chez vous, dans un dossier exécuté par un serveur local (type MAMP, WAMP, LAMP, etc.)
- Créez une base de données vide appelée : `blog_forteroche`
- Importez le fichier `blog_forteroche.sql` dans votre base de données.
- Si votre base de données `blog_forteroche` est déjà installée avant la mise à jour du monitoring, exécutez le fichier `migration_add_views.sql` pour ajouter la colonne des vues.

## Lancez le projet !

Pour la configuration du projet, renommez le fichier `_config.php` (dans le dossier `config`) en `config.php` et éditez-le si nécessaire.
Ce fichier contient notamment les informations de connexion à la base de données. 

Pour vous connecter en partie admin, le login est "Emilie" et le mot de passe est "password" (attention aux majuscules).

Une fois connecté, le menu contient un lien "Monitoring" : il affiche les statistiques de chaque article (vues, commentaires, date) avec un tri sur chaque colonne, et permet de consulter et supprimer les commentaires.

## Problèmes courants :

Il est possible que la librairie `intl` ne soit pas activée sur votre serveur par défaut. Cette librairie sert notamment à traduire les dates en français et à trier les titres d'articles dans la page de monitoring. Dans ce cas, vous pouvez soit utiliser l'interface de votre serveur local pour activer l'extension (WAMP), soit aller modifier directement le fichier `php.ini`.

Ce projet a été réalisé avec PHP 8.2. Bien que d'autres versions de PHP puissent fonctionner, il n'est pas garanti que le projet fonctionne avec des versions antérieures.

## Copyright :

Projet utilisé dans le cadre d'une formation OpenClassrooms.