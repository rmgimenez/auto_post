----
-- phpLiteAdmin database dump (https://bitbucket.org/phpliteadmin/public)
-- phpLiteAdmin version: 1.9.6
-- Exported: 10:22pm on June 30, 2016 (CEST)
-- database file: ./application/banco/\banco.sqlite
----
BEGIN TRANSACTION;

----
-- Drop table for imagens
----
DROP TABLE "imagens";

----
-- Table structure for imagens
----
CREATE TABLE imagens(
    id integer primary key autoincrement,
    posts_id integer,
    link varchar(200)
);

----
-- Data dump for imagens, a total of 0 rows
----

----
-- Drop table for origens
----
DROP TABLE "origens";

----
-- Table structure for origens
----
CREATE TABLE "origens" ('id' INTEGER PRIMARY KEY NOT NULL, 'nome' TEXT, 'nome_reddit' TEXT, 'tags' TEXT, 'grupo' TEXT, 'ativo' INTEGER DEFAULT 1 );

----
-- Data dump for origens, a total of 1 rows
----
INSERT INTO "origens" ("id","nome","nome_reddit","tags","grupo","ativo") VALUES ('1','r/cosplay','cosplay','#cosplay #costumes','cosplay','1');

----
-- Drop table for senders
----
DROP TABLE "senders";

----
-- Table structure for senders
----
CREATE TABLE 'senders' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'email' TEXT, 'smtp_secure' TEXT, 'username' TEXT, 'password' TEXT,'from' TEXT, 'host' TEXT, 'port' INTEGER, 'is_smtp' INTEGER, 'smtp_auth' INTEGER, 'ativo' INTEGER);

----
-- Data dump for senders, a total of 1 rows
----
INSERT INTO "senders" ("id","email","smtp_secure","username","password","from","host","port","is_smtp","smtp_auth","ativo") VALUES ('1','rmgimenez1@hotmail.com','tls','rmgimenez1@hotmail.com','moura85@','rmgimenez1@hotmail.com','smtp.live.com','587','1','1','1');

----
-- Drop table for destinos
----
DROP TABLE "destinos";

----
-- Table structure for destinos
----
CREATE TABLE 'destinos' ('id' INTEGER PRIMARY KEY NOT NULL, 'email' TEXT, 'tipo_site' TEXT, 'site' TEXT, 'pode_nsfw' INTEGER DEFAULT 0 , 'tags' TEXT, 'texto_adicional' TEXT, 'descricao' TEXT);

----
-- Data dump for destinos, a total of 3 rows
----
INSERT INTO "destinos" ("id","email","tipo_site","site","pode_nsfw","tags","texto_adicional","descricao") VALUES ('1','w3ndyiyq6n82c@tumblr.com','tumblr','cosplay-and-costumes.tumblr.com','0','#cosplay','','Cosplay and Costumes');
INSERT INTO "destinos" ("id","email","tipo_site","site","pode_nsfw","tags","texto_adicional","descricao") VALUES ('2','9bvkjmooirf9k@tumblr.com','tumblr','','0','#geek','','SOS Geek');
INSERT INTO "destinos" ("id","email","tipo_site","site","pode_nsfw","tags","texto_adicional","descricao") VALUES ('3','rmgimenez.rage@blogger.com','blogger','','0','','','RageFaces - blogger');

----
-- Drop table for posts
----
DROP TABLE "posts";

----
-- Table structure for posts
----
CREATE TABLE 'posts' (
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
    nsfw integer,
    is_album integer, -- imgur ou deviantart
    tags varchar(200),
    grupo varchar(100)
, 'source' TEXT, 'origem_imgur_id' INTEGER, 'total_geral_imagens' INTEGER DEFAULT 0, 'total_gif' INTEGER DEFAULT 0, 'total_imagens' INTEGER DEFAULT 0, 'reddit_link' TEXT);

----
-- Data dump for posts, a total of 0 rows
----

----
-- Drop table for jobs_envio
----
DROP TABLE "jobs_envio";

----
-- Table structure for jobs_envio
----
CREATE TABLE "jobs_envio" ('id' INTEGER PRIMARY KEY NOT NULL, 'ativo' INTEGER DEFAULT 0 , 'grupo' TEXT, 'ordem' TEXT, 'ordem_forma' TEXT, 'quantidade' INTEGER, 'sender_id' INTEGER, 'destino_id' INTEGER, 'chance_de_ser_executado' INTEGER DEFAULT 0, 'usar_encurtador_link' INTEGER DEFAULT 0);

----
-- Data dump for jobs_envio, a total of 1 rows
----
INSERT INTO "jobs_envio" ("id","ativo","grupo","ordem","ordem_forma","quantidade","sender_id","destino_id","chance_de_ser_executado","usar_encurtador_link") VALUES ('1','1','cosplay','id','desc','2','1','1','80','0');

----
-- Drop table for postagens
----
DROP TABLE "postagens";

----
-- Table structure for postagens
----
CREATE TABLE 'postagens' (
    id integer primary key autoincrement,
    post_id integer,'data_post' timestamp,'destino_id' integer
);

----
-- Data dump for postagens, a total of 0 rows
----

----
-- Drop table for parametros
----
DROP TABLE "parametros";

----
-- Table structure for parametros
----
CREATE TABLE 'parametros' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'encurtador_link' TEXT, 'ajuda' TEXT);

----
-- Data dump for parametros, a total of 1 rows
----
INSERT INTO "parametros" ("id","encurtador_link","ajuda") VALUES ('1','http://q.gs/1905608/',NULL);

----
-- Drop table for jobs_pegar_posts
----
DROP TABLE "jobs_pegar_posts";

----
-- Table structure for jobs_pegar_posts
----
CREATE TABLE 'jobs_pegar_posts' ('id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'periodo' TEXT, 'ativo' INTEGER DEFAULT 0 , 'chance_de_ser_executado' INTEGER DEFAULT 0 , 'origem_id' INTEGER, 'descricao' TEXT);

----
-- Data dump for jobs_pegar_posts, a total of 2 rows
----
INSERT INTO "jobs_pegar_posts" ("id","periodo","ativo","chance_de_ser_executado","origem_id","descricao") VALUES ('1','day','1','50','1','Cosplay - Day');
INSERT INTO "jobs_pegar_posts" ("id","periodo","ativo","chance_de_ser_executado","origem_id","descricao") VALUES ('2','week','1','20','1','Cosplay - Week');
COMMIT;
