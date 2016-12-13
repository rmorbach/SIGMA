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
class Application_Model_Gruposusuario extends Zend_Db_Table_Abstract {

    protected $_name = "usuario_grupos";
    protected $_id = "id";

    /**
     * @uses método vincular usuario a um grupo
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function vincularGrupoUsuario(array $dados) {
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
     * @param int $idGrupoUsuario
     * @param int $idGrupo = null
     * @param int $idUsuario = null
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function desvincularGrupoUsuario($idGrupoUsuario, $idGrupo = null, $idUsuario = null) {
        $where = "id = '$idLocal'";
        if ($idGrupo) {
            $where .= "and id_grupo = '$idGrupo'";
        }
        if ($idUsuario) {
            $where .= "and id_usuario = '$idUsuario'";
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
     * @param int $idGrupoUsuario
     * @param int $idGrupo = null
     * @param int $idUsuario = null
     * @param int $idLocal
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateGrupoUsuario(array $dados, $idUsuario = null, $idGrupo = null) {      
        if ($idGrupo) {
            $where = "id_grupo = '$idGrupo'";
        }
        if ($idUsuario) {
            $where .= "and id_usuario = '$idUsuario'";
        }
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                $atualiza = $this->update($dados, $where);                
                if(!$atualiza){/*                 
                 * Se nao atualizar, tenta inserir registro
                 */                                  
                    return $this->vincularGrupoUsuario($dados);
                }
                else{
                    return $atualiza;
                }
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
