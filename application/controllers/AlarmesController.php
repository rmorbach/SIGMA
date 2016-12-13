<?php

class AlarmesController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }    
    public function indexAction() {        
        /*         * ***Este cÃ³digo verifica se o site estÃ¡ 'em pÃ©' */
                
        /**************Faz uma chamada RPC*********/
        /*$client = new Zend_XmlRpc_Client('http://192.168.15.14/UE/rci'); 
        $service = $client->getProxy();
        print $service->div(1,2);
        exit;
        */
        $curl = curl_init('http://10.10.6.128/status.xml');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt($curl, CURLOPT_USERPWD,'aipo:admin');
        /*         * curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
          /*Retona o cÃ³digo HTML do site
          $resultado = curl_exec($curl);
          /*Retorna o cÃ³digo da requisiÃ§ao
         * 200, 404, 500, 303, etc

          $resposta = curl_getinfo($curl, CURLINFO_HTTP_CODE);

          if($resposta == 200){
          echo "Site online";
          }
          /*Esse cÃ³digo faz uma requisiÃ§Ã£o post com cURL */                  
                 /*
                  $xmldata = '<rci_request version="1.1">
                                <do_command target="file_system">
                                <put_file name="/WEB/temperatura2.csv">
                                <data>'.$file.'</data>
                                </put_file>
                                </do_command>
                                </rci_request>';
                  */
        //curl_setopt($curl, CURLOPT_USERPWD, "root" . ':' . '');
                  $xmldata ='                        
                        <rci_request version="1.0">
                            <do_command target="zigbee">
                                <query_state addr="00:13:32:50:40:ac:6c:31!">
                                </query_state>    
                            </do_command>
                        </rci_request>';                           
        /* <rci_request version="1.1">
          <do_command target="zigbee">
          <radio_command id="MY" format="binary" addr="">
          0x1234
          </radio_command>
          </do_command>
          </rci_request>
         */
        /*curl_setopt($curl, CURLOPT_POST, 1);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);       
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmldata);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                       'Content-type: application/xml',                                        
                                     ));
        //curl_setopt($curl,CURLOPT_HTTPHEADER, array('Connection:close'));
*/
        $data = curl_exec($curl);
  //      header("Content-type: text/xml");
        header("Content-type: text/xml");
        echo $data;
        exit;
        $xml = $data;        
        $xml2 = simplexml_load_string($xml);        
        header("Content-type: text/xml");
        echo $xml2;
        exit;
        var_dump($xml);
        $a = fopen('xml/teste2.xml', 'w');
        fwrite($a, $xml);
        fclose($a);
        //var_dump($xml);
        //var_dump(curl_errno($curl));
        curl_close($curl);
    }

    public function veralarmesAction() {
        // action body
        //       $this->_helper->viewRenderer->setNoRender(); 
        $post = $this->_getAllParams();
        print_r($post);
    }

}

