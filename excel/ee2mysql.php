<html>
<title>Excel -> Baza</title>
<body>
	<div style='margin-left:400px; '>
<?php
set_time_limit (0);
ini_set ( 'memory_limit', '5000M' );

include 'ExcelExplorer.php';




if (isset($_POST['unesi']) and $_POST['unesi']=='ok') {
	
	
	function create_progress_hide() {
		// First create our basic CSS that will control
		// the look of this bar:
		echo "
		<style>
		#text {display:none;}
	    #barbox_a {display:none;}
	   .per{display:none;}
       .bar{display:none;}
       .blank{display:none;}
       img{display:none;}
	   </style>
	";
	
		// Now output the basic, initial, XHTML that
		// will be overwritten later:
		echo "
		<div id='text'></div>
		<div id='barbox_a'></div>
		<div class='bar blank'></div>
		<div class='per'></div>
		";
	
		// Ensure that this gets to the screen
		// immediately:
		flush();
	}
	 
	
	
	
	
	
	
	
function create_progress() {
		// First create our basic CSS that will control
		// the look of this bar:
		echo "
		<style>
		#text {
		position: absolute;
		top: 100px;
		left: 50%;
		margin: 0px 0px 0px -150px;
		font-size: 18px;
		text-align: center;
		width: 300px;
		color:#ffffff;
		
	}
	#barbox_a {
	position: absolute;
	top: 130px;
	left: 50%;
	margin: 0px 0px 0px -160px;
	width: 304px;
	height: 24px;
	background-color: black;
	}
	.per {
	position: absolute;
	top: 130px;
	font-size: 18px;
	left: 50%;
	margin: 1px 0px 0px 150px;
	background-color: #5C91C3;
	color:#ffffff;
	}
	
	.bar {
	position: absolute;
	top: 132px;
	left: 50%;
	margin: 0px 0px 0px -158px;
	width: 0px;
	height: 20px;
	background-color: #0099FF;
	}
	
	.blank {
	background-color: white;
	width: 300px;
	}
	</style>
	";
	
		// Now output the basic, initial, XHTML that
		// will be overwritten later:
		echo "
		<div id='text'>Unosim podatke <img src='ajax-loader.gif'></div>
		<div id='barbox_a'></div>
		<div class='bar blank'></div>
		<div class='per'>0%</div>
		";
	
		// Ensure that this gets to the screen
		// immediately:
		
		flush();
	}
	
	// A function that you can pass a percentage as
	// a whole number and it will generate the
	// appropriate new div's to overlay the
	// current ones:
	
	function update_progress($percent) {
		// First let's recreate the percent with
		// the new one:
		echo "<div class='per'>{$percent}
		%</div>\n";
	
		// Now, output a new 'bar', forcing its width
		// to 3 times the percent, since we have
		// defined the percent bar to be at
		// 300 pixels wide.
		echo "<div class='bar' style='width: ",
		$percent * 3, "px'></div>\n";
	
		// Now, again, force this to be
		// immediately displayed:
		
		flush();
	}
	
	// Ok, now to use this, first create the
	// initial bar info:
	
	

if ($_FILES ['excel_file'] && ($_FILES ['excel_file'] ['tmp_name'] != '')) {
	
	$fsz = filesize ( $_FILES ['excel_file'] ['tmp_name'] );
	$fh = @fopen ( $_FILES ['excel_file'] ['tmp_name'], 'rb' );
	if (! $fh || ($fsz == 0))
		die ( 'No file uploaded' );
	$file = fread ( $fh, $fsz );
	@fclose ( $fh );
	if (strlen ( $file ) < $fsz)
		die ( 'Cannot read the file' );
} else {
	die ( 'No file uploaded' );
}

$ee = new ExcelExplorer ();

