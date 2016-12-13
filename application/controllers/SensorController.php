<?php

class SensorController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function cadastrarsensorAction() {
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $this->_helper->viewRenderer->setNoRender();
            $dados = $this->_getAllParams();
            $dados['nm_sensor'] = utf8_decode($dados['nm_sensor']);
            $dados['ds_sensor'] = utf8_decode($dados['ds_sensor']);
            $dados['nm_unidade_medida'] = utf8_decode($dados['nm_unidade_medida']);
            $dados["dt_hr_registro"] = date('Y-m-d H:i:s');
            $idSecao = $dados['id_secao'];
            $idLocal = $dados['id_local'];
            $sensor = new Application_Model_Sensor();
            if ($id = $sensor->cadastrarSensor($dados)) {
                $dados['id_sensor'] = $id;
                $sensoresRadio = New Application_Model_Sensoresradio();
                if ($sensoresRadio->vincularSensorRadio($dados)) {
                    $dados['get_url'] = "http://(server)/logs/gravarlog/idradio/" . $dados['id_radio'] . "/idsensor/" . $id . "/valor/";
                    $sensor->updateSensor($dados, $id);
                    $url = $dados['get_url'] . "(dado de entrada aqui)";
                    echo json_encode(array("mensagem" => "ok", "url" => $url, "id_local" => $idLocal, "id_secao" => $idSecao, "id_radio" => $dados['id_radio'], "id_sensor" => $id, "nm_sensor" => $dados['nm_sensor']));
                }
            }
        }
    }

    public function dadossensorAction() {        
        $request = Zend_Controller_Front::getInstance()->getRequest();
       $this->view->headTitle('Sigma - '.$request->getControllerName(). " - ")->headTitle($request->getActionName());
        
        if ($this->getRequest()->isGet()) {
            $idLocal = strip_tags(trim($this->_getParam('idlocal')));
            $idSecao = strip_tags(trim($this->_getParam('idsecao')));
            $idRadio = strip_tags(trim($this->_getParam('idradio')));
            $idSensor = strip_tags(trim($this->_getParam('idsensor')));
            $dadosLocal = Application_Model_Local::getDadosLocal($idLocal);
            $secao = new Application_Model_Secao();
            $sensor = new Application_Model_Sensor();            

            $dadosSecao = $secao->getDadosSecao($idSecao);
            
            $dadosSensor = $sensor->getDadosSensor($idSensor);
            
            $this->view->dadosSensor = $dadosSensor;

            $this->view->idSecao = $idSecao;
            $this->view->idLocal = $idLocal;
            $this->view->idRadio = $idRadio;
            $this->view->idSensor = $idSensor;
            $this->view->secao = $dadosSecao['nm_secao'];
            $this->view->local = utf8_encode($dadosLocal['nm_local']);
        }
    }

   

    public function atualizarsensorAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $dados = $this->_getAllParams();
            $idSensor = $dados['id_sensor'];
            $dados['nm_sensor'] = utf8_decode($dados['nm_sensor']);
            $dados['ds_sensor'] = utf8_decode($dados['ds_sensor']);
            $dados['in_webservice'] = utf8_decode($dados['in_webservice']);
            $dados['nm_unidade_medida'] = utf8_decode($dados['nm_unidade_medida']);
            $sensor = new Application_Model_Sensor();
            if ($sensor->updateSensor($dados, $idSensor)) {
                echo json_encode(array("mensagem" => "ok"));
            } else {
                echo json_encode(array("mensagem" => "erro"));
            }
        }
    }

    public function removersensorAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        if ($this->getRequest()->isPost()) {
            $idSensor = trim($this->_getParam('idsensor'));
            $sensor = new Application_Model_Sensor();
            if ($sensor->excluirSensor($idSensor)) {
                echo json_encode(array("mensagem" => "ok"));
            } else {
                echo json_encode(array("mensagem" => "falha"));
            }
        }
    }

}
