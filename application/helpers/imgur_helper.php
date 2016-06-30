<?php

class Imgur
{
    public function __construct() 
    {
        
    }
    
    /**
     * Retorna um array com os dados dos posts de um subreddit
     * Formato
     * array(
     *  0 => array(
     *    'title' => 'teste....',
     *    'is_album' => 1,
     *    ... 
     *    'imagens' => array(
     *      0 => 'http://imgur.com/afdf.jpg',
     *      1 => 'http://imgur.com/sdfse.jpg',
     *      ...
     *    )
     *  ),
     * )
     */
    public function get_array_imgur($subreddit, $periodo)
    {
        $link_do_xml = $this->gera_link_reddit($subreddit, $periodo);
        $xml_imgur = $this->get_imgur_reddit($link_do_xml);
        if($xml_imgur)
        {
            $posts = array();
            $i = 0;
            foreach($xml_imgur as $item)
            {
                $posts[$i]['id'] = (int)$item->id;
                $posts[$i]['hash'] = (string)$item->hash;
                $posts[$i]['title'] = (string)$item->title;
                $posts[$i]['title_limpo'] = $this->limpa_titulo($item->title);
                $posts[$i]['description'] = (string)$item->description;
                $posts[$i]['views'] = (int)$item->views;
                $posts[$i]['score'] = (int)$item->score;
                $posts[$i]['reddit'] = (string)$item->reddit;
                $posts[$i]['reddit_link'] = $this->formata_link_reddit($item->reddit);
                $posts[$i]['subreddit'] = (string)$item->subreddit;
                $posts[$i]['nsfw'] = $this->boleano_xml($item->nsfw);
                $posts[$i]['is_album'] = $this->boleano_xml($item->is_album);  
                $posts[$i]['ext'] = (string)$item->ext;
                $posts[$i]['author'] = (string)$item->author;
                $posts[$i]['create_datetime'] = date($item->create_datetime);
                
                if($posts[$i]['is_album'] == 1)
                {
                    $posts[$i]['imagens'] = $this->get_imagens_album($item->hash);
                    $posts[$i]['source'] = 'http://imgur.com/a/'.$item->hash;
                    //print_r($this->get_dados_album($item->hash));   
                }
                else
                {
                    $posts[$i]['imagens'][] = 'http://imgur.com/'.$item->hash.$item->ext;
                    $posts[$i]['source'] = 'http://imgur.com/'.$item->hash;
                    //print_r($this->get_dados_imagem($item->hash));   
                }
                $posts[$i]['total_imagens'] = count($posts[$i]['imagens']);
                $i += 1;  
                
                                           
            }
            
            return $posts;                        
        }   
        else
        {
            // Se retornar null siginifica que deu algum erro ao pegar o xml
            return NULL;
        }             
    }
    
    /**
     * Retorna um array com os dados do album
     * Essa consulta utiliza a API do site imgur.com
     */
    private function get_dados_album($hash)
    {
        $client_id = "8150e8df809b5dd";
        $c_url = curl_init();
        curl_setopt($c_url, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c_url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c_url, CURLOPT_URL,"https://api.imgur.com/3/album/".$hash);
        curl_setopt($c_url, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
        $result=curl_exec($c_url);
        curl_close($c_url);
        $json_array = json_decode($result, true);
        return $json_array;
    }
    
    /**
     * Retorna um array com os dados da imagem
     * Essa consulta utiliza a API do site imgur.com
     */
    private function get_dados_imagem($hash)
    {
        $client_id = "8150e8df809b5dd";
        $c_url = curl_init();
        curl_setopt($c_url, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c_url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c_url, CURLOPT_URL,"https://api.imgur.com/3/image/".$hash);
        curl_setopt($c_url, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
        $result=curl_exec($c_url);
        curl_close($c_url);
        $json_array = json_decode($result, true);
        return $json_array;
    }    
    
    
    /**
     * Esse método retorna um array com todas as imagens do album
     */
    private function get_imagens_album($hash)
    {
        $stream = stream_context_create(array
        (
                'http' => array('user_agent' => 'Nokia6600/1.0 (5.27.0) SymbianOS/7.0s Series60/2.0 Profile/MIDP-2.0 Configuration/CLDC-1')
        ));
        $src = file_get_contents('http://imgur.com/a/'.$hash.'/layout/blog', FALSE, $stream);
        $doc = new DOMDocument();
        @$doc->loadHTML($src);
        $tags = $doc->getElementsByTagName('img');
        $imagens = array();
        foreach ($tags as $tag) 
        {
            //echo $tag->getAttribute('src').'</br>';
            if(substr($tag->getAttribute('src'), 0, 13) == '//i.imgur.com')
            {
                $link_imagem = 'http:'.$tag->getAttribute('src');
                if(!(in_array($link_imagem, $imagens)))
                {
                    $imagens[] = $link_imagem;
                }
            }   
        }
        
        return $imagens;
    }
    
    /**
     * Retorna um link para o xml de um subreddit
     */
    private function gera_link_reddit($subreddit, $periodo)
    {
        return 'http://imgur.com/r/'.$subreddit.'/top/'.$periodo.'.xml';       
    }
    
    /**
     * Retorna o xml de um link de um subreddit do site imgur.com
     * Se retornar null significa que deu algum erro
     */
    private function get_imgur_reddit($link_do_xml)
    {
        $xml = simplexml_load_file($link_do_xml);
        
        if($xml[0]['status'] == 200)
        {
            return $xml;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Retira algumas palavras do título
     */
    private function limpa_titulo($titulo)
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
    
    /**
     * Função que retorna o link do reddit formatado
     * como entrada seria:
     *      /r/cosplay/comments/4qa8we/photographer_pokemon_umbreon_cosplay/
     */
    private function formata_link_reddit($parte_reddit)
    {
        return 'http://reddit.com'.$parte_reddit;
    }
    
    /**
     * Retorna 1 ou 0 dependendo do que vier do xml
     */
    private function boleano_xml($valor)
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
}