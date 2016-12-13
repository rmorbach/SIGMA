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
class Application_Model_Sensoresradio extends Zend_Db_Table_Abstract {

    protected $_name = "radio_sensores";
    protected $_id = "id";

    /**
     * @uses método vincular um sensor a um rádio
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function vincularSensorRadio(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para desvincular um sensor de um radio
     * @param int $idSensorRadio
     * @param int $idSensor = null
     * @param int $idUsuario = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function desvincularSensorRadio($idSensorRadio, $idSensor = null, $idRadio = null) {
        $where = "id = '$idSensorRadio'";
        if ($idSensor) {
            $where .= "and id_sensor = '$idSensor'";
        }
        if ($idRadio) {
            $where .= "and id_radio = '$idRadio'";
        }
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados da tabela usuarios_grupo
     * @param array $dados
     * @param int $idSensorRadio
     * @param int $idSensor = null
     * @param int $idRadio = null
     * @param int $idLocal
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateSensorRadio(array $dados, $idSensorRadio, $idSensor = null, $idRadio = null) {
        $where = "id = '$idSensorRadio'";
         if ($idSensor) {
            $where .= "and id_sensor = '$idSensor'";
        }
        if ($idRadio) {
            $where .= "and id_radio = '$idRadio'";
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
