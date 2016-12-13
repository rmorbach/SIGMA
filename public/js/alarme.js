

function executaRCI(){
    $.ajax({
    url: 'http://192.168.0.106/UE/rci',
    data: '<?xml version="1.0" ?><rci_request version="1.1"><do_command target="rci_callback_example">ping  </do_command></rci_request>', 
    type: 'POST',
    contentType: "text/xml",
    dataType: "text",
    success : function(e){
        console.log(e);
    },
    error : function (xhr, ajaxOptions, thrownError){  
        console.log(xhr.status);          
        console.log(thrownError);
    } 
}); 
}
