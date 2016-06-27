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
    private function pegar_imgur($subreddit, $periodo)
    {
        $itens = get_imgur_reddit($subreddit, $periodo);
        // se não entrar no if significa que deu algum erro
        if($itens)
        {
            foreach ($itens as $item) {
                $post_existe = $this->M_Posts->find_by_id_imgur($item->id);
                
                if($post_existe != NULL)
                {
                    // atualiza
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
                    $post['is_album'] = boleano_xml($item->is_album);              
                    if(boleano_xml($item->is_album) == 1)
                    {
                        $post['link'] = link_item_imgur($item->hash);
                    }
                    else
                    {
                        $post['link'] = link_item_imgur($item->hash, $item->ext);                        
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
    }
    
    public function job_pegar_posts()
    {
        $origens_imgur = $this->config->item('origens_imgur');
        foreach ($origens_imgur as $origem) 
        {
            if($origem['ativo'])
            {
                $this->pegar_imgur($origem['nome_reddit'], 'day');
            }
        }
    }
    
    public function job_enviar_posts()
    {
        echo 'Job enviar_posts';        
    }
}
