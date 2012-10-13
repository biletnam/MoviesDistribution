<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zakljucnice</title>

<style type="text/css">

table
{
	font-family:Verdana, Geneva, sans-serif";
}

.contentTable td
{
	border: #000000 solid 1px;
}


.innerContentTable td
{
	border: none !important;
}

.new-page
{
	page-break-before:always;
}

</style>

</head>

<body>


<?php
 
$len = count( $zakljucnice );
for( $i = 0; $i < $len; $i++)
{
	echo $zakljucnice[ $i ];
	
	if( $i + 1 != $len )
	{
		echo '<p class="new-page"></p>';
	}
}

?>

</body>
</html>
