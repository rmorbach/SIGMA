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
class Application_Model_Gateway extends Zend_Db_Table_Abstract {

    protected $_name = "gateways";
    protected $_id = "id";

    /**
     * @uses método cadastrar um gateway
     * @param array $dados
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 19/09/2013
     */
    public function cadastrarGateway(array $dados) {
        try {
            if ($dados = $this->filtrarColunas($dados)) {
                return $this->insert($dados);
            }
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para pegar dados de um gateway
     * @param int $idAlarme
     * @param int $idSecao = null
     * @return array $dados
     * @author Rodrigo Morbach
     * @version 1.0 19/09/2013
     */
    public function getDadosGateway($idGateway = null, $idSecao = null) {
        try {
            if ($idGateway) {
                $where = "id = '$idGateway'";
                return $this->fetchRow($where);
            } elseif ($idSecao) {
                $where = "id_secao = '$idSecao'";
                return $this->fetchRow($where);
            }            
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método excluir um gateway
     * @param int $idGateway
     * @return boolean gateway excluído ou nao
     * @author Rodrigo Morbach
     * @version 1.0 19/09/2013
     */
    public function excluirGateway($idGateway) {
        if ($idGateway) {
            $where = "id = '$idGateway'";
        }

        try {
            return $this->delete($where);
        } catch (Zend_Db_Table_Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @uses método para atualizar os dados de um gateway
     * @param array $dados
     * @param int $idGateway     
     * @return int ultima linha inserida no banco
     * @author Rodrigo Morbach
     * @version 1.0 19/09/2013
     */
    public function updateGateway(array $dados, $idGateway) {
        $where = "id = '$idGateway'";
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
 * @uses Verifica se o endereço IP cadastrado já existe na seção selecionada
 * @param String $Ip
 * @param String $idLocal
 * @return array $dados
 */
    public static function validaEnderecoIp($Ip, $idLocal){        
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "Select
                    (gateway.id) as id_gateway 
                 from
                    gateways gateway,
                    secoes secao,
                    secoes_local secaolocal,
                    locais local 
                 where
                    gateway.id_secao = secao.id 
                    and secao.id = secaolocal.id_secao
                    and secaolocal.id_local = local.id 
                    and local.id = '$idLocal' 
                    and gateway.end_ip =  '$Ip'";
        try{            
            return $db->fetchRow($query);
        }catch(Zend_Db_Table_Exception $e){
            echo $e->getMessage();
        }
    }
    /**
     * @uses Método para retornar o login e senha de um gateway
     * @param int $idGateway = null
     * @param string $endGateway. IP do gateway em questão.
     * @return array $dados com login e senha
     * @version 1.0 13/11/2013
     * @author Morbach
     */
    public static function getCredenciaisGateway($idGateway = null, $endGateway = null){
        $db = Zend_Db_Table::getDefaultAdapter();
        if($idGateway){
            $query = "select login, senha from gateways where id = '$idGateway'";
        }
        else if($endGateway){
            $endGateway = trim($endGateway);
            $query = "select login, senha from gateways where end_ip = '$endGateway'";
        }
        try{
            return $db->fetchRow($query);
        }catch(Zend_Db_Exception $e){
            echo $e->getMessage();
        }
    }
}

?>
