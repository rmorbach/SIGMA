<?php

class LoginController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout()->disableLayout();
        Zend_Session::start();
    }

    public function indexAction() {
        if ($this->getRequest()->isPost()) {
            $loginUsuario = strip_tags(trim($this->getRequest()->getPost("loginUsuario")));
            $senhaUsuario = strip_tags(trim($this->getRequest()->getPost("senhaUsuario")));
            $dados = array('loginUsuario' => $loginUsuario, 'senhaUsuario' => $senhaUsuario);
            $authAdapter = $this->getAuthAdapter($dados);
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($authAdapter);
            if (!$result->isValid()) {
                $mensagem = $this->mensagemErro($result);
                $this->view->mensagemErro = $mensagem;
            } else {
                $dadosUsuario = $authAdapter->getResultRowObject(null, 'senha');                
                $auth->getStorage()->write($dadosUsuario);                
                $this->adicionarLog("entrada");
                $this->_redirect("/index");
            }
        }
    }

    public function recuperarAction() {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $xml = trim(file_get_contents('php://input'));
//         $obj = simplexml_load_string($xml);
//         print $obj->valor;
//         print $obj->radio;
    }

    /* @uses, retorna o adaptador para autenticação do usuário no banco de dados.
     * @param Array $formulario contendo os dados de acesso.
     * @return Zend_Auth_Adapter $authAdapter, adaptador para autenticação.
     */

    private function getAuthAdapter($formulario) {
        $bd = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($bd);
        /*
         * Seta o nome da tabela e os campos para autenticação.
         */
        $authAdapter->setTableName('usuarios')
                ->setIdentityColumn('login')
                ->setCredentialColumn('senha');
        $formulario['loginUsuario'] = strtoupper(strip_tags($formulario['loginUsuario']));
        $formulario['senhaUsuario'] = md5($formulario['senhaUsuario']);
        $authAdapter->setIdentity(strtoupper($formulario['loginUsuario']))
                ->setCredential(($formulario['senhaUsuario']));
        return $authAdapter;
    }

    /* @uses Método para retornar uma mensagem de erro ao usuário     
     * @param int $result, objeto da classe Zend_Auth_Result com o retorno da tentativa de login
     * @return string $msg
     */

    private function mensagemErro(Zend_Auth_Result $result) {
        switch ($result->getCode()) {
            case $result::FAILURE_IDENTITY_NOT_FOUND://Caso o nome de usuário não seja encontrado
                $msg = 'Verifique usuário e senha!';
                break;
            case $result::FAILURE_IDENTITY_AMBIGUOUS:
                $msg = 'Em Ambiguidade';
                break;
            case $result::FAILURE_CREDENTIAL_INVALID://Caso a senha esteja incorreta
                $msg = 'Verifique usuário e senha!';
                break;
            default:
                $msg = 'Verifique usuário e senha!';
                break;
        }
        return $msg;
    }

    /*
     * Métoto para testar uma request XML
     */

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $this->adicionarLog("saida");
        $auth->clearIdentity();
        Zend_Session::namespaceUnset("Login");
        $this->_redirect("/login");
    }

    /**
     * @uses Método para registrar logs do usuário
     * @param string $tipoLog = Entra ou saída
     */
    public function adicionarLog($tipoLog) {
        $auth = Zend_Auth::getInstance();
        $dadosSecao = $auth->getIdentity();
        $idUsuario = $dadosSecao->id;
        $dataHora = date('Y-m-d H:i:s');
        $log = new Application_Model_Logutilizacao();
        $dados = array(
            "id_usuario" => $idUsuario,
            "dt_entrada" => $dataHora,
            "pagina" => "login",
            "operacao" => $tipoLog
        );
        $log->gravarLog($dados);
    }

}
