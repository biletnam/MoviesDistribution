<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Finansijski Promet Filma</title>
<style type="text/css">
table.tableizer-table {
	border: 1px solid #CCC;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	width: 1000px;
}
.tableizer-table td {
	padding: 4px;
	margin: 3px;
	border: 1px solid #ccc;
}
.tableizer-table th {
	background-color: #C0E6EC;
	color: #000;
	font-weight: bold;
}
</style>
</head>

<body>


<table class='tableizer-table'>
  
  <tr>
    <td  colspan='4' valign='middle'  align='center'><b>Izvestaj nefakturisano</b></td>
  </tr>
  
  <tr style='font-size:10px;'>
    <td style='font-size:10px;' colspan='2'><b>Datumski interval: <?php echo $datumski_interval; ?></b></td>
    <td style='font-size:10px;' colspan='2'><b> Komitent: <?php echo  strlen( $naziv_komitenta ) > 0 ? $naziv_komitenta : 'SVI'; ?></b></td>
  </tr>
  
  <tr class='tableizer-firstrow'>
   
    <th style='width:150px;'>Naziv filma </th>
    <th style='width:100px;'>Bruto</th>
    <th style='width:100px;'>Neto</th>
    <th style='width:100px;'>PDV</th>
  
  </tr>
  
  <?php

   foreach( $data as $row )
   {
   		echo '<tr>';
   		
		echo '<td>' . $row[ 'naziv_filma' ] . '</td>';
		echo '<td align="right">' . $row[ 'bruto_zarada' ] . '</td>';
		echo '<td align="right">' . $row[ 'neto_zarada' ] . '</td>';
		echo '<td align="right">' . $row[ 'porez' ] . '</td>';	
		
		echo '</tr>';
   }
  
  ?>
  
  <tr>
 
  	<td>UKUPNO:</td>
  	<td><?php echo number_format( $bruto_suma, 4 );?></td>
  	<td><?php echo number_format( $neto_suma, 4 );?></td>
  	<td><?php echo number_format( $pdv_suma, 4 );?></td>
  	
  	
  </tr>
  
</table>

</body>
</html>
