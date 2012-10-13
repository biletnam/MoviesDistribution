<div id="fakture-cnt"  class="module-holder">

	<div id="fakture-napredna-pretraga-dialog" style="display:none;">
		<form id="fakture-pretraga-form" action="#">		
				<table>
					
					<tr>
						<td><label><?php echo $lang['broj_dokumenta'];?>:</label></td>
						<td><input type="text" id="broj_dokumenta_fakture_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>redni_broj_u_godini" class="pretraga_input" /></td>
					</tr>
					<tr>
						<td><label><?php echo $lang['datum_unosa'];?>:</label></td>
						<td><input type="text" id="datum_unosa_fakture_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_unosa_fakture" class="pretraga_input" /></td>
					</tr>
					<tr>
						<td><label><?php echo $lang['naziv_komitenta'];?>:</label></td>
						<td><input type="text" id="naziv_komitenta_faktura_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_komitenta" class="pretraga_input" /></td>
					</tr>
					<tr>
						<td><label><?php echo $lang['naziv_bioskopa'];?>:</label></td>
						<td><input type="text" id="naziv_bioskopa_faktura_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_bioskopa" class="pretraga_input" /></td>
					</tr>
					<tr>
						<td><label><?php echo $lang['naziv_filma'];?>:</label></td>
						<td><input type="text" id="naziv_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_filma" class="pretraga_input" /></td>
					</tr>
					<tr>
						<td><label><?php echo $lang['tehnika'];?>:</label></td>
						<td>
							<select id="tehnika_kopije_faktura_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tehnika_kopije_filma" class="pretraga_input">
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
									<td align="center"><input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>stornirana_faktura_pretraga" value="" id="faktura_stornirana_radio" /></td>
									<td align="center"><input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>stornirana_faktura_pretraga" value="1" id="faktura_stornirana_radio" /></td>
									<td align="center"><input type="radio" name="<?php echo INDEX_CELL_PREFIX_NAME;?>stornirana_faktura_pretraga" value="0" id="faktura_nije_stornirana_radio"/></td>
								</tr>
								
							</table>
			
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
		</form>
	</div>
	
	<div id="promeni-fakturu-dialog">
		
		<?php echo $lang['raspodela_naocare'];?>:
		<input type="text" name="raspodela_naocare_fakture" id="raspodela-naocare-fakture-input" />
		<br />
		<br />
		<input type="button" id="update_faktura_button" class="save-button controll-button" value="Сачувај" />
		
	</div>
	
	<div class="module-menu-cnt"> 
		
		<label><?php echo $lang['storno'];?>:</label><input type="checkbox" id="fakture-mode-storno" />
			
		<button id="pratraga-fakture-btn" class="advanced-search-button controll-button"><?php echo $lang['pretraga'];?></button>
		<button id="promeni-fakturu-btn" class="update-button controll-button"><?php echo $lang['promeni'];?></button>
		<button id="preview-fakture-btn" class="list-button controll-button"><?php echo $lang['pregled'];?></button>
		<button id="storno-fakture-btn" class="delete-button controll-button"><?php echo $lang['storniraj'];?></button>
		<button id="povrati-storno-fakture-btn" class="add-button controll-button"><?php echo $lang['povrati'];?></button>
		<button id="export-fakture-btn" class="import-export-button controll-button"><?php echo $lang['eksport'];?></button>
		
	</div>
	
	<table>
		<tr>
			<td valign="top">
				<table id="fakture-grid"></table> 
				<div id="fakture-grid-pager"></div>
			
				<table id="fakture-grid-storno"></table> 
				<div id="fakture-grid-storno-pager"></div>
				
				
			</td>
			<td valign="top" align="left">
			
				<div class="module-menu-cnt"> 
					<button id="dodaj-uplatu-fakture" class="add-button controll-button"><?php echo $lang['uplati'];?></button>
					<button id="obrisi-uplatu-fakture" class="delete-button controll-button"><?php echo $lang['obrisi_uplatu'];?></button>
					<?php echo $lang['total'];?>: <input type="text" id="faktura-uplaceno-total-input" disabled="disabled" style="text-align:right"/>
				</div>
				
				<table id="fakture-uplate-grid"></table>
			</td>
		</tr>
	</table>

</div>