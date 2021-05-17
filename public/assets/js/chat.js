$(function() {
    var webSocket = WS.connect("ws://127.0.0.1:1337");

    let sessionGL=null;
    webSocket.on("socket/connect", function (session) {
        sessionGL=session;
        //session is an AutobahnJS WAMP session.
    //the callback function in "subscribe" is called everytime an event is published in that channel.
        session.subscribe("acme/channel", function (uri, payload) {
            if(payload.msg.user)
                generate_message(payload.msg, 'self');
        });

    })
var INDEX = 0; 
$("#chat-submit").click(function(e) {
    e.preventDefault();
    var msg = $("#chat-input").val(); 
    if(msg.trim() == ''){
    return false;
    }
    var myHeaders = new Headers();
    myHeaders.append("apikey", "rSCgL3S97mSJvbM1nD1eTpOmV7JVp4UV");

    var raw = "body";

    var requestOptions = {
    method: 'POST',
    redirect: 'follow',
    headers: myHeaders,
    body: msg
    };

    fetch("https://api.promptapi.com/bad_words?censor_character=*", requestOptions)
    .then(response => response.text())
    .then(result => {
        result=JSON.parse(result)
        msg=result.censored_content?result.censored_content:result.content;
        const obj={user:$("#userName").val(),msg,id:$('#idUser').val()}
        sessionGL.publish("acme/channel", obj);
        if(result.bad_words_total>0){
            let oldBad=parseInt(localStorage.getItem('nbBadWord'));
            if(oldBad)
                localStorage.setItem('nbBadWord',oldBad+1);
            else
                localStorage.setItem('nbBadWord',1);
            let msgAdmin="";
            let type="warning";
            if(oldBad+1>=3){  
               msgAdmin=" <i class='fas fa-info-circle'></i> "+ obj.user +" is banned from the server  ";
               type="info";
               localStorage.setItem('nbBadWord',0);
               fetch("/member/"+$('#idUser').val()+"/desactiver",{method: 'GET'})
               .then(response => response.text())
               .then(result => {
                   result=JSON.parse(result)
                console.log(result)
                })
                .catch(error =>{
                    window.location.href ="/logout"
                });
            } else
             msgAdmin=" <i class='fas fa-bomb'></i>  <i class='fas fa-bomb'></i>  <i class='fas fa-bomb'></i> "+ obj.user +" don't use bad words you will be banned <i class='fas fa-exclamation-triangle'></i> <i class='fas fa-bomb'></i>  <i class='fas fa-bomb'></i>  <i class='fas fa-bomb'></i> ";
            const obj2={user:'Admin',msg:msgAdmin,type};
            sessionGL.publish("acme/channel", obj2);
        }
    })
    .catch(error => console.log('error', error));
    
})

function generate_message(obj, type) {
    INDEX++;
    let styleBg="";
    let styleName="";
    let bgColor="#ffeb3b";
    if(obj.type){
        if(obj.type=="info")
            bgColor="#cce5ff";
        styleBg=" style='background:"+bgColor+";color: #f44336;'  ";
        styleName=" style='color:#f44336' ";
    }
    if(obj.user!='Admin'){
        if(obj.id==$('#idUser').val()){
            obj.user='You'
        }
    }
    var str="";
    str += "<div id='cm-msg-"+INDEX+"' class=\"chat-msg "+type+"\" >";
   str += "         <span class='userName' "+styleName+"> "+obj.user+" : </span> ";
    str += "          <div class=\"cm-msg-text\" "+styleBg+">";
    str += obj.msg;
    str += "          <\/div>";
    str += "        <\/div>";
    $(".chat-logs").append(str);
    $("#cm-msg-"+INDEX).hide().fadeIn(300);
    if(type == 'self'){
    $("#chat-input").val(''); 
    }    
    $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);    
}  

$(document).delegate(".chat-btn", "click", function() {
    var value = $(this).attr("chat-value");
    var name = $(this).html();
    $("#chat-input").attr("disabled", false);
    generate_message(name, 'self');
})

$("#chat-circle").click(function() {    
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
})

$(".chat-box-toggle").click(function() {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
})


})

