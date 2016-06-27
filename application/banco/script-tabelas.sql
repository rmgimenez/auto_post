/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  rgimenez
 * Created: 27/06/2016
 */
-- http://imgur.com/r/cosplay/top/day.xml
create table posts(
    id integer primary key auto_increment,
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
    link vachar(200)
);

create table imagens(
    id integer primary key auto_increment,
    posts_id integer,
    link varchar(200)
);

create table postagens(
    id integer primary key auto_increment,
    post_id integer,
    data_post timestamp,
    destino_postado integer
);