<div id="zakljucnice-cnt"  class="module-holder">


	<div id="zakljucnice-napredna-pretraga-dialog">
			
		<form id="zakljucnice-pretraga-form" action="#">
			<table>
				<tbody>
					<tr>
						<td><label><?php echo $lang['sifra'];?>:</label></td>
						<td><input type="text" id="zakljucnica_id_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>zakljucnica_id" class="pretraga_input" /></td>
					</tr>
					<tr>		
						<td><label><?php echo $lang['naziv_komitenta'];?>:</label></td>
						<td><input type="text" id="naziv_komitenta_zakljucnice_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_komitenta" class="pretraga_input" /></td>
					</tr>
					<tr>	
						<td><label><?php echo $lang['naziv_bioskopa'];?>:</label></td>
						<td><input type="text" id="naziv_bioskopa_zakljucnice_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_bioskopa" class="pretraga_input" /></td>
					</tr>
					<tr>	
						<td><label><?php echo $lang['naziv_filma'];?>:</label></td>
						<td><input type="text" id="naziv_filma_zakljucnice_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_filma" class="pretraga_input" /></td>
					</tr>
					<tr>
						<td><label><?php echo $lang['tip_raspodele'];?>:</label></td>
						<td>
							<select id="tip_raspodele_zakljucnice_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tip_raspodele" class="pretraga_input">
								<option value="">--</option>
								<option value="1"><?php echo $lang['minimalna_garancija'];?></option>
								<option value="2"><?php echo $lang['ugovoren_iznos'];?></option>
								<option value="3"><?php echo $lang['raspodela'];?></option>
							</select>
						</td>
					</tr>
					<tr>	
						<td><label><?php echo $lang['datum'];?>:</label></td>
						<td><input type="text" id="datum_zakljucnice_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_zakljucnice" class="pretraga_input" /></td>
	
					</tr>
					<tr>
					
						<td><label><?php echo $lang['tip'];?>:</label></td>
						<td>
						<select id="tip_zakljucnice_pretraga" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tip" class="pretraga_input" >
						<option value="">--</option>
					    <option value="sr">SR</option>
					    <option value="cg">CG</option>				    	    
					    </select>
					    </td>
					</tr>
					
				</tbody>
			</table>
		</form>
		
	</div>
		
	<div id="zakljucnice-top-group" class="module-menu-cnt">
		<button id="nova-zakljucnica-button" class="add-button controll-button" style="left:0"><?php echo $lang['nova_zakljucnica'];?></button>	
		<button id="promeni-zakljucnicu-button" class="update-button controll-button" style="left:0"><?php echo $lang['promeni_zakljucnicu'];?></button>
		<button id="napredna-pretraga-zakljucnice-button" class="advanced-search-button controll-button" style="left:0"><?php echo $lang['napredna_pretraga'];?></button>
		<button id="preview-zakljucnice-btn" class="list-button controll-button"><?php echo $lang['pregled'];?></button>	
	</div>
	
	<table id="zakljucnice-lista-grid"></table> 
	<div id="zakljucnice-grid-pager"></div>
	
	<div id="nova-zakljucnica-dialog">
		<table width="100%">
			<tr><td><h4 id="zakljucnica-info-text" style="display:none;margin: 0;"></h4></td></tr>
			<tr>
				<td><div id="nova-zakljucnica-kalendar" style="width:600px"></div></td>
				<td  valign="top">
					<div>
						<form action="#">
							
							<table>
								<tr>
									<td><button id="dodaj-kopiju-zakljucnice" class="add-button controll-button"><?php echo $lang['nova_kopija'];?></button></td>
									<td><button id="promeni-kopiju-zakljucnice" class="update-button controll-button"><?php echo $lang['promeni_kopiju'];?></button></td>
									<td><button id="obrisi-kopiju-zakljucnice-btn" class="delete-button controll-button"><?php echo $lang['obrisi_kopiju'];?></button></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['datum'];?>:</label></td>
									<td><label><?php echo $lang['sifra_komitenta'];?>:</label></td>
									<td><label><?php echo $lang['naziv_komitenta'];?>:</label></td>
									<td><label><?php echo $lang['tip'];?>:</label></td>
								</tr>
								
								<tr>
									<td><input type="hidden" id="datum-zakljucnice-input" /><input type="text" id="datum-zakljucnice-input-stampa" /></td>
									<td><input type="text" id="sifra-komitenta-zakljucnice" /></td>
									<td><input type="text" id="naziv-komitenta-zakljucnice" /></td>
									<td>
									<select id="tip-zakljucnice">
									<option value="">--</option>
					    	        <option value="sr">SR</option>
					    	        <option value="cg">CG</option>				    	    
					    	        </select>
									
									</td>
								</tr>
								
							</table>
							
							
						</form>	
					</div>
					
					<table id="kopije-zakljucnice-lista-grid"></table>
				</td>	
			</tr>
			
		</table>
	</div>
	 		
</div>