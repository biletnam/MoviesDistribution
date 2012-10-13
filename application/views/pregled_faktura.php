<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fakture</title>

<style type="text/css">

.artikal_header
{
	border-left:1px solid #000000;
}

.artikal_header_last
{
	border-right:1px solid #000000;
}


.borderedCell
{
	border:#000 1px solid;
}

.inCellBorderRows
{
	border-right:#000 1px solid; 
	border-top:#000 1px solid
}

.inCellBorderRowsLast
{
	border-right:#000 1px solid; 
	border-top:#000 1px solid;
	border-bottom:#000 1px solid;	
}

.inCellBorderRowsLast
{
	border-top:#000 1px solid;
}

.cellP
{
	padding-left:5px; 
	padding-right:5px;
}

.cellPInner
{
	padding-left:5px; 
	padding-right:5px;
	margin:3px;
}

.fakturaCellDetaljiLeft
{
	
	border-left:1px solid #000000;
	border-bottom:1px solid #000000;
	border-right:1px solid #000000;
	
}

.fakturaCellDetaljiRight
{
	
	border-bottom:1px solid #000000;
	
}


.porez td
{
	border-bottom:1px solid #000000;
}

.new-page
{
	page-break-before:always;
}

</style>

</head>

<body>

<?php
 
$len = count( $fakture );

for( $i = 0; $i < $len; $i++)
{
	echo $fakture[ $i ];
	
	if( $i + 1 != $len )
	{
		echo '<p class="new-page"></p>';
	}
}

?>
</body>
</html>
