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
		$Key="0";
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
		// print_r($arr);
		return $arr;
	}
	$IP="10.151.36.69";
	$Key="0";
	$listUser = array();

	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
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

	$buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
	$buffer=explode("\r\n",$buffer);

	$curSize = sizeof($buffer);
	// print_r($curSize);

	for($a=0;$a<count($buffer);$a++){
		$data=Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN=Parse_Data($data,"<PIN>","</PIN>");
		$DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
		$Verified=Parse_Data($data,"<Verified>","</Verified>");
		$Status=Parse_Data($data,"<Status>","</Status>");
		$temp = Get_Detail($PIN);
		$data = array(
			'time' => $DateTime,
			'pin' => $PIN,
			'detail' => $temp
		);
		$listUser[] = $data;
		// echo $data."_".$PIN."_".$DateTime."_".$Verified."_".$Status."<br>";
	}
	unset($listUser[0]);
	unset($listUser[sizeof($listUser)]);
	echo json_encode($listUser);
	// print_r($listUser);
 ?>