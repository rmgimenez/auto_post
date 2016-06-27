<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Job extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->config->load('rmg_config');
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
            $this->pegar_posts();
            $this->enviar_posts();            
        }
        else
        {
            $chance = rand(1,100);
            if($chance <= $chance_de_pegar_posts)
            {
                $this->pegar_posts();
            }
            else
            {
                $this->enviar_posts();
            }
        }
        
    }
    
    public function pegar_posts()
    {
        echo 'Job pegar_posts';
    }
    
    public function enviar_posts()
    {
        echo 'Job enviar_posts';        
    }
}
