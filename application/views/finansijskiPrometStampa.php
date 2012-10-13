
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Finansijski izvestaj</title>


</head>

<body>

<table class='tableizer-table' >

  <?php
  $row_num = 1;

   foreach( $data as $row )
   {
      echo '<tr>';
      
      echo '<td width="66px">' . $row_num . '</td>';
      echo '<td width="267px">' . $row[ 'naziv_filma' ] . ' / ' .  $row[ 'producent_filma' ] . ' / ' . $row[ 'start_filma' ] . '</td>';
      echo '<td width="221px">' . $row[ 'naziv_bioskopa' ] . '</td>';
      echo '<td width="94px">' . $row[ 'broj_dokumenta_rokovnika' ] . '</td>';
      
      echo '<td>' . $row[ 'datum_z_gledanost_od_stampa' ] . '</td>';
      echo '<td >' . $row[ 'datum_z_gledanost_do_stampa' ] . '</td>';
      
      echo '<td>' . $row[ 'oznaka_kopije_filma' ] . ' / ' . $row[ 'serijski_broj_kopije' ] . '</td>';
      
      echo '<td >' . $row[ 'bruto' ] . '</td>';
      echo '<td >' . $row[ 'neto_sa_pdv' ] . '</td>';
      echo '<td >' . $row[ 'neto' ] . '</td>';
      
      echo '<td >' . $row[ 'broj_gledalaca' ] . ' / ' . $row[ 'raspodela_iznos' ] . '</td>';
      
      echo '<td >' . $row[ 'broj_dokumenta_fakture' ] . ' / ' . $row[ 'datum_prometa' ] . '</td>';
      
      echo '</tr>';

      $row_num ++;
    
   }
   
     
  ?>

  <?php
  //if( isset( $last_page ) ) {
  ?>  

  <tr>
    
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    
    
    
    <td>UKUPNO:</td>
    

    <td align="right"><?php echo number_format( $suma_bruto, 2 ); ?></td>
    <td align="right"><?php echo number_format( $suma_neto_sa_pdv, 2 ); ?></td>
    <td align="right"><?php echo number_format( $suma_neto, 2 ); ?></td>
    <td align="right"><?php echo number_format( $suma_gledalaca , 2 ) . ' / ' . number_format( $suma_raspodela, 2 ); ?></td>
    
    <td></td>
    
  </tr>
  
  <?php //} ?>

</table>

  </body>
</html>
