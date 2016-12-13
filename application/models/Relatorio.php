<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Relatorio
 *
 * @author Rodrigo Morbach
 */
define("TYPE", ".txt");

class Application_Model_Relatorio {

    //put your code here

    /**
     * Método para gerar o arquivo de exportacao no formato desejado.
     * @param type $dados, array de dados
     * @param type $inicio, data de início
     * @param type $fim, data final do período
     * @param type $ext, extensão para a criação do arquivo
     * @param type $nome, nome do arquivo
     * @return type, string contendo o link para download
     */
    public static function gerarRelatorio($dados, $inicio, $fim, $ext = null, $nome = null) {
        $ext = (($ext) ? $ext : TYPE);
        $arquivo = fopen("txt/" . $nome . $ext, 'w');
        fwrite($arquivo, "De: ".$inicio." até: ".$fim);
        foreach ($dados as $key => $value) {
            fwrite($arquivo, "\nValor : " . $value['vl_dado'].";\t\t Horário: ".$value['dt_hr_registro']);
        }
        fclose($arquivo);
        return "/txt/" . $nome . $ext;
    }
}
