<?php
if (isset($_POST['pins'])) {
    $data = explode(',', $_POST['pins']);
    $packet = '';
    foreach ($data as $byte) {
        $packet.= chr($byte);
    }
    $fp = stream_socket_client("udp://192.168.1.5:1099", $errno, $errstr, STREAM_SERVER_BIND);
    if (!$fp) {
        die("$errstr ($errno)");
    }

    fwrite($fp, chr(strlen($packet) + 1) . $packet);
    exit;
};


	function strToBin($mystring){
		$mybitseq = array();
		$end = strlen($mystring);
		for($i = 0 ; $i < $end; $i++){
			$mybyte = decbin(ord($mystring[$i])); // convert char to bit string
			$mybitseq[] =substr("00000000",0,8 - strlen($mybyte)) . $mybyte; // 8 bit packed
		}
		
		array_shift($mybitseq);
		$mybitseq = implode('',$mybitseq);
		return $mybitseq;
	}

	$fp = stream_socket_client("udp://192.168.1.5:1099", $errno, $errstr, STREAM_SERVER_BIND);
	if (!$fp) {
		die("$errstr ($errno)");
	}
	fwrite($fp, chr(50));
	$a = fread($fp, 10);
	$a = strToBin($a);
	
?>
<!DOCTYPE html>
<html style="height: 100%; padding-left: 3px;"  >
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title>ligth controller</title>
        <script src="mootools-core.js" type="text/javascript"></script>
    </head>
    <body style="height: 100%;margin: 0;padding: 0;"  >
        <div id="sss"></div>
        <script type="text/javascript">

            window.addEvent('domready', function() {
            function sendState(pins){
                new Request({
                    url: '<?php echo pathinfo(__FILE__,  PATHINFO_BASENAME ); ?>', 
                    onSuccess: function(res) {
                        document.id('sss').set('html',res);
                    }
                }).post('pins='+pins);
            }
                var pins = [];
                var pinPlace = document.getElement('#pins');

                var addpins = function(pinPlace,count,state){
                    for(var i = 0; i <count*8; i++){
                        var item =  new Element('div',{'style':"float:left;",'html':i+'<br>'}).inject(pinPlace);
                        pins.push(new Element('input',{
							checked:state[i]==1,
							type:'checkbox'
						}).inject(item));

                    }
                }
				
                addpins(pinPlace , 5, '<?php echo $a ?>');

                function getBytes(){
                    var bytes = [];
                    var count = 0;
                    var curbyte = '';
                    pins.each(function(el){
                        curbyte+= el.checked ? '1':'0'; 
                        count++
                        if(count==8){
                            count = 0;
                            bytes.push(parseInt(curbyte,2));
                            curbyte = '';
                        }
                    });
                    return bytes;
                }

				
                pinPlace.addEvent('click',function(){
                    sendState(getBytes());
                });

                var count = 0;
                var action = function(type){
                    setTimeout(function(){
                        if(pins[count]){
                            pins[count].checked = type;
                            var bytes = getBytes();
                            sendState(bytes);
                            //setTimeout(function(){
                                pins[count].checked = type;
                                action(type)
                            //},500);
                            count++;
                        }else{
                            action(!type)
                            count=0;
                            console.log('finish');
                        }
                    },100);
                }
					
                var swipeOn = document.getElement('#swipe-on');
                swipeOn.addEvent('click',function(){
                    action(true);
                    count = 0;
                });
				
                var swipeOff = document.getElement('#swipe-off');
				
                swipeOff.addEvent('click',function(){
                    action(false);
                    count = 0;
                });

            });
        </script>

        <div id="pins" style="margin:50px;"></div>
        <button id="swipe-on">swipe on</button>
        <button id="swipe-off">swipe off</button>
    </body>


</html>