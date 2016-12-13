<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Locais
 *
 * @author Morbach
 */
class Zend_View_Helper_Locaisinativos extends Zend_View_Helper_Abstract {

    //put your code here
    protected $_conexao;
    protected static $dados;

    function __construct() {
        $this->_conexao = Zend_Db_Table::getDefaultAdapter();
    }

    public function locaisinativos() {
        $query = "SELECT * FROM locais where in_ativo = 'S'";
        try{
            return $this->_conexao->fetchAll($query);
        }catch(Zend_Db_Table_Exception $e){
            echo $e->getMessage();
        }
    }

}

?>
