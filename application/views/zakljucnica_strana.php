<table width="800" align="center" cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" style='width:50%;'><img width='156' height="110" src='resources/images/logo.png' /></td>
        <td  align='center' style="width:50%; font-size:12px">
        	<?php
			echo 			
             $m[ 'naziv_maticne_firme' ] . 
             "<br />" . 
             $m[ 'adresa_maticne_firme' ] . 
             "<br />" . 
             $m[ 'zip_maticne_firme' ] . 
             "<br />" . 
             $m[ 'mesto_maticne_firme' ] . 
             "<br />" . 
             "PIB:"  . 
             $m[ 'pib_maticne_firme' ] . 
             "<br />" . 
             "Tel:"  . 
             $m[ 'tel_maticne_firme' ] . 
            "<br />" . 
             "Fax:"  . 
             $m[ 'fax_maticne_firme' ] . 
             "<br />"; 
			 
			?> 
		</td>
        
    </tr>
    
</table>

	<table width="800" align="center" cellpadding="0" cellspacing="0" class="contentTable">
	
		<tr>
	    	<td class='borderedCell' width='50%' align="center">
	    	
	        	<table width='100%' class="innerContentTable">
	            	<tr>
	                	<td align='center' style='border-bottom:#000000 1px solid'><b>ZAKLJUCNICA</b></td>
	                </tr>
	                <tr>
				
	                    <td style='border-bottom:#000000 1px solid' align='center'><b>br. <?php echo $rd[ 'broj_dokumenta_zakljucnice' ]; ?> </b> </td>
	 				</tr>
	                <tr>
	                	<td style='font-weight:bold' height='100'> Beograd, <?php echo $rd[ 'datum_zakljucnice_stampa' ]; ?> <br /> </td>
	                </tr>
	            </table>
	            
	        </td>
	        <td class='borderedCell' width='50%' align='center' valign='top' style='padding-top:38px'>         
	            <table class="innerContentTable">
	                <tr>
                    <td align="center"><?php echo $rd['komitent_id'] . "<br />" . $rd['naziv_komitenta']?></td>
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
	        
<div style="height:530px;" align="center">   
     
	<table width="800" align="center" cellpadding="10" cellspacing="0" class="contentTable">				
		
	    <tr>
	        <td align='center'><b>R.br.</b></td>
	        <td align='center'><b>Naziv filma</b></td>
	        <td align='center'><b>Termin Prikazivanja</b></td>
	        <td align='center'><b>Prihod od ulaznica( sa PDV )</b></td>
	    </tr>        
	    
	<?php
	
	$class = '';
	
	$len = count( $kopije_zak );
	$c = 1;
	$html = "";
	
	foreach( $kopije_zak as $v )
	{
		if( $c == $len )
			$class = '';
		
		$html .= '<tr>';
		$html .=  '<td class="'.$class.'"><p class="cellP">'.$c.'</p></td>';
		$html .=  '<td style="width:360px;" class="'.$class.'"><p class="cellP">'.$v['naziv_filma']. '<br />Bioskop: ' . $v['naziv_bioskopa']. '<br />Start: ' . $v['start_filma_stampa'] . '</p></td>';
		$html .=  '<td class="'.$class.'"><p class="cellP">' . $v['datum_kopije_od_stampa'] . ' - '. $v['datum_kopije_do_stampa']. '</p></td>';
		$html .=  '<td class="'.$class.'"><p class="cellP">';
		 
		switch( $v['tip_raspodele'] )
		{
			case 1:
				$html .= 'Minimalna garancija: ' . $v['raspodela_iznos']; 
			break;
			
			case 2:
				$html .= 'Ugovoreni iznos: ' . $v['raspodela_iznos'];
			break;
			
			case 3:
				$html .= 'Procenat: ' . $v['raspodela_iznos'];
			break;
		}
		
		$html .= '</p></td>';
		$html .= '</tr>';
		
		$c++;
	}
	
	echo $html;
	?>
	            	
	</table>
	
</div>

<br />

<table width="800" align="center" cellpadding="0" cellspacing="0" class="contentTable">    
    <tr>
        <td height="100" colspan="2"></td>
    </tr>
</table>

<br />
    
<table width="800" align="center" cellpadding="0" cellspacing="0" class="contentTable">
    <tr>        	
        <td class="borderedCell" align="center"><br />ZA PRIKAZIVACA <br /><br /><br /> ________________ <br /><br /></td>
        <td class="borderedCell" align="center" >Poslovni racun: 165 - 1086 - 85 Hypo Alpe Adria Bank <br />MB: 17544900, PIB 103339938, SD: 92120</td>
        <td class="borderedCell" align="center"><br />ZA DISTRIBUTERA <br /><br /><br /> ________________ <br /><br /></td>        
    </tr>    
</table>

<table align="center" width="800" cellpadding="0" cellspacing="0">
    
    <tr>
    	<td align="center" colspan="3">
    		<?php echo "Ref.:" . $rd[ "referent" ]; ?>
    	</td>
    </tr>
        
</table>