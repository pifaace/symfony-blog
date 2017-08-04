# Training Symfony 3.3

My application to try some stuffs about Symfony 3.3

## Getting Started

### Installing

Clone the project 
```
git clone https://github.com/pifaace/training-symfony.git
```

Run composer install
```
composer install
```

Run npm install (nothing really useful at this moment ..)
```
npm install
```

### Dump database

```
php bin/console doctrine:schema:update --force
```

And run datafixtures

```
php bin/console doctrine:fixtures:load
```
