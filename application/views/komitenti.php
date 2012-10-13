<div id="komitenti-cnt"  class="module-holder">
 
<div id="komitenti-top-group" class="module-menu-cnt" align="left" style="width:1100px">

	<input type="button" id="novi-komitent" class="add-button controll-button" style="left:0" value="<?php echo $lang['novi_komitent'];?>" />
	<input type="button" id="promeni-komitenta" class="update-button controll-button" style="left:0" value="<?php echo $lang['promeni_komitenta'];?>" />
	<input type="button" id="napredna-pretraga-komitenta" class="advanced-search-button controll-button" style="left:0" value="<?php echo $lang['napredna_pretraga'];?>" />

	<div id="komitenti-napredna-pretraga-dialog">
		<form id="komitenti-pretraga-form" action="#">
		
				<table>
					<tr>
						<td>
							<table>
								<tr>
									<td><label><?php echo $lang['sifra'];?>:</label></td>
									<td><input type="text" id="komitent_id_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>komitent_id" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['sifra_delatnosti'];?>:</label></td>
									<td><input type="text" id="sifra_delatnosti_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>sifra_delatnosti_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['naziv'];?>:</label></td>
									<td><input type="text" id="naziv_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['primenjen_porez'];?>:</label></td>
									<td>
										<select id="primenjen_porez_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>primenjen_porez_komitenta" class="pretraga_input">
													<option value="">--</option>
													<option value="1">0%</option>
													<option value="2">8%</option>
													<option value="3">18%</option>
													<option value="4"><?php echo $lang['bez_poreza'];?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label><?php echo $lang['adresa'];?></label></td>
									<td><input type="text" id="adresa_komitenta_naziv_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>adresa_komitenta" class="pretraga_input" /></td>
									
								</tr>
								<tr>
									<td><label><?php echo $lang['postanski_broj'];?>:</label></td>
									<td><input type="text" id="p_broj_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>p_broj" class="pretraga_input" /></td>
									
								</tr>
								
							</table>
						</td>
					
						<td>
							<table>
								<tr>
									<td><label><?php echo $lang['grad'];?>:</label></td>
									<td><input type="text" id="mesto_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>mesto_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['telefon'];?>:</label></td>
									<td><input type="text" id="tel1_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tel1_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['telefon2'];?>:</label></td>
									<td><input type="text" id="tel2_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tel2_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['email'];?>:</label></td>
									<td><input type="text" id="email_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>email_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['pib'];?>:</label></td>
									<td><input type="text" id="pib_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>pib_komitenta" class="pretraga_input" /></td>
								</tr>
								<tr>
									<td><label><?php echo $lang['gledanost'];?>:</label></td>
									<td>
										<select id="gledanost_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>gledanost_komitenta" class="pretraga_input">
													<option value="">--</option>
													<option value="1">RSD</option>
													<option value="2">KM</option>
													<option value="3">EUR</option>
										</select>
									</td>
								</tr>
								<tr>
									<td><label><?php echo $lang['kontakt_osoba'];?>:</label></td>
									<td><input type="text" id="kontakt_osoba_komitenta_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>kontakt_osoba_komitenta" class="pretraga_input" /></td>
								</tr>

							</table>
						</td>
					</tr>
			</table>
	
		</form>
	
	</div>
	
	
	
</div>

<table width="100%" height="100%">
	<tr>
		<td valign="top" align="center">
			<table id="komitenti-grid"></table> 
			<div id="komitenti-grid-pager"></div>
		</td>
		<td valign="top" align="left">
			<div class="module-menu-cnt" align="left" style="padding-top: 0px !important">
				<button id="novi-bioskop" class="add-button controll-button"><?php echo $lang['novi_bioskop'];?></button>
				<button id="novi-bioskop-alias" class="add-button controll-button"><?php echo $lang['novi_alias'];?></button>
			</div>
			<table id="bioskopi-grid"></table>
			<table id="bioskopi-aliases-grid"></table>
		</td>
	</tr>
</table> 
 
</div>