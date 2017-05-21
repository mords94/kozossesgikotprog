CREATE SEQUENCE "public.clubs_id_seq" START 1;
CREATE SEQUENCE "public.comment_id_seq" START 1;
CREATE SEQUENCE "public.photo_id_seq" START 1;
CREATE SEQUENCE "public.school_id_seq" START 1;
CREATE SEQUENCE "public.users_id_seq" START 1;
CREATE SEQUENCE "public.workplace_id_seq" START 1;

CREATE TABLE clubs
(
  id INTEGER DEFAULT nextval('"public.clubs_id_seq"'::regclass) PRIMARY KEY NOT NULL,
  name VARCHAR(30) NOT NULL
);

CREATE TABLE users
(
  id INTEGER DEFAULT nextval('"public.users_id_seq"'::regclass) PRIMARY KEY NOT NULL,
  email VARCHAR(30) NOT NULL,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  password VARCHAR(32) NOT NULL,
  gender SMALLINT NOT NULL,
  registered_at TIMESTAMP DEFAULT now(),
  birthdate DATE NOT NULL,
  photo_id INTEGER
);

CREATE TABLE club_member
(
  since DATE DEFAULT now() NOT NULL,
  user_id INTEGER NOT NULL,
  club_id INTEGER NOT NULL,
  CONSTRAINT "public.user_id" FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT "public.club_id" FOREIGN KEY (club_id) REFERENCES clubs (id)
);

CREATE TABLE photo
(
  id INTEGER DEFAULT nextval('"public.photo_id_seq"'::regclass) PRIMARY KEY NOT NULL,
  title TEXT,
  src TEXT NOT NULL
);

CREATE TABLE comment
(
  id INTEGER DEFAULT nextval('"public.comment_id_seq"'::regclass) PRIMARY KEY NOT NULL,
  description VARCHAR(30) NOT NULL,
  wrote_at TIMESTAMP DEFAULT now(),
  photo_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  CONSTRAINT "public.photo_id" FOREIGN KEY (photo_id) REFERENCES photo (id),
  CONSTRAINT "public.user_id" FOREIGN KEY (user_id) REFERENCES users (id)
);


CREATE TABLE message
(
  id INTEGER PRIMARY KEY NOT NULL,
  status BOOLEAN NOT NULL,
  body VARCHAR(30) NOT NULL,
  wrote_at DATE DEFAULT now() NOT NULL,
  from_user_id INTEGER NOT NULL,
  to_user_id INTEGER NOT NULL,
  CONSTRAINT "public.from_user_id" FOREIGN KEY (from_user_id) REFERENCES users (id),
  CONSTRAINT "public.to_user_id" FOREIGN KEY (to_user_id) REFERENCES users (id)
);
CREATE TABLE school
(
  id INTEGER DEFAULT nextval('"public.school_id_seq"'::regclass) PRIMARY KEY NOT NULL,
  name VARCHAR(30) NOT NULL
);
CREATE TABLE user_friend
(
  user_id INTEGER NOT NULL,
  friend_id INTEGER NOT NULL,
  status INTEGER DEFAULT 0
);
CREATE TABLE user_school
(
  "from" DATE DEFAULT now() NOT NULL,
  "to" DATE DEFAULT now() NOT NULL,
  school_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  CONSTRAINT "public.school_id" FOREIGN KEY (school_id) REFERENCES school (id),
  CONSTRAINT "public.user_id" FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE workplace
(
  id INTEGER DEFAULT nextval('"public.workplace_id_seq"'::regclass) PRIMARY KEY NOT NULL,
  name VARCHAR(30) NOT NULL
);


CREATE TABLE user_work
(
  "from" DATE DEFAULT now() NOT NULL,
  "to" DATE DEFAULT now() NOT NULL,
  user_id INTEGER NOT NULL,
  workplace_id INTEGER NOT NULL,
  CONSTRAINT "public.user_id" FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT "public.workplace_id" FOREIGN KEY (workplace_id) REFERENCES workplace (id)
);