/*
drop table posts;
drop table imagens;
drop table postagens;
*/
-- http://imgur.com/r/cosplay/top/day.xml
create table posts(
    id integer primary key autoincrement,
    id_imgur varchar(50),
    hash varchar(20),
    author varchar(100),
    title varchar(100),
    title_limpo varchar(100),
    score integer,
    views integer,
    reddit varchar(200),
    subreddit varchar(100),
    description text,
    create_datetime timestamp,
    section varchar(100),
    nsfw integer,
    is_album integer,
    site_origem varchar(100), -- imgur ou deviantart
    tags varchar(200),
    link vachar(200),
    ext varchar(10),
    grupo varchar(100)
);

create table imagens(
    id integer primary key autoincrement,
    posts_id integer,
    link varchar(200)
);

create table postagens(
    id integer primary key autoincrement,
    post_id integer,
    data_post timestamp,
    destino_postado integer
);

----
-- Table structure for senders
----
CREATE TABLE 'senders' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'email' TEXT, 'smtp_secure' TEXT, 'username' TEXT, 'password' TEXT, 'form' TEXT, 'host' TEXT, 'port' INTEGER, 'is_smtp' INTEGER, 'smtp_auth' INTEGER, 'ativo' INTEGER);
INSERT INTO "senders" ("id","email","smtp_secure","username","password","form","host","port","is_smtp","smtp_auth","ativo") VALUES ('1','rmgimenez1@hotmail.com','tls','rmgimenez1@hotmail.com','moura85@','rmgimenez1@hotmail.com','smtp.live.com','587','1','1','1');

----
-- Table structure for origens_imgur
----
CREATE TABLE 'origens_imgur' ('id' INTEGER PRIMARY KEY NOT NULL, 'nome' TEXT, 'nome_reddit' TEXT, 'tags' TEXT, 'grupo' TEXT, 'ativo' INTEGER DEFAULT 1 );
INSERT INTO "origens_imgur" ("id","nome","nome_reddit","tags","grupo","ativo") VALUES ('1','r/cosplay','cosplay','#cosplay #costumes','cosplay','1');

COMMIT;