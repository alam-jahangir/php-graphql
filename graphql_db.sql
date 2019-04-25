create table users
(
  id                bigint unsigned auto_increment
    primary key,
  name              varchar(255) not null,
  full_name         varchar(255) null,
  email             varchar(255) not null,
  email_verified_at timestamp    null,
  password          varchar(255) not null,
  remember_token    varchar(100) null,
  created_at        timestamp    null,
  updated_at        timestamp    null,
  constraint users_email_unique
    unique (email)
)
  collate = utf8mb4_unicode_ci;
