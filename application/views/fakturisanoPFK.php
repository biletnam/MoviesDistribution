<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fakturisano</title>
</head>

<body>



<table class='tableizer-table' width='100%'>
  
  <?php

   foreach( $data as $row )
   {
   		echo '<tr>';
   		
		if( $sumarno_po_filmu != 'da' )
		{
			echo '<td class="broj_fakture">' . $row['broj_dokumenta'] . '</td>';
			echo '<td class="datum_fakture">' . $row[ 'datum_unosa_fakture' ] . '</td>';
		}
		
		echo '<td class="producent">' . $row[ 'producent_filma' ] . '</td>';
		echo '<td class="film">' . $row[ 'naziv_filma' ] . '</td>';
		echo '<td class="komitent">' . $row[ 'naziv_komitenta' ] . '</td>';
		echo '<td align="right" class="fakturisano">' . $row[ 'fakturisano' ] . '</td>';
		echo '<td align="right" class="neto">' . $row[ 'neto' ] . '</td>';
		echo '<td align="right" class="porez">' . $row[ 'porez' ] . '</td>';
		echo '<td align="right" class="naocare">' . $row[ 'naocare' ] . '</td>';
		echo '<td align="right" class="naocare_prihod">' . $row[ 'naocare_prihod' ] . '</td>';
			
		if( $sa_uplatama == 'da'  )
		{
			echo '<td align="right" class="uplaceno">' . $row['uplate_total'] . '</td>';
			echo '<td align="right" class="duguje">' . $row[ 'duguje' ] . '</td>';
		}
		
		echo '</tr>';
   }
  
  ?>
  
  <tr>
  
  	<?php 

  	if( $sumarno_po_filmu != 'da' ) 
  	{
  		echo '<td></td><td></td>';
  	} 

  	?>
  	
  	<td></td>
  	<td></td>
  	<td>UKUPNO:</td>
  	<td align="right"><?php echo $fakturisano_suma;?></td>
  	<td align="right"><?php echo $neto_suma;?></td>
  	<td align="right"><?php echo $porez_suma;?></td>
  	<td align="right"><?php echo $naocare_suma;?></td>
  	<td align="right"><?php echo $naocare_prihod_suma;?></td>
  	
  	<?php 

  	if( $sa_uplatama == 'da'  )
	{
		echo '<td align="right">' . $uplate_total_suma . '</td>';
		echo '<td align="right">' . $duguje_suma . '</td>';
	}
  		
  	?>
  	
  </tr>
  
</table>

</body>
</html>
