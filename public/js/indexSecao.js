/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var id_radio = 0;

$("#btnVerLog").click(function(){
    window.location = "/radios/mensagensalerta/idgateway/"+id_gateway;
})



$(document).ready(function() {
    $('#ip').mask('099.099.099.099');
    $("#form-edit").change(function() {
        $("#form-edit button").removeClass('disabled');
    });

    $("#check-auth").click(function() {
        if ($("#check-auth").prop('checked')) {
            $("#form-autenticacao .form-control").removeAttr('readonly')
        } else {
            $("#form-autenticacao .form-control").attr('readonly', true)
        }
    });
    //Envia o endereço dos MACs para verificar o estado    
    //dadosTopologia.dados = [arrayRadios];
    $.ajax({
        url: "/radios/getstate",
        type: "POST",
        data: {gateway: gateway, macs: arrayRadios, opt:opt},
        success: function(e) {
            try {
                data = JSON.parse(e);
                if (data) {
                    if (data.mensagem) {
                        alerta('#alerta', ERRO_GATEWAY,'alert-danger', true);
                        $("#div-topologia div").html(ERRO_GATEWAY);
                        $(".div-status").removeClass('icon-refresh');
                        $(".div-status").addClass('down-status');
                    }
                    else {
                        $(".div-status").removeClass('icon-refresh');
                        for (var i = 0; i < data.length; i++) {
                            $("#status-" + i).html("");
                            if (data[i].status == 'up') {
                                dadosTopologia.dados[i].status = 'up';
                                $("#status-" + i).addClass('up-status');
                                $("#status-" + i).attr('title', 'Rádio ativo');
                            }
                            else {
                                dadosTopologia.dados[i].status = 'down';
                                $("#status-" + i).addClass('down-status');
                                $("#status-" + i).attr('title', 'Rádio Inativo');
                            }
                            dadosTopologia.dados[i].rssi = data[i].rssi;
                            dadosTopologia.dados[i].net_addr = data[i].net_addr;
                        }
                        drawTopology(dadosTopologia);
                    }                    
                }
            } catch (e) {

            }
        },
        error: function(e) {
        }
    });
});

function mudarStatus(idSecao, statusAtual) {
    novoStatus = "S";
    textoBotao = "<span class='hidden-xs'>Remover Seção <span class='glyphicon glyphicon-trash'></span></span><span class='visible-xs glyphicon glyphicon-trash'></span>";
    if (statusAtual == "S") {
        msg = "Deseja realmente remover a seção?";
        classBtn = 'btn-success';
        classAntiga = 'btn-danger';
        novoStatus = "N";
        textoBotao = "<span class='hidden-xs'>Restaurar Seção <span class='glyphicon glyphicon-refresh'></span></span><span class='visible-xs glyphicon glyphicon-refresh'></span>";
    } else {
        msg = "Deseja restaurar a seção?";
        classBtn = 'btn-danger';
        classAntiga = 'btn-success';
    }

    bootbox.confirm(msg, function(result) {
        if (result) {
            alerta('alerta', 'Aguarde...', 'alert-success', false);
            $.ajax({
                type: "POST",
                url: "/secoes/alterarstatus",
                data: {
                    idsecao: idSecao,
                    status: statusAtual
                },
                success: function(e) {
                    if (e != "") {
                        $("#btn-" + idSecao).removeClass(classAntiga);
                        $("#btn-" + idSecao).addClass(classBtn);
                        $("#btn-" + idSecao).removeAttr('onclick');
                        $("#btn-" + idSecao).attr('onclick', "mudarStatus(" + idSecao + ",'" + novoStatus + "');");
                        $("#btn-" + idSecao).html(textoBotao);
                        alerta('#alerta', 'Status alterado com sucesso', 'alert-success', true);
                    }
                }
            })
        }
    });
}
/**
 * @uses Método para procurar rádios no local indicado
 * @param string gateway
 * @param string acao
 * @return html
 */
