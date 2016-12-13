<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Evento
 *
 * @author Morbach
 */
class Application_Model_Regra extends Zend_Db_Table_Abstract {

    protected $_name = 'regras';
    protected $_id = 'id';

    /**
     * @uses método criar uma regra
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function criarRegra(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de uma regra
     * @param int $idRegra     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function getRegra($idRegra) {
        $where = "id = '$idRegra'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir uma regra
     * @param int $idRegra
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function excluirRegra($idRegra) {
        $where = "id = '$idRegra'";

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de uma regra
     * @param array $dados
     * @param int $idRegra     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function updateRegra(array $dados, $idRegra) {
        $where = "id = '$idRegra'";
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->update($dados, $where);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses Método para verificar se os dados do array correspondem 
     * as colunas da tabela no banco de dados
     * @param array $dados
     * @return array $dadosFiltrados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function filtrarColunas(array $dados) {
        $dados['nr_ip_terminal'] = $_SERVER['REMOTE_ADDR'];
        $dados['dt_inclusao'] = date('Y-m-d');
        $colunas = $this->_getCols();
        $dadosFiltrados = array();
        try {
            foreach ($dados as $chave => $valor) {
                if (in_array($chave, $colunas)) {
                    $dadosFiltrados[$chave] = $valor;
                }
            }
            return $dadosFiltrados;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
  
}

?>
