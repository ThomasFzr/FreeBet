# Installation Guide


# Installing Docker Desktop

[Download Docker Desktop | Docker](https://www.docker.com/products/docker-desktop/)

# Project Setup
- Create a folder to contain your code.
- In this folder, create a file: `docker-compose.yml`
- Paste the following content (be mindful of spaces and tabs):

```
version: '3'

services:
  web:
    build: .
    ports:
      - "8080:80" # Expose port 8080 on WSL to port 80 in the container
    volumes:
      - ./src:/var/www/html

  mysql:
    image: mysql:5.7
    environment:
      MYSQL_ROOT_PASSWORD: my-secret-pw
      MYSQL_DATABASE: my_database
      MYSQL_USER: my_user
      MYSQL_PASSWORD: my_password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306" # Expose port 3306 on the host to port 3306 in the container

volumes:
  db_data:
  ```

- Create a file : ```Dockerfile```  
- Paste the following content :  
  
 ```
FROM php:8.2-apache
# Install additional PHP extensions
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN service apache2 restart 
```

  
- Launch the Apache server: ```docker-compose up -d``` 
- Provide necessary permissions to the src folder : ```sudo chmod 777 -R src```  
  

# Database Access

To access the database, you can use any tool, but we recommend using Beekeeper Studio, a powerful database editor.

[https://github.com/beekeeper-studio/beekeeper-studio](https://github.com/beekeeper-studio/beekeeper-studio)

Once in the application, configure a new connection. 

![Untitled](https://i.imgur.com/RZ693Z2.png)

Here, we choose MySQL. 

Fill in the connection information with the details provided in the docker-compose.yml file.

![Untitled](https://i.imgur.com/uxmEInv.png)

Now, you have access to the database.


- Clone the github repo: ```git clone https://github.com/B2-Info-23-24/php-ThomasFzr.git src``` from your folder
- Navigate to the src folder: ```cd src```
- Install dependencies : ```composer install```

- Find your container number ```docker ps```
- Copy the number below CONTAINER ID, corresponding to the php-web image (e.g., b21268552815)

- Create the database with this command, replacing b21268552815 with your CONTAINER ID:  ```docker exec b21268552815 php createDb.php```

- Go to the internet and enter the URL: `http://localhost:8080/`
