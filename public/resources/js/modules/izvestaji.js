var Izvestaji = function()
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	
	this.mainMenu = null;
	
	this.odabranKomitentId = 0;
	this.komitentSifraAutocompleteOptions = null;
	this.komitentImeAutocompleteOptions = null;
	
	
	// LICNA KARTA GRIDS
	this.lkOdigraniFilmoviGrid = null;
	this.lkFilmoviBezIzvestajaGrid = null;
	this.lkOdigraniFilmoviSaIzvestajimaGrid = null;
	this.lkProsecnaCenaKarteGrid = null;
	this.lkNefakturisaniIzvestajiGrid = null;
	this.lkNebukiraniFilmoviGrid = null;
	this.lkDugovanjeKomitentaGrid = null;
	this.lkBioskopiIzvestaj = null;
	this.lkBioskopiSumeIzvestaj = null;
	
	// FINANSIJSKI IZVESTAJ
	this.finansijskiIzvestajGrid = null;
	this.finSifraFilmaAutoCompleteOptions = null;
	this.finImeFilmaAutoCompleteOptions = null;
	
	
	// PROCENTI IZVESTAJ
	this.prSifraFilmaAutoCompleteOptions = null;
	this.prImeFilmaAutoCompleteOptions = null;
	this.prSifraKomitentaAutoCompleteOptions = null;
	this.prImeKomitentaAutoCompleteOptions = null;
	
	
	this.finPrometOdabraniFilmId = 0;
	this.gledanostOdabranKomitentId = 0;
}

Izvestaji.prototype.getKomitentBioskopi = function( komitent_id, element_id ){
	
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "komitenti/getBioskopiAliasesSelectOptions/" + komitent_id,
 		success: function(data)
 		{
			 
			var options =  data.getElementsByTagName("option");
			 
				var el = $( "#" + element_id );
				
				$( el ).empty();
				
				 $( el ).append($("<option></option>").
						  attr("value", 0 ).
						  text( "--" )); 
						  	
				 $.each(options, function(key, value)
				 {   
					 $( el ).
						  append($("<option></option>").
						  attr("value", $( value ).attr( "value" )).
						  text( $( value ).text() )); 
				 });	
			
		}
	}); 
}

