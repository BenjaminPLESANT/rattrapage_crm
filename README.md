
# Rattrapage projet CRM

Rappel de la demande:
-	Sur symfony, faire un système de register (pseudo(unique), email(unique), password)
     et login user (pseudo, password) à la main avec hash de password et verif de password
     hashé au moment du login
-	Accès sur une page sécurisée (non accessible en non-connecté)
-	Faire un système de token "à la main" (sans JWT donc), avec un statut valid, refresh et revoke
-	Quand la personne se log, on lui affecte un token, et si on raffraichi la page le token se refresh, si il s'est écoulé trop de temps entre le dernier refresh de page et le nouveau, on revoke et on déconnecte la personne
-	En gros : au login, un token est généré (a toi de choisir le type de hash). Tu peux choisir la durée aussi dans une constante par exemple. (en général, 30min ou 1h c'est bien)
-	la personne est redirect sur une page sécurisée (pas accessible en non-connecté), token status à "valid"
-	timestamp du token a 14h00 par exemple
-	si l'utilisateur refresh la page a 14h05, ça refresh le token (ça rallonge sa durée de vie, status passé à refreshed)
-	si l'utilisateur refresh la page à 14h56, ça déconnecte car le token est trop ancien (status passé à revoked)
-	Le but est de comprendre le fonctionnement des token oAuth2 même si là c'est très simplifié.
-	Pas de design front end nécessaire, du html noir du blanc me suffit, c'est la gestion de token qui m'intéresse.


### Etape 1
Cloner le dépôt du projet
* HTTPS : ```git clone https://github.com/BenjaminPLESANT/rattrapage_crm.git```
* SSH : ```git clone git@github.com:BenjaminPLESANT/rattrapage_crm.git```

### Etape 2
```
composer install
```

### Etape 3

* Créer un fichier .env à la racine du projet
* Dans ce fichier .env, copiez le contenu du fichier .env.local et modifiez la partie: DATABASE_URL


Example :

```
# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=ea5ad8ae724f7abf9a442a7b65651dcf
###< symfony/framework-bundle ###

###> symfony/webapp-meta ###
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/webapp-meta ###

###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
#DATABASE_URL="postgresql://symfony:ChangeMe@127.0.0.1:5432/app?serverVersion=13&charset=utf8"
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
# Choose one of the transports below
# MESSENGER_TRANSPORT_DSN=doctrine://default
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
###< symfony/messenger ###

###> symfony/mailer ###
# MAILER_DSN=null://null
###< symfony/mailer ###
```

### Etape 4
Créer la base de données à partir de la variable d'environnement DATABASE_URL:
```
php bin/console doctrine:database:create
```

Pour mettre à jour le schéma
``` 
php bin/console d:s:u --force 
```
Si le dossier de migration est vide
``` 
php bin/console doctrine:migrations:migrate
```

### Etape 5
Lancez l'application
(N'oubliez pas de lancez votre serveur xammp, wamp etc...)

```
symfony server:start
```

## Contact

Pour une éventuelle demande, contactez moi

Email: benjamin.plesant@outlook.fr

Linkedin: https://www.linkedin.com/in/benjamin-plesant/

