<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
#box-table-a {
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin: 45px;
	text-align: left;
	border-collapse: collapse;
	border: 1px solid;
}

#box-table-a th {
	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;
}

#box-table-a td {
	padding: 8px;
	background: #e8edff;
	border-bottom: 1px solid #fff;
	color: #669;
	border-top: 1px solid transparent;
}

#box-table-a tr:hover td {
	background: #d0dafd;
	color: #339;
	border: 1px solid;


.all-rounded {
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
 
.spacer {
	display: block;
}
 
#progress-bar {
	width: 300px;
	margin: 0 auto;
	background: #cccccc;
	border: 3px solid #f2f2f2;
}
 
#progress-bar-percentage {
	background: #3063A5;
	padding: 5px 0px;
 	color: #FFF;
 	font-weight: bold;
 	text-align: center;
}
}
</style>
</head>
<body>
<?php
$host = "localhost";
$username="root";
$password="test1234";
$db_name="distribucija_filmova";
mysql_connect ( "$host", "$username", "$password" ) or die ( "Neuspesno povezivanje sa serverom!" );
mysql_select_db ( "$db_name" ) or die ( "Neuspesno povezivanje sa bazom!" );

$sheet = $_GET ['sheet'];






$sql = "SELECT * FROM cell WHERE sheet='$sheet' ORDER BY col DESC";
$rezultat = mysql_query ( $sql );
$red = mysql_fetch_array ( $rezultat );
$colmax = $red ['col'];

$sql1 = "SELECT * FROM cell WHERE sheet='$sheet' ORDER BY row DESC";
$rezultat1 = mysql_query ( $sql1 );
$red1 = mysql_fetch_array ( $rezultat1 );
$rowmax = $red1 ['row'];





echo "<table id='box-table-a'>";

$x = 1;
while ( $x <= $rowmax ) {

	if ($x == '1') {
		echo "";
	} else {
		echo "<tr>";
		
		$y = 1;
		while ( $y <= $colmax ) {
			
			$upit = "SELECT * FROM cell WHERE sheet='$sheet' AND row=$x AND col=$y";
			$rezultat = mysql_query ( $upit );
			$red = mysql_fetch_array ( $rezultat );
			if ($y == '12' and is_numeric ( $data )) {
				$data = round ( $red ['data'] );
			} else {
				$data = $red ['data'];
			}
			
			if ($x == '1' and $y == '1' or $x == '1' and $y == '2' or $x == '1' and $y == '3' or $x == '1' and $y == '4' or $x == '1' and $y == '5' or $x == '1' and $y == '6' or $x == '1' and $y == '7' or $x == '1' and $y == '8' or $x == '1' and $y == '9' or $x == '1' and $y == '10' or $x == '1' and $y == '11' or $x == '1' and $y == '12') {
				$style = "style='border: 0px;	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;'";
			} 

			else if ($x == '2' and $y == '2' or $x == '2' and $y == '4' or $x == '2' and $y == '5' or $x == '2' and $y == '6' or $x == '2' and $y == '7' or $x == '2' and $y == '8' or $x == '2' and $y == '9' or $x == '2' and $y == '10' or $x == '2' and $y == '11' or $x == '2' and $y == '12') {
				
				$style = "style='border: 0px;	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;'";
			} else if ($x == '4') {
				
				$style = "style='	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;border-top: 0px;border-left: 1px solid black;border-bottom: 1px solid black;border-right: 1px solid black;'";
			} else if ($x == '3') {
				
				$style = "style='	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;border-bottom: 0px;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;'";
			} 

			else if ($x == '2' and $y == '1') {
				
				$style = "style='border: 0px;	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;'";
			} else if ($x == '2' and $y == '3') {
				
				$style = "style='border: 0px;	font-size: 13px;
	font-weight: normal;
	padding: 8px;
	background: #b9c9fe;
	border-top: 4px solid #aabcfe;
	border-bottom: 1px solid #fff;
	color: #039;'";
			} 

			else if ($x == '2' and $y == '7') {
				
				$style = "style='border: 0px; width:500px;	font-size: 13px;
			font-weight: normal;
			padding: 8px;
			background: #b9c9fe;
			border-top: 4px solid #aabcfe;
			border-bottom: 1px solid #fff;
			color: #039;'";
			} else {
				$style = "style='border: 1px solid black;'";
			}
			
			if ($y == '8') {
				echo "";
			} else {
				
				if ($x == $rowmax) {
					echo "<td style='color: #039; font-weight: normal;background: none repeat scroll 0 0 #b9c9fe;'>&nbsp;$data</td>";
				} else {
					
					if ($x == '2' and $data == '') {
						echo "";
					} else if ($x == '2' and $y == '1') {
						echo "<td colspan='3' $style>$data</td>";
					} 

					else if ($x == '2' and $y == '3') {
						echo "<td colspan='3' $style>$data</td>";
					} 

					else if ($x == '2' and $y == '7') {
						echo "<td colspan='4' $style>$data</td>";
					} 

					else {
						echo "<td $style>$data</td>";
					}
				}
			
			}
			$y ++;
		}
		echo "</tr>";
	}
	$x ++;
}

echo "</table>";

function progressBar($percentage) {
	print "<div id=\"progress-bar\" class=\"all-rounded\">\n";
	print "<div id=\"progress-bar-percentage\" class=\"all-rounded\" style=\"width: $percentage%\">";
	if ($percentage > 5) {
		print "$percentage%";
} else {print "<div class=\"spacer\">&nbsp;</div>";
}
print "</div></div>";
}
?>
</body>
</html>