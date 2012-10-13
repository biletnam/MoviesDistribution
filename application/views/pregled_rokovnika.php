<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rokovnici</title>

<style type='text/css'>

table
{
	font-family:Verdana, Geneva, sans-serif";
}

#rokovnik-view-content-cnt p
{
	padding-left:30px;
}

#rokovnik-view-content-cnt td
{
	border: #000000 solid 1px;
}

.borderedCell
{
	border:#000 1px solid;
}


.terminCellHeader
{
	border-right:#000 2px solid;
	border-top:#000 2px solid;
	border-bottom: #000 2px solid;
	padding-right:2px;
}

.terminCell
{
	border-right:#000 2px solid;
	padding-right:2px;
}

.firstCellHeader
{
	border:#000 2px solid;
}

.firstCell
{	
	border-left:#000 2px solid;
	border-right:#000 2px solid;
}

.inCellFirst
{
	border-bottom:#000 2px solid;
	border-right:#000 2px solid;
}


.inCellLast
{
	border-bottom: #000 2px solid;
}

.new-page
{
	page-break-before:always;
}

</style>

</head>

<body>


<?php
 
$len = count( $rokovnici );

for( $i = 0; $i < $len; $i++)
{
	echo $rokovnici[ $i ];
	
	if( $i + 1 != $len )
	{
		echo '<p class="new-page"></p>';
	}
}

?>

</body>
</html>
