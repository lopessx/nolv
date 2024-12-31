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

After the container is built, update the .env file located in the /api directory with your sandbox keys and database credentials.

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


## Setup for production

1. Update the docker-compose variables;
2. Run docker-compose up
3. Now your application is running correctly.