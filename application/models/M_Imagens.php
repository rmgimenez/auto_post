<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Imagens
 *
 * @author rgimenez
 */
class M_Imagens extends MY_Model {
    function __construct()
    {
        parent::__construct();
        $this->table = 'imagens';
    }
    
    public function find_all_por_post($post_id, $trazer_apenas_imagens = TRUE)
    {
        $this->db->where('post_id', $post_id);
        if($trazer_apenas_imagens)
        {
            $this->db->not_like('link', '.gif');
        }
        
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}
/* End of file */
