<table width="800" align="center">
	<tr>
    	<td align="center"><img src="resources/images/logo.png" width="156" height="100"/></td>
        <td align="center" style="font-weight:bold; font-size:12px">
			<?php
			
			if ($rd[ 'tip' ] == 'cg' ) 
			{
				echo $n[ 'naziv_maticne_firme' ]  . "<br />";
				echo $n[ 'adresa_maticne_firme' ]  . "<br />";
				echo $n[ 'zip_maticne_firme' ] . " "  . $n[ 'mesto_maticne_firme' ]  . "<br />";
				echo "PIB: " . $n[ 'pib_maticne_firme' ]  . "<br />"; 
				echo "Tel: " . $n[ 'tel_maticne_firme' ]  . "<br />";
				echo "Fax: " . $n[ 'fax_maticne_firme' ]  . "<br />";
				
			}else{
				
				echo $m[ 'naziv_maticne_firme' ]  . "<br />";
				echo $m[ 'adresa_maticne_firme' ]  . "<br />";
				echo $m[ 'zip_maticne_firme' ] . " "  . $m[ 'mesto_maticne_firme' ]  . "<br />";
				echo "PIB: " . $m[ 'pib_maticne_firme' ]  . "<br />"; 
				echo "Tel: " . $m[ 'tel_maticne_firme' ]  . "<br />";
				echo "Fax: " . $m[ 'fax_maticne_firme' ]  . "<br />";
			}
			
			?>
        </td>
    </tr>
</table>

<table width="800" cellpadding="0" cellspacing="0"  align="center" style="font-family:Verdana, Geneva, sans-serif">
   
   <tr>
   		<td class="borderedCell" width="400">
	   		<table width="400">
	   			<tr>
	       			<td align="center" style="border-bottom:#000000 1px solid" colspan="2"><b><?php if( $storno ) echo 'STORNO '; ?>RACUN</b></td>	
		        </tr>
		       
		       <tr>
		       		<td style="border-bottom:#000000 1px solid;border-right:#000000 1px solid" align="center">
		        		<font size="5"><?php echo $rd[ 'broj_dokumenta_fakture' ]; if( $storno ) echo 's';?></font>
		        	</td>
		        
		        	<td style="border-bottom:#000000 1px solid" align="center"><b><?php echo $rd['komitent_id'];?></b></td>
		        	
		       </tr>
		       
	       		
	       	   <tr>
	       			 <td style="font-weight:bold; font-size:12px; padding-left:5px" height="100" colspan="2">
	                  <?php if ($rd[ 'tip' ]=='cg') {echo "Podgorica";} else{ echo "Beograd";}?>, <?php echo $rd[ 'datum_unosa_fakture_stampa' ]; ?><br />
	                    Datum prometa dobara: <?php echo $rd[ 'datum_prometa_stampa' ]; ?><br />
						Rok placanja: <?php echo $rd[ 'rok_placanja_stampa' ]; ?><br />
						Poziv na broj: <?php if($rd[ 'tip' ]=='cg'){echo $rd[ 'broj_dokumenta_fakture' ];}else{echo $rd[ 'poziv_na_broj' ];} ?>
       				</td>
	       		</tr>
	       </table>
	    </td>
	   
	    <td class="borderedCell" width="400" align="center" valign="top" style="padding-top:38px">        
     	
     		<table>
                <tr>
                    <td align="center"><?php  echo $rd['naziv_komitenta']?></td>
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
                
      		</table>
    	
	    </td>
   </tr>
            
</table>