Izvestaji.prototype.init = function()
{
	var s = this;
	 $.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "izvestaji/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			s.mainMenu = $( "#izvestaji-main-menu" ).tabs();
			
			$( "#lk-izvestaj-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#lk-izvestaj-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			
			$( "#nep-izvestaj-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#nep-izvestaj-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
		
			$( "#izvestaj-rok-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#izvestaj-bio-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#izvestaj-bio-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			
			
			$( "#izvestaj-gle-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#izvestaj-gle-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			
			$( "#izvestaj-list-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#izvestaj-list-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
			
			$( "#izvestaj-fak-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			$( "#izvestaj-fak-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
			
			
			s.mainMenu.bind('tabsselect', function(event, ui) {
				
				if( ui.panel.id != "tab-licna-karta" )
				{						
					switch( ui.panel.id )
					{
						case "tab-fin-izv":
							s.initFnReport();
						break;
						
						case "tab-rok-izv":
							break;
								
						case "tab-bio-izv":	
							s.initFnReport();
						break;
						
						case "tab-gled-izv":
							s.initFnReport();
						break;
						
						case "tab-liste":		
							s.initFnReport();
						break;
						
						case "tab-fak-izv":
							s.initFnReport();
						break;
						
						case "tab-nep-izv":
					    break;
						
						case "tab-promet":
						break;
						
						case "tab-procenti":
							s.initProcentiReport();
						break;
						
					}
				}
				
				//console.log( ui.tab );     // anchor element of the selected (clicked) tab
				//console.log( ui.panel );   // element, that contains the selected/clicked tab contents
				//console.log( ui.index );   // zero-based index of the selected (clicked) tab
				
			});
			
			// init first tab content
			s.komitentSifraAutocompleteOptions = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromId/",
				width:300,
				minLength: 1,
				select: function( event, ui ) 
				{	
					if( ui.item )
					{
						$( "#lk-izvestaj-ime-komitenta-input" ).attr( "value" ,ui.item.label );
						
						if( s.odabranKomitentId != ui.item.id )
						{
							s.odabranKomitentId = ui.item.id;
							s.getKomitentBioskopi( s.odabranKomitentId , 'izvestaj_lk_bioskop_select' );
						}
					}
					
						
					return true;
				}
			}
			
			s.komitentImeAutocompleteOptions = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromName/",
				width:300,
				minLength: 2,
				select: function( event, ui ) {	
						
						if( ui.item )
						{
							$( "#lk-izvestaj-sifra-komitenta-input" ).attr( "value", ui.item.id );
							
							if( s.odabranKomitentId != ui.item.id )
							{
								s.odabranKomitentId = ui.item.id;
								s.getKomitentBioskopi( s.odabranKomitentId, 'izvestaj_lk_bioskop_select' );	
							}
						}
						
						return true;
				}
			}
			
			
			$( '#lk-izvestaj-sifra-komitenta-input' ).autocomplete( s.komitentSifraAutocompleteOptions );
			$( '#lk-izvestaj-ime-komitenta-input' ).autocomplete( s.komitentImeAutocompleteOptions );
				
			
			
			s.komitentSifraAutocompleteOptions_c = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromId/",
				width:300,
				minLength: 1,
				select: function( event, ui ) 
				{	
					if( ui.item )
					{
						$( "#lk-izvestaj-ime-komitenta-input_c" ).attr( "value" ,ui.item.label );
						
						if( s.gledanostOdabranKomitentId != ui.item.id )
						{
							s.gledanostOdabranKomitentId = ui.item.id;
							s.getKomitentBioskopi( s.gledanostOdabranKomitentId, 'izvestaj_gledanost_bioskop_select' );	
						}
					}
					
						
					return true;
				}
			}
			
			s.komitentSifraAutocompleteOptions_l = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromId/",
				width:300,
				minLength: 1,
				select: function( event, ui ) 
				{	
					if( ui.item )
					{
						$( "#lk-izvestaj-ime-komitenta-input_l" ).attr( "value" ,ui.item.label );
						
					}
					
						
					return true;
				}
			}
			
			s.komitentSifraAutocompleteOptions_d = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromId/",
				width:300,
				minLength: 1,
				select: function( event, ui ) 
				{	
					if( ui.item )
					{
						$( "#lk-izvestaj-ime-komitenta-input_d" ).attr( "value" ,ui.item.label );
					}
					
						
					return true;
				}
			}
			
			
			
			s.komitentImeAutocompleteOptions_c = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromName/",
				width:300,
				minLength: 2,
				select: function( event, ui ) {	
						
						if( ui.item )
						{
							$( "#lk-izvestaj-sifra-komitenta-input_c" ).attr( "value", ui.item.id );
							
							if( s.gledanostOdabranKomitentId != ui.item.id )
							{
								s.gledanostOdabranKomitentId = ui.item.id;
								s.getKomitentBioskopi( s.gledanostOdabranKomitentId, 'izvestaj_gledanost_bioskop_select' );	
							}
						}
						
						return true;
				}
			}
			
			s.komitentImeAutocompleteOptions_l = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromName/",
				width:300,
				minLength: 2,
				select: function( event, ui ) {	
						
						if( ui.item )
						{
							$( "#lk-izvestaj-sifra-komitenta-input_l" ).attr( "value", ui.item.id );
							
							if( s.odabranKomitentId != ui.item.id )
							{
								s.odabranKomitentId = ui.item.id;	
							}
						}
						
						return true;
				}
			}
			
			
			
			s.komitentImeAutocompleteOptions_d = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromName/",
				width:300,
				minLength: 2,
				select: function( event, ui ) {	
						
						if( ui.item )
						{
							$( "#lk-izvestaj-sifra-komitenta-input_d" ).attr( "value", ui.item.id );
							
							if( s.odabranKomitentId != ui.item.id )
							{
								s.odabranKomitentId = ui.item.id;	
							}
						}
						
						return true;
				}
			}
			
			
			

			$( '#lk-izvestaj-sifra-komitenta-input_c' ).autocomplete( s.komitentSifraAutocompleteOptions_c );
			$( '#lk-izvestaj-ime-komitenta-input_c' ).autocomplete( s.komitentImeAutocompleteOptions_c );
			
			$( '#lk-izvestaj-ime-komitenta-input_l' ).autocomplete( s.komitentImeAutocompleteOptions_l );
			$( '#lk-izvestaj-sifra-komitenta-input_l' ).autocomplete( s.komitentSifraAutocompleteOptions_l );
			
			
			$( '#lk-izvestaj-sifra-komitenta-input_d' ).autocomplete( s.komitentSifraAutocompleteOptions_d );
			$( '#lk-izvestaj-ime-komitenta-input_d' ).autocomplete( s.komitentImeAutocompleteOptions_d );
			
			s.lkOdigraniFilmoviGrid = $("#iz-lk-odigrani-filmovi-grid").jqGrid({
							width:400,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkOdigraniFilmoviRead/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"film_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id',
									xmlmap:'film_id',
									hidden:true, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:150
							  }
							],
							// end of col model
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								
								return p;
							},
							
							
							pager: '#iz-lk-odigrani-filmovi-grid-pager',
							rowNum:25,
							rowList: [10,20,30, 60, 100, 200],
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[10,20,30],
							sortname: 'naziv_filma',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.odigrani_filmovi,
							
						  }).navGrid('#iz-lk-odigrani-filmovi-grid-pager',{ view:false, search: false, edit:false, del:false, refresh:true, add:false } ); 
					  // END OF ODIGRANI FILMOVI GRID
			
			s.lkFilmoviBezIzvestajaGrid  = $("#iz-lk-bez-izvestaja-grid").jqGrid({
							width:800,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkFilmoviBezIzvestajaRead/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"kopije_zakljucnice_id"
							},
							colModel:[ 
							  {
								  	label: s.base.app.config.lang.sifra_kopije,
									name: s.base.app.config.SCPN + 'kopije_zakljucnice_id', 
									index:'kopije_zakljucnice_id',
									xmlmap:'kopije_zakljucnice_id',
									hidden:true,  
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.sifra_zakljucnice,
									name: s.base.app.config.SCPN + 'zakljucnica_id', 
									index:'zakljucnica_id',
									xmlmap:'zakljucnica_id', 
									width:45
							  },
							  {
								    label: s.base.app.config.lang.sifra_rokovnika,
									name: s.base.app.config.SCPN + 'rokovnik_id', 
									index:'rokovnik_id',
									xmlmap:'rokovnik_id', 
									width:45
							  }, 
							  {
								    label: s.base.app.config.lang.serijski_broj_kopije,
									name: s.base.app.config.SCPN +  'serijski_broj_kopije', 
									index:'serijski_broj_kopije', 
									xmlmap:'serijski_broj_kopije',
									width:30
							  },
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:220
							  },
							  {
								    label: s.base.app.config.lang.bioskop,
									name: s.base.app.config.SCPN +  'bioskop', 
									index:'bioskop', 
									xmlmap:'bioskop',
									width:120
							  },
							  {
								    label: s.base.app.config.lang.datum,
									name: s.base.app.config.SCPN + 'datum', 
									index:'datum', 
									xmlmap:'datum',
									width:70
							  },
							  
							],
							// end of col model
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								
								return p;
							},
							
							
							emptyrecords: s.base.app.config.lang.nema_podataka,
							pager: '#iz-lk-bez-izvestaja-grid-pager',
							sortname: 'rokovnik_id',
							sortorder: 'desc',
							rowNum:30,
							rowList: [10,20,30, 60, 100, 200],
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.filmovi_bez_izvestaja,
							
						  }).navGrid('#iz-lk-bez-izvestaja-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} );
						  
						  
						  
						  // END OF FILMOVI BEZ IZVEŠTAJA GRID
						  
			s.lkProsecnaCenaKarteGrid = $("#iz-lk-top-lista-filmova-grid").jqGrid({
							width:600,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							sortable:false,
							url:s.base.config.baseUri + "izvestaji/lkTopListaFilmovaRead/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"film_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id',
									xmlmap:'film_id',
									hidden:true, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:250
							  }, 
							  {
								    label: s.base.app.config.lang.ukupan_prihod_bruto,
									name: s.base.app.config.SCPN +  'bruto_zarada', 
									index:'bruto_zarada', 
									xmlmap:'bruto_zarada',
									width:100
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod_neto,
									name: s.base.app.config.SCPN +  'neto_zarada', 
									index:'neto_zarada',
									xmlmap:'neto_zarada',
									width:100
							  },
							  {
								    label: s.base.app.config.lang.ukupno_gledalaca,
									name: s.base.app.config.SCPN +  'ukupno_gledalaca', 
									index:'ukupno_gledalaca', 
									xmlmap:'ukupno_gledalaca',
									width:70
							  }
							],
							// end of col model
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								
								return p;
							},
							
							pager: '#iz-lk-top-lista-filmova-grid-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							sortname: 'ukupno_gledalaca',
							sortorder: 'desc',
							rowNum:30,
							rowList: [10,20,30, 60, 100, 200],
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.top_lista_filmova,
							
						  }).navGrid('#iz-lk-top-lista-filmova-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} ); 		
						  // END OF FILMOVI BEZ IZVEŠTAJA GRID
						  			  
			s.lkOdigraniFilmoviSaIzvestajimaGrid = $("#iz-lk-filmovi-sa-izvestajima-grid").jqGrid({
							width:1800,
							height:450,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkFilmoviSaIzvestajimaRead/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"film_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id',
									xmlmap:'film_id',
									hidden:true, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:150
							  },
							  {
								    label: s.base.app.config.lang.datum_od,
									name: s.base.app.config.SCPN +  'datum_z_gledanost_od', 
									index:'datum_z_gledanost_od', 
									xmlmap:'datum_z_gledanost_od',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.datum_do,
									name: s.base.app.config.SCPN +  'datum_z_gledanost_do', 
									index:'datum_z_gledanost_do', 
									xmlmap:'datum_z_gledanost_do',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupno_gledalaca,
									name: s.base.app.config.SCPN +  'ukupno_gledalaca', 
									index:'ukupno_gledalaca', 
									xmlmap:'ukupno_gledalaca',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod,
								    label: s.base.app.config.lang.ukupan_prihod,
									name: s.base.app.config.SCPN +  'ukupan_prihod', 
									index:'ukupan_prihod', 
									xmlmap:'ukupan_prihod',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod_eur,
									name: s.base.app.config.SCPN +  'ukupan_prihod_eur', 
									index:'ukupan_prihod_eur', 
									xmlmap:'ukupan_prihod_eur',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod_karte,
									name: s.base.app.config.SCPN +  'ukupan_prihod_karte', 
									index:'ukupan_prihod_karte', 
									xmlmap:'ukupan_prihod_karte',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod_karte_eur,
									name: s.base.app.config.SCPN +  'ukupan_prihod_karte_eur', 
									index:'ukupan_prihod_karte_eur', 
									xmlmap:'ukupan_prihod_karte_eur',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod_naocare,
									name: s.base.app.config.SCPN +  'ukupan_prihod_naocare', 
									index:'ukupan_prihod_naocare', 
									xmlmap:'ukupan_prihod_naocare',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupan_prihod_naocare_eur,
									name: s.base.app.config.SCPN +  'ukupan_prihod_naocare_eur', 
									index:'ukupan_prihod_naocare_eur', 
									xmlmap:'ukupan_prihod_naocare_eur',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.za_placanje,
									name: s.base.app.config.SCPN +  'za_placanje', 
									index:'za_placanje', 
									xmlmap:'za_placanje',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.eurocent,
									name: s.base.app.config.SCPN +  'euro_centi', 
									index:'euro_centi', 
									xmlmap:'euro_centi',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.osnovica,
									name: s.base.app.config.SCPN +  'osnovica', 
									index:'osnovica', 
									xmlmap:'osnovica',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.uplate_total,
									name: s.base.app.config.SCPN +  'uplate_total', 
									index:'uplate_total', 
									xmlmap:'uplate_total',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.dugovna_strana,
									name: s.base.app.config.SCPN +  'dugovna_strana', 
									index:'dugovna_strana', 
									xmlmap:'dugovna_strana',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.kasnjenje,
									name: s.base.app.config.SCPN +  'kasnjenje', 
									index:'kasnjenje', 
									xmlmap:'kasnjenje',
									width:90,
									formatter: function currencyFmatter (cellvalue, options, rowObject){
									   
									   if( parseFloat( $( rowObject ).find( "dugovna_strana" ).text() ) > 0 )
									   {
										   return cellvalue;
									   }
									   else
									   {
										   return "n/a";
									   }
									}
							  },
							  {
								    label: s.base.app.config.lang.danasnji_datum,
									name: s.base.app.config.SCPN +  'danasnji_datum', 
									index:'danasnji_datum', 
									xmlmap:'danasnji_datum',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.prosecna_cena_karte,
									name: s.base.app.config.SCPN +  'prosek_cena_karte_rsd', 
									index:'prosek_cena_karte_rsd', 
									xmlmap:'prosek_cena_karte_rsd',
									width:90
							  }
							],
							// end of col model
							
							serializeGridData:function( p ){
								
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								
								return p;
							},
							
							pager: '#iz-lk-filmovi-sa-izvestajima-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList: [10,20,30, 60, 100, 200],
							sortname: 'naziv_filma',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption:  s.base.app.config.lang.odigrani_filmovi_sa_izvestajem,
							
						  }).navGrid('#iz-lk-filmovi-sa-izvestajima-pager',{view:true, search: false, edit:false, del:false, refresh:true, add:false} ); 				  
						  
				// END OF ODIGRANI FILMOVI GRID
				
				
			s.lkNefakturisaniIzvestajiGrid = $("#iz-lk-nefakturisani-izvestaji-grid").jqGrid({
							width:680,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkNefakturisaniFilmoviRead/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"komitent_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id',
									xmlmap:'film_id',
									hidden:true, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:200
							  }, 
							  {
								    label: s.base.app.config.lang.bruto_zarada,
									name: s.base.app.config.SCPN +  'bruto_zarada', 
									index:'bruto_zarada', 
									xmlmap:'bruto_zarada',
									width:120
							  }, 
							  {
								    label: s.base.app.config.lang.neto_zarada,
									name: s.base.app.config.SCPN +  'neto_zarada', 
									index:'neto_zarada', 
									xmlmap:'neto_zarada',
									width:120
							  }
							  
							],
							// end of col model
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								
								return p;
							},
							
							
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList: [10,20,30, 60, 100, 200],
							sortname: 'naziv_filma',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption:s.base.app.config.lang.nefakturisani_izvestaji,
							
						  })
				// END OF FILMOVI BEZ IZVEŠTAJA GRID
				
				
			s.lkNebukiraniFilmoviGrid =	$("#iz-lk-nebukirani-filmovi-grid").jqGrid({
							width:1100,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkNebukiraniFilmoviRead/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"komitent_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id',
									xmlmap:'film_id',
									hidden:false, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:130
							  },
							  {
								    label: s.base.app.config.lang.naziv_bioskopa,
									name: s.base.app.config.SCPN +  'naziv_bioskopa', 
									index:'naziv_bioskopa', 
									xmlmap:'naziv_bioskopa',
									width:100
							  }, 
							  {
								    label: s.base.app.config.lang.start_filma,
									name: s.base.app.config.SCPN +  'start_filma', 
									index:'start_filma', 
									xmlmap:'start_filma',
									width:50
							  }
							],
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								
								return p;
							},
							
							// end of col model
							
							pager:'#iz-lk-nebukirani-filmovi-grid-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList: [10,20,30, 60, 100, 200],
							sortname: 'film_id',
							sortorder: 'desc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.nebukirani_filmovi,
							
						  }).navGrid('#iz-lk-nebukirani-filmovi-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} );
						  
				// END OF FILMOVI BEZ IZVEŠTAJA GRID
			
				s.lkBioskopiIzvestaj =	$("#iz-lk-bioskopi-izvestaj-grid").jqGrid({
							width:830,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkIzvestajPoBioskopu/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"bioskop_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_bioskopa,
									name: s.base.app.config.SCPN + 'bioskop_id', 
									index:'bioskop_id',
									xmlmap:'bioskop_id',
									hidden:true, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.bioskop,
									name: s.base.app.config.SCPN +  'bioskop_alias_name', 
									index:'bioskop_alias_name', 
									xmlmap:'bioskop_alias_name',
									width:100
							  },
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:250
							  }, 
							  {
								    label: s.base.app.config.lang.bruto_zarada,
									name: s.base.app.config.SCPN +  'bruto_zarada', 
									index:'bruto_zarada', 
									xmlmap:'bruto_zarada',
									width:100
							  }, 
							  {
								    label: s.base.app.config.lang.neto_zarada,
									name: s.base.app.config.SCPN +  'neto_zarada', 
									index:'neto_zarada', 
									xmlmap:'neto_zarada',
									width:100
							  },
							  {
								    label: s.base.app.config.lang.ukupno_gledalaca, 
									name: s.base.app.config.SCPN +  'broj_gledalaca', 
									index:'broj_gledalaca', 
									xmlmap:'broj_gledalaca',
									width:70
							  }
							],
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tehnika_kopije_filma" ] = $( "#lk-tehnika-kopije-filter-select" ).val(),
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();

								return p;
							},
							
							// end of col model
							pager: '#iz-lk-bioskopi-izvestaj-pager',
							emptyrecords:  s.base.app.config.lang.nema_podatka,
							rowNum:30,
							rowList:[10,20,30, 60, 100, 200],
							sortname: 'bioskop_id',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.izvestaj_po_bioskopu,
							
						  }).navGrid('#iz-lk-bioskopi-izvestaj-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} );
				// END OF BIOSKOPI IZVESTAJ GRID

			
			s.lkBioskopiSumeIzvestaj =	$("#iz-lk-bioskopi-sume-izvestaj-grid").jqGrid({
							width:830,
							height:350,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/lkIzvestajPoBioskopu/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"bioskop_alias_id"
							},
							colModel :[ 
							  { 
								  	label: s.base.app.config.lang.sifra_aliasa,
									name: s.base.app.config.SCPN + 'bioskop_alias_id', 
									index:'bioskop_alias_id',
									xmlmap:'bioskop_alias_id',
									width:35
							  }, 
							  {
								  	label: s.base.app.config.lang.bioskop,
									name: s.base.app.config.SCPN +  'bioskop_alias_name', 
									index:'bioskop_alias_name', 
									xmlmap:'bioskop_alias_name',
									width:150
							  },
							  {
								  	label: s.base.app.config.lang.ukupno_gledalaca,
									name: s.base.app.config.SCPN +  'broj_gledalaca', 
									index:'broj_gledalaca', 
									xmlmap:'broj_gledalaca',
									width:150
							  },  
							  {
								  	label: s.base.app.config.lang.bruto_zarada,
									name: s.base.app.config.SCPN +  'bruto_zarada', 
									index:'bruto_zarada', 
									xmlmap:'bruto_zarada',
									width:100
							  }, 
							  {
								  	label: s.base.app.config.lang.neto_zarada,
									name: s.base.app.config.SCPN +  'neto_zarada', 
									index:'neto_zarada', 
									xmlmap:'neto_zarada',
									width:100
							  }
							],
							
							serializeGridData:function( p ){
								
								p[ "komitent_id" ] = s.odabranKomitentId;
								p[ "datum_kopije_od" ] = $( "#lk-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#lk-izvestaj-datum-do-input" ).val();
								p[ "tip_komitenta"] = $( "#izvestaj-lk-tip-komitenta-select" ).val();
								p[ "tehnika_kopije_filma" ] = $( "#lk-tehnika-kopije-filter-select" ).val(),
								p[ "izvestaj_lk_bioskop_select"] = $( "#izvestaj_lk_bioskop_select" ).val();
								p[ "sub_sume"] = true;
								
								return p;
							},
							
							// end of col model
							pager: '#iz-lk-bioskopi-sume-izvestaj-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[10,20,30],
							sortname: 'bioskop_id',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.izvestaj_po_bioskopu_sumarno,
							
						  }).navGrid('#iz-lk-bioskopi-sume-izvestaj-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} );
				// END OF BIOSKOPI SUME IZVESTAJ GRID
				
				
				
				
			$( "#lk-izvestaj-generisi-button" ).click( function( e ) {
			
				s.lkOdigraniFilmoviGrid.setGridParam( { datatype: 'xml' } );
				s.lkFilmoviBezIzvestajaGrid.setGridParam( { datatype: 'xml' } );		
				s.lkProsecnaCenaKarteGrid.setGridParam( { datatype: 'xml' } );	
				s.lkOdigraniFilmoviSaIzvestajimaGrid.setGridParam( { datatype: 'xml' } );
				s.lkNefakturisaniIzvestajiGrid.setGridParam( { datatype: 'xml' } );
				s.lkNebukiraniFilmoviGrid.setGridParam( { datatype: 'xml' } );
				s.lkBioskopiIzvestaj.setGridParam( { datatype: 'xml' } );
				s.lkBioskopiSumeIzvestaj.setGridParam( { datatype: 'xml' } );
				
			
				s.lkOdigraniFilmoviGrid.trigger( "reloadGrid" );
				s.lkFilmoviBezIzvestajaGrid.trigger( "reloadGrid" );		
				s.lkProsecnaCenaKarteGrid.trigger( "reloadGrid" );	
				s.lkOdigraniFilmoviSaIzvestajimaGrid.trigger( "reloadGrid" );
				s.lkNefakturisaniIzvestajiGrid.trigger( "reloadGrid" );
				s.lkNebukiraniFilmoviGrid.trigger( "reloadGrid" );
				s.lkBioskopiIzvestaj.trigger( "reloadGrid" );
				s.lkBioskopiSumeIzvestaj.trigger( "reloadGrid" );
				
				
				var sd = {	komitent_id : s.odabranKomitentId,
							datum_kopije_od : $( "#lk-izvestaj-datum-od-input" ).val(),
							datum_kopije_do : $( "#lk-izvestaj-datum-do-input" ).val(),
							tehnika_kopije_filma : $( "#lk-tehnika-kopije-filter-select" ).val(),
							izvestaj_lk_bioskop_select: $( "#izvestaj_lk_bioskop_select" ).val()
						 }
				
						 
				$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "izvestaji/getSumeFilmovaSaIzvestajima/",
					data:sd,
					success: function( data )
					{				
						var gled = $( data ).find( "ukupno_gledalaca" ).text();
						var gled_n = $( data ).find( "ukupno_prodato_naocara" ).text();
						var up = $( data ).find( "ukupan_prihod" ).text();
						var upe = $( data ).find( "ukupan_prihod_eur" ).text();
						var upk = $( data ).find( "ukupan_prihod_karte" ).text();
						var upke = $( data ).find( "ukupan_prihod_karte_eur" ).text();
						var upn = $( data ).find( "ukupan_prihod_naocare" ).text();
						var upne = $( data ).find( "ukupan_prihod_naocare_eur" ).text();
						var zp = $( data ).find( "za_placanje" ).text();
						var o = $( data ).find( "najamnina_bez_pdv" ).text();
						var ut = $( data ).find( "uplate_total" ).text();
						var ds = $( data ).find( "dugovna_strana" ).text();
						
						var title = '<table align="center" cellpadding="9" cellspacing="0" border="1"><tr><td align="center" colspan="12"><b>' + s.base.app.config.lang.odigrani_filmovi_sa_izvestajem + '</b></td></tr><tr>';
						
							title += '<td align="center">' + s.base.app.config.lang.odigrani_filmovi_sa_izvestajem + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.najamnina_sa_pdv + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.najamnina_bez_pdv + '</td>';
							
							title += '<td align="center">' + s.base.app.config.lang.gledanost + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.gledanost_naocare + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.placeno + '</td>'
							title += '<td align="center">' + s.base.app.config.lang.dugovna_strana + '</td>';
							
							title += '<td align="center">' + s.base.app.config.lang.ukupan_prihod_eur + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.ukupan_prihod_karte + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.ukupan_prihod_karte_eur + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.ukupan_prihod_naocare + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.ukupan_prihod_naocare_eur + '</td></tr><tr>';
							
							
							
							
							title += '<td align="center"><b>' + up +     '</b></td>';
							title += '<td align="center"><b>' + zp +     '</b></td>';
							title += '<td align="center"><b>' + o +      '</b></td>';
							
						 	title += '<td align="center"><b>'  + gled +  '</b></td>';
							title += '<td align="center"><b>' + gled_n + '</b></td>';
							
							title += '<td align="center"><b>' + ut +     '</b></td>';
							title += '<td align="center"><b>' + ds +     '</b></td>';
							
							title += '<td align="center"><b>' + upe +    '</b></td>';
							title += '<td align="center"><b>' + upk +    '</b></td>';
							title += '<td align="center"><b>' + upke +   '</b></td>';
							title += '<td align="center"><b>' + upn +    '</b></td>';
							title += '<td align="center"><b>' + upne +   '</b></td>';
							
							
							
							title += '</tr></table>';
								
						var g =	$( "#gbox_iz-lk-filmovi-sa-izvestajima-grid" );
						
						$( g ).find( ".ui-widget-header" ).css( "font-weight", "normal" );
						$( g ).find( ".ui-jqgrid-title" ).html( title );
						
					}
				});
				
				
				$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "izvestaji/getSumeIzvestajiPoBioskopu/",
					data:sd,
					success: function( data )
					{				
						
						var bruto = $( data ).find( "bruto_zarada" ).text();
						var neto = $( data ).find( "neto_zarada" ).text();
						var gledanost = $( data ).find( "broj_gledalaca" ).text();

					
						var title = '<table align="center" cellpadding="9" cellspacing="0" border="1"><tr><td align="center" colspan="3"><b>' + s.base.app.config.lang.total + '</b></td></tr><tr>';
						
							title += '<td align="center">' + s.base.app.config.lang.bruto + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.neto + '</td>';
							title += '<td align="center">' + s.base.app.config.lang.ukupno_gledalaca + '</td></tr>';

							title += '<tr><td align="center"><b>' + bruto +  '</b></td>';
							title += '<td align="center"><b>' + neto +  '</b></td>';
							title += '<td align="center"><b>' + gledanost +  '</b></td>';
							
							title += '</tr></table>';
							
								
						var g =	$( "#gbox_iz-lk-bioskopi-sume-izvestaj-grid" );
						
						
						$( g ).find( ".ui-widget-header" ).css( "font-weight", "normal" );
						$( g ).find( ".ui-jqgrid-title" ).html( title );
						
					}
				});
				
				return false;
			
			});	
						  
		}
	 });
}


