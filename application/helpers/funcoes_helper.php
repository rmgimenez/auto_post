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

/*
 * Retorna 1 ou 0 dependendo do que vier do xml
 */
function boleano_xml($valor)
{
	if($valor == 'true')
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

/*
 * Limpa o título dos posts
 */
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
        return trim($post_titulo);
    }
}

if(!function_exists('link_item_imgur'))
{
    function link_item_imgur($hash, $extensao = null)
    {
        if($extensao === null)        
        {
            return 'http://imgur.com/a/'.$hash;
        }
        else
        {
            return 'http://imgur.com/'.$hash.$extensao;
        }
    }
}


/**
 * Funções utilizadas para pegar os posts da deviantart
 */ 
function get_image_sites($link)
{
	$link = fix_url($link);
	$image_sites = array("flickr" => '/https?:\/\/[w\.]*flickr\.com\/photos\/([^?]*)/is', "twitpic" => '/https?:\/\/[w\.]*twitpic\.com\/([^?]*)/is', "imgur" => '/https?:\/\/[w\.]*imgur\.[^\/]*\/([^?]*)/is',	"deviantart" => '/https?:\/\/[^\/]*\.*deviantart\.[^\/]*\/([^?]*)/is', "instagram" => '/https?:\/\/[w\.]*instagram\.[^\/]*\/([^?]*)/is');
	foreach($image_sites as $site => $regexp)
	{
		preg_match($regexp, $link, $match);
		if(!empty($match))
		{
			switch ($site)
			{
				case "flickr":
				$flickr_json = "http://www.flickr.com/services/oembed/?format=json&maxwidth=500&maxheight=380&url=".$link;
				$image = get_json_response($flickr_json);
				break;
				case "instagram":
				$instagram_json = "http://api.instagram.com/oembed?format=json&maxwidth=500&maxheight=380&url=".$link;
				$image = get_json_response($instagram_json);
				break;
				case "deviantart":
				$deviantart_json = "http://backend.deviantart.com/oembed?format=json&thumbnail_width=500&thumbnail_height=380&url=".$link;
				$image = get_json_response($deviantart_json);
				break;
				case "twitpic":
				$code = $match[1];
				$image = "<img src='http://twitpic.com/show/large/".$code.".jpg'>";
				break;
				case "imgur":
				$imgur_json = "http://api.imgur.com/oembed/?format=json&url=".$link;
				$image = get_json_response($imgur_json);
				break;
				case "":
				$image = "";
				break;
			}
			return $image;
		}
	}
}

//function used to fix the url by adding http / https
function fix_url($url) {
 if (substr($url, 0, 7) == 'http://') { return $url; }
 if (substr($url, 0, 8) == 'https://') { return $url; }
 return 'http://'. $url;
}

function get_json_response($url)
{
	$json_response = get_url_data($url);
	$res = json_decode($json_response, true);
	if(is_array($res) && !empty($res))
	{
		//$image = "<img src='".$res["url"]."'>";
        $image = $res["url"];
		return $image;
	}
}

/**
 * Função que retorna o link do reddit formatado
 * como entrada seria:
 *      /r/cosplay/comments/4qa8we/photographer_pokemon_umbreon_cosplay/
 */
function formata_link_reddit($parte_reddit)
{
    return 'http://reddit.com'.$parte_reddit;
}

/**
 * Retorna um número aleatório entre 1 e 100
 */
function sorteio()
{
    return rand(1, 100);
}