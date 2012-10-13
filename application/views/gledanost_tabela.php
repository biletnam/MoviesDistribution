<div style="background-color:#FFFFFF; width:800px">
<?php


$host= "localhost";
$username="root";
$password="test1234";
$db_name="distribucija_filmova";

$conn = @mysql_connect("$host", "$username", "$password");

if( $conn )
{
	@mysql_select_db("$db_name", $conn );
	@mysql_query("SET NAMES utf8", $conn );
	

	if( $_POST["filmovi_filter"] !='' && $_POST["kopije_filter"] == '' && $_POST["komitenti_filter"] == '' && $_POST["bioskopi_filter"]=='' && $_POST["tehnika_kopije_filter"] == '' && $_POST["status_gledanosti_filter"] == '') 
	{
	
		
		$upit ="select sum(suma_zarada_karte_rsd) as bo, sum(suma_zarada_naocare_rsd) as naocare, sum(suma_gledanosti) as gledanost from `gledanost` where rokovnik_id in(select rokovnik_id from `rokovnici` where film_id='".$_POST["filmovi_filter"]."' ) and datum_gledanosti='".$_POST["datum"]."' ";
	    $result = mysql_query($upit);
	    $red = mysql_fetch_array($result);
		//echo $upit;
	    $redbonao = $red[ "bo" ] + $red[ "naocare" ];
	    echo "<b>BO:</b> " . $red[ "bo" ] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>NAOCARE:</b> " . $red[ "naocare" ] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>BO + NAOCARE:</b> ". $redbonao. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>GLEDANOST:</b> ".$red[ "gledanost" ];
	
	}
	
	if( $_POST["filmovi_filter"] == '' && $_POST["kopije_filter"] != '' && $_POST["komitenti_filter"] == '' && $_POST["bioskopi_filter"]=='' and $_POST["tehnika_kopije_filter"] == '' && $_POST["status_gledanosti_filter"] == '') 
	{
		//echo $_POST["kopije_filter"];
		$upit ="select sum(suma_zarada_karte_rsd) as bo, sum(suma_zarada_naocare_rsd) as naocare, sum(suma_gledanosti) as gledanost from `gledanost` where rokovnik_id in(select rokovnik_id from `rokovnici` where kopija_id='".$_POST["kopije_filter"]."' ) and datum_gledanosti='".$_POST["datum"]."' ";
	    $result = mysql_query($upit);
	    $red = mysql_fetch_array($result);
		//echo $upit;
	    $redbonao = $red[ "bo" ] + $red[ "naocare" ];
	    echo "<b>BO:</b> " . number_format( $red[ "bo" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>NAOCARE:</b> " . number_format( $red[ "naocare" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>BO + NAOCARE:</b> ". number_format( $redbonao, 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>GLEDANOST:</b> ".$red[ "gledanost" ];
		
	}
	
	
	if( $_POST["filmovi_filter"] == '' && $_POST["kopije_filter"] == '' && $_POST["komitenti_filter"] != '' && $_POST["bioskopi_filter"] == '' && $_POST["tehnika_kopije_filter"] == '' && $_POST["status_gledanosti_filter"] == '') 
	{
		//echo $_POST["komitenti_filter"];
		$upit ="select sum(suma_zarada_karte_rsd) as bo, sum(suma_zarada_naocare_rsd) as naocare, sum(suma_gledanosti) as gledanost from `gledanost` where rokovnik_id in(select rokovnik_id from `rokovnici` where komitent_id='".$_POST["komitenti_filter"]."' ) and datum_gledanosti='".$_POST["datum"]."' ";
	    $result = mysql_query($upit);
	    $red = mysql_fetch_array($result);
		//echo $upit;
	    $redbonao = $red[ "bo" ] + $red[ "naocare" ];
	    echo "<b>BO:</b> " . number_format( $red[ "bo" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>NAOCARE:</b> " . number_format( $red[ "naocare" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>BO + NAOCARE:</b> ". number_format( $redbonao, 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>GLEDANOST:</b> ". $red[ "gledanost" ];
		
	}
	
	
	if( $_POST["filmovi_filter"] == '' && $_POST["kopije_filter"] == '' && $_POST["komitenti_filter"] == '' && $_POST["bioskopi_filter"]!='' and $_POST["tehnika_kopije_filter"]=='' and $_POST["status_gledanosti_filter"] == '') 
	{
		//echo $_POST["bioskopi_filter"];
		$upit ="select sum(suma_zarada_karte_rsd) as bo, sum(suma_zarada_naocare_rsd) as naocare, sum(suma_gledanosti) as gledanost from `gledanost` where rokovnik_id in(select rokovnik_id from `rokovnici` where bioskop_id='".$_POST["bioskopi_filter"]."' ) and datum_gledanosti='".$_POST["datum"]."' ";
	    $result = mysql_query($upit);
	    $red = mysql_fetch_array($result);
		//echo $upit;
	    $redbonao = $red[ "bo" ] + $red[ "naocare" ];
	    echo "<b>BO:</b> " . number_format( $red[ "bo" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>NAOCARE:</b> " . $red[ "naocare" ] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>BO + NAOCARE:</b> ". number_format( $redbonao, 4 ). "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>GLEDANOST:</b> ".$red[ "gledanost" ];
		
	}
	
	
	if( $_POST["filmovi_filter"] == '' && $_POST["kopije_filter"] == '' && $_POST["komitenti_filter"] == '' && $_POST["bioskopi_filter"]=='' && $_POST["tehnika_kopije_filter"] !='' && $_POST["status_gledanosti_filter"] == '' ) 
	{
		//echo $_POST["tehnika_kopije_filter"];
	   $upit ="select sum(suma_zarada_karte_rsd) as bo, sum(suma_zarada_naocare_rsd) as naocare, sum(suma_gledanosti) as gledanost from `gledanost` where rokovnik_id in(select rokovnik_id from `rokovnici` where kopija_id in (select kopija_id from `kopije_filma` where  tehnika_kopije_filma='".$_POST["tehnika_kopije_filter"]."') ) and datum_gledanosti='".$_POST["datum"]."' ";
	   $result = mysql_query($upit);
	   $red = mysql_fetch_array($result);
		//echo $upit;
	   $redbonao = $red[ "bo" ] + $red[ "naocare" ];
	   echo "<b>BO:</b> " . number_format( $red[ "bo" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>NAOCARE:</b> " . number_format( $red[ "naocare" ], 4 ) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>BO + NAOCARE:</b> ". number_format( $redbonao, 4 ). "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>GLEDANOST:</b> ".$red[ "gledanost" ];
		
	}
}


?>

</div>
<br/>
<div style="background-color:#FFFFFF; width:1600px">

	<form id="<?php echo $form_id;?>" class="gledanost-tabela-form">
	    
		<br />
		<label>
			<?php 
		
				
				$naziv_tehnike = "";
				
				if( $tehnika_kopije_filma == 1 )
				{
					$naziv_tehnike = "35мм";
				}
				else if( $tehnika_kopije_filma == 2 )
				{
					$naziv_tehnike = "3D";
				}
				else if( $tehnika_kopije_filma == 3 )
				{
					$naziv_tehnike = "2D";
				}
				
				
				echo $naziv_komitenta . " - " . 
					 $naziv_bioskopa . " - " . 
					 $naziv_filma . " - " . 
					 $serijski_broj_kopije . " - " . 
					 $naziv_tehnike;
			?>
		
		</label>
		
		<br />
		<br />
		
		<table>
			
			
			<tr id="gledanost-header-row">
				
				<td></td>
				
				<td align="center"><?php echo $lang[ 'vreme' ];?></td>
				<td align="center"><?php echo $lang[ 'cena_rsd' ];?></td>
				<td align="center"><?php echo $lang[ 'cena_eur' ];?></td>
				<td align="center"><?php echo $lang[ 'cena_km' ];?></td>
				<td align="center"><?php echo $lang[ 'gledanost' ];?></td>
				
				<td align="center"><?php echo $lang[ 'zarada_rsd' ];?></td>
				<td align="center"><?php echo $lang[ 'zarada_eur' ];?></td>
				<td align="center"><?php echo $lang[ 'zarada_km' ];?></td>
				
				
			<?php if( $tehnika == 2 ) { ?>
				
				<td align="center"><?php echo $lang[ 'cena_n_rsd' ];?></td>
				<td align="center"><?php echo $lang[ 'cena_n_eur' ];?></td>
				<td align="center"><?php echo $lang[ 'cena_n_km' ];?></td>
				<td align="center"><?php echo $lang[ 'prodato_naocara' ];?></td>
				
				<td align="center"><?php echo $lang[ 'zarada_naocara_rsd' ];?></td>
				<td align="center"><?php echo $lang[ 'zarada_naocara_eur' ];?></td>
				<td align="center"><?php echo $lang[ 'zarada_naocara_km' ];?></td>
				
			<?php } ?>	
				
			</tr>
			
			<?php 
			
			/*
			_CLIENT_key_id'	  => '4c11614f-120e-11e0-bcb5-8a016704da3b',
					'_CLIENT_MODULES_0_A_name' => 'users',
					'_CLIENT_MODULES_0_A_template' => 'default',
					'_CLIENT_MODULES_0_A_active' => true,
					'_CLIENT_MODULES_0_A_action' => 'create',
					
					'_CLIENT_MODULES_1_A_name' => 'privileges',
					'_CLIENT_MODULES_1_A_template' => 'default',
					'_CLIENT_MODULES_1_A_active' => true,
					'_CLIENT_MODULES_1_A_action' => 'create',
					
			*/		
			
			$nova_gledanost = false;
			if( $g_data && count( $g_data ) > 0 &&  @$g_data[ "gledanost_id"] > 0 )
			{
				$nova_gledanost = false;
			}
			else
			{
				$nova_gledanost = true;
			}
			
			(int)$tdi = 0;
			
			$ispravan_termin = false;
			
			(string)$s = "";
			
			(string)$ind = '';
			
			(string)$hid = '';
			
			for( (int)$t = 0; $t < $status_kopije; $t++ )
			{ 
				
				$ind = '';
				$hid = '';
				
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'sat', 4 ) . ' : ';
				$ind .=  		 getGledanostInputElement( $t_data, $tdi, $t,'minut', 4 ) . '</td>';
				
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'cena_karte_rsd' ) . '</td>';
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'cena_karte_eur' ) . '</td>';
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'cena_karte_km' ) . '</td>';
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'broj_gledalaca' ) . '</td>';
				
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'zarada_po_terminu_rsd', 8, true ) . '</td>';
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'zarada_po_terminu_eur', 8, true ) . '</td>';
				$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'zarada_po_terminu_km',  8, true ) . '</td>';
				
				if( $tehnika == 2 )
				{
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'cena_naocara_rsd',  8 ) . '</td>';
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'cena_naocara_eur', 8 ) . '</td>';
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'cena_naocara_km',  8 ) . '</td>';
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'broj_prodatih_naocara', 8 ) . '</td>';
					
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'zarada_naocara_po_terminu_rsd',  8, true ) . '</td>';
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'zarada_naocara_po_terminu_eur',  8, true ) . '</td>';
					$ind .= '<td>' . getGledanostInputElement( $t_data, $tdi, $t, 'zarada_naocara_po_terminu_km',  8, true ) . '</td>';
				}
				 
			
				$hid .= '<input type="hidden" name="'. SAVE_CELL_PREFIX_NAME .'termini_'. $t . POST_ARRAY_DELIMITER .'redni_broj" value="'. ( $t + 1 ) .'" />';
				
				if( (int)@$t_data[ $tdi ][ 'redni_broj_termina' ] === ( $t + 1 ) )
				{
					if( $tehnika_kopije_filma == 1 )
					{
						if( 
						     strlen( @$t_data[ $tdi ][ 'cena_karte_rsd' ] ) < 1 || 
						     strlen( @$t_data[ $tdi ][ 'broj_gledalaca' ] ) < 1 
						  )
						{
							$ispravan_termin = false;
						}
						else
						{
							$ispravan_termin = true;
						}
					}
					else
					{
						if( 
						    strlen( @$t_data[ $tdi ][ 'cena_karte_rsd' ] ) < 1 || 
						    strlen( @$t_data[ $tdi ][ 'broj_gledalaca' ] ) < 1 || 
						    strlen( @$t_data[ $tdi ][ 'cena_naocara_rsd' ] ) < 1 || 
						    strlen( @$t_data[ $tdi ][ 'broj_prodatih_naocara' ] ) < 1 
						  )
						{
							$ispravan_termin = false;
						}
						else
						{
							$ispravan_termin = true;
						}
					}
				
						
					$hid .= '<input type="hidden" name="'. SAVE_CELL_PREFIX_NAME .'termini_'. $t . POST_ARRAY_DELIMITER .'t_opt" value="update" size="12"/>';
					$hid .= '<input type="hidden" name="'. SAVE_CELL_PREFIX_NAME .'termini_'. $t . POST_ARRAY_DELIMITER .'gledanost_termin_id" value="'. @$t_data[ $tdi ][ "gledanost_termin_id" ] .'" />';

					$tdi++;
				}
				else
				{
					$ispravan_termin = true;
					$hid .= '<input type="hidden" name="'. SAVE_CELL_PREFIX_NAME .'termini_'. $t . POST_ARRAY_DELIMITER .'t_opt" value="create" size="12"/>';
				}

				$hid .= '</td>';
				
				if( $ispravan_termin || $nova_gledanost  == true )
				{
					$s .= "<tr><td>";
				}
				else
				{
					$s .= "<tr class='nedovrsen-termin'><td>";
				}
				
				$s .= $hid;
				$s .= $ind;

				$s .= "</tr>";
				
				
			}// END FOR LOOP
			
			
			$s .= '<tr><td></td><td></td><td></td><td></td><td></td>';
			
			$s .= '<td><input type="text" class="suma" name="suma_gledanosti" value="'. @$g_data['suma_gledanosti'] .'" size="8" disabled="disabled"/></td>';
			$s .= '<td><input type="text" class="suma" name="suma_zarada_karte_rsd" value="'. @$g_data['suma_zarada_karte_rsd'] .'" size="8" disabled="disabled"/></td>';
			$s .= '<td><input type="text" class="suma" name="suma_zarada_karte_eur" value="'. @$g_data['suma_zarada_karte_eur'] .'" size="8" disabled="disabled"/></td>';
			$s .= '<td><input type="text" class="suma" name="suma_zarada_karte_km" value="'. @$g_data['suma_zarada_karte_km'] .'" size="8" disabled="disabled"/></td>';
			
			
			
			
			if( $tehnika == 2 )
			{
				$suma_karata_i_naocare=$g_data['suma_zarada_naocare_rsd']+ $g_data['suma_zarada_karte_rsd'];
				$s .= '<td></td><td><input type="text" class="suma" name="suma_zarada_naocare_karte_rsd" value="'. $suma_karata_i_naocare .'" size="8" disabled="disabled"/></td><td></td>';
				
				$s .= '<td><input type="text" class="suma" name="suma_prodatih_naocara" value="'. @$g_data['suma_prodatih_naocara'] .'" size="8" disabled="disabled"/></td>';
				$s .= '<td><input type="text" class="suma" name="suma_zarada_naocare_rsd" value="'. @$g_data['suma_zarada_naocare_rsd'] .'" size="8" disabled="disabled"/></td>';
				$s .= '<td><input type="text" class="suma" name="suma_zarada_naocare_eur" value="'. @$g_data['suma_zarada_naocare_eur'] .'" size="8" disabled="disabled"/></td>';
				$s .= '<td><input type="text" class="suma" name="suma_zarada_naocare_km" value="'. @$g_data['suma_zarada_naocare_km'] .'" size="8" disabled="disabled"/></td>';
			}
			
			$s .= '</tr>';
			
			echo $s;			
			
			?>
			
		</table>
		
		<br />
		<br />
	
	<?php 

	if( $nova_gledanost == false )
	{
		echo '<input type="hidden" name="termini_g_opt" value="update" size="12"/>';
		echo '<input type="hidden" name="gledanost_id" value="'. @$g_data[ "gledanost_id" ] . '" size="12"/>';
	}
	else
	{
		echo '<input type="hidden" name="termini_g_opt" value="create" size="12"/>';
		
		
	}
	
	?>
		
		<label><?php echo $lang[ 'zarada_do_sad' ] . ": " . number_format( $ukupna_zarada[ "suma_zarada_rsd" ], 4 ); ?></label><br /><br />
		
		<input type="hidden" name="rokovnik_id" value="<?php echo $rokovnik_id; ?>" size="12"/> 
		<input type="hidden" name="tehnika_kopije_filma" value="<?php echo $tehnika_kopije_filma; ?>" size="12"/>
		
		
		
		<input type="radio" name="status_gledanosti" value="1" <?php if( @$g_data['status_gledanosti'] == 1 ) echo 'checked="checked"';?>>
		<label><?php echo $lang[ 'radna' ]; ?></label>
		
		
		<input type="radio" name="status_gledanosti" value="2" <?php if( @$g_data['status_gledanosti'] == 2 ) echo 'checked="checked"';?>>
		<label><?php echo $lang[ 'kontrolna' ]; ?></label>
		
		
		<input type="radio" name="status_gledanosti" value="3" <?php if( @$g_data['status_gledanosti'] == 3 ) echo 'checked="checked"';?>>
		<label><?php echo $lang[ 'finalna' ]; ?></label>
			
		<input type="submit" id="sacuvaj-gledanost" class="save-button controll-button" value="<?php echo $lang[ 'sacuvaj' ]; ?>" />
	
		<br />
		<br />
	
	</form>	
	
	
</div>	
<br />
<br />
