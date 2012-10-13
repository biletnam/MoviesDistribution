<?php 

 $max_colspan = 12;
 
 if( $sumarno_po_filmu ) $max_colspan -= 2;
 
 if( ! $sa_uplatama ) $max_colspan -= 2;

?>

<table class='tableizer-table' width="100%">
  
  <tr>
    <td  style='font-size:10px;' colspan='<?php echo $max_colspan ?>' valign='middle'  align='center'><b>Izvestaj fakturisano po p/k/f</b></td>
  </tr>
  
  <tr style='font-size:10px;'>
    <td colspan='4'><b>Datumski interval: <?php echo $datumski_interval; ?></b></td>
    <td colspan="<?php echo ( $max_colspan - 4 ) / 2; ?>"><b> Producent: <?php echo $naziv_producenta; ?></b></td>
    <td colspan="<?php echo ( $max_colspan - 4 ) / 2; ?>"><b> Komitent: <?php echo $naziv_komitenta; ?></b></td>
  </tr>
  
  <tr class='tableizer-firstrow'>
 
  <?php if( $sumarno_po_filmu != 'da' ) { ?>

    <th class="broj_fakture_header">Broj f. </th>
    <th class="datum_fakture_header">Datum</th>
    
  <?php } ?> 
    
    <th class="producent_header">Producent </th>
    <th class="film_header">Naziv filma </th>
    <th class="komitent_header">Komitent</th>
    <th class="fakturisano_header">Fakturisano</th>
    <th class="neto_header">Neto</th>
    <th class="porez_header">Porez</th>
    <th class="naocare_header">Naocare</th>
    <th class="naocare_vrednost_header">Naocari v.</th>
  
    <?php if( $sa_uplatama == 'da' ) { ?>
    
    <th class="uplaceno_header">Uplaceno</th>
    <th class="duguje_header">Duguje</th>
    
    <?php } ?>
    
  </tr>

</table>