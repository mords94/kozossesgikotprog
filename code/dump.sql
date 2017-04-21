create table users
(
	id serial not null
		constraint "public.users_id_pk"
			primary key,
	email varchar(30) not null,
	firstname varchar(30) not null,
	lastname varchar(30) not null,
	password varchar(30) not null,
	gender smallint not null,
	registered_at timestamp default now(),
	birthdate date not null,
	photo_id integer
)
;

create table photo
(
	id serial not null
		constraint "public.photo_pkey"
			primary key,
	title text,
	src text not null
)
;

create table user_friend
(
	user_id integer not null,
	friend_id integer not null,
	status integer default 0
)
;

create table clubs
(
	id serial not null
		constraint "public.clubs_pkey"
			primary key,
	name char not null
)
;

create table club_member
(
	since date default now() not null,
	user_id integer not null
		constraint public.user_id
			references users,
	club_id integer not null
		constraint public.club_id
			references clubs
)
;

create table public_message
(
	id integer not null
		constraint public_message_pkey
			primary key,
	status boolean not null,
	body char not null,
	wrote_at date default now() not null,
	from_user_id integer not null
		constraint public.from_user_id
			references users,
	to_user_id integer not null
		constraint public.to_user_id
			references users
)
;

create table comment
(
	id serial not null
		constraint "public.comment_pkey"
			primary key,
	description char not null,
	wrote_at date default now(),
	photo_id integer not null
		constraint public.photo_id
			references photo,
	user_id integer not null
		constraint public.user_id
			references users
)
;

create table user_school
(
	"from" date default now() not null,
	"to" date default now() not null,
	school_id integer not null,
	user_id integer not null
		constraint public.user_id
			references users
)
;

create table school
(
	id serial not null
		constraint "public.school_pkey"
			primary key,
	name char not null
)
;

alter table user_school
	add constraint public.school_id
		foreign key (school_id) references school
;

create table workplace
(
	id serial not null
		constraint "public.workplace_pkey"
			primary key,
	name char not null
)
;

create table user_work
(
	"from" date default now() not null,
	"to" date default now() not null,
	user_id integer not null
		constraint public.user_id
			references users,
	workplace_id integer not null
		constraint public.workplace_id
			references workplace
)
;