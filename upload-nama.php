<html>
<head><title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title></head>
<body bgcolor="#caffcb">

<H3>Upload Nama</H3>

<?php
$IP=$_GET["ip"];
$Key=$_GET["key"];
if($IP=="") $IP="192.168.1.201";
if($Key=="") $Key="0";
?>

<form action="upload-nama.php">
IP Address: <input type="Text" name="ip" value="<?php=$IP?>" size=15><BR>
Comm Key: <input type="Text" name="key" size="5" value="<?php=$Key?>"><BR><BR>

UserID: <input type="Text" name="id" size="5" value="<?php=$id?>"><BR>
Nama: <input type="Text" name="nama" size="15" value="<?php=$nama?>"><BR><BR>

<input type="Submit" value="Upload Nama">
</form>
<BR>

<?php
if($_GET["ip"]!=""){
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
	if($Connect){
		$id=$_GET["id"];
		$nama=$_GET["nama"];
		$soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN>".$id."</PIN><Name>".$nama."</Name></Arg></SetUserInfo>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
	}else echo "Koneksi Gagal";
	include("parse.php");	
	$buffer=Parse_Data($buffer,"<Information>","</Information>");
	echo "<B>Result:</B><BR>";
	echo $buffer;
}	
?>

</body>
</html>

