/**
 * Declaração de variáveis globais * 
 */
var ERRO_GATEWAY = "Gateway inacessível. Verifique se a autenticação está correta"
try {
    if (id_local == "undefined") {
        var id_local = null;
    }
} catch (e) {
    //ignore
}
/**
 * @description Método para criar uma mensagem de feedback
 * @param String idElemento
 * @param String msg
 * @param String classe
 * @param boolean fechar
 * @returns html
 */

function alerta(idElemento, msg, classe, fechar) {

    var alerta = '<div class="alert ' + classe + '">';

    if (fechar) {
        alerta += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    }

    alerta += msg + '</div>';

    $(idElemento).html(alerta);
}
/**
 * @argument {int} div identificador da div a qual se quer adicionar um elemento
 * @argument {string} content conteúdo que se deseja adicionar
 * @returns {undefined}
 */
function appendGeral(div, content) {
    try {
        $("#" + div).append(content);
    } catch (e) {
        alert(e.message);
    }
}
$(document).ready(function() {
    //Habilita tooltips para os botões e campos de entrada de dados    
    $('button').tooltip({
        show: 500, hide: 100, placement:'top'        
    });
     $('a').tooltip({
        show: 500, hide: 100, placement:'top'        
    });
    $('input').tooltip({
        show: 500, hide: 100, placement:'right'
    });
    try {
        if (id_local != "undefined") {
            $("[id_local=" + id_local + "]").addClass('ativo');
        }
    } catch (e) {
        //console.log(e.message);
    }
    finally {
        
    }

});
/*Método para realizar um scroll na página para determinado elemento*/
function scrollInto(elemento) {
    $('html, body').animate({
        scrollTop: $(elemento).offset().top
    }, 1000);
}

function removeGeral(elemento){
    $(elemento).remove();
}