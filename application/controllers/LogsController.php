<?php

class LogsController extends Zend_Controller_Action {

    public function init() {
      
    }

    public function indexAction() {
        /*$dados = array(
            "nm_sensor" => "Temperatura",
            "ds_sensor" => "Mensura temperatura em Celsius",
            "fl_unidade_medida" => "C",
            "nm_unidade_medida" => "Celsius",
            "in_ativo" => "S"
        );
        
        $sensor = new Application_Model_Sensor();
        if ($id = $sensor->cadastrarSensor($dados)) {
            $radiosensor = new Application_Model_Sensoresradio();
            $dados['id_radio'] = "23";
            $dados['id_sensor'] = $id;
            $radiosensor->vincularSensorRadio($dados);
        }*/
    }

    public function buscardadoslogAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
             
        $idRadio = strip_tags(trim($this->_getParam('idradio')));
        $idSensor = strip_tags(trim($this->_getParam('idsensor')));
        $flag = strip_tags(trim($this->_getParam('gerartxt')));
        $dtInicial = null;
        $dtFinal = null;

        if (($this->_hasParam('dtInicial')) && ($this->_hasParam('dtFinal'))) {
            $dtFinal = $this->_getParam('dtFinal');
            $dtInicial = $this->_getParam('dtInicial');
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
        $sensor = new Application_Model_Sensor();
        $log = new Application_Model_Log();

        //Verificar parametro para geracao de arquivo de texto ou criacao de graficos

        $dadosLog = $log->getLogSensor(null, $idRadio, $idSensor, $dtInicial, $dtFinal);        
        $dadosSensor = $sensor->getDadosSensor($idSensor);
       
        if ($flag == "N") {
            /* Esse bloco é responsável pela criacao do json para gerar o gráfico* */
            if (empty($dadosLog['vl_dado'])) {
                echo json_encode(array('mensagem' => 'vazio'));
            } else if ((!empty($dadosLog) && (!empty($dadosSensor)))) {                
                $dadosChart = array(
                    'Highcharts.theme' =>array(
                        "colors"=>array("#DDDF0D", "#55BF3B", "#DF5353", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
                                    "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"
                        )
                    ),
                    'chart' => array(
                        'type' => 'line',                                                                       
                        'plotBackgroundColor' => 'rgba(255, 255, 255, .1)',                        
                        'className'=>'dark-container',
                         'plotBorderColor'=> '#CCCCCC'
                    ),
                    'tooltip' => array(
                        'backgroundColor' => "#FFFFFF"
                    ),
                    'title' => array(
                        'text' => $dadosSensor['nm_sensor']
                    ),
                    'xAxis' => array(
                        'categories' => array(
                        ),
                        'gridLineColor'=> '#ccc',
                        'gridLineWidth'=> '1',
                    ),
                    'yAxis' => array(
                        'gridLineColor'=> '#ccc',
                        'title' =>
                        array(
                            'text' => utf8_encode($dadosSensor['nm_unidade_medida']) . " (" . $dadosSensor['fl_unidade_medida'] . ")"
                        ),
                        'plotLines'=>array(                            
                            array('value'=>'0'),
                            array('width'=>2),
                            array('color'=>'#000')
                        )
                    ),
                    'series' => array(
                        array(
                            'data' => array(
                            ),
                            'step' => 'right',
                            'name' => utf8_encode($dadosSensor['nm_sensor']) . " - " . utf8_encode($dadosSensor['nm_unidade_medida'])
                        ),
                    ),
                );
                foreach ($dadosLog as $key => $log):
                    $dadosChart['xAxis']['categories'][$key] = date('d-m H:i', strtotime($log['dt_hr_registro']));
                    array_push($dadosChart['series'][0]['data'], floatval($log['vl_dado']));
                endforeach;
                echo json_encode($dadosChart);
            }
        }
        else if ($flag == "S") {
            if (empty($dadosLog['vl_dado'])) {
                echo json_encode(array('mensagem' => 'vazio'));
            } else {
                $relatorio = Application_Model_Relatorio::gerarRelatorio($dadosLog, $dtInicial, $dtFinal, ".xls", 'report');
                echo json_encode(array("link" => $_SERVER['REMOTE_ADDR'] . $relatorio));
            }
        }
    }

    public function gravarlogAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
      
        $xml = trim(file_get_contents('php://input'));        
      
        $obj = simplexml_load_string($xml);
        
        $valor = (int) $obj->valor;
       
        $macradio = (string)$obj->radio;                         
        
        $macradio = str_replace("[","",$macradio);
                
        $macradio = str_replace("]","",$macradio);                
        if(isset($obj->sensor)){            
            $ids = Application_Model_Radio::getIdFromMACRadio($macradio, $obj->sensor);             
            $idRadio = $ids['id'];                
            $idSensor = $ids['id_sensor'];                
        }
        else{
            $idRadio = Application_Model_Radio::getIdFromMACRadio($macradio);   
            $idRadio = $idRadio['id'];        
            //Ainda preciso do id do sensor como parâmetro, até pensar em outra alternativa.
            $idSensor = trim($this->_getParam('idsensor'));  
        }                
        $dataHrGateway = (string) $obj->dataHoraAtual;
        
        $objDataHrGateway =  new DateTime(date($dataHrGateway));       
        
        $dataHoraAtual = new DateTime(date("Y-m-d H:i:s"));
                        
        $diferencaData = ($dataHoraAtual->diff($objDataHrGateway));
                                
        $dados = array(
            "vl_dado" => $valor,
            "id_radio" => $idRadio,
            "id_sensor" => $idSensor,
            "dt_hr_registro" => ((($diferencaData->h)>0)?date('Y-m-d H:i:s'):$dataHrGateway)
        );                
        $log = new Application_Model_Log();
        $log->registrarLog($dados);        
    }
}
