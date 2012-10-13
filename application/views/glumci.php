<div id="glumci-cnt"  class="module-holder">
 
<div id="glumci-top-group" class="module-menu-cnt" align="left" style="width:1200px">

	<form id="glumci-pretraga-form" action="#">
		
		<table>
			<tbody>
				<tr>
					<td></td>
					<td></td>
					<td><label><?php echo $lang[ 'sifra' ];?>:</label></td>
					<td><label><?php echo $lang[ 'ime' ];?>:</label></td>
					<td><label><?php echo $lang[ 'prezime' ];?>:</label></td>
					<td><label><?php echo $lang[ 'link' ];?>:</label></td>
					
				</tr>
				
				<tr>
					<td><input type="button" id="novi-glumac" class="add-button controll-button" style="left:0" value="<?php echo $lang[ 'novi_glumac' ];?>" /></td>
					<td><input type="button" id="update-glumac" class="update-button controll-button" style="left:0" value="<?php echo $lang[ 'promeni_glumca' ];?>" /></td>
					
					<td><input type="text" id="glumac_id_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>glumac_id" class="pretraga_input" /></td>
					<td><input type="text" id="ime_glumca_retraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>ime_glumca" class="pretraga_input" /></td>
					<td><input type="text" id="prezime_glumca_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>prezime_glumca" class="pretraga_input" /></td>
					<td><input type="text" id="link_glumca_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>link_glumca" class="pretraga_input" /></td>
					
					<td>
						<input type="button" id="glumci_pretraga_submit" name="trazi" value="<?php echo $lang[ 'trazi' ];?>" class="advanced-search-button controll-button" />
						<input type="reset" id="glumci_pretraga_reset" name="resetuj" value="<?php echo $lang[ 'resetuj' ];?>" class="reset-search-button controll-button" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	
	 
</div>
<table width="100%" height="100%">
	<tr>
		<td valign="top" align="center">
			<table id="glumci-grid"></table> 
			<div id="glumci-grid-pager"></div>
		</td>
		
	</tr>
</table> 
 
<!--     
	<h3><a href="#">Lista Komitenata</a></h3>
        
     <div id="lista-komitenata" style="height:600px"></div>
    
    
    <h3><a href="#">Novi komitent</a></h3>
        
    <div id="novi-komitent" ></div>
    
    
    
    <h3><a href="#">Promeni Komitenta</a></h3>
        
    <div id="izmena-komitenta"></div>
-->   
  
</div>