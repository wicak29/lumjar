<?php 
	function Parse_Data($data,$p1,$p2){
		$data=" ".$data;
		$hasil="";
		$awal=strpos($data,$p1);
		if($awal!=""){
			$akhir=strpos(strstr($data,$p1),$p2);
			if($akhir!=""){
				$hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
			}
		}
		return $hasil;	
	}
	function Get_Detail($PIN) {
		$IP="10.151.36.69";
		$Key = 0;
		$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
		if($Connect){
			$soap_request="<GetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$Key."<ArgComKey><Arg><PIN Xsi:type=\"xsd:integer\">".$PIN."</PIN></ Arg></ GetUserInfo>";
			fputs($Connect, "POST /iWsService HTTP/1.0\r\n");
		    fputs($Connect, "Content-Type: text/xml\r\n");
		    fputs($Connect, "Content-Length: ".strlen($soap_request)."\r\n\r\n");
		    fputs($Connect, $soap_request."\r\n");
			$buffer="";
			while($Response=fgets($Connect, 1024)){
				$buffer=$buffer.$Response;
				// echo $buffer;
			}
		}else echo "Koneksi Gagal";

		$buffer=Parse_Data($buffer,"<GetUserInfoResponse>","</GetUserInfoResponse>");
		$buffer=explode("\r\n",$buffer);

		$data=Parse_Data($buffer[count($buffer)-2],"<Row>","</Row>");
		$PIN1=Parse_Data($data,"<PIN>","</PIN>");
		$Name=Parse_Data($data,"<Name>","</Name>");
		$DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
		// echo $data;
		$arr = array(
			'pin' => $PIN,
			'nama' => $Name
		);
		return $arr;
		// print_r($arr);
	}
	$_POST = json_decode(file_get_contents('php://input'), true);
	// $ret = array(
	// 	'status' => 200, 
	// 	'data' => $_POST['data']
	// );
	$ret = Get_Detail($_POST['data']);
	echo json_encode($ret);

?>