<table width="800" align="center"> 
	<tr>
		<td width="800" height="515" valign="top">
			<table width="800" 
			   align="center" 
			   cellpadding="0" 
			   cellspacing="0"
			   style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
		    
			    <tr>
			    <?php if ($rd['a']!='da') { ?>
			    
			        <td align="center" class="artikal_header"><b>R.br.</b></td>
			        <td align="center" class="artikal_header"><b>Opis</b></td>
			        <td align="center" class="artikal_header"><b>Gled.</b></td>
			        <td align="center" class="artikal_header"><b>Prihod</b></td>
			        <td align="center" class="artikal_header"><b>Cr.krst</b></td>
			        <td align="center" class="artikal_header"><b>%</b></td>
			        <td align="center" class="artikal_header"><b>Za raspodelu</b></td>
			        <td align="center" class="artikal_header_last"><b>Za distributera</b></td>
			    
			    <?php }else{ ?>
			    
			    	<td align="center" class="artikal_header"><b>R.br.</b></td>
			        <td align="center" class="artikal_header"><b>Opis</b></td>
			        <td align="center" class="artikal_header"><b>j.m.</b></td>
			        <td align="center" class="artikal_header"><b>Kolicina</b></td>
			        <td align="center" class="artikal_header"><b>Cena</b></td>
			        <td align="center" class="artikal_header"><b>Rabat%</b></td>
			        <td align="center" class="artikal_header"><b>Fakt. cena</b></td>
			        <td align="center" class="artikal_header_last"><b>PDV</b></td>
			        <td align="center" class="artikal_header_last"><b>Cena(PDV)</b></td>
			        <td align="center" class="artikal_header_last"><b>Vrednost</b></td>
			    
			    <?php }?>
			    </tr>
		    
			    <?php
			        
			        $iznos_pdv = 0;
			        $porez = 0;
			        $porez_name = "";
			        $porez_postoji = false;
			        if ($rd['a']=='da') {
			        $porez_str = "<tr class='porez'><td></td><td><b>TARIFA PDV</b></td><td></td><td></td><td></td><td></td><td><b>OSNOVICA</b></td><td></td><td><b>STOPA</b></td><td><b>PDV</b></td></tr>";
			        }else{
			        	$porez_str = "<tr class='porez'><td></td><td><b>TARIFA PDV</b></td><td></td><td></td><td><b>OSNOVICA</b></td><td></td><td><b>STOPA</b></td><td><b>PDV</b></td></tr>";
			        	
			        }
			        $stopa_naziv = "";
			        
			        $tip_raspodele = 0;
			         
			        $class = 'inCellBorderRows';
			        
			        $len = count( $fakture_stavke );
			        $c = 1;
			        
			        foreach( $fakture_stavke as $v )
			        {
			            $tip_raspodele = $v['tip_raspodele'];
			            
			            if( $c == $len )
			                $class = 'inCellBorderRowsLast';
			            
			            echo '<tr>';
			            
			            if ($rd['a']=='da') {
			            	
			            	$cena1 = $v['za_distributera'] +($v['za_distributera'] * 0.18);
			            	$pdv1 = $v['za_distributera'] * 0.18;
			            	if( $rd["valuta_fakture"] == 2 ){
			            		$cena1 = $v['za_distributera'];
			            		$pdv1 = 0;
			            	}
			            	echo '<tr>';
			            	echo '<td class="'.$class.'"><p class="cellP">'.$c.'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. $v['naziv_artikla']. ': ' . $rd['naziv_filma'] . ' ' . $rd['datum_z_gledanost_od'] . ' - ' . $rd['datum_z_gledanost_do'] . '(' . $rd['naziv_bioskopa'] . ')' . '</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">-</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">1.00</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $v['za_distributera'], 2, ',', '.' ) .'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">0.00</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $v['za_distributera'], 2, ',', '.' ) .'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $pdv1, 2, ',', '.' ).'</p></td>';
	                        echo '<td class="'.$class.'"><p class="cellP">'. number_format( $cena1, 2, ',', '.' ) .'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $cena1, 2, ',', '.' ) .'</p></td>';
			            	echo '</tr>';
			            
			            }else{
			            
			            
			            echo '<td class="'.$class.'"><p class="cellP">'.$c.'</p></td>';
			            
			            
			            if( $v['artikal_id'] == 1 )
			            {
			                echo '<td class="'.$class.'"><p class="cellP">'. $v['naziv_artikla']. ': ' . $rd['naziv_filma'] . ' ' . $rd['datum_z_gledanost_od'] . ' - ' . $rd['datum_z_gledanost_do'] . '(' . $rd['naziv_bioskopa'] . ')' . '</p></td>';
			                echo '<td class="'.$class.'"><p class="cellP">' . $v['broj_gledalaca'].'</p></td>';
			            }
			            else
			            {
			                echo '<td class="'.$class.'"><p class="cellP">'. $v['naziv_artikla']. '</p></td>'; 	
			                echo '<td class="'.$class.'"><p class="cellP">' . $v['broj_prodatih_naocara'].'</p></td>';
			            }
			            
			            echo '<td class="'.$class.'"><p class="cellP">'. number_format( $v['prihod'], 2, ',', '.' ) .'</p></td>';
			            echo '<td class="'.$class.'"><p class="cellP">'. $v['ckr'] .'</p></td>';
			            echo '<td class="'.$class.'"><p class="cellP">'.$v['procenat'].'</p></td>';
			            echo '<td class="'.$class.'"><p class="cellP">'. number_format( $v['za_raspodelu'], 2, ',', '.' ).'</p></td>';
			            echo '<td class="'.$class.'"><p class="cellP">'. number_format( $v['za_distributera'], 2, ',', '.' ) .'</p></td>';
			            
			            
			            }
			            echo '</tr>';
			            
			            
			            
			            $porez = $v[ 'primenjen_porez_komitenta' ];
			            
			            if( $porez > 0 )
			            {
			                ( $porez > 8 ) ? $porez_name = 'PDV visa stopa r. br' . $c  : $porez_name = 'PDV niza stopa r. br' . $c;
			                $porez_postoji = true;
			                
			                $iznos_pdv += $v[ 'iznos_pdv' ];
			                
			                if ($rd['a']=='da') {
			                	
			                	$porez_str .= "<tr class='porez'><td></td><td>" .$porez_name . "</td><td></td><td></td><td></td><td></td><td>". number_format( $v[ 'za_distributera' ], 2, ',', '.' )."</td><td></td><td>". $porez ."%</td><td>". number_format( $v[ 'iznos_pdv' ], 2, ',', '.' )."</td></tr>";
			                	
			                }else{
			                	
			                	$porez_str .= "<tr class='porez'><td></td><td>" .$porez_name . "</td><td></td><td></td><td>". number_format( $v[ 'za_distributera' ], 2, ',', '.' )."</td><td></td><td>". $porez ."%</td><td>". number_format( $v[ 'iznos_pdv' ], 2, ',', '.' )."</td></tr>";
			                }
			                
			            }
	
			            $c++;
			            
			            
			            
			        }
			         if ($rd['a']=='da') {
			         	
	
			            	$bc = $c +1;
			            	$cena = $rd['avans'] -($rd['avans'] * (($rd['prim_porez'] *100)/($rd['prim_porez']+100)/100));
			            	
			            	$pdv =$rd['avans'] - $cena;
			            	if( $rd["valuta_fakture"] == 2 ){
			            		$pdv=0;
			            		$cena = $rd['avans'];
			            	}
			            	echo '<tr>';
			            	echo '<td class="'.$class.'"><p class="cellP">'.$c.'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">Avansna uplata</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">-</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">-1.00</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $cena, 2, ',', '.' ).'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">0.00</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $cena, 2, ',', '.' ).'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $pdv, 2, ',', '.' ).'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">'. number_format( $rd['avans'], 2, ',', '.' ) .'</p></td>';
			            	echo '<td class="'.$class.'"><p class="cellP">-'. number_format( $rd['avans'], 2, ',', '.' ) .'</p></td>';
			            	echo '</tr>';
			            
			            }
		
			            if ($rd['a']=='da') {
			            	( $porez > 8 ) ? $porez_name = 'PDV visa stopa r. br' . $c  : $porez_name = 'PDV niza stopa r. br' . $c;
			            	$porez_str .= "<tr class='porez'><td></td><td>" .$porez_name . " </td><td></td><td></td><td></td><td></td><td>". number_format($cena, 2, ',', '.' )."</td><td></td><td>".$rd['prim_porez']."%</td><td>". number_format( $pdv, 2, ',', '.' )."</td></tr>";
			            
			            }
			            $razlika = $v['za_distributera'] - $cena;
			            if ($rd['a']=='da') {
			            $pdvrazlika = $iznos_pdv - $pdv;	
			            $porez_str .= "<tr class='porez'><td></td><td><b>Ukupan porez:</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>". number_format( $pdvrazlika, 2, ',', '.' )."</td></tr>";
			            }else{
			            $porez_str .= "<tr class='porez'><td></td><td><b>Ukupan porez:</b></td><td></td><td></td><td></td><td></td><td></td><td>". number_format( $iznos_pdv, 2, ',', '.' )."</td></tr>";
			            	
			            }
			            if( $porez )
			                echo $porez_str;
			    ?>
			    

		    
			</table>
		
		</td>
	</tr>
	<?php  if ($rd['a']=='da') {?>
<br/><br/>

			    <table align='center' cellpadding="0" cellspacing="0" width="400">
			     <tr>
                    <td class="fakturaCellDetaljiLeft"></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b>PO AVANSNOM RACUNU</b></p></td><td class="fakturaCellDetaljiRight"><p class="cellPInner"><b>PO KONACNOM RACUNU</b></p></td><td class="fakturaCellDetaljiRight"><p class="cellPInner"><b>RAZLIKA</b></p></td>
                </tr>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Osnovica</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $cena, 2, ',', '.' );?></b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['osnovica'], 2, ',', '.' );?></b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['osnovica']-$cena, 2, ',', '.' );?></b></p></td>
                </tr>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>PDV</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ){$pdv=0; echo "EUR "; } echo number_format( $pdv, 2, ',', '.' );?></b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['ukupan_pdv'], 2, ',', '.' );?></b></p></td>
                     <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['ukupan_pdv'] - $pdv, 2, ',', '.' );?></b></p></td>
                </tr>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Ukupno</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['avans'] , 2, ',', '.' );?></b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['ukupan_prihod'] , 2, ',', '.' );?></b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['ukupan_prihod']- $rd['avans'], 2, ',', '.' );?></b></p></td>
                </tr>
                <tr>

                </tr>
            </table>
  <?php }?>       
