<div id="zvanicnaGledanost-cnt"  class="module-holder">

	<div id="zvanicna-gledanost-napredna-pretraga-dialog">
		<form id="zvanicna-gledanost-pretraga-form" action="#">
		
				<table>
					<tr>
						<td>
							<table>
								<tr>
									<td><label><?php echo $lang['broj_dokumenta'];?>:</label></td>
									<td><input type="text" id="broj_dokumenta_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>broj_dokumenta_z_gledanosti" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum_od'];?>:</label></td>
									<td><input type="text" id="datum_od_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_z_gledanost_od" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum_do'];?>:</label></td>
									<td><input type="text" id="datum_do_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_z_gledanost_do" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv_filma'];?>:</label></td>
									<td><input type="text" id="naziv_filma_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_filma" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv_komitenta'];?>:</label></td>
									<td><input type="text" id="naziv_komitenta_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv_bioskopa'];?>:</label></td>
									<td><input type="text" id="naziv_bioskopa_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_bioskopa" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['tip_raspodele'];?>:</label></td>
									<td>
										<select id="tip_raspodele_z_geldanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tip_raspodele" class="pretraga_input">
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
										<select id="tip_z_gledanosti_pretraga" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tip" class="pretraga_input" >
											<option value="">--</option>
										    <option value="sr">SR</option>
										    <option value="cg">CG</option>				    	    
									    </select>
									</td>
								</tr>
								
								<tr>
									<td><label><?php echo $lang['tehnika'];?>:</label></td>
									<td>
										<select id="tehnika_kopije_z_gledanosti_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tehnika_kopije_filma" class="pretraga_input">
											<option value="">--</option>
											<option value="1">35mm</option>
											<option value="2">3D</option>
											<option value="3">2D</option>
										</select>
									</td>
								</tr>
								
								<tr>
									<td><label><?php echo $lang['stornirana'];?>:</label></td>
									<td>
									
										<table width="150">
											
											<tr>
												<td align="center">-</td>
												<td align="center"><?php echo $lang['da'];?></td>
												<td align="center"><?php echo $lang['ne'];?></td>
											</tr>
											
											<tr>
												<td align="center"><input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>stornirana_z_gledanost_pretraga" value="" id="z_gledanost_stornirana_radio" /></td>
												<td align="center"><input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>stornirana_z_gledanost_pretraga" value="1" id="z_gledanost_stornirana_radio" /></td>
												<td align="center"><input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>stornirana_z_gledanost_pretraga" value="0" id="z_gledanost_nije_stornirana_radio"/></td>
											</tr>
											
										</table>
							
									</td>
								</tr>
								
							</table>
						</td>
					
					</tr>
			</table>
	
		</form>
	
	</div>
	
	<div id="nova-faktura-dialog">
	
		<form id="nova-faktura-form">
			<h4 id="nova-faktura-info-text" style="display:none;margin: 0;"></h4>
			<br />
			
			RSD:
			<input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>valuta_fakture" value="1" id="valuta-fakture-1" />
			
			EUR:
			<input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>valuta_fakture" value="2" id="valuta-fakture-2"/>
			
			
			
			<br />
			<br />
			
			<?php echo $lang['raspodela_naocare'];?>:
			<input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>raspodela_naocare" id="raspodela_naocare-fakture-input" />
			<input type="checkbox" id="sa-porezom-naocare-fakture-input" name="sa_porezom_naocare" checked="checked"/> <?php echo $lang['sa_porezom'];?>
			
			<br />
			<br />
			
			<?php echo $lang['rok_placanja'];?>:
			<input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>rok_placanja_fakture" id="rok-placanja-fakture-input" />
			
			<br />
			<br />
			
			<?php echo $lang['datum_prometa'];?>:
			<input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>datum_prometa_fakture" id="datum-prometa-fakture-input" />
			<input type="hidden" id="datum-prometa-fakture-input-insert-format" />
			
			
			
			<br />
		</form>	
		
		<br />
		<button id="save-faktura-btn" style="position:relative; float: right;" class="save-button controll-button"><?php echo $lang['sacuvaj'];?></button>
			
	</div>
	
	<div class="module-menu-cnt"> 
		
		<button id="pretraga-zvanicne-gledanosti-btn"   class="advanced-search-button controll-button"><?php echo $lang['pretraga'];?></button>
		<button id="preview-zvanicne-gledanosti-btn"  class="list-button controll-button"><?php echo $lang['pregled'];?></button>
		<button id="create-faktura-btn" 			  class="fakture-button controll-button"><?php echo $lang['nova_faktura'];?></button>
		<button id="storniraj-zvanicnu-gledanost-btn" class="delete-button controll-button"><?php echo $lang['storniraj_z_gledanost'];?></button>
		<button id="povrati-zvanicnu-gledanost-btn"   class="add-button controll-button"><?php echo $lang['povrati_z_gledanost'];?></button>
		
<!--	<button id="delete-zvanicna-gledanost-btn" class="delete-button controll-button">Обриши</button>-->


		
	</div>
	
	<table id="zvanicnaGledanost-grid"></table> 
	<div id="zvanicnaGledanost-grid-pager"></div>

</div>