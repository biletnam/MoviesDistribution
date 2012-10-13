<table width="800">
	<tr>
    	<td><img src="<?php echo BASE_URI_RESOURCE; ?>images/logo.png" /></td>
        <td align="center" style="font-weight:bold">
			<?php
				echo $m[ 'naziv' ]  . "<br />";
				echo $m[ 'adresa' ]  . "<br />";
				echo $m[ 'zip' ] . " "  . $m[ 'mesto' ]  . "<br />";
				echo "PIB: " . $m[ 'pbroj' ]  . "<br />"; 
				echo "Tel: " . $m[ 'tel' ]  . "<br />";
				echo "Fax: " . $m[ 'fax' ]  . "<br />";
			?>
        </td>
    </tr>
</table>
<table width="800" style="font-family:Verdana, Geneva, sans-serif">

	<tr>
    	<td class="borderedCell" width="50%">
        	<table width="100%">
            	<tr>
                	<td align="center" style="border-bottom:#000000 2px solid"><b>ZAKLJUCNICA</b></td>
                </tr>
                <tr>
                	<td style="border-bottom:#000000 2px solid" align="center"><b>br. <?php echo $rd[ 'broj_dokumenta_zakljucnice' ];?></b></td>
                </tr>
                <tr>
                	<td style="font-weight:bold" height="100">
                    	Beograd, <?php echo $rd[ 'datum_zakljucnice' ]; ?><br />
                    </td>
                </tr>
            </table>
        </td>
        <td class="borderedCell" width="50%" align="center">        
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
                
            </table>
        </td>
    </tr>
    
    <tr>
    	<td  class="borderedCell" colspan="2" valign="top" height="450">
			<table width="100%" cellpadding="0" cellspacing="0" >
            	
                <tr>
                	<td align="center"><b>R.br.</b></td>
                    <td align="center"><b>Naziv filma</b></td>
                    <td align="center"><b>Termin Prikazivanja</b></td>
                    <td align="center"><b>Prihod od ulaznica( sa PDV )</b></td>
                </tr>
            	
            	<?php
				
            		$class = 'inCellBorderRows';
            		
            		$len = count( $kopije_zak );
            		$c = 1;
            		foreach( $kopije_zak as $v )
            		{
            			if( $c == $len )
            				$class = 'inCellBorderRowsLast';
            			
            			echo '<tr>';
            			echo '<td class="'.$class.'"><p class="cellP">'.$c.'</p></td>';
            			echo '<td class="'.$class.'"><p class="cellP">'.$v['naziv_filma']. '<br />Bioskop: ' . $v['naziv_bioskopa']. '<br />Start: ' . $v['start_filma'] . '</p></td>';
            			echo '<td class="'.$class.'"><p class="cellP">' . $v['datum_kopije_od'] . ' - '. $v['datum_kopije_do']. '</p></td>';
            			echo '<td class="'.$class.'"><p class="cellP">';
            			 
            			switch( $v['tip_raspodele'] )
            			{
            				case 1:
            					echo 'Minimalna garancija: ' . $v['raspodela_iznos']; 
            				break;
            				
            				case 2:
            					echo 'Ugovoreni iznos: ' . $v['raspodela_iznos'];
            				break;
            				
            				case 3:
            					echo 'Procenat: ' . $v['raspodela_iznos'];
            				break;
            			}
            			
            			echo '</p></td>';
            			echo '<tr>';
            			
            			$c++;
            		}
            	
            	?>
            	
            </table>
        </td>
        
    </tr>
    
    <tr>
    	<td class="borderedCell" height="100" colspan="2"></td>
    </tr>
    
    <tr>
    	<td width="100%" colspan="2">
        	<table width="100%">
        		<tr>
                    <td class="borderedCell" align="center"><br />ZA PRIKAZIVACA <br /><br /><br /> ________________ <br /><br /></td>
                    <td class="borderedCell" align="center" width="45%">Poslovni racun: 165 - 1086 - 85 Hypo Alpe Adria Bank MB: 17544900, PIB 103339938, SD: 92120</td>
                    <td class="borderedCell" align="center"><br />ZA DISTRIBUTERA <br /><br /><br /> ________________ <br /><br /></td>
                </tr>
            </table>
        </td>
    </tr>    
</table>