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
class Application_Model_Log extends Zend_Db_Table_Abstract {

    protected $_name = "logs";
    protected $_id = "id";

    /**
     * @uses método para regitrar log de um sensor
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function registrarLog(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um log
     * @param int $idLog = null
     * @param int $idRadio = null
     * @param int $idSensor = null
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getLogSensor($idLog = null, $idRadio = null, $idSensor = null, $dtInicial = null, $dtFinal = null, $limit = null, $order = null) {
        $order = (($order) ? $order : "asc");
        if ($idLog) {
            $where = "id = '$idLog'";
        } else if (($idRadio) && ($idSensor)) {
            $where = "id_radio = '$idRadio' and id_sensor = '$idSensor'";
        }
        if (($dtFinal) && ($dtInicial)) {
            $where .= "and dt_hr_registro >= '$dtInicial' and dt_hr_registro <= '$dtFinal'";
        }
        try {
            return $this->fetchAll($where, 'id ' . $order, $limit);
            
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir um log
     * @param int $idLog
     * @param int $idSensor = null
     * @param int $idRadio = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirLog($idLog = null, $idRadio = null, $idSensor = null) {
        if ($idLog) {
            $where = "id = '$idLog'";
        }
        if (($idRadio) && ($idSensor)) {
            $where .= "and id_radio = '$idRadio' and id_sensor = '$idSensor'";
        }

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de um log
     * @param array $dados
     * @param int $idLog
     * @param int $idRadio = null
     * @param int $idSensor = null     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateLog(array $dados, $idLog, $idSensor = null, $idRadio = null) {
        if ($idLog) {
            $where = "id = '$idLog'";
        }
        if (($idRadio) && ($idSensor)) {
            $where .= "and id_radio = '$idRadio' and id_sensor = '$idSensor'";
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
     * Método para retornar os logs com base no identificador de uma seção. Usado na tela inicial
     * @param int $idSecao, identificador da seção
     * @param int $limit, quantidade máxima de elementos retornados
     * @return array $dados
     * @version 1.0 11/12/2013
     * @author Morbach
     */
    public static function getLogsfromSecao($idSecao, $limit = 5) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "select log.vl_dado, log.dt_hr_registro from logs log,
                radios radio, radios_secao radiosec, secoes secao
                where
                    log.id_radio = radio.id
                and
                    radio.id = radiosec.id_radio
                and
                    radiosec.id_secao = secao.id
                and 
                    secao.id = $idSecao
                limit $limit";
        try {
            return $db->fetchAll($query);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }
    /*
     * Método utilizado para retornar dados de log para um serviço webservice, com base no nome do local, da seção, e da variável monitorada
     * @param $local, nome do local
     * @param $secao, nome da secao
     * @param $sensor, nome do sensor
     * @param $dtInicial, data de início
     * @param $dtFinal, data de final
     * @limit = null, limit, caso haja.
     * @author Rodrigo Morbach
     * @version 1.0 01/02/2014
     */
    public static function getLogsToWebservice($local, $secao, $sensor, $dtInicial, $dtFinal, $limit = null) {
        $db = Zend_Db_Table::getDefaultAdapter();
        //$order = (($order) ? $order : "desc");
        $query = "select log.vl_dado, log.dt_hr_registro,sensor.in_webservice, sensor.nm_sensor,local.nm_local, 
	secao.nm_secao from logs log, 
                radios radio, radios_secao radiosec, secoes secao, locais local, secoes_local secoes_local, sensores sensor, radio_sensores sensor_radio
                where
                    sensor.id = sensor_radio.id_sensor
		and
                    radio.id = sensor_radio.id_radio
		and
                    log.id_radio = radio.id
                and 
                    log.id_sensor = sensor.id
                and
                    radio.id = radiosec.id_radio
                and
                    radiosec.id_secao = secao.id
                and 
                    secao.id = secoes_local.id_secao
                and
                    secoes_local.id_local = local.id
		and	
                    local.nm_local like lower('$local%')
		and
                    secao.nm_secao like lower('$secao%')
                and
                    sensor.nm_sensor like lower('$sensor%') 
				and log.dt_hr_registro >= '$dtInicial' and log.dt_hr_registro <= '$dtFinal'";        
        try {             
             return $db->fetchAll($query);
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
