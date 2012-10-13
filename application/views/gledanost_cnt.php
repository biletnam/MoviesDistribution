<?php

$filmovi_filter_data = @$filter_data[ 'filmovi' ];
$kopije_filter_data = @$filter_data[ 'kopije' ];
$komitenti_filter_data = @$filter_data[ 'komitenti' ];
$bioskopi_filter_data = @$filter_data[ 'bioskopi' ];

?>

<div>

	<div>
		<form id="gledanost-kurs-data-form">
			<input type="hidden" value="<?php echo $kurs_data["rsd"];?>" 			id="kurs_data_rsd"/>
			<input type="hidden" value="<?php echo $kurs_data["eur"];?>" 			id="kurs_data_eur"/>
			<input type="hidden" value="<?php echo $kurs_data["km"];?>" 			id="kurs_data_km"/>
			<input type="hidden" value="<?php echo $kurs_data["km_eur"];?>" 		id="kurs_data_km_eur"/>
			<input type="hidden" value="<?php echo $kurs_data["faktor_rsd"];?>" 	id="kurs_data_faktor_rsd"/>
			<input type="hidden" value="<?php echo $kurs_data["faktor_eur"];?>" 	id="kurs_data_faktor_eur"/>
			<input type="hidden" value="<?php echo $kurs_data["faktor_km"];?>" 		id="kurs_data_faktor_km"/>
		</form>
	</div>
	
	<div class="module-menu-cnt">

		<div style="background-color:#FFFFFF; width:800px; padding:5px">
		
			<table align="center" border="1" cellpadding="5" cellspacing="0">
				<tr>
					<td><b><?php echo $lang['gledanost'];?></b></td>
					<td><b><?php echo $lang['naocare'];?></b></td>
					<td><b>SUM 35mm</b></td>
					<td><b>SUM 3D</b></td>
					<td><b>SUM 2D</b></td>
				</tr>
				
				<tr>
					<td><?php echo $s_data[ "suma_gledanosti" ];?></td>
					<td><?php echo $s_data[ "suma_naocara" ];?></td>
					<td><?php echo number_format( $s_data[ "sum_35mm" ], 4 );?></td>
					<td><?php echo number_format( $s_data[ "sum_3d" ], 4 );?></td>
					<td><?php echo number_format( $s_data[ "sum_2d" ], 4 );?></td>
				</tr>
				 
			</table>
			
			
			
		</div>
		
		<form action="#" id="dnevna-gledanost-pretraga-form">
		
			 <table>
		    	<tr>
		        	<td><label><?php echo $lang['filmovi'];?>: </label></td>
		            <td><label><?php echo $lang['kopije'];?>: </label></td>
		            <td><label><?php echo $lang['komitenti'];?>: </label></td>
		            <td><label><?php echo $lang['bioskopi'];?>: </label></td>
		            <td><label><?php echo $lang['tehnika'];?>: </label></td>
		            <td><label><?php echo $lang['status'];?></label></td>
		            <td></td>
		        </tr>
		        <tr>
		     		<td>
					    <select name="filmovi_filter" id="gledanost-filmovi-filter-select">
					    <option value="">--</option>
							<?php
					              
							  foreach( @$filmovi_filter_data as $ffd )
							  {
								  echo '<option value="' . $ffd[ 'film_id' ].  '">' . $ffd[ 'naziv_filma' ] . '</option>'; 
							  }
								  
					         ?>
					    </select>
					 </td>
		    		<td>
		    			<select name="kopije_filter" id="gledanost-kopije_filter_select">
		    			<option value="">--</option>
					    	<?php
					              
							  foreach( @$kopije_filter_data as $kfd )
							  {
								  echo '<option value="' . $kfd[ 'kopija_id' ].  '">' . $kfd[ 'serijski_broj_kopije' ] . '</option>'; 
							  }
								  
					         ?>
		    			</select>
		     		</td>
		    		<td>
		    			<select name="komitenti_filter" id="gledanost-komitenti_filter_select">
					    <option value="">--</option>
					    	<?php
					              
							  foreach( @$komitenti_filter_data as $komfd )
							  {
								  echo '<option value="' . $komfd[ 'komitent_id' ].  '">' . $komfd[ 'naziv_komitenta' ] . '</option>'; 
							  }
								  
					         ?>
					    </select>
					</td>
					<td>
					    <select name="bioskopi_filter" id="gledanost-bioskopi_filter_select">
					    	<option value="">--</option>
					    	<?php
					              
							  foreach( @$bioskopi_filter_data as $bioskopfd )
							  {
								  echo '<option value="' . $bioskopfd[ 'bioskop_id' ].  '">' . $bioskopfd[ 'naziv_bioskopa' ] . '</option>'; 
							  }
								  
					         ?>
					    </select>
					</td>
			    	<td>
					    <select name="tehnika_kopije_filter" id="gledanost-tehnika_kopije_filter_select">
					    	<option value="">--</option>
					    	<option value="1">35мм</option>
					    	<option value="2">3D</option>
					    	<option value="3">2D</option>
					    </select>
					</td>
					<td>
					    <select name="status_gledanosti_filter" id="gledanost-status_filter_select">
					    	<option value="">--</option>
					    	<option value="1"><?php echo $lang['radna'];?></option>
					    	<option value="2"><?php echo $lang['kontrolna'];?></option>
					    	<option value="3"><?php echo $lang['finalna'];?></option>
					    </select>
					</td>
			    	<td>
			    		<input type="submit" id="pretraga-gledanosti-button" class="advanced-search-button controll-button" style="left:0" value="<?php echo $lang[ 'trazi' ]; ?>" />
			    		<input type="button" id="prikazi-gledanosti-button" class="list-button controll-button" style="left:0" value="<?php echo $lang[ 'pregled' ]; ?>" />
			    		
					</td>
			    
		       </tr>
		    </table>
		    
		</form>   
	    
	    
	    <div id="gledanost-forms-cnt"></div>
	    
	</div>

</div>

