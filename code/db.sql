/*
Users table
 */
CREATE TABLE public.users (
  id INTEGER NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  email CHARACTER VARYING(255) NOT NULL,
  firstname CHARACTER VARYING(255) NOT NULL,
  lastname CHARACTER VARYING(255) NOT NULL,
  password CHARACTER VARYING(255) NOT NULL,
  gender SMALLINT NOT NULL,
  registered_at TIMESTAMP WITHOUT TIME ZONE DEFAULT now(),
  birthdate DATE NOT NULL,
  photo_id INTEGER
);
CREATE UNIQUE INDEX users_id_uindex ON users USING BTREE (id);
CREATE UNIQUE INDEX users_email_uindex ON users USING BTREE (email);
COMMENT ON TABLE public.users IS 'Felhasznalok';


/*
  Photo table
 */
CREATE TABLE public.photo (
  id INTEGER NOT NULL DEFAULT nextval('photo_id_seq'::regclass),
  title TEXT,
  src TEXT NOT NULL
);

/**
User has photo foreign key constaint
 */

ALTER TABLE public.users
  ADD CONSTRAINT users__photo_fk
FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE SET NULL;

/*
User friend relationship
 */
CREATE TABLE public.user_friend (
  user_id INTEGER NOT NULL,
  friend_id INTEGER NOT NULL,
  status INTEGER DEFAULT 0
);