-- Script pour ajouter la colonne last_login à la table users si elle n'existe pas
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `last_login` TIMESTAMP NULL DEFAULT NULL;
