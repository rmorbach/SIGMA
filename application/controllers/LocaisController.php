<?php

class LocaisController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
    }

    public function exibelocalAction() {
        if ($this->_hasParam('id')) {           
           $idLocal = trim(strip_tags($this->_getParam('id')));           
           $this->view->idlocal = $idLocal;                      
           $this->view->dados = Application_Model_Local::getDadosLocal($idLocal);                       
           $secaoLocal = new Application_Model_Secoeslocal();
           $idsSecoes = $secaoLocal->getDadosSecaoLocal(null, null, $idLocal);               
           $secoes = array();           
           if(!empty($idsSecoes)){     
               $objSecao = new Application_Model_Secao();
               foreach($idsSecoes as $key=>$idSecao){
                   $secoes[$key] = $objSecao->getDadosSecao($idSecao['id_secao']);                                      
               }                 
           }                              
           $this->view->secoes = $secoes;        
        }
    }

    public function cadastrarlocalAction() {
        // action body
    }

    public function alterarstatusAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isPost()) {
            $status  = trim(strip_tags($this->_getParam('statusAtual')));
            $idLocal = trim(strip_tags($this->_getParam('idlocal')));
            $dados['in_ativo'] = (($status == "S")? "N":"S");
            $local = new Application_Model_Local();
            echo $local->updateLocal($dados, $idLocal);
        }
    }

    public function salvarlocalAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isPost()) {            
            $dados = array();
            $dados['nm_local'] = utf8_decode(trim(strip_tags($this->_getParam('nome'))));
            $dados['ds_local'] = utf8_decode(trim(strip_tags($this->_getParam('descricao'))));
            $dados['vl_latitude'] = ($this->_hasParam('latitude') ? trim(strip_tags($this->_getParam('latitude'))) : null);
            $dados['vl_longitude'] = ($this->_hasParam('longitude') ? trim(strip_tags($this->_getParam('longitude'))) : null);
            $dados['id_cidade'] = trim(strip_tags($this->_getParam('cidade')));
            $dados['endereco'] = utf8_decode(trim(strip_tags($this->_getParam('endereco'))));

            $local = new Application_Model_Local();                                    
            if($local->cadastrarLocal($dados)){                                
                $idLocal = $local->cadastrarLocal($dados);
                $localUsuario = new Application_Model_Usuariolocal();
                $auth = Zend_Auth::getInstance();        
                $dadosLogin = $auth->getIdentity();
                $idUsuario = $dadosLogin->id;
                $dadosUsuarioLocal = array("id_local"=>$idLocal, "id_usuario"=>$idUsuario);
                if($localUsuario->vincularUsuarioLocal($dadosUsuarioLocal)){
                    echo "ok";
                }
            }
        }
    }

    public function cidadesAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        $cidades = Application_Model_Cidade::retornaTodasCidades();
        if (!empty($cidades)) {
            echo "<option>Selecione uma cidade</option>";
            foreach ($cidades as $cidade) {
                echo "<option value='" . $cidade['id'] . "'>" . $cidade['nm_cidade'] . " - " . $cidade['sg_estado'] . "</option>";
            }
        }
    }

    public function cadastrarsecaoAction() {
        // action body
    }

}

