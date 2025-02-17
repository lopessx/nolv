# Nolv

A responsive open source software marketplace built with Lumen and Quasar.

This project is split in two directories

### API

Has the back-end logic built with lumen. Instructions about running the server and migrations are inside the directory in a readme.md file.

### Front

Has the front-end views built with Quasar. Instructions about building for production are inside the directory.

## Setup development environment

To set up the environment, you need Docker, and we recommend installing the Dev Containers extension for Visual Studio Code.

### Steps to Set Up the Environment

- Install the Extension:

Install the Dev Containers extension from the Visual Studio Code marketplace.

- Reopen in a Container:

Open Visual Studio Code, press F1, and select the option Dev Containers: Reopen in Container. This will build the container environment.

- Update Environment Variables:

After the container is built, update the .env file located in the /api directory with your sandbox keys and database credentials. Currently this project supports [Cielo API 3.0](https://docs.cielo.com.br/ecommerce-cielo/reference/como-usar-o-sandbox) and [Paghiper](https://dev.paghiper.com/reference/pr%C3%A9-requisitos-e-neg%C3%B3cio) payment methods.

- Default Database Credentials for Dev Containers

If you're using the default Dev Container database setup, use the following credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mariadb
DB_USERNAME=mariadb
DB_PASSWORD=mariadb
```

- SMTP configuration

This project needs SMTP to work correctly. For testing we recomend the [Mailtrap](https://mailtrap.io/pt/) API.

## Setup for production

1. Update the front end variable front/quasar.conf.js 
```js
env: {
        API:ctx.dev
        ? 'http://127.0.0.1:8000'
        : 'https://your-production-api-url.com'
    }
```
2. Run quasar build
3. Update your .env file in api/.env with your production credentials and URL
4. In the /api directory run
```sh
php artisan optimize && composer install --no-dev
```
5. Now upload the project to a server, the API and FRONT don't need to be in the same server
For the front we recommend to upload all the contents in front/dist/spa for the production server;

For the api you need to upload all the files to the production server. Caution needs to be taken with the .env and /storage directories, we recomend to upload the api in the root directory instead of the public_html or take action to the directories and files outside the /public don't be accessed directly.