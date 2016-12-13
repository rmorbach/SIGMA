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
class Application_Model_Condicaoalarme extends Zend_Db_Table_Abstract {

    protected $_name = "condicoes_alarmes";
    protected $_id = "id";

    /**
     * @uses método para cadastrar uma condição de alarme
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function cadastrarCondicaoAlarme(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar condições de um alarme
     * @param int $idCondicaoAlarme
     * @param int $idRadio = null
     * @param int $idSensor = null
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function getDadosCondicaoAlarme($idCondicaoAlarme = null, $idRadio = null, $idSensor = null) {
        if ($idCondicaoAlarme) {
            $where = "id = '$idCondicaoAlarme'";
        } elseif (($idRadio) && ($idSensor)) {
            $where .= "id_radio = '$iRadio' and id_sensor = '$idSensor'";
        }
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para excluir uma condição de alarme
     * @param int $idAlarme
     * @return boolean alarme excluído
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluiCondicaoAlarme($idCondicaoAlarme) {
        if ($idCondicaoAlarme) {
            $where = "id = '$idCondicaoAlarme'";
        }

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses mÃ©todo para atualizar os dados de uma condição alarme
     * @param array $dados
     * @param int $idCondicaoAlarme     
     * @param int $idRadio = null
     * @param int $idSensor = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateCondicaoAlarme(array $dados, $idCondicaoAlarme = null, $idRadio = null, $idSensor = null) {
        if ($idCondicaoAlarme) {
            $where = "id = '$idCondicaoAlarme'";
        }
        elseif(($idRadio)&&($idSensor)){
            $where = "id_radio = '$idRadio' and id_sensor = '$idSensor'";
        }
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->update($dados, $where);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses MÃ©todo para verificar se os dados do array correspondem 
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
