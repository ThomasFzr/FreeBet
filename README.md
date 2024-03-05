# Installation Guide


# Installing Docker Desktop

[Download Docker Desktop | Docker](https://www.docker.com/products/docker-desktop/)

# Project Setup

- Clone the project:
```bash
git clone https://github.com/ThomasFzr/FreeBet.git
```
  
- Launch the Apache server:
  ```bash
  docker-compose up -d
  ``` 
- Provide necessary permissions to the src folder :
   ```bash
  sudo chmod 777 -R src
   ```  

Move every file and folder into the src folder.
  

# Database Access

To access the database, you can use any tool, but we recommend using Beekeeper Studio, a powerful database editor.

[https://github.com/beekeeper-studio/beekeeper-studio](https://github.com/beekeeper-studio/beekeeper-studio)

Once in the application, configure a new connection. 

![Untitled](https://i.imgur.com/RZ693Z2.png)

Here, we choose MySQL. 

Fill in the connection information with the details provided in the docker-compose.yml file.

![Untitled](https://i.imgur.com/uxmEInv.png)

Now, you have access to the database.


- Navigate to the src folder:
  ```bash
  cd src
  ```
- Install dependencies :
  ```bash
  composer install
  ```

- Go to the internet and enter the URL: `http://localhost:8080/`
