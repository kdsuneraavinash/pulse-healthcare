create table test
(
  ID        varchar(32)             not null
    primary key,
  LastName  varchar(255)            not null,
  FirstName varchar(255)            null,
  Age       int                     null,
  Password  varchar(128) default '' not null
);

INSERT INTO pulse.test (ID, LastName, FirstName, Age, Password) VALUES ('170074', 'Chamantha', 'Anju', 22, 'anju');
INSERT INTO pulse.test (ID, LastName, FirstName, Age, Password) VALUES ('170081', 'Chandrasiri', 'Sunera', 22, 'sunera');
INSERT INTO pulse.test (ID, LastName, FirstName, Age, Password) VALUES ('170109', 'Udayanga	', 'Lahiru', 22, 'lahiru');
INSERT INTO pulse.test (ID, LastName, FirstName, Age, Password) VALUES ('pTest', 'Doe', 'John', 101, 'password');