function executaRCI(gateway, acao) {
    $(".btn-primary").addClass("disabled");
    alerta("#alerta", "Aguarde, pesquisando dispositivos...", "alert alert-warning", false);
    $.ajax({
        url: "/rci/executacomando",
        type: "post",
        data: {gateway: gateway, acao: acao, id_gateway: id_gateway, opt:opt},
        success: function(dados) {
            $(".btn-primary").removeClass("disabled");
            data = JSON.parse(dados);
            if (data.mensagem != "ok") {
                alert(data.mensagem);
                alerta("#alerta", data.mensagem, "alert alert-danger", true);
                $(".alert-info").html();
            } else {
                $(".alert-info").removeClass('hidden');
                $(".alert-info").addClass('visible');
                $(".alert-info").html("");
                alerta("#alerta", data.qtd_dispositivos + " rádios encontrados. Veja abaixo.", "alert alert-success", true);
                scrollInto("#alerta-radios");
                for (var i = 0; i < data.qtd_dispositivos; i++) {
                    $(".alert-info").append(montaHTML(data[i], i, data.pan));
                }
            }
        },
        erro: function(a) {
            console.log(a);
        }

    });
}

function montaHTML(json, count, pan) {
    //console.log(json.nome);

    html = "<div class='panel panel-default'><div class='panel-heading'><strong>" + json.nome + "</strong></div><div class='panel-body'><div>Dispositivo: <input type='text' class='form-control' value='" + json.nome + "' readonly id='nome-" + count + "'></div>";
    html += "<div>MAC: <input type='text' class='form-control' value='" + json.mac + "' readonly id='mac-" + count + "'></div><div>Rede: <input type='text' class='form-control' value='" + pan + "' readonly id='pan-" + count + "'></div><div>Na rede: <input type='text' class='form-control' value='" + json.net + "' readonly id='net-" + count + "'></div><div>Perfil: <input type='text' class='form-control' value='" + json.perfil_id + "' readonly id='perfil-" + count + "'></div>";
    html += "<div>Tipo: <input type='text' class='form-control' value='" + json.tipo + "' readonly id='tipo-" + count + "'></div><div>Status: <input type='text' class='form-control' value='" + json.status + "' readonly id='status-" + count + "'></div><div>Função: <input type='text' class='form-control' value='" + json.funcao + "' readonly id='descricao-" + count + "' /></div>";
    html += "</div><button class='btn btn-success pull-right' title='Salvar rádio " + json.nome + "' onclick='salvarRadio(" + count + ")'>Salvar Rádio</button></div></div>";
    return html;
}

