create table test
(
  ID        varchar(32)             not null
    primary key,
  LastName  varchar(255)            not null,
  FirstName varchar(255)            null,
  Age       int                     null,
  Password  varchar(128) default '' not null
);

create table user_agents
(
  id         int auto_increment
    primary key,
  user_agent text       not null,
  hash       binary(20) not null,
  constraint user_agents_hash_uindex
    unique (hash)
)
  comment 'Table to save user agents';

create table sessions
(
  user        varchar(32) not null,
  ip_address  varchar(45) not null,
  user_agent  int         not null,
  created     datetime    not null,
  expires     datetime    not null,
  session_key binary(20)  not null,
  primary key (user, ip_address, user_agent),
  constraint sessions_user_agents_id_fk
    foreign key (user_agent) references user_agents (id)
      on delete cascade
)
  comment 'Table to store sessions of all users';

