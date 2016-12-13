<?php

class ConfiguracoesController extends Zend_Controller_Action {

    protected $_auth;
    protected $_dataAtual;
    protected $_idUsuario;
    protected $_nomeUsuario;
    protected $_emailUsuario;
    protected $_generoUsuario;
    protected $_telefoneUsuario;
    protected $_dadosLogin;
    protected $_idGrupo;

    public function init() {
        $this->_auth = Zend_Auth::getInstance();
        if ($this->_auth->hasIdentity()) {
            $this->_dadosLogin = $this->_auth->getIdentity();
            $this->_idUsuario = $this->_dadosLogin->id;
            $this->_nomeUsuario = utf8_encode($this->_dadosLogin->nm_usuario);
            $this->_generoUsuario = $this->_dadosLogin->in_genero;
            $this->_emailUsuario = $this->_dadosLogin->ds_email;
            $this->_telefoneUsuario = $this->_dadosLogin->ds_telefone;
            $this->_idGrupo = $this->_dadosLogin->id_grupo;
        }
    }

    public function indexAction() {
        $this->view->nmUsuario = $this->_nomeUsuario;
        $this->view->emailUsuario = $this->_emailUsuario;
        $this->view->generoUsuario = $this->_generoUsuario;
        $this->view->telefoneUsuario = $this->_telefoneUsuario;
    }

    public function atualizardadosAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if ($this->getRequest()->isPost()) {

            $nome = trim(strip_tags(addslashes(ucwords(strtolower($this->getParam('nome'))))));
            $login = strip_tags(addslashes($this->getParam('login')));
            $genero = strip_tags(addslashes($this->getParam('genero')));
            $senhaAtual = strip_tags(addslashes($this->getParam('senhaAtual')));
            $novaSenha = strip_tags(addslashes($this->getParam('novaSenha')));
            $telefone = strip_tags(addslashes($this->getParam('telefone')));
            $grupo = strip_tags(addslashes($this->getParam('grupo')));

            $dados = array();
            if (!empty($nome)): $dados['nm_usuario'] = utf8_decode($nome);
            endif;
            if (!empty($login)): $dados['login'] = $login;
            endif;
            if (!empty($senhaAtual)): $dados['senhaAtual'] = $senhaAtual;
            endif;
            if (!empty($novaSenha)): $dados['senha'] = $novaSenha;
            endif;
            $dados['telefone'] = $telefone;
            $dados['in_genero'] = $genero;
            $dados['id_grupo'] = $grupo;


            if (!empty($dados['nm_usuario'])) {
                $usuario = new Application_Model_Usuario();
                $idEmail = Application_Model_Usuario::checkEmailUsuario($login);
                if ((!$idEmail) || ($idEmail == $this->_idUsuario)) {
                    if (!empty($dados['senha'])) {
                        $dados['senha'] = md5($dados['senha']);
                    }
                    if (!empty($dados['senhaAtual'])) {
                        $verificaSenha = Application_Model_Usuario::checkSenhaAtualUsuario(md5($dados['senhaAtual']), $this->_idUsuario);
                        if ($verificaSenha) {
                            $usuario->updateUsuario($dados, $this->_idUsuario);
                            $grupoUsuario = new Application_Model_Gruposusuario();
                            //  $dados['id_usuario'] = $this->_idUsuario;
                            //  $grupoUsuario->updateGrupoUsuario($dados, $this->_idUsuario, $dados['id_grupo']);
                            $msgRetorno = array("Sucesso" => ("Dados atualizados!"));
                            echo json_encode(($msgRetorno));
                            $this->_dadosLogin->nm_usuario = $dados['nm_usuario'];
                            $this->_dadosLogin->ds_email = $login;
                        } else {
                            $msgRetorno = array("Erro" => ("Senha atual inválida!"));
                            echo json_encode($msgRetorno);
                        }
                    } else {
                        $usuario->updateUsuario($dados, $this->_idUsuario);
                        $msgRetorno = array("Sucesso" => ("Dados atualizados!"));
                        echo json_encode($msgRetorno);
                        $this->_dadosLogin->id_grupo = $grupo;
                        $this->_dadosLogin->nm_usuario = $dados['nm_usuario'];
                        $this->_dadosLogin->in_genero = $genero;
                        $this->_dadosLogin->ds_email = $login;
                    }
                } else {
                    $msgRetorno = array("Erro" => ("Email já cadastrado!"));
                    echo json_encode(($msgRetorno));
                }
            }
        }
    }

    public function carregagruposAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $grupos = Application_Model_Grupo::retornaTodosGrupos();
        if (!empty($grupos)) {            
            foreach ($grupos as $grupo) {
                if ($grupo['id'] == $this->_idGrupo) {
                    echo "<option value='" . $grupo['id'] . "' selected>" . $grupo['nm_grupo'] . "</option>";
                } else {
                    echo "<option value='" . $grupo['id'] . "'>" . $grupo['nm_grupo'] . "</option>";
                }
            }
        }
    }

}
