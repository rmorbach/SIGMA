$(document).ready(function() {
    $('#dtInicial').mask('99-99-9999');
    $('#dtFinal').mask('99-99-9999');
    $("#form-edit button").removeClass('disabled');
    $("#chart").html('Aguarde...');
    $.ajax({
        type: "get",
        url: "/logs/buscardadoslog",
        data: {
            idradio: idRadio, idsensor: idSensor, gerartxt: "N"
        },
        success: function(e) {
            data = JSON.parse(e);
            dataChart = data;
            if (data.mensagem) {
                alerta("#alerta", "Informe um período para pesquisa de logs.", 'alert-warning', true);
                scrollInto("#alerta");
                $("#chart").html('<span class="glyphicon glyphicon-ban-circle vazio" title="Nenhum dado encontrado"></span>')
            }
            else {                
                geraGrafico();
            }
        },
        error: function(e) {

        }
    })
});

function geraGrafico() {
    $('#chart').highcharts(dataChart);
}

function buscarDados() {
    var dataInicial = $.trim($("#dtInicial").val());
    var dataFinal = $.trim($("#dtFinal").val());
    if ((dataFinal == "") || (dataInicial == "")) {
        scrollInto("#alerta");
        alerta('#alerta', "Preencha os campos corretamente", 'alert-warning', true);
    }
    else {
        $.ajax({
            type: 'POST',
            url: '/logs/buscardadoslog',
            data: {
                idradio: idRadio, idsensor: idSensor, dtInicial: dataInicial, dtFinal: dataFinal, gerartxt: "N"
            },
            success: function(e) {
                data = JSON.parse(e);
                if (data.mensagem) {
                    alerta("#alerta", "Nenhum dado encontrado, redefina a sua pesquisa", 'alert-danger', true);
                    scrollInto("#alerta");
                    $("#chart").html('<span class="glyphicon glyphicon-ban-circle vazio" title="Nenhum dado encontrado"></span>')
                }
                else {
                    $(".alert").remove();
                    dataChart = data;
                    geraGrafico();
                }
            }
        });
    }
}
function changeChart(tipo) {
    dataChart.chart.type = tipo;
    geraGrafico();
}

function editarSensor() {
    $('#form-edit').removeClass('hidden');
    $('#info-sensor').addClass('hidden');
    $('#btn-edit span').removeClass('glyphicon-edit');
    $('#btn-edit span').addClass('glyphicon-remove');
    $('#btn-edit').attr('onclick', 'cancelarEdicao()');
}
function cancelarEdicao() {
    $('#form-edit').addClass('hidden');
    $('#info-sensor').removeClass('hidden');
    $('#btn-edit span').addClass('glyphicon-edit');
    $('#btn-edit span').removeClass('glyphicon-remove');
    $('#btn-edit').attr('onclick', 'editarSensor()');
}

function salvarSensor() {
    var descricao = $.trim($("#descricao").val());
    var nm_sensor = $.trim($("#nm_sensor").val());    
    var fl_unidade_medida = $.trim($("#fl_unidade_medida").val());
    var nm_unidade_medida = $.trim($("#nm_unidade_medida").val());
    var url = $.trim($("#url").val());
    
    var in_webservice = $.trim($("#webservice").val());
    if ((nm_unidade_medida == "") || (fl_unidade_medida == "") || (descricao == "") || (nm_sensor == "")) {
        alerta('#alerta', 'Verifique os dados', 'alert-danger', true);
        return false;
    }
    $.ajax({
        url: '/sensor/atualizarsensor',
        type: 'post',
        data: {
            ds_sensor: descricao, nm_sensor: nm_sensor, fl_unidade_medida: fl_unidade_medida, nm_unidade_medida: nm_unidade_medida, id_sensor: idSensor, get_url: url, in_webservice:in_webservice
        },
        success: function(e) {
            data = JSON.parse(e);
            if (data.mensagem == "ok") {
                $(".nm_sensor").html(nm_sensor);
                $(".ds_sensor").html(descricao);
                $(".un_medida").html(nm_unidade_medida + " - " + fl_unidade_medida);
                $("#nm_unidade_medida").val(nm_unidade_medida);
                $("#fl_unidade_medida").val(fl_unidade_medida);
                $("#nm_sensor").val(nm_sensor);
                $("#descricao").val(descricao);
                $("#url").val(url);
                $(".url").html(url);                
                $(".in_webservice").html(((in_webservice == "S")?"Sim":"Não"));

                cancelarEdicao();
                scrollInto("#alerta");
                alerta("#alerta", "Dados Atualizados", "alert-success", true);
            }
            else {
                scrollInto("#alerta");
                alerta("#alerta", "Erro ao atualizar o sensor", "alert-danger", true);
                cancelarEdicao();
            }
        },
        error: function(e) {

        }
    });

}

function removerSensor(idSensor) {
    msg = "Deseja remover o sensor?<br> Ao remover um sensor os logs não poderão mais ser visualizados";
    bootbox.confirm(msg, function(result) {
        if (result) {
            alerta('alerta', 'Aguarde...', 'alert-success', false);
            $.ajax({
                type: "POST",
                url: "/sensor/removersensor",
                data: {
                    idsensor: idSensor
                },
                success: function(e) {
                    data = JSON.parse(e);
                    if (data.mensagem == "ok") {
                        alerta("#alerta", "Sensor removido com sucesso.", 'alert-success', true);
                        $('button').addClass('disabled');
                    } else {
                        alerta("#alerta", "Erro ao remover o sensor.", 'alert-danger', true);
                    }
                }
            })
        }
    });
}

//Realiza busca para criar arquivo de log

function criarTxt() {
    var dataInicial = $.trim($("#dtInicial").val());
    var dataFinal = $.trim($("#dtFinal").val());
    if ((dataFinal == "") || (dataInicial == "")) {
        scrollInto("#alerta");
        alerta('#alerta', "Preencha os campos corretamente", 'alert-warning', true);
    }
    else {
        $.ajax({
            type: 'POST',
            url: '/logs/buscardadoslog',
            data: {
                idradio: idRadio, idsensor: idSensor, dtInicial: dataInicial, dtFinal: dataFinal, gerartxt: "S"
            },
            success: function(e) {
                 data = JSON.parse(e);            
            if (data.mensagem) {
                alerta("#alerta", "Nenhum dado encontrado.", 'alert-warning', true);
                scrollInto("#alerta");
                $("#chart").html('<span class="glyphicon glyphicon-ban-circle vazio" title="Nenhum dado encontrado"></span>')
            }else{
                //$('#download-link').html("<a href='http://"+data.link+"' target='_blank'>Baixar</a>");
                //window.open("http://"+data.link+"", "_blank");
                window.location.href = "http://"+data.link+"";
            }
            },
            error: function(e) {

            }
        });
    }
}