<?php

/**
 * Origens dos subreddit do site imgur.com
 */
$config['origens_imgur'] = array(
    0 => array(
        'nome' => 'r/cosplay',
        'nome_reddit' => 'cosplay',
        'tags' => '#cosplay #costumes',
        'ativo' => TRUE,
        'grupo' => 'cosplay',
    ),
    
    1 => array(
        'nome' => 'r/funny',
        'nome_reddit' => 'funny',
        'tags' => '#funny',
        'ativo' => TRUE,
        'grupo' => 'cosplay',
    )
);

/**
 * Links rss das páginas do deviantart
 */
$config['origens_deviantart'] = array(
    0 => array(
        'nome' => 'Lord%2F61912148&type=deviation',
        'rss' => 'http://backend.deviantart.com/rss.xml?q=favby%3AZ-Lord%2F61912148&type=deviation',
        'tags' => '#cosplay #sexy #girls #sexycosplay',
        'possui_nsfw' => FALSE, // indica se possui posts NFSW
        'grupo' => 'cosplay',
        'ativo' => FALSE,
    ),
    
    1 => array(
        'nome' => 'Girls-Cosplay%2F33609516&type=deviation',
        'rss' => 'http://backend.deviantart.com/rss.xml?q=gallery%3AGirls-Cosplay%2F33609516&type=deviation',
        'tags' => '#cosplay #girls #sexy #deviantart',
        'possui_nsfw' => TRUE, // indica se possui posts NFSW
        'grupo' => 'cosplay',
        'ativo' => TRUE,
    ),
);

/**
 * Destinos que receberão os posts
 */
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

/**
 * Parâmetros gerais do sistema
 */
$config['parametros'] = array(
    'qtd_pegar_deviantart' => 1, // quantidade de rss do deviantart que será lido por vez no job
    
    'usar_encurtador_link' => TRUE, // TRUE ou FALSE, indica se que usar o encurtador de link no campo source dos posts
    'tipo_encurtador_link' => 0, // coloque aqui o índice do item 'encurtador_link'
    
    /*
     * Esse índice do array armazena a chance rodar a função de pegar posts
     * no job principal. Por exemplo, se tiver 50 indica que tem 50% de chance
     * de rodar para pegar os posts e 50% de chance para postar.
     * Se tiver -1 indica que é para rodar as duas funções no job
     */
    'chance_de_pegar_posts' => 50,
);

/**
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
    ),
);

$config['jobs_para_enviar'] = array(
    0 => array(
        'ativo' => TRUE,
        'origem_grupo' => 'cosplay',
        'origem_site' => 'todos', // colocar aqui o site que é para pegar, pode ser: imgur, deviantart ou todos
        'ordem' => 'id', // colocar aqui por qual campo ordenar ou se é aleatório
        'ordem_forma' => 'desc',
        'quantidade' => 1, // quantos serão postados por vez
        'servidor_smtp' => 0, // índice do 'dados_smtp'
        'destino' => 0, // índice do 'destinos'
    ),
);