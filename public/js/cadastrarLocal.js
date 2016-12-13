var globalLatitude;
var globalLongitude;
$(document).ready(function() {
    $("#cadastrarLocal").click(function() {        
        cadastrarLocal();
    });
    $("#formCadastroLocal").change(function(e) {
            $("#cadastrarLocal").removeClass('disabled');
        });
   /* $.ajax({
        type: "GET",
        url: "/locais/cidades/",
        success: function(resp) {
            if (resp != "") {
                $("#selectCidades").html(resp);
            }
        }
    });*/
});
/**
 * @uses Função para enviar dados para cadastro de local
 */
function cadastrarLocal()
{    
    try {
        var nmLocal = $.trim($("#nmLocal").val());
        var descricaoLocal = $.trim($("#descricaoLocal").val());
        var endereco = $.trim($("#endereco").val());
        var cidade = 4; //Id da cidade de campinas no sistema
       // var latitude = $.trim($("#latitude").val());
        //var longitude = $.trim($("#longitude").val());
    } catch (e) {
        //ignore
    }
    if ((nmLocal == "") || (descricaoLocal == "") || (endereco == "") || (cidade == "Selecione uma cidade")) {        
        alerta("#alerta", "<b>Atenção:</b> Verifique os dados inseridos!", "alert-warning", true);
    }    
    else if((globalLatitude == undefined) ||(globalLongitude == undefined)){        
        alerta("#alerta", "Selecione um ponto no mapa", "alert-danger", false);
    }
    else {
        alerta("#alerta", "Aguarde....", "alert-success", false);
        $.ajax({
            type: "POST",
            url: "/locais/salvarlocal/",
            data: {
                nome: nmLocal,
                descricao: descricaoLocal,
                cidade: cidade,
                endereco: endereco,
                longitude:  globalLongitude,
                latitude:globalLatitude
            },
            success: function(e) {
                if (e != "") {
                    alerta("#alerta", "Local registrado com sucesso. <a href='/secoes/cadastrarsecao/idcidade/"+cidade+"/idlocal/" + e + "'>Cadastre uma seção</a>.", "alert-success", false);
                    appendGeral("menuLocais", "<a href='/locais/exibelocal/id/" + e + "' title='" + nmLocal + "'>" + nmLocal + "<span class='glyphicon glyphicon-tower pull-right' title='Gerenciar local'></span></a>");
                    $('#btnLimpar').click();
                    $("#cadastrarLocal").addClass('disabled');
                }
            }
        });
    }

}