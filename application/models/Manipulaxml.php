<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Manipulaxml
 *
 * @author Morbach
 */
class Application_Model_Manipulaxml {

//put your code here

    protected $_arquivoXML;
    protected $_schema;

    public function __construct($schema, $xml) {
        $this->_arquivoXML = $xml;
        $this->_schema = $schema;
        // Enable user error handling 
    }

    public function getNome() {
        return $this->_name;
    }

    /**
     * @uses valida um documento xml a partir do schema xsd
     * @return boolean
     * @author Morbach
     * @version 1.0 24/09/2013
     */
    public function validaXML() {
        libxml_use_internal_errors(true);
        $xml = new DOMDocument();
        $xml->load($this->_arquivoXML);
        try {
            return $xml->schemaValidate($this->_schema);
        } catch (DOMException $e) {
          //echo $e->getMessage();
        }
    }

    /**
     * @uses Retorna código, mensagem e linha do erro ocorrido 
     * @param LibXMLError $error
     * @return string $erro;
     * @author Morbach
     * @version 1.0 24/09/2013
     */
    public function libxml_display_error($error) {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Atenção $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Erro $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Erro fatal $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            // $return .= " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    /**
     * @uses Imprime todos os erros encontrados em um arquivo xml
     * @author Morbach
     * @version 1.0 24/09/2013
     */
    public function libxml_display_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print $this->libxml_display_error($error);
        }
        libxml_clear_errors();
    }

}
?>
