<?php
/**
 * Description of M_Postagens
 *
 * @author rgimenez
 */
class M_Postagens extends MY_Model {
    function __construct()
    {
        parent::__construct();
        $this->table = 'postagens';
    }
}
/* End of file */
