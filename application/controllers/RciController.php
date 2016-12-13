<?php

class RciController extends Zend_Controller_Action {

    protected $_qtdDispositos;
    protected $_dispositivos;
    protected $_count = 0;
    protected $_arrayDispositivos;
    protected $_pan; //Endereço da rede

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function indexAction() {
        echo "index";
    }

    public function executacomandoAction() {
        if ($this->_hasParam('acao')) {            
            $gateway = trim(strip_tags($this->_getParam("gateway")));
            $idGateway = trim(strip_tags($this->_getParam("id_gateway")));
            $acao = trim(strip_tags($this->_getParam("acao")));
            $opt = $this->_getParam("opt");                        
            $rci = new Sigma_RCI($gateway, $opt);
            $dispositivos = array();
            $dadosAuth = Application_Model_Gateway::getCredenciaisGateway($idGateway);            
            if(isset($dadosAuth['login'])){
                $rci->setCredenciais($dadosAuth['login'], $dadosAuth['senha']);                        
            }
            if ($rci->pingGateway($gateway)) {
                if ($acao == 'reboot') {                    
                    $reboot = $rci->executaComando($acao);                    
                    if($reboot){
                        echo json_encode(array("mensagem"=>"reiniciando"));
                    }
                } else {
                    $a = $rci->executaComando($acao);                         
                    if ($a) {
                        $dispositivos['mensagem'] = "ok";
                        $b = Sigma_XML::xmlToArray($a, array());                        
                        $this->_qtdDispositos = (count($b["do_command"][0]["discover"][0]["device"]));
                        $this->_dispositivos = $b["do_command"][0]["discover"][0]["device"];
                        $dispositivos['qtd_dispositivos'] = $this->_qtdDispositos;
                        for ($i = 0; $i < $this->_qtdDispositos; $i++) {
                            $dispositivos[$i] = $this->retornaDispositivos($this->_dispositivos[$i]);
                            if ($dispositivos[$i]['tipo'] == 'Gateway ConnectPort X2') {
                                $this->_pan = $dispositivos[$i]['net'];
                            }
                        }
                        if (isset($this->_pan)) {
                            $dispositivos['pan'] = $this->_pan;
                        }
                        echo json_encode($dispositivos);
                    } else {
                        $dispositivos['mensagem'] = "Gateway inacessível. Verifique se a autenticação está correta.";
                        echo json_encode($dispositivos);
                    }
                }
            } else {
                $dispositivos['mensagem'] = "Gateway inacessível. Verifique se a autenticação está correta.";
                echo json_encode($dispositivos);
            }
        }
    }

    function retornaDispositivos($dispositivos) {
        foreach ($dispositivos as $key => $dispo) {            
            if (is_array($dispo)) {
                $this->retornaDispositivos($dispo);
                $this->_count++;
            } else {
                switch ($key) {
                    case "ext_addr":
                        $this->_arrayDispositivos['mac'] = $dispo;
                        break;
                    case "profile_id":
                        $this->_arrayDispositivos['perfil_id'] = $dispo;
                        break;
                    case "device_type":
                        if ($dispo == "0x30000") {
                            $this->_arrayDispositivos['tipo'] = "Xbee comum";
                        } else if ($dispo == "0x30003") {
                            $this->_arrayDispositivos['tipo'] = "Gateway ConnectPort X2";
                        } else {
                            $this->_arrayDispositivos['tipo'] = "Deconhecido";
                        }
                        break;
                    case "node_id":
                        $this->_arrayDispositivos['nome'] = $dispo;
                        break;
                    case "status":
                        $this->_arrayDispositivos['status'] = $dispo;
                        break;
                    case "net_addr":
                        $this->_arrayDispositivos['net'] = $dispo;
                        break;
                    case 'type':
                        switch ($dispo) {
                            case '0':
                                $this->_arrayDispositivos['funcao'] = 'Coordenador';
                                break;
                            case '1':
                                $this->_arrayDispositivos['funcao'] = 'Roteador';
                                break;
                            case '2':
                                $this->_arrayDispositivos['funcao'] = 'End-Device';
                                break;
                        }
                        break;
                }
            }
        }
        return $this->_arrayDispositivos;
        //  var_dump($this->_arrayDispositivos);
    }

}

?>
