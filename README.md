# Training Symfony 3.3

My application to try some stuffs about Symfony 3.3

## Getting Started

### Installing

#### Clone the project
```
git clone https://github.com/pifaace/training-symfony.git
```

#### Run composer install
```
composer install
```

#### Run npm install
```
npm install
```

#### Run gulp
```
gulp
```

### Running docker containers

#### Update database configuration
```
parameters:
    database_host: mysql
    database_port: 3306
    database_name: training-symfony
    database_user: root
    database_password: secret
```

#### Running containers
```
$ docker-compose up -d
```

#### Stoping containers
```
$ docker-compose stop
```

### Dump database

```
docker-compose run blog-server php bin/console doctrine:schema:update --force
```

And run datafixtures

```
docker-compose run blog-server php bin/console doctrine:fixtures:load
```

### Routes
To access to the dashboard admin go to the following url :
```
/admin/dashboard
login : admin
password : password
```
