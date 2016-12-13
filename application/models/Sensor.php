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
class Application_Model_Sensor extends Zend_Db_Table_Abstract {

    protected $_name = "sensores";
    protected $_id = "id";

    /**
     * @uses método cadastrar um sensor
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarSensor(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um sensor
     * @param int $idSensor
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosSensor($idSensor) {
        $where = "id = '$idSensor'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir um sensor
     * @param int $idSensor
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirSensor($idSensor) {
        $where = "id = '$idSensor'";

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados da tabela usuarios_grupo
     * @param array $dados
     * @param int $idSensor
     * @param int $idLocal
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateSensor(array $dados, $idSensor) {
        $where = "id = '$idSensor'";

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

    /**
     * Retorna os sensores existentes em uma seção
     * @param int $idSecao
     * @return array $dados
     * @version 1.0 11/12/2013
     * @author Morbach
     */
    public static function getSensoresFromSecao($idSecao) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "select (radio.id) as id_radio, sensor.id, sensor.nm_sensor, sensor.fl_unidade_medida, sensor.nm_unidade_medida from sensores sensor,
                radios radio, radios_secao radiossec, secoes sec, radio_sensores radiosensor
                where
                    radiosensor.id_sensor = sensor.id
                and
                    radiosensor.id_radio = radio.id
                and radiossec.id_radio = radio.id
                and sec.id = radiossec.id_secao
                and sec.id = $idSecao";
        try {
            return $db->fetchAll($query);
        } catch (Zend_Db_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses Método para retornar os sensores vinculados a um rádio
     * @param int $idRadio
     * @return array $query
     * @version 1.0 09/11/2013
     * @author Morbach
     */
    public static function getSensorByRadio($idRadio) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "SELECT sensores.nm_sensor, sensores.id
                FROM sensores
                JOIN radio_sensores
                JOIN radios
                ON sensores.id=radio_sensores.id_sensor 
                AND radio_sensores.id_radio = radios.id and radios.id = '$idRadio' order by sensores.nm_sensor ";
        try {
            return $db->fetchAll($query);
        } catch (Zend_Db_Exception $e) {
            echo $e->getMessage();
        }
    }

}

?>
