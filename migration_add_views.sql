-- Étape 2 : ajout du compteur de vues dans la table des articles.
-- À exécuter UNE SEULE FOIS sur la base "blog_forteroche" déjà installée.
ALTER TABLE `article` ADD `views` INT NOT NULL DEFAULT 0;
 