function salvarRadio(indice) {
    nome = $("#nome-" + indice).val();
    mac = $("#mac-" + indice).val();
    rede = $("#net-" + indice).val();
    perfil = $("#perfil-" + indice).val();
    tipo = $("#tipo-" + indice).val();
    status = $("#status-" + indice).val();
    pan = $("#pan-" + indice).val();
    descricao = $("#descricao-" + indice).val();
    $.ajax({
        type: "POST",
        url: "/radios/salvarradio",
        data: {pan_id: pan, nm_radio: nome, ds_funcao: descricao, net_id: rede, high_id: mac, in_ativo: 's', id_secao: id_secao},
        success: function(e) {
            try {
                data = JSON.parse(e);
                if (data.hasOwnProperty('alerta')) {
                    scrollInto("#alerta");
                    alerta("#alerta", data.alerta, "alert alert-warning", true);
                }
                else {
                    if ($("#tabela-radios").length > 0) {
                        append = "tabela-radios";
                        html = "<tr id='" + data.radio + "'><td><a href='/radios/configradios/secao/" + nm_secao + "/idradio/" + data.radio + "/local/" + nm_local + "/idlocal/" + id_local + "/idsecao/" + id_secao + "/mac/" + mac + "/gateway/" + gateway + "/idgateway/" + id_gateway + "'>" + nome + "</a></td><td>" + descricao + "</td><td>" + mac + "</td><td>" + pan + "</td><td>" + rede + "</td><td></td><td><select><option selected value='S'>S</option><option value='N'>N</option></select></td><td><span  onclick=removerRadio('" + data.radio + "')><span class='glyphicon glyphicon-remove pointer' title='Excluir rádio'></span></span></td></tr>";
                    }
                    else {
                        $("#panel-radios").removeClass('hidden');
                        append = "novos-radios";
                        html = '<table class="table table-striped table-hover" id="tabela-radios"><tr><th>Rádio</th><th>Função</th><th>Pan ID</th><th>Net ID</th><th>MAC</th><th>Sensor</th><th>Ativo</th></tr>';
                        html += "<tr id='" + data.radio + "'><td><a href='/radios/configradios/secao/" + nm_secao + "/idradio/" + data.radio + "/local/" + nm_local + "/idlocal/" + id_local + "/idsecao/" + id_secao + "/mac/" + mac + "/gateway/" + gateway + "/idgateway/" + id_gateway + "'>" + nome + "</a></td><td>" + descricao + "</td><td>" + mac + "</td><td>" + pan + "</td><td>" + rede + "</td><td></td><td><select><option selected value='S'>S</option><option value='N'>N</option></select></td><td><span  onclick=removerRadio('" + data.radio + "')><span class='glyphicon glyphicon-remove pointer' title='Excluir rádio'></span></span></td></tr>";
                        html += "</table>";
                    }
                    appendGeral(append, html);
                    scrollInto("#alerta");
                    alerta("#alerta", "Rádio cadastrado com sucesso", "alert alert-success", true);
                }
            } catch (msg) {
                console.log(msg.message);
            }
        }
    });
}

function removerRadio(idRadio) {
    msg = "Deseja realmente remover o rádio?";
    bootbox.confirm(msg, function(result) {
        if (result) {
            scrollInto("#alerta");
            alerta('#alerta', 'Aguarde...', 'alert-success', false);
            $.ajax({
                type: "POST",
                url: "/radios/excluirradio",
                data: {
                    id_radio: idRadio, id_secao: id_secao
                },
                success: function(e) {
                    removeGeral("#" + idRadio);
                    alerta('#alerta', 'Rádio removido!', 'alert-success', true);
                }
            })
        }
    });
}

function editarDados() {
    $('#form-edit').removeClass('hidden');
    $('#info-secao-gateway').addClass('hidden');
    $('#btn-edit span').removeClass('glyphicon-edit');
    $('#btn-edit span').addClass('glyphicon-remove');
    $('#btn-edit').attr('onclick', 'cancelarEdicao()');
}
function cancelarEdicao() {
    $('#form-edit').addClass('hidden');
    $('#info-secao-gateway').removeClass('hidden');
    $('#btn-edit span').addClass('glyphicon-edit');
    $('#btn-edit span').removeClass('glyphicon-remove');
    $('#btn-edit').attr('onclick', 'editarDados()');
}