</table>

           
<table cellpadding="0" cellspacing="0" width="800" style="font-size:12px" align="center">
            	
    <tr>
        <td align="left" style="border-top:1px solid #000000;border-left:1px solid #000000">
        
        <p style="padding-left:10px">
        Placanje: <?php echo $rd[ 'nacin_placanja' ];?> <br />
        Po dokumentu: Gledanost <?php echo $rd[ 'broj_dokumenta_z_gledanosti' ]. ' od ' . $rd[ 'datum_z_gledanost_do' ];?> <br />
        Ugovoreno: 
             <?php
            
                switch( $tip_raspodele )
                {
                    case 1:
                        echo 'Minimalna garancija'; 
                    break;
                    
                    case 2:
                        echo 'Ugovoreni iznos';
                    break;
                    
                    case 3:
                        echo 'Procenat';
                    break;
                }
    
            ?><br />
        Producent: <?php echo $rd['producent_filma']; ?>	
        </p>
            
        </td>
        
        <td align="left" style="border-top:1px solid #000000;border-right:1px solid #000000" width="400">
            <table cellpadding="0" cellspacing="0" width="400">
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Osnovica</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['osnovica'], 2, ',', '.' );?></b></p></td>
                </tr>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>PDV</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['ukupan_pdv'], 2, ',', '.' );?></b></p></td>
                </tr>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Ukupno</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['ukupan_prihod'] , 2, ',', '.' );?></b></p></td>
                </tr>
                  <?php if( $rd[ 'a' ] == 'da' ) {?>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Avans</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format(  $rd['avans'], 2, ',', '.' );?></b></p></td>
                </tr>
              
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Za placanje</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format(  $rd['ukupan_prihod']- $rd['avans'], 2, ',', '.' );?></b></p></td>
                </tr>
                <?php }else{ ?>
                <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Uplaceno</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR ";?> 0.00</b></p></td>
                </tr>
                   <tr>
                    <td class="fakturaCellDetaljiLeft"><p class="cellPInner"><b>Za placanje</b></p></td>
                    <td class="fakturaCellDetaljiRight"><p class="cellPInner"><b><?php if( $rd["valuta_fakture"] == 2 ) echo "EUR "; echo number_format( $rd['za_placanje'], 2, ',', '.' );?></b></p></td>
                </tr>
                <?php }?>
            </table>
        </td>
    </tr>
    
