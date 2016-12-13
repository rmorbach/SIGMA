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
class Application_Model_Logutilizacao extends Zend_Db_Table_Abstract {

    protected $_name = "logs_utilizacao";
    protected $_id = "id";

    /**
     * @uses método para gravar um log de usuário
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 28/09/2013
     */
    public function gravarLog(array $dados) {             
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de log um usuário
     * @param int $idUsuario
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 25/09/2013
     */
    public function getDadosLog($idUsuario) {
        $where = "id_usuario = '$idUsuario'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para exclusão de usuário
     * @param int $idLog
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 28/09/2013
     */
    public function excluirLog($idLog) {
        $where = "id = '$idLog'";
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * @uses Método para verificar se os dados do array correspondem 
     * as colunas da tabela no banco de dados
     * @param array $dados
     * @return array $dadosFiltrados
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
     * Método estático para checar a existencia de um email no sistema
     * @param string $email
     * @return boolean
     * @author Morbach
     * @version 1.0 25/09/2013
     */
    public static function checkEmailUsuario($login) {
        
        $conexao = Zend_Db_Table::getDefaultAdapter();
        $query = "select id FROM rede_sensora.usuarios WHERE LCASE(login) = '$login'";
        try {
            $info = $conexao->fetchOne($query);
            return $info;
        } catch (Zend_Db_Exception $e) {
            echo $e->getMessage();
        }
    }
    
     /**
     * Método estático para checar se a senha atual que o usuário informou está correta
     * @param string $senha
     * @param int $idUsuario
     * @return boolean
     * @author Morbach
     * @version 1.0 25/09/2013
     */
    public static function checkSenhaAtualUsuario($senha, $idUsuario) {        
        $conexao = Zend_Db_Table::getDefaultAdapter();
        $query = "SELECT id FROM usuarios WHERE senha = '$senha' and id = '$idUsuario'";        
        try {
            $info = $conexao->fetchOne($query);
            return $info;
        } catch (Zend_Db_Exception $e) {
            echo $e->getMessage();
        }
    }

}

?>
