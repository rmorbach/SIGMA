<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author Morbach
 */
class Application_Model_Alarme extends Zend_Db_Table_Abstract {

    protected $_name = "alarmes_gerados";
    protected $_id = "id";

    /**
     * @uses método cadastrar um alarme
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarAlarme(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um alarme
     * @param int $idAlarme
     * @param int $idLog = null
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosAlarme($idAlarme = null, $idLog = null) {
        if ($idAlarme) {
            $where = "id = '$idAlarme'";
        }
        if($idLog){
            $where .= "and id_log = '$idLog'";
        }
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir um alarme
     * @param int $idAlarme
     * @return boolean alarme excluído ou nao
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirAlarme($idAlarme) {
         if ($idAlarme) {
            $where = "id = '$idAlarme'";
        }        

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de um alarme
     * @param array $dados
     * @param int $idAlarme     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateAlarme(array $dados, $idAlarme) {
        $where = "id = '$idAlarme'";

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
