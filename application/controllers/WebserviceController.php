<?php

class WebserviceController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->disableLayout();
        
    }

    public function servicosdisponiveisAction() {
        /*
         * Habilita o layout que não exige autenticação
         */        
        $this->_helper->layout->setLayout('layout_webservice');                
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->view->headTitle('Sigma - ' . $request->getControllerName() . " - ")->headTitle($request->getActionName());
        $webservice = new Application_Model_Webservice();
        $dados = $webservice->getInfoServicosWeb();
        $this->view->dados = $dados;
    }

    public function indexAction() {
        $this->forward($this->servicoAction());
      /*  exit;
        $this->_helper->viewRenderer->setNoRender();

        /* $server = new Zend_Rest_Server();
          $server->setClass('Log');
          $server->handle();
       
        //Daqui pra baixo é SOAP
        if (($this->_hasParam('wsdl'))) {
            $wsdl = new Zend_Soap_AutoDiscover();
            $wsdl->setClass('Log');
            //var_dump($wsdl->getUri());
            $wsdl->handle();
        } else {
            $server = new Zend_Soap_Server('http://sigma/webservice/?wsdl', array("encoding" => "iso-8859-1"));
            $server->setClass('Log');
            $server->handle();
        }*/
    }

    public function clientAction() {
        $this->_helper->viewRenderer->setNoRender();
        /* ($client = new Zend_Rest_Client('http://sigma/webservice');
          var_dump($client->restGet('http://sigma/webservice'));
          exit;
          var_dump($client->buscardados("Universidade", "Larcom", "temperatura", "2013-11-01", "2014-02-01", 'xml')->get());
         */
        //Daqui para baixo é cliente SOAP
        $client = new Zend_Soap_Client("http://sigma/webservice/?wsdl");
        var_dump($client->getLastRequest());
        //  header('Content-type: text/xml;');
        try {
            echo $client->buscardados("Universidade", "Larcom", "temperatura", "2013-11-01", "2014-02-01", 'xml');
        } catch (Zend_Soap_Client_Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function servicoAction() {        
        $this->_helper->viewRenderer->setNoRender();      
        $metodo = strip_tags(trim($this->_getParam('servico')));
        $local = strip_tags(trim($this->_getParam('local')));                        
        $secao = strip_tags(trim($this->_getParam('secao')));
        $sensor = strip_tags(trim($this->_getParam('sensor')));
        $dtInicial = null;        
        if (($this->_hasParam('dtInicial')) && ($this->_hasParam('dtFinal'))) {
            $dtFinal = trim($this->_getParam('dtFinal'));
            $dtInicial = trim($this->_getParam('dtInicial'));
            $tipo = trim($this->_getParam('formato'));
            try {
                $explodeDtInicial = explode('-', $dtInicial);
                $explodeDtFinal = explode('-', $dtFinal);
                if ((strlen($explodeDtFinal[0]) < 3) && (strlen($explodeDtFinal[0]) < 3)) {
                    $dtFinal = $explodeDtFinal[2] . "-" . $explodeDtFinal[1] . "-" . $explodeDtFinal[0];
                    $dtInicial = $explodeDtInicial[2] . "-" . $explodeDtInicial[1] . "-" . $explodeDtInicial[0];
                }
                $dtFinal = $dtFinal . " 23:59";
                $dtInicial = date('Y-m-d H:i', strtotime($dtInicial));
                $dtFinal = date('Y-m-d H:i', strtotime($dtFinal));
            } catch (Exception $e) {
                //ignore
            }
        } else {
            $dtInicial = date('Y-m-d') . " 00:00";
            $dtFinal = date('Y-m-d') . " 23:59";
            $dtInicial = date('Y-m-d H:i', strtotime("-7days"));
            $dtFinal = date('Y-m-d H:i', strtotime($dtFinal));
        }
        if ($metodo == "buscardados") {            
            switch ($tipo) {
                case "xml":
                    header('Content-type: text/xml');
                    echo $this->buscarDados($local, $secao, $dtInicial, $dtFinal, $sensor, $tipo);
                    break;
                case "json":
                    echo $this->buscarDados($local, $secao, $dtInicial, $dtFinal, $sensor, $tipo);
                    break;
            }
        }
    }

    protected function buscarDados($local, $secao, $dtInicial, $dtFinal, $sensor, $tipo) {
        if ((!empty($local)) && (!empty($secao)) && (!empty($sensor))) {
            
            $dadosLog = Application_Model_Log::getLogsToWebservice($local, $secao, $sensor, $dtInicial, $dtFinal); 

            if (!empty($dadosLog)) {
                //Caso a URL contenha o atributo type, significa que ela est� se comunicando com um sistema externo.                                          
                switch ($tipo) {
                    case "json":
                        if ($dadosLog[0]['in_webservice'] == "N")
                            return json_encode(array("erro" => "Dados indisponíveis via Web service"));
                        $dadosJson['local'] = utf8_encode($dadosLog[0]['nm_local']);
                        $dadosJson['secao'] = utf8_encode($dadosLog[0]['nm_secao']);
                        $dadosJson['sensor'] = utf8_encode($dadosLog[0]['nm_local']);
                        foreach ($dadosLog as $key => $log):
                            $dadosJson['dados'][$key] = array("valor" => $log['vl_dado'], "horario" => $log['dt_hr_registro']);
                        endforeach;                      
                        header('Content-type: text/json');
                        return json_encode($dadosJson);
                        break;
                    case "xml":
                        if ($dadosLog[0]['in_webservice'] == "N")
                            return "<erro><mensagem>Dados indisponiveis via Web Service</mensagem></erro>";
                        $xml = "<log><local>" . utf8_encode($dadosLog[0]['nm_local']) . "</local>";
                        $xml .= "<secao>" . utf8_encode($dadosLog[0]['nm_secao']) . "</secao>";
                        $xml .= "<sensor>" . utf8_encode($dadosLog[0]['nm_sensor']) . "</sensor>";
                        $xml .= "<dados>";
                        foreach ($dadosLog as $key => $log):
                            $xml .= "<registro>";
                            $xml .= "<valor>" . $log['vl_dado'] . "</valor>";

                            $dtHora = explode(" ", $log['dt_hr_registro']);

                            $xml .= "<data_hora>" . $dtHora[0] . "T" . $dtHora[1] . "</data_hora>";
                            $xml .= "</registro>";
                        endforeach;
                        $xml .= "</dados>";
                        $xml .= "</log>";
                        #Muda o formato de sa�da para XML

                        return $xml;
                        break;
                    default:
                        break;
                }
            }else {
                switch ($tipo) {
                    case "xml":
                        $xml = "<resultado><mensagem>Nenhum dado encontrado</mensagem></resultado>";
                        //header('Content-type: text/xml');
                        return $xml;
                        break;
                    case "json":
                        return json_encode(array("resultado" => array("mensagem" => "Nenhum dado encontrado")));
                        break;
                }
            }
        }
    }

    public function buscardadosAction() {
        
    }

}

/**
 * O Zend_Soap_AutoDiscover mapeia a classe definida como servidora e suas funções para a criação do WSDL.
 * Os comentários dos métodos também são utilizados para a constração do documento
 */
class Log {

    /**
     * 
     * @param string $local
     * @param string $secao
     * @param string $sensor
     * @param string $dtInicial
     * @param string $dtFinal
     * @param string $tipo
     * @return object
     */
    public function buscardados($local, $secao, $sensor, $dtInicial = null, $dtFinal = null, $tipo) {

        /*      $local = strip_tags(trim($this->_getParam('local')));
          $secao = strip_tags(trim($this->_getParam('secao')));
          $sensor = strip_tags(trim($this->_getParam('sensor')));
          $dtInicial = null;
          $dtFinal = null;
         */
        if ((!empty($local)) && (!empty($secao)) && (!empty($sensor))) {
            if (($dtInicial) && ($dtFinal)) {
                try {
                    $explodeDtInicial = explode('-', $dtInicial);
                    $explodeDtFinal = explode('-', $dtFinal);
                    if ((strlen($explodeDtFinal[0]) < 3) && (strlen($explodeDtFinal[0]) < 3)) {
                        $dtFinal = $explodeDtFinal[2] . "-" . $explodeDtFinal[1] . "-" . $explodeDtFinal[0];
                        $dtInicial = $explodeDtInicial[2] . "-" . $explodeDtInicial[1] . "-" . $explodeDtInicial[0];
                    }
                    $dtFinal = $dtFinal . " 23:59";
                    $dtInicial = date('Y-m-d H:i', strtotime($dtInicial));
                    $dtFinal = date('Y-m-d H:i', strtotime($dtFinal));
                } catch (Exception $e) {
                    //ignore
                }
            } else {
                $dtInicial = date('Y-m-d') . " 00:00";
                $dtFinal = date('Y-m-d') . " 23:59";
                $dtInicial = date('Y-m-d H:i', strtotime("-7days"));
                $dtFinal = date('Y-m-d H:i', strtotime($dtFinal));
            }
            $dadosLog = Application_Model_Log::getLogsToWebservice($local, $secao, $sensor, $dtInicial, $dtFinal);
            if (!empty($dadosLog)) {
                //Caso a URL contenha o atributo type, significa que ela está se comunicando com um sistema externo.                                          
                switch ($tipo) {
                    case "json":
                        $dadosJson['local'] = utf8_encode($dadosLog[0]['nm_local']);
                        $dadosJson['secao'] = utf8_encode($dadosLog[0]['nm_secao']);
                        $dadosJson['sensor'] = utf8_encode($dadosLog[0]['nm_sensor']);                        
                        foreach ($dadosLog as $key => $log):
                            $dadosJson['dados'][$key] = array("valor" => $log['vl_dado'], "horario" => $log['dt_hr_registro']);
                        endforeach;
                        //header('Content-type: text/json');
                        return json_encode($dadosJson);
                        break;
                    case "xml":
                        $xml = "<log><sensor>" . $dadosLog[0]['nm_sensor'] . "</sensor>";
                        $xml .= "<dados>";
                        foreach ($dadosLog as $key => $log):
                            $xml .= "<registro>";
                            $xml .= "<valor>" . $log['vl_dado'] . "</valor>";

                            $dtHora = explode(" ", $log['dt_hr_registro']);

                            $xml .= "<data_hora>" . $dtHora[0] . "T" . $dtHora[1] . "</data_hora>";
                            $xml .= "</registro>";
                        endforeach;
                        $xml .= "</dados>";
                        $xml .= "</log>";
                        #Muda o formato de saída para XML
                        header('Content-type: text/xml');
                        return $xml;
                        break;
                    default:
                        break;
                }
            }else {
                switch ($tipo) {
                    case "xml":
                        $xml = "<resultado><mensagem>Nenhum dado encontrado</mensagem></resultado>";
                        header('Content-type: text/xml');
                        return $xml;
                        break;
                    case "json":
                        return json_encode(array("resultado" => array("mensagem" => "Nenhum dado encontrado")));
                        break;
                }
            }
        }
    }

}
