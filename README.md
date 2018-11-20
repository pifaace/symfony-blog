[![Build Status](https://travis-ci.org/pifaace/symfony-blog.svg?branch=master)](https://travis-ci.org/pifaace/symfony-blog)

# Symfony-blog 4.1

My application to try some stuffs about Symfony 4
In this project, I'm trying to use various components of Symfony like :
* [Authentication](https://symfony.com/doc/current/security.html)
* [Service container](http://symfony.com/doc/current/service_container.html)
* [Listener](http://symfony.com/doc/current/doctrine/event_listeners_subscribers.html#creating-the-listener-class)
* [Subscriber](http://symfony.com/doc/current/doctrine/event_listeners_subscribers.html)
* [Data transormers](https://symfony.com/doc/current/form/data_transformers.html)
* [Monolog](https://symfony.com/doc/current/logging.html)
* [Guard Authentication](https://symfony.com/doc/current/security/guard_authentication.html)

this list should evolve.

Also, to improve this project, I'm using some tools like :
* [Bulma.io](https://bulma.io/)
* [SASS](http://sass-lang.com/documentation/file.SASS_REFERENCE.html)
* [Font-awesome](http://fontawesome.io/)
* [Github OAuth](https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/)


## Getting Started

### Installing

This project require 
* [Docker](https://docs.docker.com/)
* [Docker-compose](https://docs.docker.com/compose/)

#### Clone the project
```
$ git clone https://github.com/pifaace/symfony-blog.git
```

#### Run dependencies
```
$ docker-compose run --rm --no-deps blog-server composer install
$ docker run --rm -it -v $(pwd):/application -w /application node yarn run install
```

#### Assets
I'm using [Webpack encore](https://symfony.com/doc/current/frontend.html) to build assets.
Some commands are available, you can run those in a container like :
```
$ docker run --rm -it -v $(pwd):/application -w /application node yarn encore dev
$ docker run --rm -it -v $(pwd):/application -w /application node yarn encore dev --watch
$ docker run --rm -it -v $(pwd):/application -w /application node yarn encore production --progress
```
Just notice that, running those in a container do not trigger webpack-notifier.
You should run these commands directly on your host to use it. In this case be 
sure you have yarn installed.

### Running docker containers

#### Running containers
```
$ docker-compose up -d
$ start http://localhost:8000/ # Windows
$ open http://localhost:8000/ # Mac
```

#### Stopping containers
```
$ docker-compose stop
```

### Migrations

```
$ docker-compose exec blog-server php bin/console doctrine:migrations:migrate
```

And run datafixtures

```
$ docker-compose exec blog-server php bin/console doctrine:fixtures:load
```

### Account
You can connect as admin with these infos :

```
login : admin
password : password
```

### SMTP
To use features that implements swiftmailer, you need to add a MAILER_URL in .env
You can use mailtrap for your developments, so your MAILER_URL should look like this :
```
MAILER_URL=smtp://smtp.mailtrap.io:25?encryption=&auth_mode=cram-md5&username=your_username&password=your_password
```

### Github OAuth
For registration with Github, you should register your own OAuth app, follow 
this [link](https://developer.github.com/apps/building-github-apps/creating-a-github-app/) for a quick tutorial.
The authorization callback URL should be :
```
http://localhost:8000/login/github/callback
```
When your app created, you should get a __client ID__ and __client secret__, 
remplace these informations in the .env file in the right section.
and that's it !

### Running tests
```
$ docker-compose run --rm blog-server ./bin/phpunit
```

##  License
This project is released under the [MIT](https://opensource.org/licenses/MIT) license.