switch ($ee->Explore ( $file )) {
	case 0 :
		break;
	case 1 :
		die ( 'File corrupted or not in Excel 5.0 and above format' );
	case 2 :
		die ( 'Unknown or unsupported Excel file version' );
	default :
		die ( 'ExcelExplorer give up' );
}

$mysql_server = 'localhost';
$mysql_username = 'root';
$mysql_password = 'test1234';


$mysql_link = @mysql_connect ( $mysql_server, $mysql_username, $mysql_password ) or die ( 'Could not connect to a database' );

@mysql_select_db ( 'distribucija_filmova', $mysql_link ) or die ( 'Could not select "exceldb" database' );

mysql_query ( "SET NAMES utf8" );
mysql_set_charset ( 'utf8', $mysql_link );



for($sheet = 0; $sheet < $ee->GetWorksheetsNum (); $sheet ++) {
	
	create_progress();

	mysql_query ( "insert into sheet (id,name) values ($sheet,'" . addslashes ( $ee->AsIs ( $ee->GetWorksheetTitle ( $sheet ) ) ) . "')" );
	$shiit = mysql_insert_id();
	if (! $ee->IsEmptyWorksheet ( $sheet )) {
		
		for($col = 0; $col <= $ee->GetLastColumnIndex ( $sheet ); $col ++) {
			
			
			if (! $ee->IsEmptyColumn ( $sheet, $col )) {
				
				for($row = 0; $row <= $ee->GetLastRowIndex ( $sheet ); $row ++) {
					
					if (! $ee->IsEmptyRow ( $sheet, $row )) {
						
						$data = $ee->GetCellData ( $sheet, $col, $row );
						
						switch ($ee->GetCellType ( $sheet, $col, $row )) {
							case 0 :
							case 7 :
							case 8 :
								continue;
							case 1 :
							case 3 :
								break;
							case 2 :
								$data = (100 * $data) . '%';
								break;
							case 4 :
								$data = ($data ? 'TRUE' : 'FALSE');
								break;
							case 5 :
								switch ($data) {
									case 0x00 :
										$data = "#NULL!";
										break;
									case 0x07 :
										$data = "#DIV/0";
										break;
									case 0x0F :
										$data = "#VALUE!";
										break;
									case 0x17 :
										$data = "#REF!";
										break;
									case 0x1D :
										$data = "#NAME?";
										break;
									case 0x24 :
										$data = "#NUM!";
										break;
									case 0x2A :
										$data = "#N/A!";
										break;
									default :
										$data = "#UNKNOWN";
										break;
								}
								break;
							case 6 :
								$data = $data ['string'];
								break;
							default :
								break;
						}
						

						
						mysql_query ( "insert into cell (sheet,col,row,data) values ($shiit,$col,$row,'" . addslashes (trim($data)) . "')" );
					
						if ($ee->GetWorksheetsNum () > 1) {
							$broj_iteracija = $ee->GetWorksheetsNum ();
							$procenat_po_iteraciji	= 100 / $broj_iteracija;
							$percent_sada = round($sheet * $procenat_po_iteraciji);
							if($percent_sada==80){
								create_progress_hide();
							
							}
							update_progress($percent_sada);
						}else{
							$broj_iteracija = $ee->GetLastColumnIndex ( $sheet );
							$procenat_po_iteraciji	= 100 / $broj_iteracija;
							$percent_sada = round($col * $procenat_po_iteraciji);
							if($percent_sada==100){
								create_progress_hide();
							
							}
							update_progress($percent_sada);
						}

					}
				
				}
			
			}
		
		}
	}
}

@mysql_close ( $mysql_link );

echo "<div style='color:#ffffff;'>Svi podaci su uneti u bazu!</div>";
}
?>


<div style='color:#ffffff;'>
<form action="#" method="post" enctype="multipart/form-data">
			Excel file: <input type=file name="excel_file"><br>
			<br> <input type='hidden' name='unesi' value='ok'> <input type=submit
				value="Unesi">
		</form>
		</div>
	</div>
</body>
</html>