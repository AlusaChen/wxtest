	  $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
	$nonce = $_GET["nonce"];	
        $echostr = $_GET["echostr"];	

	$token = md5('alusachen');
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	
	if( $tmpStr == $signature ){
		echo $echostr;
	}else{
		return 'aa';
	}
}
else{
	echo 'wx';
}
?>