<div id="izvestaji-cnt"  class="module-holder">

	<div id="izvestaji-main-menu" style="padding-top:30px">
	     
	     <ul>
	      <li><a href="#tab-licna-karta"><?php echo $lang['licna_karta_prikazivaca'];?></a></li>	
	     <li><a href="#tab-fin-izv"><?php echo $lang['finansijski'];?></a></li>
	                  
	         <li><a href="#tab-rok-izv"><?php echo $lang['prijem_i_slanje_kopije'];?></a></li>
	         <li><a href="#tab-bio-izv"><?php echo $lang['dnevni_bioskopski_izvestaj_po_regionima'];?></a></li>
	         <li><a href="#tab-nep-izv"><?php echo $lang['neposlati_zvanicni_izvestaji'];?></a></li>
	         <li><a href="#tab-gled-izv"><?php echo $lang['gledanosti'];?></a></li>
	         <li><a href="#tab-fak-izv"><?php echo $lang['ne_fakturisano_po_pfk'];?></a></li>
	        <li><a href="#tab-liste"><?php echo $lang['top_liste'];?></a></li>
          <li><a href="#tab-procenti">Procenti</a></li>
	     </ul>
	     
	    <div id="tab-licna-karta">
   			
   			<table>
   				<tr>
   					<td>
   						<form action="<?php echo BASE_URI_SERVICE; ?>izvestaji/prikaziLicnuKartu/" method="post" target="_blank">
   							
   							<table align="center">
   								<tr>
   									<td><?php echo $lang[ 'sifra_komitenta' ];?>:</td>
   									<td><?php echo $lang[ 'naziv_komitenta' ];?>:</td>
   									<td><?php echo $lang[ 'bioskop' ];?>:</td>
   									<td><?php echo $lang[ 'tehnika' ];?>:</td>
   									<td><?php echo $lang[ 'tip' ];?>:</td>
   									<td><?php echo $lang[ 'datum_od' ];?>:</td>
   									<td><?php echo $lang[ 'datum_do' ];?>:</td>
   									<td></td>
   								</tr>
   								<tr>
	   								<td><input type="text" name="komitent_id" id="lk-izvestaj-sifra-komitenta-input" size="12" /></td>
	   								<td><input type="text" name="naziv_komitenta" id="lk-izvestaj-ime-komitenta-input"/></td>
	   								<td><select name="izvestaj_lk_bioskop_select" id="izvestaj_lk_bioskop_select"><option value="0">--</option></select></td>
	   								<td>
	   									<select name="tehnika_kopije_filter" id="lk-tehnika-kopije-filter-select">
									    	<option value="">--</option>
									    	<option value="1">35мм</option>
									    	<option value="2">3D</option>
									    	<option value="3">2D</option>
									    </select>
	   								</td>
	   								<td>
	   									<select name="tip_komitenta" id="izvestaj-lk-tip-komitenta-select">
	   										<option value="0">--</option>
	   										<option value="sr">SR</option>
	   										<option value="cg">CG</option>
	   									</select>
	   								</td>
	   								<td><input type="text" name="datum_kopije_od" id="lk-izvestaj-datum-od-input" size="12"/></td>
	   								<td><input type="text" name="datum_kopije_do" id="lk-izvestaj-datum-do-input" size="12"/></td>
	   								<td>
	   									<button id="lk-izvestaj-generisi-button" class="update-button controll-button"><?php echo $lang['generisi'];?></button>
	   									<input type="submit" id="lk-izvestaj-stampaj-button" class="list-button controll-button" value="<?php echo $lang['pregled'];?>"/>
	   								</td>
   								</tr>
   							</table>
				   			
				   		</form>
				   		
				   		
   					</td>
   				</tr>
   			</table>
   			
   			
   			<table width="100%">
   			
   				<tr>
   					<td valign="top">
   						<table id="iz-lk-odigrani-filmovi-grid"></table> 
						<div id="iz-lk-odigrani-filmovi-grid-pager"></div>
					</td>
					
					<td valign="top">
						<table id="iz-lk-bez-izvestaja-grid"></table> 
						<div id="iz-lk-bez-izvestaja-grid-pager"></div>
					</td>
					
					<td valign="top">
						<table id="iz-lk-top-lista-filmova-grid"></table> 
						<div id="iz-lk-top-lista-filmova-grid-pager"></div>
					</td>
					
   				</tr>
   			</table>	
   			
   			<table width="100%">
   				<tr>
   					<td colspan="3" valign="top">
						<table id="iz-lk-filmovi-sa-izvestajima-grid"></table> 
						<div id="iz-lk-filmovi-sa-izvestajima-pager"></div>
					</td>   				
   				</tr>
   				
   				<tr>
   					<td  valign="top">
   						<table id="iz-lk-nefakturisani-izvestaji-grid"></table> 
   					</td>
   					<td colspan="2" valign="top">
   						<table id="iz-lk-nebukirani-filmovi-grid"></table> 
   						<div id="iz-lk-nebukirani-filmovi-grid-pager"></div>
   					</td>
   				</tr>
   				
   			</table>
   			
   			<table width="100%">
   				
   				<tr>
   					<td colspan="3">
   						<table>
   							<tr>
   								<td>
	   								<table id="iz-lk-bioskopi-izvestaj-grid"></table> 
									<div id="iz-lk-bioskopi-izvestaj-pager"></div>
	   							</td>
	   							<td>		
	   								<table id="iz-lk-bioskopi-sume-izvestaj-grid"></table> 
									<div id="iz-lk-bioskopi-sume-izvestaj-pager"></div>
								</td>
							</tr>
						</table>
   					</td>
   				</tr>
   				
			</table>
			
   		</div>
   
   		<div id="tab-fin-izv">
   		<form action='<?php echo BASE_URI_SERVICE . 'izvestaji/prikaziFinansijskiIzvestaj'; ?>' method='post' target="_blank">
   		 <input type="hidden" name="nula" id="nula" value='0'/>
   			<?php echo $lang['sifra_filma'];?>: <input type="text" name="film_id" id="finansijski-izvestaj-sifra-filma-input" />
   			<?php echo $lang['naziv_filma'];?>: <input type="text" name="naziv_filma" id="finansijski-izvestaj-ime-filma-input"/>
   			<?php echo $lang['datum_od'];?>: <input type="text" name="datum_kopije_od" id="finansijski-izvestaj-datum-od-input" size="12"/>
   			<?php echo $lang['datum_do'];?>: <input type="text" name="datum_kopije_do" id="finansijski-izvestaj-datum-do-input" size="12"/>
   			<?php echo $lang['tip'];?>:
   			
                <select name="tip_komitenta" id="izvestaj-finansijski-tip-komitenta-select">
                    <option value="0">--</option>
                    <option value="sr">SR</option>
                    <option value="cg">CG</option>
                </select>
	   		
   			<button id="lk-izvestaj-fin-promet-generisi-button" class="update-button controll-button"><?php echo $lang['generisi'];?></button>
   			<input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="<?php echo $lang['pregled'];?>">
   			
   		</form>

   		<table id="iz-lk-finansijski-izvestaj-grid"></table> 
		<div id="iz-lk-finansijski-izvestaj-pager"></div>
						
   		</div>
   		   		<div id="tab-rok-izv">
   		<form action='pdf/pdf/rokovnici_izvestaj.php' method='post' target="_blank">
   		<?php echo $lang['datum'];?>: <input type="text" name="datum_izvestaj_od" id="izvestaj-rok-datum-od-input" size="12"/>
         <input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="<?php echo $lang['pregled'];?>">
   		</form>

						 
   		</div>
   		
   		<div id="tab-bio-izv" style='font-size:14px;'>
   		<form action='izv/izvestajbioskopi.php' method='post' target="_blank">
   		 <?php echo $lang['sifra_filma'];?>: <input type="text" name="film" id="lk-izvestaj-sifra-filma-input_b" size="12"/>
   		 <?php echo $lang['naziv_filma'];?>: <input type="text" name="naziv_filma" id="lk-izvestaj-ime-filma-input_b" size="12"/>
   		 <?php echo $lang['ili'];?>
   		 <?php echo $lang['originalno_ime_filma'];?>: <input type="text" name="originalni_naziv_filma" id="lk-izvestaj-ime-filma-input_b_org" size="12"/>
   		 <?php echo $lang['datum'];?>: <input type="text" name="datum" id="izvestaj-bio-datum-od-input" size="12"/>
   		 <?php echo $lang['datum_do'];?>: <input type="text" name="datum_do" id="izvestaj-bio-datum-do-input" size="12"/>
         <input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="<?php echo $lang['pregled'];?>">
   		</form>			
   		</div>
   		
   		
   		<div id="tab-gled-izv" style='font-size:14px;'>
   		<form action='pdf/pdf/izvestaji.php' method='post' target="_blank">
   		<table> 

   		<tr><td><?php echo $lang['sifra_filma'];?>: </td><td><input type="text" name="film" id="lk-izvestaj-sifra-filma-input_c"/></td></tr>
   		<tr><td><?php echo $lang['naziv_filma'];?>: </td><td><input type="text" name="naziv_filma" id="lk-izvestaj-ime-filma-input_c" /></td></tr>
   		<tr><td><?php echo $lang['sifra_komitenta'];?>: </td><td><input type="text" name="komitent_id" id="lk-izvestaj-sifra-komitenta-input_c" /></td></tr>
   		<tr><td><?php echo $lang['naziv_komitenta'];?>: </td><td><input type="text" name="naziv_komitenta" id="lk-izvestaj-ime-komitenta-input_c"/></td></tr>
   		<tr><td><?php echo $lang['bioskop'];?>: </td><td><select name="bioskop_alias_id" id="izvestaj_gledanost_bioskop_select"><option value="0">--</option></select></td></tr>
   		<tr><td><?php echo $lang['tehnika'];?>: </td><td><select id="gledanost-tehnika_kopije_filter_select" name="tehnika_kopije_filter">
					    	<option value="">--</option>
					    	<option value="1">35мм</option>
					    	<option value="3">2D</option>
					    	<option value="2">3D</option>
					    </select></td></tr>
   		<tr><td><?php echo $lang['datum_od'];?>: </td><td><input type="text" name="datum_od" id="izvestaj-gle-datum-od-input" /></td></tr>
   		<tr><td><?php echo $lang['datum_do'];?>: </td><td><input type="text" name="datum_do" id="izvestaj-gle-datum-do-input" /></td></tr>
   		<tr><td><?php echo $lang['sumarno_po_filmu'];?>: </td><td><input type="checkbox" name="sumarno" value="da" /></td></tr>
   		<tr><td><input type='submit'  style="position:relative; left:0" class="list-button controll-button" value=<?php echo $lang['pregled'];?>></td></tr>
   		</table>
   	
   		</form>			
   		</div>
   		
   		<div id="tab-procenti" style='font-size:14px;'>
   		<form action='pdf/pdf/procenti.php' method='post' target="_blank">
   		<table> 

   		<tr><td>Sifra filma: </td><td><input type="text" name="film" id="procenti-izvestaj-sifra-filma-input"/></td></tr>
   		<tr><td>Ime filma: </td><td><input type="text" name="naziv_filma" id="procenti-izvestaj-ime-filma-input" /></td></tr>
   		<tr><td>Sifra komitenta: </td><td><input type="text" name="komitent_id" id="procenti-izvestaj-sifra-komitenta-input" /></td></tr>
   		<tr><td>Ime komitenta: </td><td><input type="text" name="naziv_komitenta" id="procenti-izvestaj-ime-komitenta-input"/></td></tr>
   		<tr><td>Tehnika: </td><td><select id="gledanost-tehnika_kopije_filter_select" name="tehnika_kopije_filter">
					    	<option value="">--</option>
					    	<option value="1">35мм</option>
					    	<option value="3">2D</option>
					    	<option value="2">3D</option>
					    </select></td></tr>
   		<tr><td>Datum od: </td><td><input type="text" name="datum_od" id="izvestaj-procenti-datum-od-input" /></td></tr>
   		<tr><td>Datum do: </td><td><input type="text" name="datum_do" id="izvestaj-procenti-datum-do-input" /></td></tr>
   		<tr><td>Tip izvestaja: </td><td><select id="gledanost-tehnika_kopije_filter_select" name="tipizv">
					    	<option value="">--</option>
					    	<option value="fpf">Fakturisano po filmu</option>
					    	<option value="nfpf">Nefakturisano po filmu</option>
                            </select></td></tr>
   		<tr><td><input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="Prikazi"></td></tr>
   		</table>
   	
   		</form>			
   		</div>
   		
   		<div id="tab-liste" style='font-size:14px;'>
   		<form action='pdf/pdf/top_liste.php' method='post'  target="_blank">
   		<table> 
        <tr><td><?php echo $lang['producent'];?>: </td><td><input type="text" name="producent" id="lk-izvestaj-ime-producenta-input_l"/></td></tr>
   		<tr><td><?php echo $lang['sifra_filma'];?>: </td><td><input type="text" name="film" id="lk-izvestaj-sifra-filma-input_l"/></td></tr>
   		<tr><td><?php echo $lang['naziv_filma'];?>: </td><td><input type="text" name="naziv_filma" id="lk-izvestaj-ime-filma-input_l" /></td></tr>
   		<tr><td><?php echo $lang['sifra_komitenta'];?>: </td><td><input type="text" name="komitent_id" id="lk-izvestaj-sifra-komitenta-input_l" /></td></tr>
   		<tr><td><?php echo $lang['naziv_komitenta'];?>: </td><td><input type="text" name="naziv_komitenta" id="lk-izvestaj-ime-komitenta-input_l"/></td></tr>
   		<tr><td><?php echo $lang['tehnika'];?>: </td><td><select id="gledanost-tehnika_kopije_filter_select" name="tehnika_kopije_filter">
					    	<option value="">--</option>
					    	<option value="1">35мм</option>
					    	<option value="3">2D</option>
					    	<option value="2">3D</option>
					    </select></td></tr>
					    
		<tr><td><?php echo $lang['lokacija'];?>: </td><td><select id="lok-tehnika_kopije_filter_select" name="loka">
        <option value="">--</option>
        <option value="1">Beograd</option>
        <option value="2">Unutrašnjost</option>
        <option value="3">Crna Gora</option>
        <option value="4">BIH</option>
		</select></td></tr>
   		<tr><td><?php echo $lang['datum_od'];?>: </td><td><input type="text" name="datum_od" id="izvestaj-list-datum-od-input" /></td></tr>
   		<tr><td><?php echo $lang['datum_od'];?>: </td><td><input type="text" name="datum_do" id="izvestaj-list-datum-do-input" /></td></tr>
   		<tr><td><?php echo $lang['top_lista_gled'];?>: </td><td><input type="checkbox" name="pbgl" value="da" /></td></tr>
   		<tr><td><?php echo $lang['graf_prikaz_gled'];?>: </td><td><input type="checkbox" name="graf" value="da" /></td></tr>
   		<tr><td><?php echo $lang['top_lista_prihod'];?>:</td><td><input type="checkbox" name="popr" value="da" /></td></tr>
   		<tr><td><?php echo $lang['graf_prikaz_prihod'];?>: </td><td><input type="checkbox" name="graf1" value="da" /></td></tr>
   		<tr><td><input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="<?php echo $lang['pregled'];?>"></td></tr>
   		</table>
   	
   		</form>			
   		</div>
   		
   		
   		<div id="tab-fak-izv" style='font-size:14px;'>
   		<form action='<?php echo BASE_URI_SERVICE . 'faktureIzvestaji/ne_i_fakturisano_PFK'; ?>' method='post' target="_blank">
   		<table> 
   		<tr><td><?php echo $lang['producent'];?>: </td><td><input type="text" name="producent_filma" id="lk-izvestaj-ime-producenta-input_d"/></td></tr>
   		<tr><td><?php echo $lang['sifra_filma'];?>: </td><td><input type="text" name="film_id" id="lk-izvestaj-sifra-filma-input_d"/></td></tr>
   		<tr><td><?php echo $lang['naziv_filma'];?>: </td><td><input type="text" name="naziv_filma" id="lk-izvestaj-ime-filma-input_d" /></td></tr>
   		<tr><td><?php echo $lang['sifra_komitenta'];?>: </td><td><input type="text" name="komitent_id" id="lk-izvestaj-sifra-komitenta-input_d" /></td></tr>
   		<tr><td><?php echo $lang['naziv_komitenta'];?>: </td><td><input type="text" name="naziv_komitenta" id="lk-izvestaj-ime-komitenta-input_d"/></td></tr>
   		<tr><td><?php echo $lang['datum_od'];?>: </td><td><input type="text" name="datum_od" id="izvestaj-fak-datum-od-input" /></td></tr>
   		<tr><td><?php echo $lang['datum_do'];?>: </td><td><input type="text" name="datum_do" id="izvestaj-fak-datum-do-input" /></td></tr>
   		<tr>
   			<td><?php echo $lang['tip'];?>:</td>
   			<td>	
                <select name="tip_komitenta" id="izvestaj-fakturisano-tip-komitenta-select">
                    <option value="0">--</option>
                    <option value="sr">SR</option>
                    <option value="cg">CG</option>
                </select>
        	</td>
        </tr>
               
   		<tr><td><?php echo $lang['sa_uplatama'];?>: </td><td><input type="checkbox" name="sa_uplatama" value="da" /></td></tr>
   		<tr><td><?php echo $lang['sumarno_po_filmu'];?>: </td><td><input type="checkbox" name="sumarno_po_filmu" value="da" /></td></tr>
   		
   		<tr><td>-------------</td></tr>
   		<tr><td><?php echo $lang['nefakturisano'];?>: </td><td><input type="checkbox" name="nefakturisano" value="da" /></td></tr>
   		<tr><td>-------------</td></tr>
   		<tr><td><input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="<?php echo $lang['pregled'];?>"></td></tr>
   		</table>
   	
   		</form>			
   		</div>
   		
   		
   		
   		
   		<div id="tab-nep-izv">
   		<form action='pdf/pdf/neposlati.php' method='post' target="_blank">   		  
   			<?php echo $lang['datum_od'];?>: <input type="text" name="datum_od" id="nep-izvestaj-datum-od-input" size="12"/>
   			<?php echo $lang['datum_do'];?>: <input type="text" name="datum_do" id="nep-izvestaj-datum-do-input" size="12"/>
   			
         <input type='submit'  style="position:relative; left:0" class="list-button controll-button" value="<?php echo $lang['pregled'];?>">
   		</form>			
   		</div>
   		<div id="tab-promet">
   			
   		</div>
   		
	</div>
   
</div>