function salvarEdicao() {
    var descricao = $.trim($("#descricao").val());
    var nm_gateway = $.trim($("#gateway").val());
    var ip = $.trim($("#ip").val());
    var login = null;
    var senha = null;

    flag = ((gateway != ip) ? 'S' : 'N');

    if ((ip == "") || (nm_gateway == "") || (descricao == "")) {
        alerta('#alerta', 'Verifique os dados', 'alert-danger', true);
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
    $.ajax({
        url: '/secoes/editarsecaogateway',
        type: 'post',
        data: {
            ds_secao: descricao, nm_gateway: nm_gateway, end_ip: ip, id_gateway: id_gateway, id_secao: id_secao, id_local: id_local, fl_mudaip: flag,
            login: login, senha: senha
        },
        success: function(e) {
            data = JSON.parse(e);
            if (data.mensagem == "ok") {
                $(".lead").html(descricao);
                $(".ip").html(ip);
                $(".gateway").html(nm_gateway);
                if (login) {
                    $("#user-gateway").val(login);
                }
                gateway = ip;
                cancelarEdicao();
                scrollInto("#alerta");
                alerta("#alerta", "Dados Atualizados", "alert-success", true);
            }
            else if (data.mensagem == "existe") {
                //cancelarEdicao();
                scrollInto("#alerta");
                alerta("#alerta", "Gateway já cadastrado. Informe outro endereço", "alert-danger", true);
            }
            else {
                scrollInto("#alerta");
                alerta("#alerta", "Erro ao atualizar", "alert-danger", true);
                cancelarEdicao();
            }
        },
        error: function(e) {

        }
    });

}


function reiniciarGateway() {
    alerta("#alerta", "Aguarde, O gateway está sendo reiniciado...", "alert alert-warning", false);
    $.ajax({
        url: "/rci/executacomando",
        type: "post",
        data: {gateway: gateway, acao: 'reboot', id_gateway: id_gateway},
        success: function(e) {
            data = JSON.parse(e);
            counter = 60;
            if (data.mensagem == "reiniciando") {
                $("button").toggleClass('disabled');
                //Cria um contador para reiniciar o dispositivo
                id = setInterval(function() {
                    counter--;
                    if (counter < 0) {
                        alerta("#alerta", "Gateway reiniciado com sucesso.", "alert alert-success", true);
                        clearInterval(id);
                        $("button").toggleClass('disabled');
                    } else {
                        alerta("#alerta", "Aguarde, O gateway será reiniciado em <span id='counter'>" + counter.toString() + "</span> segundos.", "alert alert-warning", false);
                    }
                }, 1000);
            } else {
                scrollInto("#alerta");
                alerta("#alerta", data.mensagem, "alert alert-danger", true);
            }
        },
        error: function(e) {

        }
    });
}


function showFormSensor(idRadio) {
    id_radio = idRadio;
    alerta("#alerta", "Aguarde ...", "alert-warning", true);
    scrollInto("#div-form-sensor");
    $.ajax({
        type: "GET",
        url: "/sensor/cadastrarsensor",
        success: function(e) {
            $("#div-form-sensor").show('slow');
            alerta("#alerta", "", "", false);
            $("#div-form-sensor").html(e);
        },
        error: function(e) {
            alerta("#alerta", "alert-danger", "Erro interno ...", true);
        }
    })
}
function salvarSensor() {
    nm_sensor = $.trim($("#nm_sensor").val());
    ds_sensor = $.trim($("#ds_sensor").val());
    fl_unidade = $.trim($("#fl_unidade").val());
    nm_unidade = $.trim($("#nm_unidade").val());
    $.ajax({
        type: "post",
        url: "/sensor/cadastrarsensor",
        data: {nm_sensor: nm_sensor, ds_sensor: ds_sensor, fl_unidade_medida: fl_unidade, nm_unidade_medida: nm_unidade, id_radio: id_radio, id_local: id_local, id_secao: id_secao},
        success: function(e) {
            data = JSON.parse(e);
            if (data.mensagem == "ok") {
                alerta("#alerta", "Sensor cadastrado. Utilize a url: <span class='text-info'>" + data.url + "</span> no seu microcontrolador.", "alert-success", true);
                scrollInto("#alerta");
                html = "<a href='/sensor/dadossensor/idsensor/" + data.id_sensor + "/idradio/" + data.id_radio + "/idlocal/" + data.id_local + "/idsecao/" + data.id_secao + "' title='Clique para visualizar'><span class='pointer text-info'>" + data.nm_sensor + "</span></a>";
                $("#sensor-" + id_radio).html(html);
                cancelarSensor();
            }
        }
    })
}
function cancelarSensor() {
    $("#div-form-sensor").hide('slow');

}