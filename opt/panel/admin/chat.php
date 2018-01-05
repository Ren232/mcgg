<?php 
require_once('header_inc.php');
require_once('includes/header.php');
?>
	<div id="page_wrap">
	
		<div class="info">Info message</div>
        <div class="success">Successful operation message</div>
        <div class="warning">Warning message</div>
        <div class="error">Error message</div>
		
		<div id="chatwindow">
			<h1>Console</h1>
			<table width="100%">
				<tr>
					<Td  width="100%">
						<table width="100%">
							<tr>
								<td>
									<div name="loader" id="loader"></div>
									<div id="chatwarper" style="z-index:-1;"><applet code="Window.class" width="100%" height="300" archive="json-rpc-1.0.jar, swing-layout-1.0.1.jar">
									<param name="url" value="<?php echo "http://".$_SERVER["SERVER_NAME"].":".$API['PORT']."/api/subscribe?source=all&key=" . hash('sha256', $API['USER']."all".$API['PASS'].$API['SALT']); ?>">
									</applet></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<Td nowrap align="center">
						<div>
							<input type="text" id="cmd" style="width:90%" onkeyPress="var charCode;var e=event;if(e && e.which){charCode = e.which;}else if(window.event){e = window.event;charCode = e.keyCode;}if(charCode == 13) {document.getElementById('sendbutton').click();}" /><input type="button" value="Send" id="sendbutton" accesskey="enter" onclick="ajaxOpenPage('cmdhandler.php?cmd=' + document.getElementById('cmd').value, 'loader');document.getElementById('cmd').value='';"/>
							</div>
					</td>
				</tr>
			</table>
		</div>	
	</div>
<?php require_once('includes/footer.php'); ?>


<?php

/*
<?php
header('Content-type: text/html');
  echo "<!--";
  for($i=0;$i<1000;$i++) {
  echo "          ";
  }
  echo "-->";
  flush();
?>
<html>
  <head>
  <title>Chat</title>
  </head>
  <body>
  <table>
  <th>Player</th>
  <th>Message</th>
  <th>Source</th>
  <th>Date</th>
<?php
  $f = fopen("http://".$API['ADDRESS'].":".$API['PORT']."/api/subscribe?source=chat&username=".$API['USER']."&password=".$API['PASS']."","r");
  ob_end_flush();
  while(!feof($f)) {
  $output = fread($f,8192);
  $arr = json_decode($output,true);
print_r($arr);
echo "<tr>";
  echo "<td>".$arr['data']['player']."</td><td>".$arr['data']['message']."</td><td>".$arr['source']."</td><td>".date('r')."</td><br>\n";
  echo "</tr>";
  flush();
  }
  fclose($f);
  ?>
</table>
  </body>
  </html>
  */
  ?>
