<div id="settings-cnt"  class="module-holder">
 
	<br />
	<br />
	
	<div id="update-password-dialog-cnt">
	
		<form id="update-user-password-form">
			
			<?php echo $lang['stara_sifra'];?>:
			<input type="text" name="stara_sifra"  id="stara-sifra-input" />
			<br />
			
			<?php echo $lang['nova_sifra'];?>:
			<input type="text" name="nova_sifra" id="nova-sifra-input" />
			<br />
			
			<?php echo $lang['ponovi_sifru'];?>:
			<input type="text" name="ponovi_sifru" id="ponovi-sifra-input" />
			<br />
			<br />
			
			<input type="submit" name="" value="<?php echo $lang['promeni_sifru'];?>" class="save-button controll-button" />
			<br />
			
		</form>
	</div>
	
	<div id="opcije-podesavanja-cnt" style="width:1124px">
		
						
		<h3><a href="#"><?php echo $lang['korisnici'];?></a></h3>
        
	    <div id="korisnici-podesavanja">
	     	
	     	<div class="module-menu-cnt" align="center">
				<button id="novi-korisnik-btn" class="add-button controll-button"><?php echo $lang['novi_korisnik'];?></button>
				
				<button id="promeni-sifru-korisnika-btn" class="lock-button controll-button"><?php echo $lang['promeni_sifru_korisnika'];?></button>
				
				<button id="obrisi-korisnika-btn"  class="delete-button controll-button"><?php echo $lang['obrisi_korisnika'];?></button>
			</div>
		
			<table id="korisnici-grid"></table>
			
	    </div>
		
		
		
			
	    <h3><a href="#"><?php echo $lang['maticna_firma'];?></a></h3>
	        
	    <div id="podesavanja-maticna-firma">
	    
		    <form id="settings-save-form" action="#">
			
				<table align="center">
					<tbody>
						
						<tr>
							<td align="center"><h3 style="color:white"><?php echo $lang['maticna_firma'];?></h3></td>
						</tr>
						<tr>
							<td><label><?php echo $lang['naziv'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>naziv_maticne_firme" id="naziv_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['adresa'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>adresa_maticne_firme" id="adresa_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['pib'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>pib_maticne_firme" id="pib_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['sifra'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>sifra_maticne_firme" id="sifra_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['grad'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>mesto_maticne_firme" id="mesto_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['postanski_broj'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>zip_maticne_firme" id="zip_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['telefon'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>tel_maticne_firme" id="tel_maticne_firme_input" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['fax'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>fax_maticne_firme" id="fax_maticne_firme_input" size="35" /></td>
						</tr>
						
						
						
						<tr>
							<td></td>
							<td><input style="margin-top:15px" type="submit" value="<?php echo $lang['sacuvaj'];?>" class="save-button controll-button" /></td>
						</tr>	
					</tbody>
				</table>
			</form>
	    
	   </div>
						    
		
			    <h3><a href="#"><?php echo $lang['maticna_firma_cg'];?></a></h3>
	        
	    <div id="podesavanja-maticna-firma_cg">
	    
		    <form id="settings-save-formcg" action="#">
			
				<table align="center">
					<tbody>
						
						<tr>
							<td align="center"><h3 style="color:white"><?php echo $lang['maticna_firma_cg'];?></h3></td>
						</tr>
						<tr>
							<td><label><?php echo $lang['naziv'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>naziv_maticne_firme" id="naziv_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['adresa'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>adresa_maticne_firme" id="adresa_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['pib'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>pib_maticne_firme" id="pib_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['sifra'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>sifra_maticne_firme" id="sifra_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['grad'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>mesto_maticne_firme" id="mesto_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['postanski_broj'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>zip_maticne_firme" id="zip_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['telefon'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>tel_maticne_firme" id="tel_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						<tr>
							<td><label><?php echo $lang['fax'];?>:</label></td>
							<td><input type="text" name="<?php echo INDEX_CELL_PREFIX_NAME;?>fax_maticne_firme" id="fax_maticne_firme_inputcg" size="35" /></td>
						</tr>
						
						
						
						<tr>
							<td></td>
							<td><input style="margin-top:15px" type="submit" value="<?php echo $lang['sacuvaj'];?>" class="save-button controll-button" /></td>
						</tr>	
					</tbody>
				</table>
			</form>
	    
	   </div>
		
		
		
						    
	</div>
		
	 
</div>
