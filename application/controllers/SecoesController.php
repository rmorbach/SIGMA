<?php

class SecoesController extends Zend_Controller_Action {

    protected $_request;
    
    public function init() {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
    }

    public function indexAction() {         
          $this->view->headTitle('Sigma - '.$this->_request->getControllerName(). " - ")->headTitle($this->_request->getActionName());
        if ($this->_hasParam('id')) {
            $idSecao = trim(strip_tags($this->_getParam('id')));
            $nmLocal = trim(strip_tags($this->_getParam('local')));
            $idLocal = trim(strip_tags($this->_getParam('idlocal')));
            $this->view->idSecao = $idSecao;
            $secao = new Application_Model_Secao();
            $this->view->dados = $secao->getDadosSecao($idSecao);
            $gateway = new Application_Model_Gateway();
            $this->view->gateway = $gateway->getDadosGateway(null, $idSecao);            
            $this->view->qtdRadios = Application_Model_Secao::contadorSensoresSecao($idSecao);
            $radios = Application_Model_Radio::dadosRadiosSensoresSecao($idSecao);
            if(!empty($radios)){               
                foreach($radios as $key=>$radio){
                    $radios[$key]['sensores'] = Application_Model_Sensor::getSensorByRadio($radio['id_radio']);                    
                }                
            }            
            $this->view->dadosRadios = $radios;
            $this->view->local = $nmLocal;
            $this->view->idlocal = $idLocal;
        }
    }

    public function cadastrarsecaoAction() {
        $this->view->headTitle('Sigma - '.$this->_request->getControllerName(). " - ")->headTitle($this->_request->getActionName());
        if (($this->_hasParam('idcidade')) && ($idLocal = $this->_hasParam('idlocal'))) {
            $idCidade = $this->_getParam('idcidade');
            $idLocal = $this->_getParam('idlocal');
            $cidade = new Application_Model_Cidade();
            $local = new Application_Model_Local();
            $dadosCidade = $cidade->getDadosCidade($idCidade);
            $dadosLocal = $local->getDadosLocal($idLocal);
            $this->view->nmCidade = $dadosCidade['nm_cidade'];
            $this->view->sgEstado = $dadosCidade['sg_estado'];
            $this->view->nmLocal = utf8_encode($dadosLocal['nm_local']);
            $this->view->idLocal = $dadosLocal['id'];
        }
    }

    public function salvarsecaoAction() {        
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isPost()) {
            $dados = array();
            $dados['nm_secao'] = utf8_decode(trim(strip_tags($this->_getParam('nome'))));
            $dados['ds_secao'] = utf8_decode(trim(strip_tags($this->_getParam('descricao'))));
            $dados['end_ip'] = trim(strip_tags($this->_getParam('endip')));
            $dados['nm_gateway'] = utf8_decode(trim(strip_tags($this->_getParam('nmgateway'))));
            $dados['id_local'] = trim(strip_tags($this->_getParam('idlocal')));
            $dados['login'] = trim(strip_tags($this->_getParam('login')));
            $dados['senha'] = trim(strip_tags($this->_getParam('senha')));
            try {
                $secao = new Application_Model_Secao();
                $idSecao = $secao->cadastrarSecao($dados);
                if ($idSecao) {
                    $dados['id_secao'] = $idSecao;
                    $gateway = new Application_Model_Gateway();
                    $gateway->cadastrarGateway($dados);
                    $secoesLocal = new Application_Model_Secoeslocal();
                    echo $secoesLocal->vincularSecaoLocal($dados);
                }
            } catch (Exception $e) {
                //ignore
            }
        }
    }

    public function alterarstatusAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $idSecao = trim(strip_tags($this->_getParam('idsecao')));
            $status = trim(strip_tags($this->_getParam('status')));
            $secao = new Application_Model_Secao();
            $dados['in_ativo'] = (($status == "S") ? "N" : "S");
            echo $secao->updateSecao($dados, $idSecao);
        }
    }

    public function validaipAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $ip = trim(strip_tags($this->_getParam('endip')));
        $idLocal = trim(strip_tags($this->_getParam('idlocal')));
        $gateway = new Application_Model_Gateway();
        $verifica = $gateway->validaEnderecoIp($ip, $idLocal);
        if ($verifica) {
            $a = array("endip" => $verifica['id_gateway'], "existe" => true);
        } else {
            $a = array("existe" => false);
        }
        echo json_encode($a);
    }

    public function editarsecaogatewayAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $dados = $this->_getAllParams();
            $dados['nm_gateway'] = utf8_decode($dados['nm_gateway']);
            $dados['ds_secao'] = utf8_decode($dados['ds_secao']);
            $idSecao = $dados['id_secao'];
            $idGateway = $dados['id_gateway'];
            $verifica = false;
            if ($dados['fl_mudaip'] == 'S') {
                $verifica = Application_Model_Gateway::validaEnderecoIp($dados['end_ip'], $dados['id_local']);
            }            
            if ($verifica) {
                echo json_encode(array("mensagem" => "existe"));
            } else {
                $secao = new Application_Model_Secao();
                $gateway = new Application_Model_Gateway();
                $updateSecao = $secao->updateSecao($dados, $idSecao);
                $updateGt = $gateway->updateGateway($dados, $idGateway);
                if (($updateSecao) || ($updateGt)) {
                    echo json_encode(array("mensagem" => "ok"));
                } else {
                    echo json_encode(array("mensagem" => "falha"));
                }
            }
        }
    }

}

