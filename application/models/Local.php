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
class Application_Model_Local extends Zend_Db_Table_Abstract {

    protected $_name = "locais";
    protected $_id = "id";

    /**
     * @uses método cadastrar um local
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarLocal(array $dados) {
        try {            
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um local
     * @param int $idLocal
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public static function getDadosLocal($idLocal) {
        $db  = Zend_Db_Table::getDefaultAdapter();
        //$where = "id = '$idLocal'";
        $query = "select (local.id) as id, (local.vl_longitude) as vl_longitude, (local.vl_latitude) as vl_latitude, (local.ds_local) as ds_local, (local.nm_local) as nm_local,
                    (local.endereco) as endereco, (local.cep) as cep, (local.in_ativo) as in_ativo, (cidade.id) as id_cidade, (cidade.nm_cidade) as nm_cidade, (cidade.sg_estado) as
                    sg_estado
                  from
                    locais local,
                    cidades cidade
                   where local.id_cidade = cidade.id
                   and local.id = '$idLocal'";
        try {
            return $db->fetchRow($query);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para exclusão de um local
     * @param int $idUsuario
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirLocal($idLocal) {
        $where = "id = '$idLocal'";
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar dados de um local
     * @param array $dados
     * @param int $idLocal
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateLocal(array $dados, $idLocal) {
        $where = "id = '$idLocal'";
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
