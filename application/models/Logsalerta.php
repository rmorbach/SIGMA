<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Logsalerta extends Zend_Db_Table_Abstract {

    protected $_name = "logs_alerta";
    protected $_id = "id";

    /**
     * @author Morbach
     * @version 1.0 29/06/2014
     * @param array $dados
     * @return boolean
     * @uses gravarLog Insere uma notificação no banco de dados
     */
    public function gravarLog(array $dados){
        try{
        if($dados = $this->filtrarColunas($dados)){
            return $this->insert($dados);
        }
        }  catch (Zend_Db_Table_Exception $e){
            //ignore
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
        //$dados['dt_inclusao'] = date('Y-m-d');
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
