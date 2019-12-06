<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
</head>
<body>
    <h1>Пример работы с WebSocket</h1>
    <!--<form id="form" action="" name="messages">-->
    <!--    <div class="row">Имя: <input type="text" class="fname" name="fname"></div>-->
    <!--    <div class="row">Текст: <input type="text" class="msg" name="msg"></div>-->
    <!--     <div class="row"><input id="submit" type="submit" value="Поехали"></div>-->
        
    <!--</form>-->
   
    <div id="status"></div>

	<script>


		window.onload = function() {
		    
		    
		    
		    var socket = new WebSocket('wss://dasdenkich.de:3333');
		    
		    
            var status = document.querySelector("#status");
            socket.onopen = function(e) {
                console.log("Connection established!");
                // socket.send('Hello World!');
            };
            
            socket.onmessage = function(e) {
                console.log(e.data);
            };
		    
		    socket.onclose = function(event) {
	          if (event.wasClean) {
	            status.innerHTML = 'cоединение закрыто';
	          } else {
	            status.innerHTML = 'соединения как-то закрыто';
	          }
	          status.innerHTML += '<br>код: ' + event.code + ' причина: ' + event.reason;
	        };
		    
		    socket.onerror = function(event) {
	           status.innerHTML = "ошибка " + event.message;
	        };
	        
	        

	       // var socket = new WebSocket("ws://localhost:8080");
	       // var status = document.querySelector("#status");
	       // console.log(socket)
	        
	       // socket.onopen = function() {
	       //   status.innerHTML = "cоединение установлено<br>";
	       //    let message = {
	       //          name:'name',
	       //          msg: 'hello'
	       //     }

	       //     socket.send(JSON.stringify(message));
	       // };

	       // socket.onclose = function(event) {
	       //   if (event.wasClean) {
	       //     status.innerHTML = 'cоединение закрыто';
	       //   } else {
	       //     status.innerHTML = 'соединения как-то закрыто';
	       //   }
	       //   status.innerHTML += '<br>код: ' + event.code + ' причина: ' + event.reason;
	       // };

	       // socket.onmessage = function(event) {
	       //   let message = JSON.parse(event.data);    
	       //   status.innerHTML += `пришли данные: <b>${message.name}</b>: ${message.msg}<br>`;
	       // };

	       // socket.onerror = function(event) {
	       //    status.innerHTML = "ошибка " + event.message;
	       // };
	        
	       
	        
	       // document.getElementById('form').onsubmit = function(){
	          
	       //     let message = {
	       //          name:this.fname.value,
	       //          msg: this.msg.value
	       //     }

	       //     socket.send(JSON.stringify(message));
	       //     return false;
	       // }

        
    }
		


	</script>
</body>
</html>