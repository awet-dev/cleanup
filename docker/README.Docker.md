## Dockerized PHP Repository

### Overview

This repository contains a Dockerized PHP environment for seamless development and deployment.

### Prerequisites

- Docker installed on your machine

### Getting Started

1. Clone the repository:
   ```bash
   git clone https://github.com/awet-dev/dockerized-php-app.git
2. Start the container:
   ```bash
   cd docker
   ```
    ```bash
   docker compose up -d 

### Check the web services
- adminer: visit [adminer](http://localhost:8080) then connect using the database variables
- Mailer: visit [mailcatcher](http://localhost:1080)

### Add or Create your php app

- #### Existing application
- From Git repository you can use this command to add your app to the application directory
  ```bash
    git clone <repository-url> application
- #### New Application
  > whether it is laravel or symfony you can create your project with the composer command
  > ```bash
  > docker exec -it docker-server-1 bash
  >

  > Check if composer installed and use the composer to create your app
  > ```bash
  > composer -v
  >

  > For Symfony you can create using this command
  > ```bash
  > composer create-project symfony/skeleton:"7.0.*" .
  > ```
  > If it is web app run the following command otherwise skip it
  > ```bash
  > composer require webapp
  > ```
  > If you asked to add docker configuration enter no, because you already have docker configuration

### Configure your environment variables
-  symfony replace the variables starting with MYSQL_ with the configuration in compose.yaml file for db service, and so the same for mailer and so on
   > ```copy 
    > DATABASE_URL="mysql://MYSQL_USER:MYSQL_PASSWORD@db:3306/MYSQL_DATABASE?serverVersion=8.3.0&charset=utf8mb4"
    > ```
   > ```copy
    > MAILER_DSN=smtp://mailer:1025

Finally you are ready to go, enjoy coding

### Deploying your application to the cloud

First, build your image, e.g.: `docker build -t myapp .`.
If your cloud uses a different CPU architecture than your development
machine (e.g., you are on a Mac M1 and your cloud provider is amd64),
you'll want to build the image for that platform, e.g.:
`docker build --platform=linux/amd64 -t myapp .`.

Then, push it to your registry, e.g. `docker push myregistry.com/myapp`.

Consult Docker's [getting started](https://docs.docker.com/go/get-started-sharing/)
docs for more detail on building and pushing.