</table>
            
<table width="800" align="center"> 
    
    <tr>
    	<td class="borderedCell" height="80" colspan="2" align="center"> 
    	
    	<?php
    	
    		echo $rd[ "napomena" ] . $rd[ "naziv_grada" ]; 
    		
    	?>
    	</td>
    </tr>
</table>

<table align="center" width="800">    

    <?php  if( $rd[ 'tip' ] == 'cg'){ ?>
    
     <tr>
        <td class="borderedCell" align="center"><br />ZA PRIKAZIVACA <br /><br /><br /> ________________ <br /><br /></td>
        <td class="borderedCell" align="center" width="45%">Tekuci racun: 	530-20070-40 <br/>NLB Montenegrobanka<br/> MB: 02861119, ŠD: 5913 <br />RACUN JE URAÐEN NA RACUNARU I VAŽI BEZ PECATA I POTPISA</td>
        <td class="borderedCell" align="center"><br />ZA DISTRIBUTERA <br /><br /><br /> ________________ <br /><br /></td>
    </tr>
    
    <?php }else{?>
    
    <tr>
        <td class="borderedCell" align="center"><br />ZA PRIKAZIVACA <br /><br /><br /> ________________ <br /><br /></td>
        <td class="borderedCell" align="center" width="45%">Tekuci racun: 165-1086-85 Hypo-Alpe-Adria Bank MB: 17544900, ŠD: 5913 <br />RACUN JE URAÐEN NA RACUNARU I VAŽI BEZ PECATA I POTPISA</td>
        <td class="borderedCell" align="center"><br />ZA DISTRIBUTERA <br /><br /><br /> ________________ <br /><br /></td>
    </tr>
    
    <?php }?>
    
</table>

<table align="center" width="800" cellpadding="0" cellspacing="0">
    
    <tr>
    	<td align="center" colspan="3">
    		<?php echo "Ref.:" . $rd[ "referent" ]; ?>
    	</td>
    </tr>
        
</table>


