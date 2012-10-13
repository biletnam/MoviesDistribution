<div id="kursnaLista-cnt"  class="module-holder">
 
<div id="kursnaLista-top-group" class="module-menu-cnt" align="left" style="width:1000px">
	<form id="kursnaLista-pretraga-form" action="#">
		
		<table>
			<tbody>
				<tr>
					<td></td>
					<td><label><?php echo $lang['datum'];?>:</label></td>
					<td><label>RSD:</label></td>
					<td><label>KM:</label></td>
					<td><label>EUR:</label></td>
					
				</tr>
				
				<tr>
					<td><input type="button" id="novi-kurs-button" class="add-button controll-button" style="left:0" value="<?php echo $lang['novi_kurs'];?>" /></td>
					
					<td><input type="text" id="datum_kursa_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>datum_kursa" class="pretraga_input" /></td>
					<td><input type="text" id="rsd_kursa_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>rsd" class="pretraga_input" /></td>
					<td><input type="text" id="km_kursa_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>km" class="pretraga_input" /></td>
					<td><input type="text" id="eur_kursa_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>eur" class="pretraga_input" /></td>
					
					<td>
						<input type="button" id="kursnaLista_pretraga_submit" name="trazi" value="<?php echo $lang['trazi'];?>" class="advanced-search-button controll-button" />
						<input type="reset" id="kursnaLista_pretraga_reset" name="resetuj" value="<?php echo $lang['resetuj'];?>" class="reset-search-button controll-button" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	 
</div>

<table width="100%" height="100%">
	<tr>
		<td valign="top" align="center">
			<table id="kursna-lista-grid"></table> 
			<div id="kursna-lista-grid-pager"></div>
		</td>
		
	</tr>
</table> 
  
</div>