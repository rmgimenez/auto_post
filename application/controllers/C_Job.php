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
    
    private function pegar_imgur($subreddit, $periodo)
    {
        $itens = get_imgur_reddit($subreddit, $periodo);
        // se não entrar no if significa que deu algum erro
        if($itens)
        {
            foreach ($itens as $item) {
                $post_existe = $this->M_Posts->find_by_id_imgur($item->id_imgur);
                
                if($post_existe)
                {
                    // atualiza
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

                    $this->M_Posts->insert($post);
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
