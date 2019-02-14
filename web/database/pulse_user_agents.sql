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

INSERT INTO pulse.user_agents (id, user_agent, hash) VALUES (1, 'UNKNOWN', 0x25BA44EC3B391BA4CE5FBBD2979635E254775E7D);
INSERT INTO pulse.user_agents (id, user_agent, hash) VALUES (4, 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36', 0x2D13425A6C604C6A91F6FFEDEC343A81E891B535);