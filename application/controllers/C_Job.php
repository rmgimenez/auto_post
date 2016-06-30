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
        $this->job_pegar_posts();
        $this->job_enviar_posts();            
    }
    
    /**
     * Pega os itens de um subreddit e um período e insere ou atualiza no banco
     * de dados
     */
    private function pegar_imgur($subreddit, $periodo, $origem_id)
    {
        $origem = $this->M_Origens->find_id($origem_id);
        
        $total['origem'] = $origem['nome'];
        $total['total'] = 0;
        $total['atualizados'] = 0;
        $total['inseridos'] = 0;
        
        $this->load->helper('imgur');
        $imgur = new Imgur();
        $itens = $imgur->get_array_imgur($subreddit, $periodo);
        
        // se não entrar no if significa que deu algum erro
        if($itens)
        {
            foreach ($itens as $item) 
            {
                $total['total'] += 1;
                
                $post_existe = $this->M_Posts->find_by_hash($item['hash']);
                
                if($post_existe != NULL)
                {
                    // atualiza
                    $total['atualizados'] += 1;
                    
                    $post = array(
                        'views' => $item['views'],
                        'score' => $item['score']
                    );
                    $this->db->where('id', $post_existe['id']);
                    $this->db->update('posts', $post);
                }
                else
                {
                    // insere
                    $total['inseridos'] += 1;
                    
                    $post = array();
                    $post['id_imgur'] = $item['id'];
                    $post['hash'] = $item['hash'];
                    $post['title'] = $item['title'];
                    $post['title_limpo'] = $item['title_limpo'];
                    $post['description'] = $item['description'];
                    $post['views'] = $item['views'];
                    $post['score'] = $item['score'];
                    $post['reddit'] = $item['reddit'];
                    $post['reddit_link'] = $item['reddit_link'];
                    $post['subreddit'] = $item['subreddit'];
                    $post['nsfw'] = $item['nsfw'];
                    $post['is_album'] = $item['is_album'];
                    $post['author'] = $item['author'];
                    $post['create_datetime'] = $item['create_datetime'];
                    $post['source'] = $item['source'];
                    $post['total_geral_imagens'] = $item['total_geral_imagens'];
                    $post['total_gif'] = $item['total_gif'];
                    $post['total_imagens'] = $item['total_imagens'];     
                    $post['grupo'] = $origem['grupo'];
                    $post['tags'] = $origem['tags'];
                    $post['origem_imgur_id'] = $origem['id'];

                    $id = $this->M_Posts->insert($post);
                    
                    foreach($item['imagens'] as $imagem)
                    {
                        $dados_imagem = array();
                        $dados_imagem['posts_id'] = $id;
                        $dados_imagem['link'] = $imagem;
                        $this->M_Imagens->insert($dados_imagem);
                    }
                }
            }                       
        }   
        
        return $total; 
    }
    
    public function job_pegar_posts()
    {
        $jobs = $this->M_Jobs_pegar_posts->find_all_ativo();
        foreach($jobs as $job)
        {
            if($job['chance_de_ser_executado'] >= sorteio())
            {
                $origem = $this->M_Origens->find_id($job['origem_id']);
                print('<p>Job Executado = '.$job['descricao'].'</p>');
                print_r($this->pegar_imgur($origem['nome_reddit'], $job['periodo'], $job['origem_id']));
            }
        }
    }
    
    public function job_enviar_posts()
    {
        echo 'Job enviar_posts';        
    }
    
    public function testes2()
    {
        print_r($this->M_Jobs_pegar_posts->find_all_ativo());
    }
    
    public function testes()
    {
        $origens_imgur = $this->config->item('origens_imgur');
        foreach($origens_imgur as $origem) 
        {
            if($origem['ativo'])
            {
                print_r($this->pegar_imgur($origem['nome_reddit'], 'day', $origem));
                print_r($this->pegar_imgur($origem['nome_reddit'], 'week', $origem));
                print_r($this->pegar_imgur($origem['nome_reddit'], 'month', $origem));
                print_r($this->pegar_imgur($origem['nome_reddit'], 'year', $origem));
                print_r($this->pegar_imgur($origem['nome_reddit'], 'all', $origem));
            }
        }
    }
}
