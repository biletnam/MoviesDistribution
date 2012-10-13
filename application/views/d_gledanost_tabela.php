<?php

	/*
	echo "<pre>";
	print_r( $t[ "termini" ] );
	exit();
	*/
	

    $tlen = count( $t[ "termini" ] );
  
    $ts = "";
    $tv = "";
    for( $i = 0; $i < 12; $i++ )
    {
    	if( $i < $tlen )
    	{
    		$ts .= '<tr>
		        <td>' . substr( $t[ "termini" ][ $i ][ "vreme" ], 0, 5 ). '</td>
		        <td>' . substr( $t[ "termini" ][ $i ][ "cena_karte_rsd" ], 0, -2 ) . '</td>
		        <td>' . $t[ "termini" ][ $i ][ "broj_gledalaca" ] . '</td>
		        <td>' . substr( $t[ "termini" ][ $i ][ "cena_naocara_rsd" ] , 0, -2 ). '</td>
		        <td>' . $t[ "termini" ][ $i ][ "broj_prodatih_naocara" ]. '</td>
		    	</tr>';
    		
    		$tv .= '<tr><td colspan="5" align="right">' . $t[ "termini" ][ $i ][ "zarada_po_terminu_rsd" ]. '</td></tr>';
    	}
    	else
    	{
    		$ts .= "<tr>
			        <td>0</td>
			        <td>0</td>
			        <td>0</td>
			        <td>0</td>
			        <td>0</td>
			    	</tr>";
    		$tv .= '<tr><td colspan="5" align="right">0</td></tr>';
    	}
    }
    
?>    

<table class="termin_table" cellpadding="4" cellspacing="0" style="font-size:11px">
   
    <tr>	
        <td colspan="5" align="center" style="font-size:14px !important"><b><?php echo getTehnikaKopijeFilma($t[ "g_data" ][ "tehnika_kopije_filma" ]); ?></b></td>
    </tr>
    
    <tr>
        <td colspan="5" align="center" style="font-size:14px !important"><b><?php echo $t[ "g_data" ][ "naziv_komitenta" ]." - ".$t[ "g_data" ][ "naziv_bioskopa" ]; ?></b></td>
    </tr>
    
    <tr> 
        <td>vreme</td>
        <td>cena</td>
        <td>adm</td>
        <td>cena n.</td>
        <td>adm n.</td>
    </tr>
    
    <?php echo $ts; ?>    
    <?php echo $tv; ?>
    
    <tr>
        <td colspan="5" 
            align="center" 
            style="font-weight:bold; 
            font-size:17px !important; color: #FF0000; "> 
            
                <?php 	echo $t[ "g_data" ][ "suma_zarada_karte_rsd"] + $t[ "g_data" ][ "suma_zarada_naocare_rsd"]?> DIN.
        </td>
    </tr>
        <tr>
        <td colspan="3">SUM: <?php  echo $t[ "g_data" ][ "suma_zarada_rsd"]; ?></td>
        <td colspan="2">N. SUM: <?php echo $t[ "g_data" ][ "suma_prodatih_naocara"]; ?></td>
    </tr>
            
</table>
