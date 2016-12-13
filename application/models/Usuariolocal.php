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
class Application_Model_Usuariolocal extends Zend_Db_Table_Abstract {

    protected $_name = "usuarios_local";
    protected $_id = "id";
    
    
    /**
     * Método para retornar todos os locais que o usuário possui vínculo
     * @param int $idUsuario
     * @return array $dados
     * @author Morbach
     * @version 1.0 11/12/2013
     */
    public function getLocaisUsuario($idUsuario) {
        try {
            $where = "id_usuario = $idUsuario";
            return $this->fetchAll($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método vincular um usuario a um local
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function vincularUsuarioLocal(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para desvincular um usuario de um grupo
     * @param int $idUsuarioLocal
     * @param int $idLocal = null
     * @param int $idUsuario = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function desvincularUsuarioLocal($idUsuarioLocal = null, $idLocal = null, $idUsuario = null) {
        if ($idUsuarioLocal) {
            $where = "id = '$idUsuarioLocal'";
        } elseif (($idLocal) && ($idUsuario)) {
            $where = "id_local = '$idLocal' and id_usuario = '$idUsuario'";
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
     * @param int $idUsuarioLocal = null
     * @param int $idLocal = null
     * @param int $idUsuario = null
     * @param int $idLocal
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function updateUsuarioLocal(array $dados, $idUsuarioLocal = null, $idLocal = null, $idUsuario = null) {
        if ($idUsuarioLocal) {
            $where = "id = '$idUsuarioLocal'";
        } elseif (($idLocal) && ($idUsuario)) {
            $where = "id_local = '$idLocal' and id_usuario = '$idUsuario'";
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
