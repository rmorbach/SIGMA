/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {

});

/**
 * 
 * @param {int} id identificador o local
 * @returns {boolean}
 * @author Morbach
 * @version 1.0 15/09/2013
 */
function mudarStatusLocal(id, statusAtual) {    
    novoStatus = "S";
    textoBotao = "<span class='hidden-xs'>Remover Local <span class='glyphicon glyphicon-trash'></span></span><span class='visible-xs glyphicon glyphicon-trash'></span>";
    if(statusAtual == "S"){
        msg = "Deseja realmente remover o local?";
        menu = 'menuLocaisInativos';
        icon = 'trash';        
        classBtn = 'btn-success';
        classAntiga = 'btn-danger' ;
        novoStatus = "N";     
        textoBotao = "<span class='hidden-xs'>Restaurar Local <span class='glyphicon glyphicon-refresh'></span></span><span class='glyphicon glyphicon-refresh visible-xs'></span>";
    }else{
        msg = "Deseja restaurar o local?";
        menu = 'menuLocais';
        icon = 'tower';        
        classBtn = 'btn-danger' ;
        classAntiga = 'btn-success';
    }        
    
    bootbox.confirm(msg, function(result) {        
        if (result) {
            alerta('alerta', 'Aguarde...', 'alert-success', false);
            $.ajax({
                type: "POST",
                url: "/locais/alterarstatus",
                data: {
                    idlocal: id,
                    statusAtual: statusAtual
                },
                success: function(e) {                   
                    if (e != "") {
                        //nmLocal = $("li #" + id).text();
                        $("li #" + id).remove();                                                
                        $(" #btn-"+id_local).removeClass(classAntiga);
                        //$(".pull-right #"+id_local+" span").addClass(icon);
                        $("#btn-"+id_local).addClass(classBtn);
                        $("#btn-"+id_local).removeAttr('onclick');
                        $("#btn-"+id_local).attr('onclick', "mudarStatusLocal("+id_local+",'"+novoStatus+"');");
                        $("#btn-"+id_local).html(textoBotao);
                        alerta('#alerta', 'Status alterado com sucesso', 'alert-success', true);                                                
                        appendGeral(menu, "<a id='"+id_local+"' href='/locais/exibelocal/id/" + id_local + "' title='" + nm_local + "'>" + nm_local + "<span class='glyphicon glyphicon-"+icon+" pull-right' title='Gerenciar local'></span></a>");
                    }
                }
            })
        }
    });
}
