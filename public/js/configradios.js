
$(document).ready(function() {
    $('.form-control .form-control').change(function(e) {
        parentId = $(this).parent().parent().parent().parent().attr("id");
        $("#" + parentId + " button").removeClass('disabled');
    })
});

function alteraConfiguracaoRadio(form) {
    dados = $("#" + form).serialize() + '&mac=' + mac + '&gateway=' + gateway + '&idradio= ' + idradio + '&idgateway= ' + idgateway + '&opt = ' + opt;
    scrollInto('#alerta');
    alerta('#alerta', 'Aguarde, aplicando alterações', 'alert alert-warning', true);
    $.ajax({
        url: "/radios/alterarconfigradio",
        type: "POST",
        data: dados,
        success: function(msg) {
            data = JSON.parse(msg);
            $(".nm_radio").html(data.node_id);

            alerta('#alerta', 'Configurações atualizadas', 'alert alert-success', true);

        },
        error: function(msg) {

        }

    });
}

function alteraConfiguracaoRede(form) {
    dados = $("#" + form).serialize() + '&mac=' + mac + '&gateway=' + gateway + '&idradio= ' + idradio + '&idgateway= ' + idgateway;
    scrollInto('#alerta');
    alerta('#alerta', 'Aguarde, aplicando alterações', 'alert alert-warning', true);
    $.ajax({
        url: "/radios/alterarconfigrede",
        type: "POST",
        data: dados,
        success: function(msg) {
            data = JSON.parse(msg);
            $(".nm_radio").html(data.node_id);
            if (data.mensagem == "ok") {
                alerta('#alerta', 'Configurações atualizadas', 'alert alert-success', true);
            }
            else {
                alerta('#alerta', data.mensagem, 'alert alert-success', true);
            }
        },
        error: function(msg) {

        }

    });
}
