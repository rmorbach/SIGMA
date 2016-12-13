<?php

class RadiosController extends Zend_Controller_Action {

    protected $_arrayConfigDispositivo = null;
    protected $_arrayEstadoDispositivo = null;

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function salvarradioAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $dados = $this->_getAllParams();
            $dados['ds_funcao'] = utf8_decode($dados['ds_funcao']);
            $verificaRadio = Application_Model_Radio::radioCadastrado(trim($dados['high_id']), $dados['id_secao']);
            if (!$verificaRadio) {
                $radio = new Application_Model_Radio();
                $idSecao = $dados['id_secao'];
                $idRadio = $radio->cadastrarRadio($dados);
                if ($idRadio) {
                    $dadosRadioSecao = array("id_radio" => $idRadio, "id_secao" => $idSecao);
                    $radiosSecao = new Application_Model_Radiosecao();
                    if ($radiosSecao->vincularRadioSecao($dadosRadioSecao)) {
                        $qtd = Application_Model_Secao::retornaQtdRadiosSecao($idSecao);
                        $qtd++;
                        $dadosSecao = array("qtd_sensores" => $qtd);
                        $secao = new Application_Model_Secao();
                        $secao->updateSecao($dadosSecao, $idSecao);
                        echo json_encode(array('radio' => $idRadio));
                    }
                }
            } else {
                echo json_encode(array('alerta' => 'Rádio já  cadastrado'));
            }
        }
    }

    public function excluirradioAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $idRadio = $this->_getParam('id_radio');
            $idSecao = $this->_getParam('id_secao');
            $radio = new Application_Model_Radio();
            $qtd = Application_Model_Secao::retornaQtdRadiosSecao($idSecao);
            $qtd--;
            $dadosSecao = array("qtd_sensores" => $qtd);
            $secao = new Application_Model_Secao();
            $secao->updateSecao($dadosSecao, $idSecao);
            echo $radio->excluirRadio($idRadio);
        }
    }

    public function configradiosAction() {
        $mac = strip_tags(trim($this->_getParam('mac')));
        $idRadio = strip_tags(trim($this->_getParam('idradio')));
        $gateway = strip_tags(trim($this->_getParam('gateway')));
        $idGateway = strip_tags(trim($this->_getParam('idgateway')));
        $local = $this->_getParam('local');
        $secao = $this->_getParam('secao');
        $idSecao = strip_tags(trim($this->_getParam('idsecao')));
        $idLocal = strip_tags(trim($this->_getParam('idlocal')));
        $opt = strip_tags(trim($this->_getParam('opt')));
        $rci = new Sigma_RCI($gateway, $opt);
        $dadosAuth = Application_Model_Gateway::getCredenciaisGateway($idGateway);
        if (isset($dadosAuth['login'])) {
            $rci->setCredenciais($dadosAuth['login'], $dadosAuth['senha']);
        }
        //Verifica se a autenticação está correta e se é possível apresentar os dados
        if ($rci->pingGateway($gateway)) {
            $xmlConfig = $rci->retornaConfiguracoesRadio($mac);
            $xmlEstado = $rci->retornaEstadoRadio($mac);
            $dadosEstado = array();
            $arrayEstado = Sigma_XML::xmlToArray($xmlEstado, $dadosEstado);
            $dadosConfig = array();
            $arrayConfig = Sigma_XML::xmlToArray($xmlConfig, $dadosConfig);
            $dataConfig = $this->retornaConfigDispositivo($arrayConfig, true);

            $dataEstado = $this->retornaConfigDispositivo($arrayEstado, false);

            $this->view->msgErro = null;
            $this->view->gateway = $gateway;
            $this->view->idGateway = $idGateway;
            $this->view->config = $dataConfig;
            $this->view->mac_radio = $mac;
            $this->view->estado = $dataEstado;
            $this->view->local = $local;
            $this->view->secao = $secao;
            $this->view->idLocal = $idLocal;
            $this->view->idSecao = $idSecao;
            $this->view->idRadio = $idRadio;

            /* 07/03/2014 */
            $this->view->opt = $opt;
        }
        //Caso nao seja, exibe mensagem de erro
        else {
            $this->view->msgErro = "Erro ao conectar ao gateway. Verifique a autenticação.";
        }
    }

    public function alterarconfigradioAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            $mac = $data['mac'];
            $gateway = $data['gateway'];
            $idRadio = $data['idradio'];
            $idGateway = $data['idgateway'];

            /* 07-03-2014 */
            $opt = trim($data['opt_']);

            unset($data['controller']);
            unset($data['idgateway']);
            unset($data['action']);
            unset($data['module']);
            unset($data['mac']);
            unset($data['gateway']);
            unset($data['idradio']);
            unset($data['opt_']);
            $rci = new Sigma_RCI($gateway, $opt);
            $dadosAuth = Application_Model_Gateway::getCredenciaisGateway($idGateway);
            if (isset($dadosAuth['login'])) {
                $rci->setCredenciais($dadosAuth['login'], $dadosAuth['senha']);
            }
            if ($rci->alteraConfiguracao($data, $mac)) {
                if (isset($data['dest_addr'])) {
                    $dados = array("nm_radio" => utf8_decode($data['node_id']), "dest_addr" => $data['dest_addr']);
                } else {
                    $dados = array("nm_radio" => utf8_decode($data['node_id']));
                }
                $radio = new Application_Model_Radio();
                $radio->updateRadio($dados, $idRadio);
                echo json_encode($data);
            }
        }
    }

    public function alterarconfigredeAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $data = $this->_getAllParams();
            $data['net_id'] = $data['net_addr'];
            $idRadio = $data['idradio'];
            unset($data['controller']);
            unset($data['idgateway']);
            unset($data['action']);
            unset($data['module']);
            unset($data['mac']);
            unset($data['gateway']);

            $radio = new Application_Model_Radio();

            if ($radio->updateRadio($data, $idRadio)) {
                echo json_encode(array("mensagem" => "ok"));
            } else {
                echo json_encode(array("mensagem" => "Nada foi alterado"));
            }
        }
    }

    public function getstateAction() {

        set_time_limit(60);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $radios = $this->_getAllParams();
        unset($radios['controller']);
        unset($radios['action']);
        unset($radios['module']);

        //Verifica se o gateway utiliza autenticação http

        $dadosAuth = Application_Model_Gateway::getCredenciaisGateway(null, $radios['gateway']);
        /* 07/03/2014 */
        $opt = (!empty($radios['opt']) ? $radios['opt'] : null);
        $rci = new Sigma_RCI($radios['gateway'], $opt);
        $arrayEstado = array();
        $dadosConfig = array();
        $status = array();
        if (isset($dadosAuth['login'])) {
            $rci->setCredenciais($dadosAuth['login'], $dadosAuth['senha']);
        }
        if ($rci->pingGateway($radios['gateway'])) {

            //Verifica se está sendo enviado mais de um rádio

            if (is_array($radios['macs'])) {
                $xmlEstado = $rci->retornaEstadoRadio($radios['macs']);
                $arrayEstado = Sigma_XML::xmlToArray($xmlEstado, $dadosConfig);
                //Pega a quantidade de dispositivos                
                $i = 0;
                foreach ($arrayEstado['do_command']['0']['query_state'] as $key => $value) {
                    try {
                        //((isset($value['do_command'][0]['query_state']['0']['radio'][0]['error'])) ? array('status' => 'down', 'rssi' => 'n', 'net_addr' => "n") : array('status' => 'up', 'rssi' => $value['do_command'][0]['query_state']['0']['radio'][0]['rssi'], 'net_addr' => $value['do_command'][0]['query_state']['0']['radio'][0]['net_addr']));                        
                        $status[$i] = ((isset($value['radio'][0]['error'])) ? array('status' => 'down', 'rssi' => 'n', 'net_addr' => "n") : array('status' => 'up', 'rssi' => $value['radio'][0]['rssi'], 'net_addr' => $value['radio'][0]['net_addr']));
                    } catch (Exception $e) {
                        
                    }
                    $i++;
                }
            }


            /*
              foreach ($radios['macs'] as $key => $mac) {
              $xmlEstado = $rci->retornaEstadoRadio($mac);
              // print_r($xmlEstado);
              $arrayEstado[$key] = Sigma_XML::xmlToArray($xmlEstado, $dadosConfig);
              }
              foreach ($arrayEstado as $key => $value) {
              try {
              $status[$key] = ((isset($value['do_command'][0]['query_state']['0']['radio'][0]['error'])) ? array('status' => 'down', 'rssi' => 'n', 'net_addr' => "n") : array('status' => 'up', 'rssi' => $value['do_command'][0]['query_state']['0']['radio'][0]['rssi'], 'net_addr' => $value['do_command'][0]['query_state']['0']['radio'][0]['net_addr']));
              } catch (Exception $e) {

              }
              } */
        } else {
            $status['mensagem'] = "falha_gateway";
        }
        echo json_encode($status);
    }

    /**
     * @uses 
     * @param type $config
     * @param type $flag Indica se é pra adicionar no array de dispositivos ou de
     * estado
     * @return type
     *
     */
    public function retornaConfigDispositivo($config, $flag) {
        foreach ($config as $key => $conf) {
            if (is_array($conf)) {
                $this->retornaConfigDispositivo($conf, $flag);
            } else {
                if ($flag) {
                    $this->_arrayConfigDispositivo[$key] = $conf;
                } else {
                    $this->_arrayEstadoDispositivo[$key] = $conf;
                }
            }
        }
        return (($flag) ? $this->_arrayConfigDispositivo : $this->_arrayEstadoDispositivo);
    }

    public function mensagensalertaAction() {        
        $input = trim(file_get_contents("php://input"));        
        if ($input) {            
            
            $dados = [];
            
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender();                        
            $obj = simplexml_load_string($input);            
            $dados['ds_log_alerta'] = $obj->descalerta;
            $dados['cd_alerta'] = $obj->codalerta;
            
            $dados['dt_hr_registro'] = (string)$obj->dt_hr_alerta;            
            $dados['dt_hr_registro'] = date("Y-m-d H:i", strtotime($dados['dt_hr_registro']));
 
            $ipGateway = $obj->ipgateway;                                
            
            $objGateway = new Application_Model_Gateway();                    
            $idGateway = ($objGateway->fetchRow("end_ip = '$ipGateway'"));                                     
            $dados['id_gateway'] = $idGateway['id'];
                        
            $objLogAlerta = new Application_Model_Logsalerta();
            
            $objLogAlerta->gravarLog($dados);
            
           
            
        } else {
            $id_gateway = trim($this->getParam("idgateway"));
            $objGateway = new Application_Model_Gateway();
            $data = $objGateway->fetchRow("id = $id_gateway");                
            $this->view->nmGateway = $data->nm_gateway;
            $log = new Application_Model_Logsalerta();
            $notificacoes = $log->fetchAll();
            $this->view->logs = $notificacoes;
        }
    }

}
