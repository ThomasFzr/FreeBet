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

# for the moment we create directly the databse inside beekeeper with this query

```sql
CREATE TABLE User (user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), surname VARCHAR(255), mail VARCHAR(255) NOT NULL, pwd VARCHAR(255) NOT NULL, phoneNbr VARCHAR(255), isAdmin BOOL NOT NULL DEFAULT false, coin INT, UNIQUE(mail));
                        CREATE TABLE IF NOT EXISTS Football_match (match_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, date VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, opponent_team_id INT NOT NULL, opponent_team_name VARCHAR(255) NOT NULL, OL_score INT, opponent_score INT, victorious_team_id INT);
                        CREATE TABLE IF NOT EXISTS Bet (bet_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, match_id INT NOT NULL, user_id INT NOT NULL, victorious_team_id INT NOT NULL, coin INT, updated BOOL default 0,FOREIGN KEY (match_id) REFERENCES Football_match(match_id), FOREIGN KEY (user_id) REFERENCES User(user_id));
```

# for load the data go on the url : http://localhost:8080/fillFootData 

return to home page football you can now see the sheet og the result and the match incomming.

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
  
