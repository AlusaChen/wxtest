<?php
file_put_contents('/tmp/wx_data.log', file_get_contents("php://input")."\n", FILE_APPEND);
echo 'success';

/*
if($_GET)
{
	$signature = $_GET["signature"];
	$timestamp = $_GET["timestamp"];
	$nonce = $_GET["nonce"];
	$echostr = $_GET["echostr"];

	$token = md5('alusachen');
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	if( $tmpStr == $signature )
	{
		echo $echostr;
	}
	else
	{
		return 'aa';
	}
}
else
{
	//echo 'wx';
	echo json_encode(['name'=>'Alusa','age'=>10]);

}
*/
?>
