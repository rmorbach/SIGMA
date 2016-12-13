<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Evento
 *
 * @author Morbach
 */
class Application_Model_Acao extends Zend_Db_Table_Abstract {

    protected $_name = 'acoes';
    protected $_id = 'id';

    /**
     * @uses método criar uma ação
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function criarAcao(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de uma acao
     * @param int $idAcao     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function getAcao($idAcao) {
        $where = "id = '$idAcao'";
        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir uma ação
     * @param int $idAcao
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function excluirAcao($idAcao) {
        $where = "id = '$idAcao'";

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de uma ação
     * @param array $dados
     * @param int $idAcao     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function updateAcao(array $dados, $idAcao) {
        $where = "id = '$idAcao'";

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
