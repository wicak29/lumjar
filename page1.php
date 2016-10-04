<html>
<head>
	<title>Contoh Koneksi Mesin Absensi Mengunakan SOAP Web Service</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body id="pageBody" bgcolor="#caffcb">
<?php 
	include("tarik-data.php"); 
?>
</body>
<script type="text/javascript">

	$(document).ready(function()
	{
		var cur = <?php echo $curSize; ?>;
		function refresh_page()
		{
			console.log("cur : ", cur);
			$.ajax({
				url : "tarik-data.php",
				cache : false
			})
			.done(function(page){
				$("#pageBody").html(page);
			})

			now = <?php echo $curSize; ?>;
			console.log("now : ", now);
			nilai = document.getElementById("banyak").value;
			console.log("nilai : ", nilai);
			if (now!=nilai) 
			{
				window.location.href = "new_page.php";
				cur = nilai;
			}

		}

		setInterval(refresh_page, 500);
	});
</script>