Izvestaji.prototype.initProcentiReport = function()
{
	var s  = this;
	
	s.prSifraFilmaAutoCompleteOptions = 
	{
		
		source: s.base.config.baseUri + "filmovi/suggestFromId/",
		width:300,
		minLength: 1,
		select: function( event, ui ) 
		{	
			if( ui.item )
			{
				$( "#procenti-izvestaj-ime-filma-input" ).attr( "value" , ui.item.label );
			
			}
				
			return true;
		}
	}
	
	
	s.prImeFilmaAutoCompleteOptions = 
	{
		source: s.base.config.baseUri + "filmovi/suggestFromName/",
		width:300,
		minLength: 2,
		select: function( event, ui ) {	
				
				if( ui.item )
				{
					$( "#procenti-izvestaj-sifra-filma-input" ).attr( "value", ui.item.id );
				
				}
				
				return true;
		}
	}
	
	s.prSifraKomitentaAutoCompleteOptions = 
	{
		
		source: s.base.config.baseUri + "komitenti/suggestFromId/",
		width:300,
		minLength: 1,
		select: function( event, ui ) 
		{	
			if( ui.item )
			{
				$( "#procenti-izvestaj-ime-komitenta-input" ).attr( "value" , ui.item.label );
			
			}
				
			return true;
		}
	}
	
	
	s.prImeKomitentaAutoCompleteOptions = 
	{
		source: s.base.config.baseUri + "komitenti/suggestFromName/",
		width:300,
		minLength: 2,
		select: function( event, ui ) {	
				
				if( ui.item )
				{
					$( "#procenti-izvestaj-sifra-komitenta-input" ).attr( "value", ui.item.id );
				
				}
				
				return true;
		}
	}
	
	
	$( '#procenti-izvestaj-sifra-filma-input' ).autocomplete( s.prSifraFilmaAutoCompleteOptions );
	$( '#procenti-izvestaj-ime-filma-input' ).autocomplete( s.prImeFilmaAutoCompleteOptions );
	
	$( '#procenti-izvestaj-sifra-komitenta-input' ).autocomplete( s.prSifraKomitentaAutoCompleteOptions );
	$( '#procenti-izvestaj-ime-komitenta-input' ).autocomplete( s.prImeKomitentaAutoCompleteOptions );
	
	
	$( "#izvestaj-procenti-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
	$( "#izvestaj-procenti-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
					
}


Izvestaji.prototype.initFnReport = function()
{
	var s = this;
	if( ! s.finansijskiIzvestajGrid )
	{
		s.finSifraFilmaAutoCompleteOptions = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromId/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#finansijski-izvestaj-ime-filma-input" ).attr( "value" ,ui.item.label );
					s.finPrometOdabraniFilmId = ui.item.id;
					
				}
				else
				{
					s.finPrometOdabraniFilmId = 0;
				}
					
				return true;
			}
		}
			
		
		s.finImeFilmaAutoCompleteOptions = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromName/",
			width:300,
			minLength: 2,
			select: function( event, ui ) {	
					
					if( ui.item )
					{
						$( "#finansijski-izvestaj-sifra-filma-input" ).attr( "value", ui.item.id );
						s.finPrometOdabraniFilmId = ui.item.id;
						
					}
					else
					{
						s.finPrometOdabraniFilmId = 0;
					}
					return true;
			}
		}
		
		s.sifraFilmaAutoCompleteOptions_b = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromId/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#lk-izvestaj-ime-filma-input_b" ).attr( "value" ,ui.item.label );
					
				}
					
				return true;
			}
		}
		
		
		s.sifraFilmaAutoCompleteOptions_c = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromId/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#lk-izvestaj-ime-filma-input_c" ).attr( "value" ,ui.item.label );
				}
					
				return true;
			}
		}
		
		s.sifraFilmaAutoCompleteOptions_l = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromId/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#lk-izvestaj-ime-filma-input_l" ).attr( "value" ,ui.item.label );
				}
					
				return true;
			}
		}
		
		
		s.sifraFilmaAutoCompleteOptions_d = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromId/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#lk-izvestaj-ime-filma-input_d" ).attr( "value" ,ui.item.label );
					
				}
					
				return true;
			}
		}
		
		 s.imeProducentaAutoCompleteOptions_d = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestProducentFromName/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#lk-izvestaj-ime-producenta-input_d" ).attr( "value" ,ui.item.label );

				}
					
				return true;
			}
		}
		
		 s.imeProducentaAutoCompleteOptions_l = 
			{
				
				source: s.base.config.baseUri + "filmovi/suggestFromNameP/",
				width:300,
				minLength: 1,
				select: function( event, ui ) 
				{	
					if( ui.item )
					{
						$( "#lk-izvestaj-ime-producenta-input_l" ).attr( "value" ,ui.item.label );

					}
						
					return true;
				}
			}
		
		
		s.sifraFilmaAutoCompleteOptions_b_org = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromIdOrg/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					$( "#lk-izvestaj-ime-filma-input_b_org" ).attr( "value" ,ui.item.label );
					
				}
					
				return true;
			}
		}
		
		
		s.imeFilmaAutoCompleteOptions_b = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromName/",
			width:300,
			minLength: 2,
			select: function( event, ui ) {	
					
					if( ui.item )
					{
						$( "#lk-izvestaj-sifra-filma-input_b" ).attr( "value", ui.item.id );
						
					}
					
					return true;
			}
		}
		
		s.imeFilmaAutoCompleteOptions_c = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromName/",
			width:300,
			minLength: 2,
			select: function( event, ui ) {	
					
					if( ui.item )
					{
						$( "#lk-izvestaj-sifra-filma-input_c" ).attr( "value", ui.item.id );
					}
					
					return true;
			}
		}
		
		s.imeFilmaAutoCompleteOptions_l = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromName/",
			width:300,
			minLength: 2,
			select: function( event, ui ) {	
					
					if( ui.item )
					{
						$( "#lk-izvestaj-sifra-filma-input_l" ).attr( "value", ui.item.id );
					}
					
					return true;
			}
		}
		
		
		s.imeFilmaAutoCompleteOptions_d = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromName/",
			width:300,
			minLength: 2,
			select: function( event, ui ) {	
					
					if( ui.item )
					{
						$( "#lk-izvestaj-sifra-filma-input_d" ).attr( "value", ui.item.id );
					}
					
					return true;
			}
		}
		
		
		
		s.imeFilmaAutoCompleteOptions_b_org = 
		{
			
			source: s.base.config.baseUri + "filmovi/suggestFromNameOrg/",
			width:300,
			minLength: 2,
			select: function( event, ui ) {	
					
					if( ui.item )
					{
						$( "#lk-izvestaj-sifra-filma-input_b" ).attr( "value", ui.item.id );
					}
					
					return true;
			}
		}
		
		
		
	
	
		$( '#finansijski-izvestaj-sifra-filma-input' ).autocomplete( s.finSifraFilmaAutoCompleteOptions );
		$( '#finansijski-izvestaj-ime-filma-input' ).autocomplete( s.finImeFilmaAutoCompleteOptions );
		
		$( '#lk-izvestaj-sifra-filma-input_b' ).autocomplete( s.sifraFilmaAutoCompleteOptions_b);
		$( '#lk-izvestaj-ime-filma-input_b' ).autocomplete( s.imeFilmaAutoCompleteOptions_b);
		
		$( '#lk-izvestaj-sifra-filma-input_c' ).autocomplete( s.sifraFilmaAutoCompleteOptions_c);
		$( '#lk-izvestaj-ime-filma-input_c' ).autocomplete( s.imeFilmaAutoCompleteOptions_c);
		
		$( '#lk-izvestaj-sifra-filma-input_l' ).autocomplete( s.sifraFilmaAutoCompleteOptions_l);
		$( '#lk-izvestaj-ime-filma-input_l' ).autocomplete( s.imeFilmaAutoCompleteOptions_l);
		
		
		$( '#lk-izvestaj-sifra-filma-input_d' ).autocomplete( s.sifraFilmaAutoCompleteOptions_d);
		$( '#lk-izvestaj-ime-filma-input_d' ).autocomplete( s.imeFilmaAutoCompleteOptions_d);
		
		$( '#lk-izvestaj-ime-producenta-input_d' ).autocomplete( s.imeProducentaAutoCompleteOptions_d);
		
		$( '#lk-izvestaj-ime-producenta-input_l' ).autocomplete( s.imeProducentaAutoCompleteOptions_l);
		
		$( '#lk-izvestaj-ime-filma-input_b_org' ).autocomplete( s.imeFilmaAutoCompleteOptions_b_org);
		
	
		$( '#lk-izvestaj-ime-producenta-input_d' ).autocomplete( s.imeProducentaAutoCompleteOptions_d);
		
		$( '#lk-izvestaj-ime-producenta-input_l' ).autocomplete( s.imeProducentaAutoCompleteOptions_l);
		
		
	
		
	
		$( "#finansijski-izvestaj-datum-od-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
		$( "#finansijski-izvestaj-datum-do-input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	

	
      
		
		s.finansijskiIzvestajGrid = $("#iz-lk-finansijski-izvestaj-grid").jqGrid({
							width:1650,
							height:650,
							datatype: 'local',
							mtype: 'POST',
							url:s.base.config.baseUri + "izvestaji/finansijskiPrometFilma/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"film_id"
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id',
									xmlmap:'film_id',
									hidden:true, 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN +  'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:150
							  },
							  {
								    label: s.base.app.config.lang.producent,
									name: s.base.app.config.SCPN +  'producent_filma', 
									index:'producent_filma', 
									xmlmap:'producent_filma',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.start_filma,
									name: s.base.app.config.SCPN +  'start_filma', 
									index:'start_filma', 
									xmlmap:'start_filma',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.naziv_komitenta,
									name: s.base.app.config.SCPN +  'naziv_komitenta', 
									index:'naziv_komitenta', 
									xmlmap:'naziv_komitenta',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.naziv_bioskopa,
									name: s.base.app.config.SCPN +  'naziv_bioskopa', 
									index:'naziv_bioskopa', 
									xmlmap:'naziv_bioskopa',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.broj_dokumenta,
									name: s.base.app.config.SCPN +  'broj_dokumenta_fakture', 
									index:'broj_dokumenta_fakture', 
									xmlmap:'broj_dokumenta_fakture',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.datum_od,
									name: s.base.app.config.SCPN +  'datum_z_gledanost_od_stampa', 
									index:'datum_z_gledanost_od', 
									xmlmap:'datum_z_gledanost_od_stampa',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.datum_do,
									name: s.base.app.config.SCPN +  'datum_z_gledanost_do_stampa', 
									index:'datum_z_gledanost_do', 
									xmlmap:'datum_z_gledanost_od_stampa',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.projekcija,
									name: s.base.app.config.SCPN +  'broj_termina', 
									index:'broj_termina', 
									xmlmap:'broj_termina',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.oznaka,
									name: s.base.app.config.SCPN +  'oznaka_kopije_filma', 
									index:'oznaka_kopije_filma', 
									xmlmap:'oznaka_kopije_filma',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.serijski_broj_kopije,
									name: s.base.app.config.SCPN +  'serijski_broj_kopije', 
									index:'serijski_broj_kopije', 
									xmlmap:'serijski_broj_kopije',
									width:90
							  },
							  {
								    label: s.base.app.config.lang.bruto,
									name: s.base.app.config.SCPN +  'bruto', 
									index:'bruto', 
									xmlmap:'bruto',
									formatter:'currency',
									formatoptions: {thousandsSeparator:'.',decimalSeparator:','},
									width:90
							  },
							  {
								    label: s.base.app.config.lang.sa_pdv,
									name: s.base.app.config.SCPN +  'neto_sa_pdv', 
									index:'neto_sa_pdv', 
									xmlmap:'neto_sa_pdv',
									formatter:'currency',
									formatoptions: {thousandsSeparator:'.',decimalSeparator:','},
									width:90
							  },
							  {
								    label: s.base.app.config.lang.bez_pdv,
									name: s.base.app.config.SCPN +  'neto',
									index:'neto', 
									xmlmap:'neto',
									formatter:'currency',
									formatoptions: {thousandsSeparator:'.',decimalSeparator:','},
									width:90
							  },
							  {
								    label: s.base.app.config.lang.ukupno_gledalaca,
									name: s.base.app.config.SCPN +  'broj_gledalaca', 
									index:'broj_gledalaca', 
									xmlmap:'broj_gledalaca',
									width:90
							  },
							  {						  
							        label: s.base.app.config.lang.raspodela_iznos,
									name: s.base.app.config.SCPN +  'raspodela_iznos', 
									index:'raspodela_iznos', 
									xmlmap:'raspodela_iznos',
									width:90
	
							  },
							  {
								    label: s.base.app.config.lang.datum_prometa,
									name: s.base.app.config.SCPN +  'datum_prometa', 
									index:'datum_prometa', 
									xmlmap:'datum_prometa',
									width:90
							  }
							],
							// end of col model
							
							serializeGridData:function( p ){
								
								p[ "film_id" ] = s.finPrometOdabraniFilmId;
								p[ "tip_komitenta"] = $( "#izvestaj-finansijski-tip-komitenta-select" ).val();
								p[ "datum_kopije_od" ] = $( "#finansijski-izvestaj-datum-od-input" ).val();
								p[ "datum_kopije_do"] = $( "#finansijski-izvestaj-datum-do-input" ).val();
								
								return p;
							},
							
							pager: '#iz-lk-finansijski-izvestaj-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[ 10, 30, 60, 100, 200],
							sortname: 'naziv_filma',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.finansijski_promet_filmova,
							
						  }).navGrid('#iz-lk-finansijski-izvestaj-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} ); 				  
						  
				// END OF FINANSIJSKI IZVESTAJ GRID
		
		
		
				
				$( "#lk-izvestaj-fin-promet-generisi-button" ).click( function( e ) {
					
					s.finansijskiIzvestajGrid.setGridParam( { datatype: "xml" } );
					s.finansijskiIzvestajGrid.trigger( "reloadGrid" );
					
					var sd = {	film_id : s.finPrometOdabraniFilmId,
								datum_kopije_od : $( "#finansijski-izvestaj-datum-od-input" ).val(),
								datum_kopije_do : $( "#finansijski-izvestaj-datum-do-input" ).val(),
								tip_komitenta:$( "#izvestaj-finansijski-tip-komitenta-select" ).val(),
								sub_sume: true
						 }
						
						 
					$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "izvestaji/finansijskiPrometFilma/",
					data:sd,
					success: function( data ) {				
							
							var bruto = $( data ).find( "suma_bruto" ).text();
							var sa_pdv = $( data ).find( "suma_neto_sa_pdv" ).text();
							var bez_pdv = $( data ).find( "suma_neto" ).text();
							var gledanost = $( data ).find( "suma_gledalaca" ).text();
	
							var title = '<table align="center" cellpadding="9" cellspacing="0" border="1"><tr><td align="center" colspan="4"><b>' + s.base.app.config.lang.finansijski_promet_filmova + '</b></td></tr><tr>';
							
								title += '<td align="center">' + s.base.app.config.lang.bruto + '</td>';
								title += '<td align="center">' + s.base.app.config.lang.sa_pdv + '</td>';
								title += '<td align="center">' + s.base.app.config.lang.bez_pdv + '</td>';
								title += '<td align="center">' + s.base.app.config.lang.ukupno_gledalaca + '</td></tr>';
	
								
								title += '<tr><td align="center"><b>' + bruto +  '</b></td>';
								title += '<td align="center"><b>' + sa_pdv +  '</b></td>';
								title += '<td align="center"><b>' + bez_pdv +  '</b></td>';
								title += '<td align="center"><b>' + gledanost +  '</b></td>';
								
								title += '</tr></table>';
								
									
							var g =	$( "#gbox_iz-lk-finansijski-izvestaj-grid" );
							
							$( g ).find( ".ui-widget-header" ).css( "font-weight", "normal" );
							$( g ).find( ".ui-jqgrid-title" ).html( title );
							
						}
					});
				
				
					return false;
					
				});
				
	}
}



