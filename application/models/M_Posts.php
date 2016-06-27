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
}
/* End of file */