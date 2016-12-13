$(document).ready(function() {
    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
        $(".exibeIE").show();
    }

    var options = {
        minChar: 6,
        showVerdicts: true,
        usernameField: "#emailUsuario"
    };
    $('#senhaCadUsuario').pwstrength(options);

});
/**
 * Método que esconde um elemento/div e exibe outro
 * @param {Mixed} visible ID, classe ou elemento a ser ocultado
 * @param {Mixed} hidden ID, classe ou elemento a ser exibido
 * @returns {void}
 */
function toggleDivs(visible, hidden) {
    $("#alert").html("");
    $(visible).hide();
    $(hidden).fadeIn(400);
}
/**
 * Método que trata a recuperação de senha
 * @returns {Boolean}
 */
function recuperarSenha() {
    var email = $.trim($("#emailRecuperar").val());
    if (!email.length) {
        exibeAlerta("Por favor, preencha o e-mail.", "alert-danger");
        return false;
    }
    var filtro = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filtro.test(email)) {
        exibeAlerta("Por favor, insira um e-mail válido.", "alert-danger");
        return false;
    }
    exibeAguarde();
    $.ajax({
        type: "POST",
        url: "/login/recuperar",
        data: {email: email},
        success: function(msg) {
            $("#emailRecuperar").val("");
            exibeAlerta("As instruções para a recuperação de sua senha foram enviadas ao seu e-mail.", "alert-success");
        },
        error: function(msg) {
            exibeAlerta("Ocorreu um erro ao recuperar a senha.<br/>Tente novamente mais tarde.", "alert-danger");
        }
    });
}
/**
 * Método que exibe um alerta ao usuário
 * @param {Text} msg Texto da mensagem
 * @param {Text} classe Tipo da mensagem (OBS: usar as classes do Bootstrap)
 * @returns {void}
 */
function exibeAlerta(msg, classe) {
    var alert = '<div class="alert ' + classe + '"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + msg + '</div>';
    $("#alert").html(alert);
}
/**
 * Método que exibe a imagem de aguarde
 * @returns {void}
 */
function exibeAguarde() {
    $("#alert").html("<img src='/images/loader-senha.gif' alt='Aguarde...' title='Aguarde'/>");
}
/**
 * Método que troca o form de login para o form de cadastro
 */
function fazerParte() {
    $("#tituloIM").hide();
    $("#tituloIM").html('Faça parte do <img class="logoIgnisMundo img-responsive" src="/images/ignismundo.png" title="IgnisMundo" alt="IgnisMundo"/>');
    document.title = "IgnisMundo - Faça Parte";
    $("#tituloIM").fadeIn(400);
    toggleDivs("#loginForm", "#cadastroForm");
}
/**
 * Método que troca o form de cadastro para o form de login
 */
function retornaLogin() {
    $("#tituloIM").hide();
    $("#tituloIM").html('Bem vindo ao <img class="logoIgnisMundo img-responsive" src="/images/ignismundo.png" title="IgnisMundo" alt="IgnisMundo"/>');
    document.title = "IgnisMundo - Bem Vindo";
    $("#tituloIM").fadeIn(400);
    toggleDivs("#cadastroForm", "#loginForm");
}
/**
 * Método para validar o formulário de cadastro
 */
function validaCadastro() {
    if (!$.trim($("#nomeUsuario").val()).length) {
        exibeAlerta("Por favor, digite seu nome!", "alert-danger");
        return false;
    }
    if (!$.trim($("#emailUsuario").val()).length) {
        exibeAlerta("Por favor, digite seu email!", "alert-danger");
        return false;
    }
    var filtro = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filtro.test($.trim($("#emailUsuario").val()))) {
        exibeAlerta("Por favor, insira um e-mail válido!", "alert-danger");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/cadastro/checardados",
        data: {tipoDado: "email", email: $.trim($("#emailUsuario").val())},
        success: function(msg) {
            if (!msg) {
                if (!$("#senhaCadUsuario").val().length) {
                    exibeAlerta("Por favor, digite uma senha!", "alert-danger");
                    return false;
                }
                if ($("#senhaCadUsuario").val().length < 8) {
                    exibeAlerta("Por favor, digite pelo menos 8 caracteres!", "alert-danger");
                    return false;
                }
                if (!$("#confSenhaUsuario").val().length) {
                    exibeAlerta("Por favor, confirme sua senha!", "alert-danger");
                    return false;
                }
                if ($("#confSenhaUsuario").val() !== $("#senhaCadUsuario").val()) {
                    exibeAlerta("As senhas digitadas não coincidem.", "alert-danger");
                    return false;
                }
                $("#formCadastro").submit();
            }
            else {
                exibeAlerta("Já existe um usuário com este email! Tente novamente.", "alert-danger");
                return false;
            }
        },
        error: function(msg) {
            exibeAlerta("Ocorreu um erro ao validar seu email! Tente novamente.", "alert-danger");
            return false;
        }
    });
}
/**
 * Método para validar o formulário de login
 */
function validaLogin() {
    if (!$.trim($("#loginUsuario").val()).length) {
        exibeAlerta("Por favor, digite seu email!", "alert-danger");
        return false;
    }
    var filtro = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filtro.test($.trim($("#loginUsuario").val()))) {
        exibeAlerta("Por favor, insira um e-mail válido!", "alert-danger");
        return false;
    }
    if (!$("#senhaUsuario").val().length) {
        exibeAlerta("Por favor, digite sua senha!", "alert-danger");
        return false;
    }
    return true;
}