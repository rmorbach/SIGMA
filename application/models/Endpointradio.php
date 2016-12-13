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
class Application_Model_Endpointradio extends Zend_Db_Table_Abstract {

    protected $_name = "radio_endpoint";
    protected $_id = "id";

    /**
     * @uses método cadastrar um endpoint em um rádio
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarEndpointRadio(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para excluir um endpoint de um rádio
     * @param int $idEndpointRadio
     * @param int $idRadio = null
     * @param int $idUsuario = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirEndpointRadio($idEndpointRadio, $idRadio = null) {
        $where = "id = '$idEndpointRadio'";
        if ($idRadio) {
            $where .= "and id_grupo = '$idRadio'";
        }
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um endpoint de um rádio
     * @param int $idEndPoint
     * @param int $idRadio = null     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosEndPointRadio($idEndPoint = null, $idRadio = null) {
        if ($idEndPoint) {
            $where = "id = '$idEndPoint'";
        } if ($idRadio) {
            $where = "and id_radio = '$idRadio'";
        }
        elseif($idRadio){
            $where = "id_radio = '$idRadio'";
        }
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar um endpoint em um radio
     * @param array $dados
     * @param int $idEndpointRadio
     * @param int $idRadio = null     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateEndpointRadio(array $dados, $idEndpointRadio, $idRadio = null) {
        $where = "id = '$idEndpointRadio'";
        if ($idRadio) {
            $where .= "and id_grupo = '$idRadio'";
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
