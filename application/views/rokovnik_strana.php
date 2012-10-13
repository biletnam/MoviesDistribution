<table width="800" align="center">
	<tr>
    	<td style='width:40%;' align="center"><img width="156" height="110" src="resources/images/logo.png" /></td>
        <td style='width:60%' align="center" style="font-weight:bold; font-size:12px">
			<?php
				echo $m[ 'naziv_maticne_firme' ]  . "<br />";
				echo $m[ 'adresa_maticne_firme' ]  . "<br />";
				echo $m[ 'zip_maticne_firme' ] . " "  . $m[ 'mesto_maticne_firme' ]  . "<br />";
				echo "PIB: " . $m[ 'pib_maticne_firme' ]  . "<br />"; 
				echo "Tel: " . $m[ 'tel_maticne_firme' ]  . "<br />";
				echo "Fax: " . $m[ 'fax_maticne_firme' ]  . "<br />";
			?>
        </td>
    </tr>
</table>

<pre><?php //print_r($rd); ?></pre>


<table width="800" align="center"  cellpadding="0" cellspacing="0">

	<tr>
    	<td class="borderedCell" width="40%">
        	<table align="center">
            	<tr>
                	<td align="center" style="border-bottom:#000000 2px solid"><b>ROKOVNIK - PROFAKTURA</b></td>
                </tr>
                <tr>
                	<td style="border-bottom:#000000 2px solid" align="center"><b>br. <?php echo $rd[ 'broj_dokumenta_rokovnika' ];?></b></td>
                </tr>
                <tr>
                	<td style="font-weight:bold" height="100">
                    	Beograd, <?php echo $rd[ 'datum_unosa_stampa' ]; ?><br />
                    </td>
                </tr>
            </table>
        </td>
        <td class="borderedCell" width="60%" align="center" style="padding-top:38p">        
            <table>
                 <tr>
                    <td align="center"><?php echo $rd['komitent_id'] . "<br />" . $rd['naziv_komitenta']; ?></td>
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
    
<br />
    
<table cellpadding="10" cellspacing="0" 
		style="font-size:12px" width="800" 
		align="center" id="rokovnik-view-content-cnt">
    
    <tr>
        <td colspan="2"><p> NA TEMELJU UGOVORA SMO VAM IZNAJMILI FILM: </p></td>
    </tr> 	
    
    <tr>
        <td width="40%">
            <p >Naziv filma / Osnovni podaci Kopija / Ser. broj / Start / Producent</p>
        </td>
        <td width="60%">
            <p>
            <?php 
                echo $rd['naziv_filma'] . '<br />';
                echo $rd['oznaka_kopije_filma'] . '/' . $rd['serijski_broj_kopije'] . '/' . $rd['start_filma'] . '/' . $rd['producent_filma'] . '<br />';
                echo 'Zanr-' . $rd['naziv_zanra'] . '<br />';
                echo 'trajanje-' .$rd['trajanje_filma'] . '<br />';
                echo 'broj cinova-' .$rd['broj_cinova_filma'] . '<br />';
                echo 'tehnika-' .$rd['tehnika_filma'] . '<br />';                    	 
            ?>
            </p>
        </td>
    </tr>
    <tr>
        <td><p>Dani prikazivanja</p></td>
        <td><p><?php echo "Od: " . $rd[ 'datum_kopije_od_stampa' ] . " Do: " . $rd[ 'datum_kopije_do_stampa' ]?></p></td>
    </tr>
    
     <tr>
        <td>
            <p>Film cete primiti od prikazivaca <br />nacin prijema: <?php if($rd['nacin_prijema_kopije']=='1'){echo "Autobusom";} if($rd['nacin_prijema_kopije']=='2'){echo "Lično";} if($rd['nacin_prijema_kopije']=='3'){echo "Špediterom";}?> <br />dana: <?php echo $rd[ 'datum_prijema_kopije_stampa' ];?></p>
        </td>
        <td>
            <p>
            
            <?php 
                
                if( $rd[ 'primiti_kopiju_od' ] == 1 )
                {
                    echo 'Skladiste -TARAMOUNT FILM <br />Beograd-Gavrila Principa 11 <br />11000 Beograd';
                }
                else
                {
                    echo 'Tranzit<br />' . $rd[ 'grad_prijema' ];
                } 
            ?>
            
            
            </p>
        </td>
    </tr>
    
    <tr>
        <td>
            <p>Film i dodatak otpremite prikazivacu <br />nacin otpreme: <?php if($rd['nacin_otpreme_kopije']=='1'){echo "Autobusom";} if($rd['nacin_otpreme_kopije']=='2'){echo "Lično";} if($rd['nacin_otpreme_kopije']=='3'){echo "Špediterom";}  ?> <br />dana: <?php echo $rd['datum_otpreme_kopije']; ?></p>
        </td>
        <td>
            <p>
            
            <?php 
                
                if( $rd[ 'otpremiti_kopiju_od' ] == 1 )
                {
                    echo 'Skladiste -TARAMOUNT FILM <br />Beograd-Gavrila Principa 11 <br />11000 Beograd';
                }
                else
                {
                    echo 'Tranzit<br />' . $rd[ 'grad_otpreme' ];
                } 
            ?>
            
            </p>
        </td>
    </tr>
            
    <tr>
        <td>
            <p>Prihod od prodaje ulaznica (sa PDV)</p>
        </td>
        <td>
            <p><?php if($rd['tip_raspodele']=='1'){echo 'Min. Granacija';}  if($rd['tip_raspodele']=='2'){echo 'Ugovoreni iznos';}  if($rd['tip_raspodele']=='3'){echo 'Procenat';} ?></p>
            <p><?php echo round($rd['raspodela_iznos'],2); if($rd['tip_raspodele']=='3'){echo '%';}  ?></p>
        </td>
    </tr>     
                  
    <tr>
        <td>
            <p>Vazna napomena</p>
        </td>
        <td>
            <p>Primljenu kopiju duzni ste odmah po primitku tacno pregledati i uporediti sa stanjem u tehnickom kartonu.</p>
        </td>
    </tr>
    
    <tr>
        <td colspan="2">
            <p>Na temelju ovoga rokovnika izvolite izvrsiti uplatu najamnine odmah nakon zavrsenog prikazivanja na nas tekuci racun. U protivnom zaracunavacemo kamatu po zakonskim propisima. <br /><br />PRILIKOM UPLATE OBAVEZNO NAZNACITI BROJ ROKOVNIKA
            </p>
        </td>
    </tr>
    
    <tr>
        <td colspan="2">
            <p> Slovima: nula i 0/100 </p>
        </td>
    </tr>
       
</table>
    
<br />
    
<table width="800" align="center" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="borderedCell" height="70" colspan="2"></td>
    </tr>
</table>
    
<br />
    
<table width="800" align="center" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="borderedCell" align="center"><br />ZA PRIKAZIVACA <br /><br /> ________________ <br /><br /></td>
        <td class="borderedCell" align="center" >Poslovni racun: 165 - 1086 - 85 Hypo Alpe Adria Bank <br />MB: 17544900, PIB 103339938, SD: 92120</td>
        <td class="borderedCell" align="center"><br />ZA DISTRIBUTERA <br /><br /> ________________ <br /><br /></td>        
    </tr>    
</table>
 
<table align="center" width="800" cellpadding="0" cellspacing="0">
    
    <tr>
    	<td align="center" colspan="3">
    		<?php echo "Ref.:" . $rd[ "referent" ]; ?>
    	</td>
    </tr>
        
</table>	   
