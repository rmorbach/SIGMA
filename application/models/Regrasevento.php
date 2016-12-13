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
    class Application_Model_Regrasevento extends Zend_Db_Table_Abstract {

    protected $_name = "regras_evento";
    protected $_id = "id";

    /**
     * @uses método vincular uma regra a um evento
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function vincularRegraEvento(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para desvincular uma ou mais regras de um evento     
     * @param int $idRegraEvento
     * @param int $idEvento
     * @param int $idRegra          
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function desvincularAcoesEvento($idRegraEvento = null, $idEvento = null, $idRegra = null) {        
        if ($idRegraEvento) {
            $where = "id = '$idRegraEvento'";
        }
        elseif ($idEvento) {
            $where = "id_evento = '$idEvento'";
            if($idRegra){
                $where .= "and id_regra = '$idRegra'";
            }
        }        
        elseif($idRegra){
            $where = "id_regra = '$idRegra'";
        }
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados da tabela regras_evento
     * @param array $dados
     * @param int $idRegraEvento
     * @return número de linhas alteradas
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function updateAcoesEvento(array $dados, $idRegraEvento) {      
      $where = "id = '$idRegraEvento'";
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
