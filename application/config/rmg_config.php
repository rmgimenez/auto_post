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

$config['destinos'] = array(
    0 => array(
        'email' => '',
        'tipo_site' => 'tumblr',
        'site' => 'cosplay-and-costumes.tumblr.com',
        'pode_nsfw' => FALSE,
        'tags' => '#cosplay',
    ),
);

$config['encurtador_link'] = array(
    0 => 'http://q.gs/1905608/',    
);

$config['parametros'] = array(
    'smtp_envio' => 0, // colocar aqui o indice do dados_smtp que quero utilizar, se tiver -1 é para pegar um aleatório toda vez que for postar
    'qtd_pegar_deviantart' => 1, // quantidade de rss do deviantart que será lido por vez no job
    
    'usar_encurtador_link' => TRUE,
    // colocar aqui qual o índice do item encurtador_link que irá utilizar
    'tipo_encurtador_link' => 0,
    
    /*
     * Esse índice do array armazena a chance rodar a função de pegar posts
     * no job principal. Por exemplo, se tiver 50 indica que tem 50% de chance
     * de rodar para pegar os posts e 50% de chance para postar.
     * Se tiver -1 indica que é para rodar as duas funções no job
     */
    'chance_de_pegar_posts' => 50,
    
    /*
     * Quantidade de itens que serão postados por vez que rodar o job
     */
    'qtd_postar_imgur' => 1,
    'qtd_postar_deviantart' => 1,
    
    /*
     * Forma de buscar o item do imgur para envio
     * 0 = aleatório
     * 1 = ordenado por ID de forma decrescente (último inserido)
     * 2 = ordenado por score de forma decrescente (com maior score)
     */
    'forma_envio_imgur' => 1,
    
    /*
     * Forma de buscar o item do deviantart para envio
     * 0 = aleatório
     * 1 = ordenado por ID de forma decrescente (último inserido)
     */
    'forma_envio_imgur' => 0,
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

