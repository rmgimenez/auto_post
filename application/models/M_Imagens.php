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
}
/* End of file */
