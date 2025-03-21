# Projet Snowtricks

## CONTEXT

This is my first project that I built entirely using Symfony.
The project is about bulding a collaborative website for snowboard freestyle. Users are able to create an account, verify their email adress, change their password if they forgot it, login, create, modify, delete and comment tricks (adding one ore more images aswell as youtube video links).


## INSTALLATION

To clone the project run the following command: 
```
git clone https://github.com/HyacineAlnuma/Snowtricks.git
```

To install the dependencies of the project run the following command:
```
composer install
```

### Environment variables

Create a .env.local file at the root of the project which is a copy of the .env file where you update the following variables with your own configuration:

DATABASE_URL  
MAILER_DSN


### Data

Import the snowtricks.sql dump in a local database to have an initial data set with all the users and snowboard tricks.


