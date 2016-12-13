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
class Application_Model_Coletaevento extends Zend_Db_Table_Abstract {

    protected $_name = "coleta_evento";
    protected $_id = "id";

    /**
     * @uses método para cadastrar uma coleta por evento
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function cadastrarColetaEvento(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar coletas por evento
     * @param int $idColetaEvento     
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function getDadosColetaEvento($idColetaEvento) {

        $where = "id = '$idColetaEvento'";

        try {
            return $this->fetchRow($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para excluir uma coleta por evento
     * @param int $idColetaEvento
     * @return boolean coletaEvento excluído
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function excluiColetaEvento($idColetaEvento) {

        $where = "id = '$idColetaEvento'";

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de uma coleta por evento
     * @param array $dados
     * @param int $idColetaEvento     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 09/09/2013
     */
    public function updateColetaTempo(array $dados, $idColetaEvento) {

        $where = "id = '$idColetaEvento'";

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
