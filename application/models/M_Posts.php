<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of M_Posts
 *
 * @author rgimenez
 */
class M_Posts extends MY_Model {
    function __construct()
    {
        parent::__construct();
        $this->table = 'posts';
    }
    
    public function find_by_id_imgur($id_imgur)
    {
        if ($id_imgur == NULL)
        {
                return NULL;
        }

        $this->db->where('id_imgur', $id_imgur);
        $query = $this->db->get($this->table);

        $result = $query->result_array();
        return (count($result) > 0 ? $result[0] : NULL);
    }
    
    public function busca_para_job($grupo, $ordem_coluna, $ordem_tipo, $quantidade, $nao_trazer_postado_em)
    {
        if($nao_trazer_postado_em > 0)
        {
            $SQL = "select *
                    from posts
                    where posts.id not in (select post_id from postagens where destino_id = {$nao_trazer_postado_em})";
        }
        else
        {
            $SQL = "select *
                    from posts 
                    where 1 = 1";
        }
        
        $SQL .= " and posts.grupo = '{$grupo}'";
        $SQL .= " and posts.total_imagens > 0";
        
        if($ordem_coluna == 'random')
        {
            $SQL .= " ORDER BY RANDOM()";
        }
        else
        {
            $SQL .= " ORDER BY {$ordem_coluna} {$ordem_tipo}";
        }
        
        $SQL .= " limit {$quantidade}";         
        
        $query = $this->db->query($SQL);

        return $query->result_array();
    }
    
    public function find_by_hash($hash)
    {
        if ($hash == NULL)
        {
                return NULL;
        }

        $this->db->where('hash', $hash);
        $query = $this->db->get($this->table);

        $result = $query->result_array();
        return (count($result) > 0 ? $result[0] : NULL);
    }
    
    public function find_by_link($link)
    {
        if ($link == NULL)
        {
                return NULL;
        }

        $this->db->where('link', $link);
        $query = $this->db->get($this->table);

        $result = $query->result_array();
        return (count($result) > 0 ? $result[0] : NULL);
    }
}
/* End of file */