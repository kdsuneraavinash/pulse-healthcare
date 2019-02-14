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

