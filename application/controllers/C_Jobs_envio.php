<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Jobs_envio extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
    }
    
    public function list_all()
    {
        $dados_pagina['jobs_envio'] = $this->M_Jobs_envio->find_all('id', 'desc');
        $conteudo = $this->load->view('jobs_envio/list_all', $dados_pagina, true);
        
        $dados_template['conteudo'] = $conteudo;
        $dados_template['pagina_titulo_corpo'] = 'Listagem - Jobs de envio';
        $dados_template['pagina_titulo'] = 'Jobs de envio';
        $this->load->view('template', $dados_template);
    }
}
