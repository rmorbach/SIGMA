<?php

class IndexController extends Zend_Controller_Action {

    protected $_dataHora;
    protected $_dadosSecao;
    protected $_idUsuario;

    public function init() {
        
    }

    public function indexAction() {      
        /*
        $rci = new Sigma_RCI("192.168.15.14", 'S');
        
        var_dump($rci->pingGateway());
        
        exit;
        
       /* 
        $httpclient = new Zend_Http_Client("http://192.168.15.14/post.py");
        
          $xmldata ='<?xml version="1.0" ?>
                        <rci_request version="1.0">
                            <do_command target="zigbee">
                                <discover />                                
                            </do_command>
                        </rci_request>';          
          
        $httpclient->setParameterPost("data", $xmldata);
               
        $response = $httpclient->request("POST");
        
        header("Content-type:text/xml");
        echo $response->getBody();
       
        exit;
        
        
        
        /* $xml = new Application_Model_Manipulaxml("xml/map.xsd", "xml/mapping.xml");
          $valida = $xml->validaXML();
          echo ((!$valida)?$xml->libxml_display_errors():"Documento válido"); */
        $sessao = Zend_Auth::getInstance();
        if ($sessao->hasIdentity()):
            $idUsuario = $sessao->getIdentity()->id;
        else:
            $this->_redirect('/login');
        endif;
        $usuarioLocal = new Application_Model_Usuariolocal();
        $log = new Application_Model_Log();
        $locais = $usuarioLocal->getLocaisUsuario($idUsuario);
        if (!empty($locais)) {
            $dados = array();            
            foreach ($locais as $key => $local) {
                $dados[$key]['secoes'] = Application_Model_Secoeslocal::getIdSecaoFromIdLocal($local['id_local']);
                $dados[$key]['local'] = Application_Model_Local::getDadosLocal($local['id_local']);                
                foreach ($dados[$key]['secoes'] as $k => $secao) {
                    $dados[$key]['secoes'][$k]['sensores'] = Application_Model_Sensor::getSensoresFromSecao($secao['id_secao']);
                    if (!empty($dados[$key]['secoes'][$k]['sensores'])) {
                        foreach ($dados[$key]['secoes'][$k]['sensores'] as $j => $sensor) {                             
                            $dados[$key]['secoes'][$k]['sensores']['log'][$j] = $log->getLogSensor(null, $sensor['id_radio'], $sensor['id'], null, null, 5, 'desc');                            
                        }
                    }
                }
            }
        }        
        $this->view->logs = $dados;
    }

}

