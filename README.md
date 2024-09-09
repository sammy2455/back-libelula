# docker-compose-yii2
A simplified Docker Compose workflow that sets up a network of containers for local Yii2 development with MongoDB.

## Usage
To get started, make sure you have [Docker installed](https://docs.docker.com/get-docker/) on your system, and then clone this repository.

Next, navigate in your terminal to the directory you cloned this, and follow these steps:

1. Spin up the containers for the web server by running:
   ```
   docker compose up -d --build
   ```

2. Install the Yii2 dependencies using Composer:
   ```
   docker compose run --rm composer install
   ```

3. After that completes, follow the steps from the [app/README.md](app/README.md) file to get your Yii2 project added in (or create a new blank one).

4. If you need to run Yii2 migrations, use:
   ```
   docker compose run --rm yii migrate
   ```

5. Your Yii2 application should now be accessible at [http://localhost:8000](http://localhost:8000)

The following containers are built for our web server, with their exposed ports detailed:

- **nginx** - `:8000`
- **php** - `:9000`
- **mongodb** - `:27017`
- **mailhog** - `:8025` for web interface, `:1025` for SMTP server

## Using Docker Compose Run Commands

This setup includes additional containers that handle Composer, Yii, and NPM commands *without* having to have these platforms installed on your local computer. Use the following command examples from your project root, modifying them to fit your particular use case:

- Update Composer dependencies:
  ```
  docker compose run --rm composer update
  ```

- Run Yii migrations:
  ```
  docker compose run --rm yii migrate
  ```

- Run NPM commands:
  ```
  docker compose run --rm npm run dev
  ```

- Generate Yii2 models or CRUD:
  ```
  docker compose run --rm yii gii/model
  docker compose run --rm yii gii/crud
  ```

## Persistent MongoDB Storage

By default, whenever you bring down the Docker network, your MongoDB data will be persisted in the `./mongodb` folder. This ensures that your data remains intact between container rebuilds.

## MailHog

MailHog is included for testing email sending and general SMTP work during local development. The service is included in the `docker-compose.yml` file, and spins up alongside the webserver and database services.

To see the dashboard and view any emails coming through the system, visit [localhost:8025](http://localhost:8025) after running `docker compose up -d`.

## Notes

- The PHP service uses a custom Dockerfile located at `./dockerfiles/php.dockerfile`.
- The Composer service uses a custom Dockerfile located at `./dockerfiles/composer.dockerfile`.
- The Yii service uses the same Dockerfile as the PHP service.
- MongoDB is configured with a root username and password, and initializes a database named "libelula".
- The nginx configuration files should be placed in `./dockerfiles/nginx`.
- Your Yii2 application code should be placed in the `./app` directory.

Remember to adjust any paths or configurations in your Yii2 application to work with this Docker setup, particularly the database connection settings to use MongoDB.

## Troubleshooting

If you encounter any issues:

1. Ensure all containers are up and running:
   ```
   docker compose ps
   ```

2. Check the logs of a specific service:
   ```
   docker compose logs [service_name]
   ```

3. If you make changes to the Dockerfiles, remember to rebuild:
   ```
   docker compose up -d --build
   ```

4. For permission issues, you may need to adjust the user in the php.dockerfile or use chown in your host machine.

If you continue to face problems, please check the issue tracker or open a new issue with detailed information about your setup and the problem you're experiencing.
