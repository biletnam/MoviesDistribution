<div id="filmovi-cnt"  class="module-holder">

	<div align="center">
		
		
			<div id="filmovi-top-group" class="module-menu-cnt" align="left">

				<input type="button" id="novi-film" class="add-button controll-button" style="left:0" value="<?php echo $lang[ 'novi_film' ]; ?>" />
				<input type="button" id="promeni-film" class="update-button controll-button" style="left:0" value="<?php echo $lang[ 'promeni_film' ]; ?>" />
				<input type="button" id="napredna-pretraga-filmova" class="advanced-search-button controll-button" style="left:0" value="<?php echo $lang[ 'napredna_pretraga' ]; ?>" />
				
				<div id="filmovi-napredna-pretraga-dialog">				
					<form id="filmovi-pretraga-form" action="#">
						
						<table>
							<tr>
								<td>
									<label for="naziv"><?php echo $lang[ 'sifra' ]; ?>:</label>
								</td>
								<td><input type="text" id="film_id_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>film_id" class="pretraga_input" /></td>
							</tr>
							<tr>
								<td><label for="naziv"><?php echo $lang[ 'naziv' ]; ?>:</label></td>
								<td><input type="text" id="naziv_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_filma" class="pretraga_input" /></td>
							</tr>
							<tr>
								<td><label for="adresa"><?php echo $lang[ 'originalni_naziv' ]; ?>:</label></td>
								<td><input type="text" id="originalni_naziv_filma__pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>originalni_naziv_filma" class="pretraga_input" /></td>
							</tr>
							<tr>
								<td><label for="pbroj"><?php echo $lang[ 'zanr' ]; ?>:</label></td>
								<td><?php echo $zanrSelect; ?></td>							
							</tr>
							<tr>
								<td><label for="mesto"><?php echo $lang[ 'trajanje' ]; ?>:</label></td>
								<td><input type="text" id="trajanje_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>trajanje_filma" class="pretraga_input" /></td>
							</tr>
								
							<tr>
								<td><label for="tel1"><?php echo $lang[ 'broj_cinova' ]; ?>:</label></td>
								<td><input type="text" id="broj_cinova_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>broj_cinova_filma" class="pretraga_input" /></td>
							</tr>
												
							
							<tr>
								<td><label for="tel2"><?php echo $lang[ 'tehnika' ]; ?>:</label></td>
								<td>
									<select id="tehnika_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>tehnika_filma" class="pretraga_input">
										<option value="">--</option>
										<option value="1">Color</option>
										<option value="2">B/W</option>
										<option value="3">HD</option>
									</select>
								</td>
							</tr>	
							<tr>
								<td><label><?php echo $lang[ 'producent' ]; ?>:</label></td>
								<td><input type="text" id="producent_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>producent_filma" class="pretraga_input" /></td>
							</tr>	
							<tr>
								<td><label><?php echo $lang[ 'ime_glumca' ]; ?>:</label></td>
								<td><input type="text" id="ime_glumca_filmovi_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>ime_glumca" class="pretraga_input" /></td>
							</tr>	
							<tr>
								<td><label><?php echo $lang[ 'prezime_glumca' ]; ?>:</label></td>
								<td><input type="text" id="prezime_glumca_filmovi_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>prezime_glumca" class="pretraga_input" /></td>
							</tr>	
							<tr>
								<td><label><?php echo $lang[ 'godina' ]; ?>:</label></td>
								<td><input type="text" id="godina_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>godina_filma" class="pretraga_input" /></td>
							</tr>
							<tr>
								<td><label><?php echo $lang[ 'studio' ]; ?>:</label></td>
								<td><input type="text" id="studio_filma_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>studio_filma" class="pretraga_input" /></td>
							</tr>
						</table>
					</form>
				</div>		
				
			</div>
				
		<table width="100%" height="100%">
			<tr>
				<td align="left">
					<div align="left">
						<table id="filmovi-grid" width="100%" height="100%"></table> 
						<div id="filmovi-grid-pager"></div>
					</div>
							
				</td>
				<td width="720">
		
						<div id="opcije-filma-cnt">
						
							<h3><a href="#"><?php echo $lang[ 'kopije_filma' ]; ?></a></h3>
				        
						     <div id="lista-filmova">
						     
						     	<div class="module-menu-cnt" align="left">
									<button id="novа-kopija" class="add-button controll-button"><?php echo $lang[ 'nova_kopija' ]; ?></button>
								</div>
								
								<table id="kopije-grid"></table>
							
						     </div>
			
						    <h3><a href="#"><?php echo $lang[ 'glumci_filma' ]; ?></a></h3>
						        
						    <div id="glumci-filma">
						    
						    	<div class="module-menu-cnt" align="left">
						    		<input type="text" id="suggest-glumac-txt" size="40" disabled="disabled"/>
									<button id="novi-glumac-filma" class="add-button controll-button" disabled="disabled"><?php echo $lang[ 'dodaj_glumca' ]; ?></button>
								</div>
							
								<table id="glumci-filma-grid"></table>
							
						    </div>
						    
						    <h3><a href="#"><?php echo $lang[ 'zanrovi' ]; ?></a></h3>
						        
						    <div id="novi-film">
						    
						    	<div class="module-menu-cnt" align="left" >
									<button id="novi-zanr" class="add-button controll-button"><?php echo $lang[ 'novi_zanr' ]; ?></button>
								</div>
							
								<table id="zanrovi-grid"></table>
								
						    </div>
				    
				    	</div>
				</td>
			</tr>
		</table>		
				
		
		
   		
   </div>
  
</div>