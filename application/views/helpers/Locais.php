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
class Zend_View_Helper_Locais extends Zend_View_Helper_Abstract {

    //put your code here
    protected $_conexao;
    protected static $dados;

    function __construct() {
        $this->_conexao = Zend_Db_Table::getDefaultAdapter();
    }

    public function locais($inativo = null, $idUsuario) {
        //$query = "SELECT * FROM locais order by nm_local";        
        $query = "select loc.id, loc.nm_local, loc.ds_local, loc.vl_latitude, 
            loc.vl_longitude, loc.endereco, loc.id_cidade, loc.cep, loc.in_ativo 
            from locais loc, usuarios_local usuloc, usuarios usu
            where loc.id = usuloc.id_local 
            and usuloc.id_usuario = usu.id 
            and usu.id = '$idUsuario'";
        if ($inativo) {
            $query .= "and in_ativo = '$inativo'";
        }
        $query .= " order by loc.nm_local";
        try {
            return $this->_conexao->fetchAll($query);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

}

?>
