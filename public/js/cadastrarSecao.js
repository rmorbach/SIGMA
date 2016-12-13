/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $("#check-auth").click(function() {
        if ($("#check-auth").prop('checked')) {
            $("#form-autenticacao .form-control").removeAttr('readonly')
        } else {
            $("#form-autenticacao .form-control").attr('readonly', true)
        }
    });
   
    $('#endIp').mask('099.099.099.099');
    try {
        /*
         * Habilita o botão de submit quando o formulário é alterado
         */
        $("#formCadastroSecao").change(function(e) {
            $("#cadastrarSecao").removeClass('disabled');
        });
    } catch (e) {
        console.log(e.message);
    }
});

function cadastrarSecao()
{
    try {
        var nmSecao = $.trim($("#nmSecao").val());
        var descricaoSecao = $.trim($("#descricaoSecao").val());
        var tpSecao = $.trim($("#selectTipoSecao").val());
        var endIp = $.trim($("#endIp").val());
        var nmGateway = $.trim($("#nmGateway").val());
        var login = null;
        var senha = null;
    } catch (e) {
        //ignore
    }
    if ((nmSecao == "") || (descricaoSecao == "") || (tpSecao == "") || (endIp == "")) {
        alerta("#alerta", "<b>Atenção:</b> Verifique os dados inseridos!", "alert-warning", true);
        return false;
    }
    if ($("#check-auth").prop('checked')) {
        login = $.trim($("#user-gateway").val());
        senha = $.trim($("#senha-gateway").val());    
    }
    if ((($("#check-auth").prop('checked')) && (login == "")) || (($("#check-auth").prop('checked')) && (senha == ""))) {
        alerta("#alerta", "<b>Atenção:</b> Informe as credenciais ou desmarque a opção!", "alert-warning", true);
        scrollInto("#alerta");
        return false;
    }
    else {
        /*
         * Verifica se o IP cadastrado já não existe
         */
        $.ajax({
            type: "GET",
            url: "/secoes/validaip",
            data: {
                endip: endIp,
                idlocal: id_local
            },
            success: function(e) {
                if (e != "") {
                    var data = JSON.parse(e);
                    if (data.existe) {
                        alerta("#alerta", "O endereço IP cadastrado já existe no local.", "alert-danger", true);
                        return false;
                    }
                    else {
                        alerta("#alerta", "Aguarde....", "alert-success", false);
                        $.ajax({
                            type: "POST",
                            url: "/secoes/salvarsecao/",
                            data: {
                                nome: nmSecao,
                                descricao: descricaoSecao,
                                endip: endIp,
                                tipo: tpSecao,
                                idlocal: id_local,
                                nmgateway: nmGateway,
                                login: login,
                                senha: senha
                            },
                            success: function(e) {
                                if (e != "") {
                                    alerta("#alerta", "Seção cadastrada com sucesso.", "alert-success", true);
                                    /*
                                     * Limpa o formulário
                                     */
                                    $('#btnLimpar').click();
                                    $("#cadastrarSecao").addClass('disabled');
                                }
                            }
                        });
                    }
                }
            }
        });

    }

}

