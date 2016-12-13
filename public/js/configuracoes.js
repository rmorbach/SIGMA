$(document).ready(function() {
    $('#perfilTab a').click(function(e) {
        e.preventDefault()
        $(this).tab('show')
    })
    $('#telefone').mask('(00) 00000-00009');
    $(".form-group").change(function() {
        $("#btnSalvar").removeClass("disabled");
    });
    $(".form-control").change(function() {
        $("#btnSalvar").removeClass("disabled");
    });

    $("#btnAlterarSenhaN").click(function() {
        if ($("#divEditarSenha").css('display') == "none") {
            $("#btnAlterarSenhaN").html("Cancelar <span class='glyphicon glyphicon-remove'></span>");
            $("#btnAlterarSenhaN").removeClass('btn-primary');
            $("#btnAlterarSenhaN").addClass('btn-warning');
            $("#btnAlterarSenhaS").html('<span class="glyphicon glyphicon-pencil"></span>');
            $("#btnAlterarSenhaS").removeClass('btn-primary');
            $("#btnAlterarSenhaS").addClass('btn-warning');
        }
        else {
            $("#btnAlterarSenhaN").html("Alterar Senha <span class='glyphicon glyphicon-pencil'></span>");
            $("#btnAlterarSenhaN").removeClass('btn-warning');
            $("#btnAlterarSenhaN").addClass('btn-primary');
            $("#btnAlterarSenhaS").html('<span class="glyphicon glyphicon-pencil"></span>');
            $("#btnAlterarSenhaS").removeClass('btn-warning');
            $("#btnAlterarSenhaS").addClass('btn-primary');
        }
        $("#divEditarSenha").toggle('slow');
    });

    $("#btnAlterarSenhaS").click(function() {
        if ($("#divEditarSenha").css('display') == "none") {
            $("#btnAlterarSenhaN").html("Cancelar <span class='glyphicon glyphicon-remove'></span>");
            $("#btnAlterarSenhaN").removeClass('btn-primary');
            $("#btnAlterarSenhaN").addClass('btn-warning');
            $("#btnAlterarSenhaS").html('<span class="glyphicon glyphicon-remove"></span>');
            $("#btnAlterarSenhaS").removeClass('btn-primary');
            $("#btnAlterarSenhaS").addClass('btn-warning');
        }
        else {
            $("#btnAlterarSenhaN").html("Alterar Senha <span class='glyphicon glyphicon-pencil'></span>");
            $("#btnAlterarSenhaN").removeClass('btn-warning');
            $("#btnAlterarSenhaN").addClass('btn-primary');
            $("#btnAlterarSenhaS").html('<span class="glyphicon glyphicon-pencil"></span>');
            $("#btnAlterarSenhaS").removeClass('btn-warning');
            $("#btnAlterarSenhaS").addClass('btn-primary');
        }
        $("#divEditarSenha").toggle('slow');
    });
    /*
     * Carrega o select de grupos de usuário
     */
    $("#selectGrupo").load("/configuracoes/carregagrupos");

});


/**
 * 
 * @returns {undefined}
 */

function atualizarDados() {

    var nome = $.trim($('#nomeUsuario').val());
    var login = $.trim($('#login').val());
    var grupo = $.trim($('#selectGrupo').val());
    var genero = $.trim($('#genero').val());
    var telefone = $('#telefone').val();
    var senhaAtual = $('#senhaAtual').val();
    var novaSenha = $('#novaSenha').val();
    var filtro = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ((nome == "") || (login == "") || (!filtro.test(login))) {
        exibeErro("msgAlerta", "Verifique os dados");
    }
    if(($("#divEditarSenha").css('display') == "block")&&(senhaAtual == "")){
        exibeErro("msgAlerta", "Informe a senha atual ou cancele a operação.")
    }
    /*
     * Se a senha não estiver vazia e for menor que 8 digitos.
     */

    else {
        $("#msgAlerta").html("");
        $.ajax({
            type: 'POST',
            url: '/configuracoes/atualizardados/',
            data: {
                nome: nome,
                login: login,
                genero: genero,
                telefone: telefone,
                senhaAtual: senhaAtual,
                novaSenha: novaSenha,
                grupo: grupo
            },
            success: function(msg) {
                msg = JSON.parse(msg);
                if (msg.Erro) {
                    exibeErro("msgAlerta", msg.Erro);
                } else if (msg.Sucesso) {
                    exibeSucesso("msgAlerta", msg.Sucesso, true, "btnSalvar");
                }
            }

        });
    }

}
function exibeErro(idDiv, msg) {
    $("#" + idDiv).addClass("alert alert-danger");
    $("#" + idDiv).html(msg);
}

function exibeSucesso(idDiv, msg, desabilitarBotao, idBotao) {
    $("#" + idDiv).removeClass("alert alert-danger");
    $("#" + idDiv).addClass("alert alert-success");
    $("#" + idDiv).html(msg);
    if (desabilitarBotao == true) {
        $("#" + idBotao).addClass("disabled");
    }

}

function destaqueCpf() {
    $("#avisoCpf").addClass("alert alert-info");
    $("#avisoCpf").text("É necessario ter um CPF válido cadastrado para criar um curso.");
}

function validaCPF(cpf) {

    cpf = cpf.replace(/[^\d]+/g, '');

    if (cpf == '')
        return false;

    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11 ||
            cpf == "00000000000" ||
            cpf == "11111111111" ||
            cpf == "22222222222" ||
            cpf == "33333333333" ||
            cpf == "44444444444" ||
            cpf == "55555555555" ||
            cpf == "66666666666" ||
            cpf == "77777777777" ||
            cpf == "88888888888" ||
            cpf == "99999999999")
        return false;

    // Valida 1o digito
    add = 0;
    for (i = 0; i < 9; i ++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(9)))
        return false;

    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i ++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(10)))
        return false;

    return true;

}

/**
 * método utilizado para validar numeração de CPF
 * @param strinf cpf
 * return boolean
 */
function verificaCPF(cpf) {
    if (cpf.length > 0) {
        if (!validaCPF(cpf)) {
            exibeErro("msgAlerta", "CPF inválido!", false);
        } else {
            cpfValido = true;
            exibeSucesso("msgAlerta", "CPF válido", false);
        }
    }
}