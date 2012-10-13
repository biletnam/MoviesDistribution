<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dnevna Gledanost</title>
<style type="text/css">

	.termin_table
	{
		float:left;
		border:#000000 1px solid;
	}
	
	.termin_table td
	{
		border:#000000 1px solid;
	}
	
	.tables-cnt
	{
		page-break-after:always;
		padding-bottom:10px;
		width:100%;
		float:left;
	}
	

	
			
		
</style>
</head>

<body>

<?php 

foreach( $gledanosti as $v )
{
	echo "<div align='left' class='tables-cnt'>".$v."</div>";
}

?>

</body>
</html>
