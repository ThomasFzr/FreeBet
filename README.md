
##Rapport d'Avancement du Projet

#Janvier : Lancement du Projet

En janvier, nous avons  lancé le projet. Cette phase initiale a été consacrée à des sessions de brainstorming  pour définir les objectifs du projet, identifier les principales fonctionnalités à développer et établir une feuille de route générale. L'équipe a proposé diverses idées  et a discuté des meilleures pratiques. Cette période de réflexion a jeté les bases pour les étapes suivantes du développement.

#Février : Mise en Place du Projet

En février, nous sommes passés à la phase de mise en place du projet. Plusieurs actions clés ont été réalisées :

- Choix des Technologies : Nous avons évalué différentes technologies et finalement choisi celles qui convenaient le mieux à nos besoins en termes de performance, de scalabilité et de facilité de maintenance.
- Répartition des Tâches : Les responsabilités ont été clairement définies et assignées à chaque membre de l'équipe, garantissant ainsi une répartition équilibrée du travail.
- Maquette Figma : Une maquette détaillée de l'application a été créée sur Figma. Cela a permis de visualiser l'interface utilisateur et d'aligner l'équipe sur les objectifs de conception.

#Mars : Développement des Fonctionnalités de Base

En mars, l'équipe s'est concentrée sur le développement des fonctionnalités de base :

- Système de Connexion et d'Inscription : Nous avons implémenté un système sécurisé de connexion et d'inscription pour les utilisateurs.
- Page Profil : Une page de profil utilisateur a été créée, permettant aux utilisateurs de gérer leurs informations personnelles.
- Mise en Place des Tables de la BDD : La structure de la base de données a été définie et les tables nécessaires ont été créées, assurant ainsi une organisation efficace des données.

 #Avril : Intégration des Fonctionnalités de Paris

En avril, nous avons ajouté des fonctionnalités essentielles pour le cœur de notre application :

- Système de Paris : Nous avons intégré un système de paris permettant aux utilisateurs de placer des paris sur divers événements.
- Gain/Perte de Coins : Les mécanismes de gain et de perte de coins en fonction des résultats des paris ont été mis en place.
- Récupération des Données de l'API : Nous avons configuré la récupération de données à partir d'une API externe pour alimenter notre application en informations pertinentes.

 #Mai : Ajout de Fonctionnalités Sociales et Administratives

En mai, nous avons enrichi l'application avec des fonctionnalités sociales et administratives :

- Système d'Ajout d'Amis : Les utilisateurs peuvent désormais ajouter des amis, favorisant ainsi l'interaction sociale au sein de l'application.
- Ajout de Match en Mode Admin : Les administrateurs ont la possibilité d'ajouter de nouveaux matchs, assurant une mise à jour régulière et dynamique des événements disponibles pour les paris.

 #Juin : Classement des Meilleurs Joueurs

En juin, nous avons introduit un système de classement :

- Classement entre Amis : Un système de classement a été implémenté pour permettre aux utilisateurs de voir qui sont les meilleurs joueurs parmi leurs amis. Cela ajoute un élément de compétition et d'engagement supplémentaire à l'application.




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


