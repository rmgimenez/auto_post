<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$config['origens_imgur'] = array(
    0 => array(
        'nome_reddit' => 'cosplay',
        'tags' => '#cosplay #costumes',
        'ativo' => TRUE
    ),
    
    1 => array(
        'nome_reddit' => 'funny',
        'tags' => '#funny',
        'ativo' => TRUE
    )
);

$config['origens_deviantart'] = array(
    0 => array(
        'rss' => 'http://backend.deviantart.com/rss.xml?q=favby%3AZ-Lord%2F61912148&type=deviation',
        'tags' => '#cosplay #sexy #girls #sexycosplay',
        'possui_nsfw' => FALSE, // indica se possui posts NFSW
    ),
    
    1 => array(
        'rss' => 'http://backend.deviantart.com/rss.xml?q=gallery%3AGirls-Cosplay%2F33609516&type=deviation',
        'tags' => '#cosplay #girls #sexy #deviantart',
        'possui_nsfw' => TRUE, // indica se possui posts NFSW
    ),
);

$config['parametros'] = array(
    'smtp_envio' => 0, // colocar aqui o indice do dados_smtp que quero utilizar, se tiver -1 é para pegar um aleatório toda vez que for postar
    'qtd_pegar_deviantart' => 3, // quantidade de rss do deviantart que será lido por vez no job
);

/*
 * Essa configuração armazena as configurações dos email responsáveis
 * pelos envios dos emails
 */
$config['dados_smtp'] = array(
    0 => array(
        'email' => 'rmgimenez1@hotmail.com',
        'smtp_secure' => 'tls',
        'username' => 'rmgimenez1@hotmail.com',
        'password' => 'moura85@',
        'from' => 'rmgimenez1@hotmail.com',
        'host' => 'smtp.live.com',
        'port' => '587',
        'is_smtp' => TRUE,
        'smtp_auth' => TRUE,
        'ativo' => TRUE,
    )
);

