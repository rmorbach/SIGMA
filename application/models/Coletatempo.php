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
class Application_Model_Coletatempo extends Zend_Db_Table_Abstract {

    protected $_name = "coleta_tempo";
    protected $_id = "id";

    /**
     * @uses método para cadastrar uma coleta por tempo
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function cadastrarColetaTempo(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar coletas por tempo
     * @param int $idColetaTempo     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function getDadosColetaTempo($idColetaTempo) {

        $where = "id = '$idColetaTempo'";

        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para excluir uma coleta por tempo
     * @param int $idColetaTempo
     * @return boolean coletaTempo excluída
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function excluiColetaTempo($idColetaTempo) {

        $where = "id = '$idColetaTempo'";

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses mÃ©todo para atualizar os dados de uma coleta por tempo
     * @param array $dados
     * @param int $idColetaTempo     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function updateColetaTempo(array $dados, $idColetaTempo) {

        $where = "id = '$idColetaTempo'";

        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->update($dados, $where);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses MÃ©todo para verificar se os dados do array correspondem 
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
