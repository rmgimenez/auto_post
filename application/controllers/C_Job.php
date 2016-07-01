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
                        $dados_imagem['post_id'] = $id;
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
    
    private function enviar_email($post, $job)
    {
        $this->load->library('My_PHPMailer');
        $mail = new PHPMailer();
        
        $sender = $this->M_Senders->find_id($job['sender_id']);
        $imagens = $this->M_Imagens->find_all_por_post($post['id'], TRUE);
        $destino = $this->M_Destinos->find_id($job['destino_id']);
        $parametros = $this->M_Parametros->find_id(1);
        
        $mail->SMTPSecure = $sender['smtp_secure'];
        $mail->Username = $sender['username'];
        $mail->Password = $sender['password'];
        //$mail->AddAddress("rmgimenez1@hotmail.com"); // pessoa que receberá o email
        $mail->From = $sender['email'];
        $mail->Host = $sender['host'];
        $mail->Port = $sender['port'];
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        
        $mail->FromName = "Ricardo";
        $mail->AddAddress($destino['email']);
        $mail->Subject = $post['title_limpo'];
        
        $encurtador_link = '';
        if($job['usar_encurtador_link'] == 1)
        {
            $encurtador_link = $parametros['encurtador_link'];
        } 
        
        $mensagem = '<p>'.$post['title_limpo'].'</p>';
        $mensagem .= nl2br(texto_to_link($post['description'], $encurtador_link));
        $mensagem .= '<p><b>Source: </b>'.texto_to_link(formata_link_reddit($post['reddit']), $encurtador_link).'</p>';
        $mensagem .= $post['tags'].' '.$job['tags'].' '.$destino['tags'];
        $mail->Body = $mensagem;
        foreach($imagens as $imagem)
        {
            $mail->addStringAttachment(file_get_contents($imagem['link']), get_nome_arquivo_imgur($imagem['link']));
        }        
        
        //$mail->IsHTML(true);
        if($mail->Send())
        {
            $enviado = array(
                'id' => $post['id'],
                'titulo' => $post['title_limpo'],
            );
        }
        else
        {
            $enviado = array(
                'Erro' => 'falha ao enviar',
            );
        }
        
        $record_postado = array(
            'post_id' => $post['id'],
            'data_post' => date('Y-m-d H:i:s'),
            'destino_id' => $destino['id'],        
        );
        $this->M_Postagens->insert($record_postado);
        
        return $enviado;
    } 
    
    public function job_enviar_posts()
    {
        $jobs = $this->M_Jobs_envio->find_all_ativo();
        foreach($jobs as $job)
        {
            if($job['chance_de_ser_executado'] >= sorteio())
            {
                $i = 0;
                while($i < $job['quantidade'])
                {
                    $post = $this->M_Posts->busca_para_job($job['grupo'], $job['ordem'], $job['ordem_forma'], 1, $job['destino_id']);
                    $post = $post[0];
                    print_r($this->enviar_email($post, $job));
                    
                    $i += 1;
                }
            }
        }       
    }
    
    public function testes2()
    {
        $this->job_enviar_posts();
    }
    
    public function testes()
    {
        echo texto_to_link('www.yahoo.com http://www.yahoo.com www.yahoo.com', 'http://adfly.com/44455/');
    }
}
