<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="50%" align="center">
    	
        	<table>
            	<tr>
                	<td align="center" style="border-bottom:#000000 2px solid"><b>IZVESTAJ O GLEDANOSTI</b></td>
                </tr>
                <tr>
                	<td style="border-bottom:#000000 2px solid" align="center"><b>br. <?php echo $rd[ 'broj_dokumenta_z_gledanosti']; ?></b></td>
                </tr>
                <tr>
                	<td style="font-weight:bold">
                    	Beograd, <?php echo $rd[ 'datum_unosa_stampa' ]; ?><br />
                        <?php echo $rd['naziv_filma'];?><br />
                        Kopija: <?php echo $rd['oznaka_kopije_filma'] . ", s/n: " . $rd['serijski_broj_kopije'];?><br />
                        TARAMOUNT FILM
                    </td>
                </tr>
            </table>
            
        </td>
        
        <td width="50%" align="center">        
            
            <table>
                <tr>
                    <td align="center"><?php echo $rd['komitent_id'] . " " . $rd['naziv_komitenta']?></td>
                </tr>
                <tr>
                    <td align="center" style="font-size:12px; font-weight:bold"><?php echo $rd['pib_komitenta'];?></td>
                </tr>
                <tr>
                    <td align="center" style="font-size:12px; font-weight:bold"><?php echo $rd['adresa_komitenta'];?></td>
                </tr>
                <tr>
                    <td align="center" style="font-size:12px; font-weight:bold"><?php echo $rd['zip_komitenta'] . ' ' . $rd['mesto_komitenta'];?></td>
                </tr>
                <tr>
                    <td align="center" style="font-size:12px; font-weight:bold"><?php echo $rd['tel1_komitenta'];?></td>
                </tr>
                <tr>
                    <td align="center" style="font-size:12px; font-weight:bold"><?php echo $rd['tel2_komitenta'];?></td>
                </tr>
                
                <tr>
                    <td align="center" style="font-size:12px; font-weight:bold">BIOSKOP: <?php echo $rd['naziv_bioskopa']; ?></td>
                </tr>
                
            </table>
            
        </td>
    </tr>
    
</table>    
 
<div style="height:230px;" align="center">

<table width="100%" style="font-size:15px" cellpadding="0" cellspacing="0" class="contentTable">
	    	            
	<?php
        
        
        $tc = 0;
        $tnum = 0;
        
        $cg = count( $gledanost_data );
        
        $fta = "<tr>";
        
		$header = "";
		
		
        $max_termina = $rd[ 'status_kopije' ];
        
        $suma_key_name = "suma_zarada_karte_"; 
        $cena_karte_key_name = "cena_karte_";
        
        
        $za_raspodelu_key_name = "za_raspodelu_";
        $za_distributera_key_name = "za_distributera_";
        
        
        $iznos_pdv = "iznos_pdv_";
        $pdv_procenat = "pdv_procenat_";
        
        $valuta  = "rsd";
        
        if( $rd[ 'gledanost_komitenta'] != 1 )
        {
            $valuta = "eur";
        }
		
		
		
        
        for( $k = 0; $k < $cg; $k++ )
        {
			$fta = "<tr>";
			
            if( $k == 0 )
            {
                $header .= '<tr style="font-weight:bold"><td align="center">Datum</td>';  
            }
            

            $fta .= '<td align="center">'.$gledanost_data[ $k ][ 'gledanost' ][ 'datum_gledanosti_stampa' ].'</td>';
			
            $tnum = count( $gledanost_data[ $k ][ 'termini' ] );
			
            for( $i = 0; $i < $tnum || $i < $max_termina; $i++ )
            {
                if( $i >= $tnum )
                {
                   
                    if( $k == 0 )
                    {
						$header .= '<td align="center">n/a</td>';
                    }
					
					$fta .= '<td align="center">n/a</td>';
					
                    continue;
                }
                else
                {
                    if( $k == 0 )
                    {
                        $header .= '<td align="center">'. substr( $gledanost_data[ $k ][ 'termini' ][ $i ]['vreme'], 0 , -3  ). '<br />';
						$header .= 	round( $gledanost_data[ $k ][ 'termini' ][ $i ][ $cena_karte_key_name . $valuta ], 2, PHP_ROUND_HALF_UP ) . '</td>';
                    }
                    
					$fta .= '<td align="center">' . $gledanost_data[ $k ][ 'termini' ][ $i ][ 'broj_gledalaca' ] . '</td>';
                }
                
            }
            
            
            if( $k !== 0 )
            {
                $fta .= '<td align="center">' . $gledanost_data[ $k ][ 'gledanost' ][ 'suma_gledanosti' ] . '</td>';
                $fta .= '<td align="center">' . number_format( $gledanost_data[ $k ][ 'gledanost' ][ $suma_key_name . $valuta ], 2, '.', ',' ) . '</td>';
            }
            
            if( $k == 0 )
            {
                $header .= '<td align="center">Gledalaca</td><td align="center">Ukupan Prihod</td></tr>';
                
                $fta .= '<td align="center">' . $gledanost_data[ $k ][ 'gledanost' ][ 'suma_gledanosti' ] . '</td>';
                $fta .= '<td align="center">' . number_format( $gledanost_data[ $k ][ 'gledanost' ][ $suma_key_name . $valuta ], 2, '.', ',' ) . '</td></tr>';		
				
				
				echo $header;
				echo $fta;
				
				$header = NULL;
            }
			else
			{
				echo $fta . "</tr>";
			}    
        }
    
        
    ?>
