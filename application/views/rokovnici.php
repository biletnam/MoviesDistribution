<div id="rokovnici-cnt"  class="module-holder">
	
	<div id="rokovnici-napredna-pretraga-dialog" style="display:none;">
		<form id="rokovnici-pretraga-form" action="#">		
				<table>
					<tr>
						<td>
							<table>
								<tr>
									<td><label><?php echo $lang['sifra'];?>:</label></td>
									<td><input type="text" id="sifra_rokonvika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>rokovnik_id" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['sifra_zakljucnice'];?>:</label></td>
									<td><input type="text" id="sifra_zakljucnice_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>zakljucnica_id" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['primiti_od'];?>:</label></td>
									<td>
										<select id="primit_od_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>primiti_kopiju_od" class="pretraga_input">
													<option value="">--</option>
													<option value="1"><?php echo $lang['skladiste'];?></option>
													<option value="2"><?php echo $lang['tranzit'];?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label><?php echo $lang['nacin_prijema'];?>:</label></td>
									<td>
										<select id="nacin_prijema_rokovnik_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>nacin_prijema_kopije" class="pretraga_input">
													<option value="">--</option>
													<option value="1"><?php echo $lang['autobusom'];?></option>
													<option value="2"><?php echo $lang['licno'];?></option>
													<option value="3"><?php echo $lang['spediterom'];?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum_prijema'];?>:</label></td>
									<td><input type="text" id="datum_prijema_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_prijema_kopije" class="pretraga_input" /></td>
									
								</tr>
								<tr>
									<td><label><?php echo $lang['otpremiti_od'];?>:</label></td>
									<td>
										<select id="otpremiti_od_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>otpremiti_kopiju_od" class="pretraga_input">
													<option value="">--</option>
													<option value="1"><?php echo $lang['skladiste'];?></option>
													<option value="2"><?php echo $lang['tranzit'];?></option>
										</select>
									</td>
									
								</tr>
								<tr>
									<td><label><?php echo $lang['nacin_otpreme'];?>:</label></td>
									<td>
										<select id="nacin_otpreme_rokovnik_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>nacin_otpreme_kopije" class="pretraga_input">
													<option value="">--</option>
													<option value="1"><?php echo $lang['autobusom'];?></option>
													<option value="2"><?php echo $lang['licno'];?></option>
													<option value="3"><?php echo $lang['spediterom'];?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum_otpreme'];?>:</label></td>
									<td><input type="text" id="datum_otpreme_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_otpreme_kopije" class="pretraga_input" /></td>
									
								</tr>
								<tr>
									<td><label><?php echo $lang['tip_raspodele'];?>:</label></td>
									<td>
										<select id="tip_raspodele_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tip_raspodele" class="pretraga_input">
											<option value="">--</option>
											<option value="1"><?php echo $lang['minimalna_garancija'];?></option>
											<option value="2"><?php echo $lang['ugovoren_iznos'];?></option>
											<option value="3"><?php echo $lang['raspodela'];?></option>
										</select>
									</td>
								</tr>
																				<tr>
					
						<td><label><?php echo $lang['tip'];?>:</label></td>
						<td>
						<select id="tip_rokovnika_pretraga" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tip" class="pretraga_input" >
						<option value="">--</option>
					    <option value="sr">SR</option>
					    <option value="cg">CG</option>				    	    
					    </select>
					    </td>
					</tr>
							</table>
						</td>
					
						<td>
							<table>
								<tr>
									<td><label><?php echo $lang['sifra_komitenta'];?>:</label></td>
									<td><input type="text" id="sifra_komitenta_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>komitent_id" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv_komitenta'];?>:</label></td>
									<td><input type="text" id="naziv_komitenta_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv_bioskopa'];?>:</label></td>
									<td><input type="text" id="naziv_bioskopa_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_bioskopa" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['sifra_filma'];?>:</label></td>
									<td><input type="text" id="sifra_filma_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>film_id" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv_filma'];?>:</label></td>
									<td><input type="text" id="naziv_filma_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_filma" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['serijski_broj_kopije'];?>:</label></td>
									<td><input type="text" id="serijski_broj_kopije_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>serijski_broj_kopije" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum_kopije_od'];?>:</label></td>
									<td><input type="text" id="datum_kopije_od_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_kopije_od" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum_kopije_do'];?>:</label></td>
									<td><input type="text" id="datum_kopije_do_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_kopije_do" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['tehnika'];?>:</label></td>
									<td>
										<select id="tehnika_kopije_rokovnika_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tehnika_kopije_filma" class="pretraga_input">
											<option value="">--</option>
											<option value="1">35mm</option>
											<option value="2">3D</option>
											<option value="3">2D</option>
										</select>
									</td>
								</tr>
								
								
							</table>
						</td>
					</tr>
			</table>
		</form>
	</div>
	 
	<div id="create-zvanicna-gledanost-dialog">
		<h4 id="zgledanost-info-text" style="display:none;margin: 0;"></h4>
		<br />
		<?php echo $lang['od'];?>:
		<input type="text" name="datum_z_gledanosti_od" id="datum_z_gledanosti_od"/>
		
		<?php echo $lang['do'];?>:
		<input type="text" name="datum_z_gledanosti_do" id="datum_z_gledanosti_do" />
		
		<br />
		<br />
		<br />
		
		
		<button id="save-zvanicna-gledanost-btn" style="position:relative; float: right;" class="save-button controll-button"><?php echo $lang['sacuvaj'];?></button>
	
	</div>
	

	<div id="rokovnici-top-group" class="module-menu-cnt" align="left" style="width:1100px">
		
		<input type="button" id="create-z_gledanost-btn" class="globe-button controll-button" style="left:0" value="<?php echo $lang['nova_zvanicna_gledanost'];?>" />
		<input type="button" id="napredna-pretraga-rokovnika" class="advanced-search-button controll-button" style="left:0" value="<?php echo $lang['napredna_pretraga'];?>" />
		<button id="preview-rokovnici-btn" class="list-button controll-button"><?php echo $lang['pregled'];?></button>
	
	</div>

	<table width="100%" height="100%">
		<tr>
			<td valign="top" align="center">
				<table id="rokovnici-grid"></table> 
				<div id="rokovnici-grid-pager"></div>
			</td>
		</tr>
	</table> 
 
</div>