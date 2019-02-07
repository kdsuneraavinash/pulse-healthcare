# Pulse Health care Project

## Running Project

`Ubuntu` is preferred to run this project.

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

5. Create the database and table.

   ```sql
   CREATE USER 'pulse_root'@'localhost' IDENTIFIED BY 'password';
   CREATE DATABASE pulse;
   USE pulse;
   GRANT ALL PRIVILEGES ON pulse . * TO 'pulse_root'@'localhost';
   CREATE TABLE test (ID int NOT NULL, LastName varchar(255) NOT NULL, FirstName varchar(255), Age int,PRIMARY KEY (ID));
   INSERT INTO test (ID, LastName, FirstName, Age ) VALUES (170081, "Chandrasiri", 'Sunera', 22 );
   SELECT * FROM test;
   ```

6. Run `web/public/` folder from `PHP`.
7. Navigate to `http://localhost:8000/test` to verify that everything is working properly.

