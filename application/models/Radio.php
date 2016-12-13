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
class Application_Model_Radio extends Zend_Db_Table_Abstract {

    /*Nome da tabela no banco de dados*/    
    protected $_name = "radios";
    /*Identificador único no banco de dado*/
    protected $_id = "id";        
    
    /**
     * @uses método cadastrar um radio sensor
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarRadio(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um rádio
     * @param int $idRadio
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosRadio($idRadio) {
        $where = "id = '$idRadio'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir um radio sensor
     * @param int $idSensor
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirRadio($idRadio) {
        $where = "id = '$idRadio'";

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de um radio
     * @param array $dados
     * @param int $idRadio     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateRadio(array $dados, $idRadio) {
        $where = "id = '$idRadio'";

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
     * @uses Método para retornar os rádios e sensores de uma seção.
     * @param int $idSecao
     * @return array $dados
     * @author Morbach
     * @version 1.0 22/09/2013
     */
    public static function dadosRadiosSensoresSecao($idSecao) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $idSecao = $db->quote($idSecao);

        $query = "select (radio.id) as id_radio, (radio.nm_radio) as nm_radio, (radio.ds_funcao) as ds_funcao, (radio.pan_id) as pan_id,
                    (radio.net_id) as net_id, (radio.dest_addr) as dest_addr, (radio.high_id) as high_id, (radio.in_ativo) as in_ativo                    
                    from 
                        radios radio,                 
                        secoes secao,
                        radios_secao radio_secao
                    where
                       
                    radio_secao.id_radio = radio.id
                    and radio_secao.id_secao = secao.id
                    and secao.id = $idSecao";     
        try {
            return $db->fetchAll($query);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function radioCadastrado($mac, $idSecao) {
        $db = Zend_Db_Table::getDefaultAdapter();
        try {
            $query = "select (radio.id) as id_radio from
                        radios radio,
                        radios_secao radiosecao
                    where
                        radiosecao.id_radio = radio.id
                    and
                        radio.high_id = '$mac'
                    and
                        radiosecao.id_secao = $idSecao";
            return $db->fetchOne($query);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }
    /**
     * Método utilizado para retornar um id do banco de dados a partir do mac address de um dispositivo
     * @param string $mac, identificador único do dispositivo MAC Address
     * @return array $idMac
     * @version 1.0 31/01/2014
     * @author Rodrigo Morbach
     */
    public static function getIdFromMACRadio($mac, $sensor = null){        
        if(!($sensor)){
            $query = "Select id from radios where high_id = '$mac'";
        }
        else{
            $query  = "select 
                    radios.id,
                    (sensores.id) as id_sensor
                from
                    radios,
                    sensores,
                    radio_sensores
                where
                    radio_sensores.id_sensor = sensores.id
                        and sensores.nm_sensor = '$sensor'
                        and radio_sensores.id_radio = radios.id
                        and radios.high_id = '$mac'";
        }                  
        $conexao = Zend_Db_Table::getDefaultAdapter();
        try{
            return $conexao->fetchRow($query);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}

?>
