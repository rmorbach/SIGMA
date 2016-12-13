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
class Application_Model_Acoesevento extends Zend_Db_Table_Abstract {

    protected $_name = "acoes_evento";
    protected $_id = "id";

    /**
     * @uses método vincular uma acao a um evento
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function vincularAcaoEvento(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para desvincular uma ou mais ações de um evento     
     * @param int $idAcaoEvento
     * @param int $idEvento
     * @param int $idAcao          
     * @author Rodrigo Morbach
     * @version 1.0 07/09/2013
     */
    public function desvincularAcoesEvento($idAcaoEvento = null, $idEvento = null, $idAcao = null) {        
        if ($idAcaoEvento) {
            $where = "id = '$idAcaoEvento'";
        }
        elseif ($idEvento) {
            $where = "id_evento = '$idEvento'";
            if($idAcao){
                $where .= "and id_acao = '$idAcao'";
            }
        }        
        elseif($idAcao){
            $where = "id_acao = '$idAcao'";
        }
        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados da tabela acoes_evento
     * @param array $dados
     * @param int $idAcaoEvento
     * @return número de linhas alteradas
     * @author Rodrigo Morbach
     * @version 1.0 01/10/2013
     */
    public function updateAcoesEvento(array $dados, $idAcaoEvento) {      
      $where = "id = '$idAcaoEvento'";
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
