<?php

class Application_Model_Webservice{
    
    public function getInfoServicosWeb(){
        $conexao = Zend_Db_Table::getDefaultAdapter();
        try{
            $query = "SELECT distinct locais.nm_local, secoes.nm_secao, sensores.nm_sensor
                FROM sensores
                JOIN radio_sensores
                JOIN radios
				join radios_secao
				join secoes
				join secoes_local
				join locais
                ON sensores.id=radio_sensores.id_sensor 
                AND radio_sensores.id_radio = radios.id 
				and radios_secao.id_radio = radios.id
				and radios_secao.id_secao = secoes.id
				and secoes.id = secoes_local.id_secao
				and secoes_local.id_local = locais.id
				and upper(sensores.in_webservice)='S'";
            return $conexao->fetchAll($query);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
}