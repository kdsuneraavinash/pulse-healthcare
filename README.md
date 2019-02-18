# Pulse Health care Project

## Running Project

`Ubuntu` + `intellij IDEA` is preferred to run this project.

1. Install `PHP` and `mysql`. Run 

   ```bash
   sudo apt-get install php
   sudo apt-get install php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml libapache2-mod-php
   
   sudo apt-get install mysql-server
   sudo apt-get install php7.2-mysqli
   sudo mysql
   ```

2. Add `root` user with `root` password.

   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
   ```

3. Run `mysql_secure_installation`. While running `mysql_secure_installation`, don't give `y` to `validate password plugin`.

4. Run `mysql` with the `root` privilege.

   ```bash
   mysql -u root -p
   ```

6. Create the database and user.
   
   ```sql
    CREATE USER 'pulse_root'@'localhost' IDENTIFIED BY 'password';
    CREATE DATABASE pulse;
    USE pulse;
    GRANT ALL PRIVILEGES ON pulse . * TO 'pulse_root'@'localhost';
    ```
   
5. Create the tables. Refer to `web/dump.sql` for database dump.

6. Run `web/public/` folder from `PHP`.

    ```bash
    cd web/public
    php -S localhost:8000
    ```

7. Navigate to `http://localhost:8000` to verify that everything is working properly.

## User Management

Current user = `pTest`

Current password = `password`

## Database Tables

| Table            | Responsibility                                               |
| ---------------- | ------------------------------------------------------------ |
| sessions         | Manage user securely to allow users to be logged in even after closing the browser |
| user_agents      | Storing browser agents to be used in sessions                |
| user_credentials | Storing user ids and passwords securely                      |
| user_types       | Storing user types(patient, doctor, medical center, admin) to be used in users table |
| users            | Store each user and type                                     |
| test             | Test database                                                |

## Available URL Paths

| URL      | Method   | Action                                       |
| -------- | -------- | -------------------------------------------- |
| /        | GET      | View home Page                               |
| /login   | GET      | View login page                              |
| /login   | POST     | Login user                                   |
| /profile | GET      | View profile(only if logged in)              |
| /logout  | POST     | Logout user                                  |
| /test    | GET/POST | Test database connection and GET/POST status |

## TODO

- [x] Implement user login
- [ ] Implement medical center account creation
- [ ] Implement doctor/patient account creation
- [ ] Implement profile viewing
- [ ] Implement selecting patient account
- [ ] Implement timeline
- [ ] Implement medication adding

## Members

>  K. D. Sunera Avinash Chandrasiri
>  T. Anju Chamantha
>  Lahiru Udayanga