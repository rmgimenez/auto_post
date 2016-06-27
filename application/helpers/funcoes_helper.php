<?php

if(!function_exists('get_url_data'))
{
    function get_url_data($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        curl_close($curl);
        return $curlData;
    }
}

/*
 * Função que retorna um array com os dados de um subreddit
 * 
 * Exemplo de uso:
 * $itens = get_imgur_reddit('cosplay', 'day');
        foreach ($itens as $item) {
            print $item->id;
            print '</br>';
        }
 */
if(!function_exists('get_imgur_reddit'))
{
    function get_imgur_reddit($subreddit, $periodo)
    {
        $link_xml = gera_link_reddit($subreddit, $periodo);
        $xml = simplexml_load_file($link_xml);
        
        if($xml[0]['status'] == 200)
        {
            return $xml;
        }
        else
        {
            return null;
        }
    }
}

if(!function_exists('gera_link_reddit'))
{
    function gera_link_reddit($subreddit, $periodo)
    {
        return 'http://imgur.com/r/'.$subreddit.'/top/'.$periodo.'.xml';       
    }
}

if(!function_exists('limpa_titulo'))
{
    function limpa_titulo($titulo)
    {
        $post_titulo = str_replace('[Photographer]', '', $titulo);
        $post_titulo = str_replace('[Found]', '', $post_titulo);
        $post_titulo = str_replace('[Self]', '', $post_titulo);
        $post_titulo = str_replace('[Help]', '', $post_titulo);

        $post_titulo = str_replace('[photographer]', '', $post_titulo);
        $post_titulo = str_replace('[found]', '', $post_titulo);
        $post_titulo = str_replace('[self]', '', $post_titulo);
        $post_titulo = str_replace('[help]', '', $post_titulo);

        $post_titulo = str_replace('[PHOTOGRAPHER]', '', $post_titulo);
        $post_titulo = str_replace('[FOUND]', '', $post_titulo);
        $post_titulo = str_replace('[SELF]', '', $post_titulo);
        $post_titulo = str_replace('[HELP]', '', $post_titulo);    
        return $post_titulo;
    }
}



