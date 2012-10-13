<div id="konkurentskiFilmovi-cnt"  class="module-holder">
 
<div id="konkurentskiFilmovi-top-group" class="module-menu-cnt" align="left" style="width:1440px">
	<form id="konkurentskiFilm-pretraga" action="#">
		
		<table>
			<tbody>
				<tr>
					<td></td>
					<td></td>
					<td><label>Шифра:</label></td>
					<td><label>Назив:</label></td>
					<td><label>Оригинални назив:</label></td>
					
				</tr>
				
				<tr>
					<td><input type="button" id="novi-konkurentskiFilm" class="add-button controll-button" style="left:0" value="Нови Филм" /></td>
					<td><input type="button" id="update-konkurentskiFilm" class="update-button controll-button" style="left:0" value="Промени Филм" /></td>
					
					<td><input type="text" id="konkurentskiFilm_id_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>id_konkurentskog_filma" class="pretraga_input" /></td>
					<td><input type="text" id="konkurentskiFilm_naziv_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>naziv_konkurentskog_filma" class="pretraga_input" /></td>
					<td><input type="text" id="konkurentskiFilm_originalni_naziv_pretraga_input" name="<?php echo SAVE_CELL_PREFIX_NAME;?>originalni_naziv_konkurentskog_filma" class="pretraga_input" /></td>
					
					<td>
						<input type="button" id="konkurentskiFilm_pretraga_submit" name="trazi" value="Тражи" class="advanced-search-button controll-button" />
						<input type="reset" id="konkurentskiFilm_pretraga_reset" name="resetuj" value="Ресетуј" class="reset-search-button controll-button" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	 
</div>

<table width="100%" height="100%">
	<tr>
		<td valign="top" align="center">
			<table id="konkurentski-film-grid"></table> 
			<div id="konkurentski-film-grid-pager"></div>
		</td>
		
	</tr>
</table> 
  
</div>