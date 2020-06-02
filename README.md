[![Build Status](https://travis-ci.org/pifaace/symfony-blog.svg?branch=master)](https://travis-ci.org/pifaace/symfony-blog)

# Symfony-blog 4.4

## What's Symfony-blog ?
This project is like a sandbox. The purpose is to implement some cool features to improve my symfony skills
but also to help people. This app should be a very good example to show how I implement things like 
[Panther](https://github.com/symfony/panther) or [Mercure](https://github.com/dunglas/mercure) !
Feel free to report bugs with an issue !

Also, to improve this project, I'm using some tools like :
* [Bulma.io](https://bulma.io/)
* [SASS](http://sass-lang.com/documentation/file.SASS_REFERENCE.html)
* [Font-awesome](http://fontawesome.io/)
* [Github OAuth](https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/)

## Getting Started

### Installing

This project requires
* [Docker](https://docs.docker.com/)
* [Docker-compose](https://docs.docker.com/compose/)

#### Clone the project
```
$ git clone https://github.com/pifaace/symfony-blog.git
```

#### Run dependencies
```
$ docker-compose run --rm --no-deps blog-server composer install
$ docker run --rm -it -v $(pwd):/application -w /application node yarn install
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

### Running Docker containers

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
$ docker-compose exec blog-server bin/console hautelook:fixtures:load
```

### Account
You can connect as admin with these infos :

```
login : admin
password : azerty
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
All tests from tests/Functional are writing with [Panther](https://github.com/symfony/panther)

## Mercure feature
I implemented a notification feature to test how can we use Mercure in situation.
At the moment, a notification will trigger each authenticated user when an admin published a new article.
The only thing you are suppose to modify is in __docker-composer__ file

```
mercure:
    image: dunglas/mercure
    ports:
      - '3000:80'
    environment:
      - JWT_KEY=symfonyBlogJwtToken
      - PUBLISH_ALLOWED_ORIGINS=*
      - CORS_ALLOWED_ORIGINS=http://symfony-blog.fr:8000
      - DEBUG=1
```
You should adapt the CORS_ALLOWED_ORIGINS value. In general it would be __localhost__

##  License
This project is released under the [MIT](https://opensource.org/licenses/MIT) license.
