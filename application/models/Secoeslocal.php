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
class Application_Model_Secoeslocal extends Zend_Db_Table_Abstract {

    protected $_name = "secoes_local";
    protected $_id = "id";

    /**
     * @uses método para vincular uma secao a um local
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function vincularSecaoLocal(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para desvincular uma secao de um local
     * @param int $idSecaoLocal
     * @param int $idSecao = null
     * @param int $idLocal = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function desvincularSecaoLocal($idSecaoLocal, $idSecao = null, $idLocal = null) {
        $where = "id = '$idSecaoLocal'";
        if ($idLocal) {
            $where .= "and id_local = '$idLocal'";
        } elseif ($idSecao) {
            $where .= "id_secao = '$idSecao'";
        }
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de secao de um local
     * @param int $idSecaoLocal
     * @param int $idSecao = null     
     * @param int $idLocal = null     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosSecaoLocal($idSecaoLocal = null, $idSecao = null, $idLocal = null) {
        if ($idSecaoLocal) {
            $where = "id = '$idSecaoLocal'";
        } elseif ($idSecao) {
            $where = "id_secao = '$idSecao'";
        } elseif ($idLocal) {
            $where = "id_local = '$idLocal'";
        }
        try {
            return $this->fetchAll($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public static function getIdSecaoFromIdLocal($idLocal){
        $db  = Zend_Db_Table::getDefaultAdapter();
        $query = "select sec.nm_secao, secloc.id_secao from secoes sec, secoes_local secloc where sec.id = secloc.id_secao and secloc.id_local = '$idLocal'";          
        try {
            return $db->fetchAll($query);
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
    public function updateEndpointRadio(array $dados, $idSecaoLocal, $idSecao = null, $idLocal = null) {
        $where = "id = '$idSecaoLocal'";
        if ($idSecao) {
            $where .= "and id_secao = '$idSecao'";
        }
        if ($idLocal) {
            $where .= "and id_local = '$idLocal'";
        } elseif ($idLocal) {
            $where .= "id_local = '$idLocal'";
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
