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
class Application_Model_Secao extends Zend_Db_Table_Abstract {

    protected $_name = "secoes";
    protected $_id = "id";

    /**
     * @uses método cadastrar uma seção
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarSecao(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de uma seção
     * @param int $idSecao
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosSecao($idSecao) {
        $where = "id = '$idSecao'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para exclusão de uma seção
     * @param int $idSecao
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirGrupo($idSecao) {
        $where = "id = '$idSecao'";
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar dados de uma seção
     * @param array $dados
     * @param int $idSecao
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateSecao(array $dados, $idSecao) {
        $where = "id = '$idSecao'";
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
     * @uses Conta a quantidade de rádios de uma seção
     * @param int $idSecao
     * @return array $dados
     * @author Morbach
     * @version 1.0 22/09/2013
     */
    public static function contadorSensoresSecao($idSecao) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "select
                    count(radio.id) as qtd_radios 
                 from  
                    secoes secao,
                    radios radio,
                    radios_secao radio_secao  
                 where    
                     radio.id = radio_secao.id_radio  
                    and radio_secao.id_secao = secao.id  
                    and secao.id = $idSecao";
        //Consulta antiga
        /*
         * select
          count(radio.id) as qtd_radios
          from
          sensores sensor,
          secoes secao,
          radios radio,
          radio_sensores radio_sensor,
          radios_secao radio_secao
          where
          radio_sensor.id_sensor = sensor.id
          and sensor.id = radio_sensor.id_sensor
          and radio_sensor.id_radio = radio.id
          and radio.id = radio_secao.id_radio
          and radio_secao.id_secao = secao.id
          and secao.id = 18
         */
        try {
            return $db->fetchRow($query);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function retornaQtdRadiosSecao($idSecao){
         $db = Zend_Db_Table::getDefaultAdapter();
         $idSecao = $db->quote($idSecao);
         $query = "select qtd_sensores from secoes where id = $idSecao";
         try{
             return $db->fetchOne($query);
        }catch(Zend_Db_Exception $e){
            echo $e->getMessage();
        }
    }
}

?>
