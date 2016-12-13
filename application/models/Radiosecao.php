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
class Application_Model_Radiosecao extends Zend_Db_Table_Abstract {

    protected $_name = "radios_secao";
    protected $_id = "id";

    /**
     * @uses método para vincular um radio a uma secao
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function vincularRadioSecao(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para desvincular um radio de uma secao
     * @param int $idRadioSecao
     * @param int $idSecao = null
     * @param int $idRadio = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function desvincularSecaoLocal($idRadioSecao, $idSecao = null, $idRadio = null) {
        $where = "id = '$idRadioSecao'";
        if ($idSecao) {
            $where .= "and id_secao = '$idSecao'";
        } elseif ($idRadio) {
            $where .= "id_radio = '$idRadio'";
        }
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um radio de uma secao
     * @param int $idRadioSecao
     * @param int $idSecao = null     
     * @param int $idRadio = null     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosSecaoLocal($idRadioSecao, $idSecao = null, $idRadio = null) {
        if ($idRadioSecao) {
            $where = "id = '$idRadioSecao'";
        } if ($idSecao) {
            $where = "and id_secao = '$idSecao'";
        } elseif ($idRadio) {
            $where = "id_local = '$idRadio'";
        }
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os de uma secao de um local
     * @param array $dados
     * @param int $idSecaoLocal
     * @param int $idSecao = null     
     * @param int $idLocal = null 
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateRadioSecao(array $dados, $idRadioSecao, $idSecao = null, $idRadio = null) {
        $where = "id = '$idRadioSecao'";
        if ($idSecao) {
            $where .= "and id_secao = '$idSecao'";
        }
        if ($idLocal) {
            $where .= "and id_local = '$idLocal'";
        } elseif ($idRadio) {
            $where .= "id_local = '$idRadio'";
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
