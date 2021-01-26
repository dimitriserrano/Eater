# Eater

Installation :

Installer les dependances avec Composer : composer install

Modifier le fichier .env : DATABASE_URL=mysql://db_user:db_pass@127.0.0.1:3306/db_name

Créer la BDD : php bin/console doctrine:database:create

Générer la migration : php bin/console make:migration

Migrer : php bin/console doctrine:migrations:migrate

Installer les fixtures : php bin/console doctrine:fixtures:load

Démarrer le serveur : symfony serve -d