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
class Application_Model_Grupo extends Zend_Db_Table_Abstract {

    protected $_name = "grupos";
    protected $_id = "id";

    /**
     * @uses método cadastrar um grupo
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function cadastrarGrupo(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }
    /**
     * @uses método para pegar dados de um grupo
     * @param int $idGrupo
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function getDadosGrupo($idGrupo) {
        $where = "id = '$idGrupo'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }
    /**
     * @uses método para exclusão de um grupo
     * @param int $idGrupo
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluirGrupo($idGrupo) {
        $where = "id = '$idGrupo'";
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar dados de um grupo
     * @param array $dados
     * @param int $idGrupo
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateGrupo(array $dados, $idGrupo) {
        $where = "id = '$idGrupo'";
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
     * @uses retorna todos os grupos de usuário cadastrados
     * @return array
     */
    public static function retornaTodosGrupos(){
        $conexao = Zend_Db_Table::getDefaultAdapter();
        $query = "SELECT * FROM grupos order by nm_grupo";
        try{
          return $conexao->fetchAll($query)  ;
    }catch(Zend_Db_Table_Exception $e){
        $e->getMessage();
    }
    }

}

?>
