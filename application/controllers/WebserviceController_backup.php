<?php

class WebserviceController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function indexAction() {
        // action body
    }

    public function buscardadosAction() {

        $local = strip_tags(trim($this->_getParam('local')));
        $secao = strip_tags(trim($this->_getParam('secao')));
        $sensor = strip_tags(trim($this->_getParam('sensor')));
        $dtInicial = null;
        $dtFinal = null;

        if ((!empty($local)) && (!empty($secao)) && (!empty($sensor))) {
            if (($this->_hasParam('dtInicial')) && ($this->_hasParam('dtFinal'))) {
                $dtFinal = $this->_getParam('dtFinal');
                $dtInicial = $this->_getParam('dtInicial');
                $tipo = $this->_getParam('formato');
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
                //Caso a URL contenha o atributo type, significa que ela est� se comunicando com um sistema externo.                                          
                switch ($tipo) {
                    case "json":
                        $dadosJson['sensor'] = $dadosLog[0]['nm_sensor'];
                        foreach ($dadosLog as $key => $log):
                            $dadosJson['dados'][$key] = array("valor" => $log['vl_dado'], "hor�rio" => $log['dt_hr_registro']);
                        endforeach;
                        //header('Content-type: text/json');
                        echo json_encode($dadosJson);
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
                        #Muda o formato de sa�da para XML
                        header('Content-type: text/xml');
                        echo $xml;
                        break;
                    default:
                        break;
                }
            }else {
                switch ($tipo) {
                    case "xml":
                        $xml = "<resultado><mensagem>Nenhum dado encontrado</mensagem></resultado>";
                        header('Content-type: text/xml');
                        echo $xml;
                        break;
                    case "json":
                        echo json_encode(array("resultado" => array("mensagem" => "Nenhum dado encontrado")));
                        break;
                }
            }
        }
    } 
}
