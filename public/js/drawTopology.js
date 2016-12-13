/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var canvas;
var context;
var pontos = [{}];
var conexoes = [{}];
$(document).ready(function() {
    canvas = document.getElementById('canvas-topology');
    context = canvas.getContext('2d');
});
$("#btnVerTopologia").click(function() {
    $("#div-topologia div").toggle('slow');
    scrollInto("#div-topologia");
});
/**/
function drawTopology(dataTopology) {


    var x = (canvas.width) / 2;
    var y = 35;
    var distance = 0;
    context.font = "12px Arial";
    count = 0;
    for (var i = 0; i < dataTopology.dados.length; i++) {
        context.beginPath();
        if (count == 15) {
            count = 0;
            y += 50;
            x = 40;
            distance = 60;
        }
        else if (i == 1) {
            y = 30 + distance;
            x = distance;
        }
        else if (i > 1) {
            x = distance;
        }
        context.arc(x, y, 10, 0, 2 * Math.PI, false);
        if (dataTopology.dados[i].status == "up") {
            context.fillStyle = 'green';
        }
        else {
            context.fillStyle = 'red';
        }
        pontos[i] = {x: x, y: y};
        context.fillText(dataTopology.dados[i].rssi, x - 5, y - 15);
        context.fillText(dataTopology.dados[i].net_addr, x - 5, y - 25);
        context.strokeStyle = "#000000";
        context.stroke();
        context.fill();
        distance += 65;
        count++;
    }
    desenhaConexoes();
}

function desenhaConexoes() {
    for (var i = 0; i < pontos.length; i++) {
        for (var j = 0; j < dadosTopologia.dados.length; j++) {
            for (var k = 0; k < dadosTopologia.dados.length; k++) {
                context.moveTo(pontos[k].x, pontos[k].y);
                if (k != j) {
                    if (dadosTopologia.dados[j].dest_addr == dadosTopologia.dados[k].node) {
                        context.lineTo(pontos[j].x, pontos[j].y);                        
                        context.stroke();
                    }
                    else if ((dadosTopologia.dados[k].dest_addr == "0") || (dadosTopologia.dados[k].dest_addr == "00:00:00:00:00:00:00:00!")) {
                        console.log(dadosTopologia.dados[j].dest_addr);
                        context.lineTo(pontos[0].x, pontos[0].y);
                        context.stroke();
                    }
                }
            }
        }
    }
}
