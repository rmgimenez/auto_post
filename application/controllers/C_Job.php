<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Job extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->config->load('rmg_config');
        $this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        
    }
    
    public function rodar_job()
    {
        $parametros = $this->config->item('parametros');
        $chance_de_pegar_posts = $parametros['chance_de_pegar_posts'];
        if($chance_de_pegar_posts == -1)
        {
            $this->job_pegar_posts();
            $this->job_enviar_posts();            
        }
        else
        {
            $chance = rand(1,100);
            if($chance <= $chance_de_pegar_posts)
            {
                $this->job_pegar_posts();
            }
            else
            {
                $this->job_enviar_posts();
            }
        }
    }
    
    /*
     * Pega os itens de um subreddit e um período e insere ou atualiza no banco
     * de dados
     */
    private function pegar_imgur($subreddit, $periodo, $origem)
    {
        $total['origem'] = $origem['nome'];
        $total['total'] = 0;
        $total['atualizados'] = 0;
        $total['inseridos'] = 0;
        $itens = get_imgur_reddit($subreddit, $periodo);
        // se não entrar no if significa que deu algum erro
        if($itens)
        {
            foreach ($itens as $item) 
            {
                $total['total'] += 1;
                
                $post_existe = $this->M_Posts->find_by_id_imgur($item->id);
                
                if($post_existe != NULL)
                {
                    // atualiza
                    $total['atualizados'] += 1;
                    
                    $post = array(
                        'views' => $item->views,
                        'score' => $item->score
                    );
                    $this->db->where('id', $post_existe['id']);
                    $this->db->update('posts', $post);
                }
                else
                {
                    // insere
                    $total['inseridos'] += 1;
                    
                    $post = array();
                    $post['id_imgur'] = $item->id;
                    $post['hash'] = $item->hash;
                    $post['title'] = $item->title;
                    $post['title_limpo'] = limpa_titulo($item->title);
                    $post['author'] = $item->author;
                    $post['score'] = $item->score;
                    $post['description'] = $item->description;                    
                    $post['nsfw'] = boleano_xml($item->nsfw);
                    $post['reddit'] = $item->reddit;
                    $post['ext'] = $item->ext;
                    $post['subreddit'] = $item->subreddit;
                    $post['site_origem'] = 'imgur';
                    $post['is_album'] = boleano_xml($item->is_album);     
                    $post['grupo'] = $origem['grupo'];
                    $post['tags'] = $origem['tags'];         
                    if(boleano_xml($item->is_album) == 1)
                    {
                        $post['link'] = link_item_imgur($item->hash);
                    }
                    else
                    {
                        $post['link'] = 'http://imgur.com/'.$item->hash;                        
                    }

                    $id = $this->M_Posts->insert($post);
                    if(boleano_xml($item->is_album) == 1)
                    {
                        // se for um album
                        $stream = stream_context_create(array
                        (
                                'http' => array('user_agent' => 'Nokia6600/1.0 (5.27.0) SymbianOS/7.0s Series60/2.0 Profile/MIDP-2.0 Configuration/CLDC-1')
                        ));
                        $src = file_get_contents('http://imgur.com/a/'.$item->hash.'/layout/blog', FALSE, $stream);
                        $doc = new DOMDocument();
                        @$doc->loadHTML($src);
                        $tags = $doc->getElementsByTagName('img');
                        foreach ($tags as $tag) 
                        {
                            //echo $tag->getAttribute('src').'</br>';
                            if(substr($tag->getAttribute('src'), 0, 13) == '//i.imgur.com')
                            {
                                $link_imagem = 'http:'.$tag->getAttribute('src');
                                $imagem = array();
                                $imagem['posts_id'] = $id;
                                $imagem['link'] = $link_imagem;
                                $this->M_Imagens->insert($imagem);
                            }   
                        }                        
                    }
                    else
                    {
                        // se não for um album                        
                        $imagem = array();
                        $imagem['posts_id'] = $id;
                        $imagem['link'] = link_item_imgur($item->hash, $item->ext);
                        $this->M_Imagens->insert($imagem);
                    }
                }
            }                       
        }   
        
        return $total; 
    }
    
    private function pegar_deviantart($origem)
    {
        $total['origem'] = $origem['nome'];
        $total['total'] = 0;
        $total['atualizados'] = 0;
        $total['inseridos'] = 0;
        
        $rss = $origem['rss'];
        $feed = get_url_data($rss);

        try
        {
            $feed_formatado = new SimpleXmlElement($feed);
        } 
        catch (Exception $e) 
        {
            $total['erros'][] = 'Erro ao ler feed: '.$e->getMessage();                        
        }
        
        foreach($feed_formatado->channel->item as $entrada)
        {
            $total['total'] += 1;
            
            $post_existe = $this->M_Posts->find_by_link($entrada->link);
            
            if($post_existe == NULL)
            {
                $total['inseridos'] += 1;
                
                $post = array();
                $post['title'] = $entrada->title;
                $post['title_limpo'] = limpa_titulo($entrada->title);
                $post['description'] = $entrada->description;                    
                if($origem['possui_nsfw'])
                {
                    $post['nsfw'] = 1;
                }
                else
                {
                    $post['nsfw'] = 0;
                }
                $post['site_origem'] = 'deviantart';
                $post['is_album'] = 0;
                $post['link'] = $entrada->link;
                $post['grupo'] = $origem['grupo'];
                $post['tags'] = $origem['tags'];
                $id = $this->M_Posts->insert($post);
                $link_imagem = get_image_sites($entrada->link);
                $imagem = array();
                $imagem['posts_id'] = $id;
                $imagem['link'] = $link_imagem;
                $this->M_Imagens->insert($imagem);
            }
            else
            {
                $total['atualizados'] += 1;
            }
        	/*echo $entrada->title.'</br>';
        	echo $entrada->link;
            echo get_image_sites($entrada->link);
        	echo $entrada->description;
        	echo '</br></br>';*/
        }     
        
        return $total;   
    }
    
    public function job_pegar_posts()
    {
        $origens_imgur = $this->config->item('origens_imgur');
        foreach($origens_imgur as $origem) 
        {
            if($origem['ativo'])
            {
                print_r($this->pegar_imgur($origem['nome_reddit'], 'day', $origem));
            }
        }
        
        $parametros = $this->config->item('parametros');
        $origens_deviantart = $this->config->item('origens_deviantart');
        
        foreach($origens_deviantart as $key=>$value)
        {
            if($value['ativo'] == FALSE)
            {
                unset($origens_deviantart[$key]);
            }                        
        }
                        
        $origens_sorteadas = array_rand($origens_deviantart, $parametros['qtd_pegar_deviantart']);
        if($parametros['qtd_pegar_deviantart'] == 1)
        {
            print_r($this->pegar_deviantart($origens_deviantart[$origens_sorteadas]));
        }
        else if($parametros['qtd_pegar_deviantart'] >= 1)
        {
            foreach($origens_sorteadas as $origem_sorteada)
            {
                print_r($this->pegar_deviantart($origens_deviantart[$origens_sorteadas]));
            }
        }
    }
    
    public function job_enviar_posts()
    {
        echo 'Job enviar_posts';        
    }
    
    public function testes()
    {
        $parametros = $this->config->item('parametros');        
        $origens_deviantart = $this->config->item('origens_deviantart');
        print_r($origens_deviantart);
        
        foreach($origens_deviantart as $key=>$value)
        {
            if($value['ativo'] == FALSE)
            {
                unset($origens_deviantart[$key]);
            }                        
        }
        print_r($origens_deviantart);
        
        $origens = array_rand($origens_deviantart, $parametros['qtd_pegar_deviantart']);
        print_r($origens);
    }
}