</table>

</div>

<br />   	
    
<table width="50%" align="right" style="font-weight:bold, font-size:12px !important" cellpadding="2" cellspacing="0" class="contentTable">
    <tr>
        <td>Broj Gledalaca</td>
        <td align="right"><?php echo number_format( $rd[ 'ukupno_gledalaca' ], 2, '.', ',' ); ?></td>
    </tr>
    <tr>
        <td>Bruto Prihod</td>
        <td align="right"><?php echo number_format( $rd[ 'ukupan_prihod_bez_smanjenja_' . $valuta ], 2, '.', ',' ); ?></td>
    </tr>
    <tr>
        <td>Crveni Krst</td>
        <td align="right"><?php echo number_format( $rd[ 'crveni_krst' ], 2, '.', ',' ); ?></td>
    </tr>
    <tr>
        <td >Ostalo</td>
        <td align="right"><?php echo number_format( $rd[ 'ostalo' ], 2, '.', ',' ); ?></td>
    </tr>
   <tr>
        <td >Za Raspodelu</td>
        <td align="right"><?php
if ($rd['tip']=='cg') {
	$prihod = round( $rd[ $za_raspodelu_key_name . $valuta ], 2 );
	$zrsp = $prihod - ($prihod * ( (7*100)/(7+100) /100 ));
	echo round($zrsp,2);
}else{
								echo round( $rd[ $za_raspodelu_key_name . $valuta ], 2 ); 
}							
								?></td>
    </tr>
    <tr>
        <td >Za Distributera <?php if( $rd[ 'tip_raspodele' ] == 3 )  echo round( $rd[ 'raspodela_iznos' ], 0 ) . "%"; ?></td>
        <td align="right"><?php
								if ($rd['tip']=='cg') {
									
									$zdist = $zrsp * ($rd[ 'raspodela_iznos' ] / 100);
									echo round($zdist,2);
								}else {echo round( $rd[ $za_distributera_key_name . $valuta ], 2 ); } ?></td>
    </tr>
    <tr>
        <td>
			<?php
                switch( $rd['primenjen_porez_komitenta'] )
                {
                    case 1:
                        echo "PDV: 0%";
                    break;
                    
                    case 2:
                        echo "PDV: 8%";
                    break;
                    
                    case 3:
                        echo "PDV: 18%";
                    break;
                    
                    case 4:
                        echo "Bez Poreza";
                    break;
                } 
            ?> 
        </td>
        <td align="right"><?php echo round( $rd[ $iznos_pdv . $valuta ], 2, PHP_ROUND_HALF_UP ); ?></td>
        
     </tr>
     
</table>
		   

<table width="100%" cellpadding="5" cellspacing="0" class="contentTable">
        
   <tr>
      <td><b>Slovima:</b></td>
   </tr>
                
   <tr>
       <td width="100%" height="70"></td>
   </tr>
   
</table>

<table width="100%" cellpadding="0" cellspacing="0" class="contentTable">                
  
    <tr>                            
        <td height="100"></td>
        
        <td height="100"></td>
        
        <td height="100"></td>
    </tr>

</table>