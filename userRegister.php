<?php 
	$IP="10.151.36.69";
	$Key="0";
	$listUser = array();

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

	function addUser($data, $Key, $IP){
		$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
		if($Connect){
			$soap_request="<SetUserInfo><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg>
				<PIN>".$data['PIN']."</PIN>
				<Name>".$data['Name']."</Name>
				<Password>".$data['Password']."</Password>
			</Arg></SetUserInfo>";
			fputs($Connect, "POST /iWsService HTTP/1.0\r\n".$newLine);
		    fputs($Connect, "Content-Type: text/xml\r\n".$newLine);
		    fputs($Connect, "Content-Length: ".strlen($soap_request)."\r\n\r\n");
		    fputs($Connect, $soap_request."\r\n");
			$buffer="";
			while($Response=fgets($Connect, 1024)){
				$buffer=$buffer.$Response;
				// echo $buffer;
			}
		}else echo "Koneksi Gagal";

		$buffer=Parse_Data($buffer,"<Information>","</Information>");
		if ($buffer == 'Successfully!') {
			$res = array(
				'status' => 200,
				'Name' => $data['Name'],
				'PIN' => $data['PIN'] 
			);
			return $res;
		}
	}

	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetAllUserInfo><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey></GetAllUserInfo>";
		fputs($Connect, "POST /iWsService HTTP/1.0\r\n".$newLine);
	    fputs($Connect, "Content-Type: text/xml\r\n".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request)."\r\n\r\n");
	    fputs($Connect, $soap_request."\r\n");
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
			// echo $buffer;
		}
	}else echo "Koneksi Gagal";

	$buffer=Parse_Data($buffer,"<GetAllUserInfoResponse>","</GetAllUserInfoResponse>");
	$buffer=explode("\r\n",$buffer);

	// $curSize = sizeof($buffer);

	for($a=0;$a<count($buffer);$a++){
		$data=Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN=Parse_Data($data,"<PIN>","</PIN>");
		$Name=Parse_Data($data,"<Name>","</Name>");
		$Password=Parse_Data($data,"<Password>","</Password>");
		$Privilege=Parse_Data($data,"<Privilege>","</Privilege>");
		$listUser[] = $PIN;
	}
	$_POST = json_decode(file_get_contents('php://input'), true);
	// echo $_POST['nama'];
	$data = array(
		'Name' => $_POST['nama'],
		'PIN' => $listUser[sizeof($listUser)-2]+1,
		'Password' => $_POST['password'],
		'Alamat' => $_POST['alamat'],
		'Telepon' => $_POST['telepon'],
		'Username' => $_POST['usename']
	);
	// echo json_encode($data);
	echo json_encode(addUser($data, $Key, $IP));
?>