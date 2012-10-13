
var Zakljucnice = function( app )
{	
	var s = this;
	
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.zakljucniceGrid = null;
	this.odabranaZakljucnicaRowId = null;
	this.odabranaZakljucnicaId = null;
	this.kopijeZakljucniceGrid = null;
	
	this.bioskopiKopijeEditSelectOptions = null;
	
	this.novaZakljucnicaKalendar = null;
	this.kopijeZakljucniceDialog = null;
	this.naprednaPretragaDialog = null;
	
	
	this.odabranKomitentId = null;
	this.komitentSifraAutocompleteOptions = null;
	this.komitentImeAutocompleteOptions = null;
	this.selectedKomitentLabel = "";
	this.zakljucnicaOperation;
	
	this.novaKopijaTableElement = null;
	
	this.odabraniFilmId;
	
	this.zakljucnicaSacuvana = false;
	this.odabranaKopijaZakljucniceRowId = null;
	
	this.kopijePreviewKalendar = null;
	
	this.kopijaCalendarOptions = {};
	
	this.odabranaKopijaZakljucniceId = null;
	
	this.kopijeFilmaJqXHR = null;
	
	this.kopijaEventsUri = null;
	
	this.odabraniKomitentData = {};
	
	this.advancedSearch = false;
	
	
	this.prikaziZakljucnice = function()
	{
		var fids = 	s.zakljucniceGrid.jqGrid( "getGridParam", "selarrrow" );
		var fsids = "";
		
		$( fids ).each(function(index, element){
            fsids += fids[index] + ",";
        });
		
		$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "zakljucnice/encodeIds/",
				data:{ zakljucniceiIds: fsids.substring( -1, fsids.length - 1 ) },
				success: function(data)
				{
					window.open( s.base.config.baseUri + "zakljucnice/prikaziZakljucnice/?zakljucnice=" + data, "_blank" );
				}
			});
	}
	
	this.obrisiKopijuZakljucnice = function()
	{	
		if( s.odabranaKopijaZakljucniceRowId )
		{
			if( confirm( s.base.app.config.lang.obrisi_kopiju_upozorenje ) )
			{
				$.ajax({
						type: 'post',
						url: s.base.config.baseUri + "zakljucnice/deleteKopijaZakljucnice/" + s.odabranaKopijaZakljucniceRowId,
						success: function(data)
						{
							if( data == 0 )
							{
								s.kopijeZakljucniceGrid.trigger( "reloadGrid" );
							}
							else
							{
								alert( s.base.app.config.lang.dogodila_se_greska );
							}
						}
					});
			}
		}
		else
		{
			alert( s.base.app.config.lang.odaberi_kopiju );
		}
	};
	
}

Zakljucnice.prototype.init = function()
{
	var s = this;
	
	s.kopijaZakljucnicePostData = function( p ){
		
		p[ "komitent_id" ] =  s.odabranKomitentId;
		p[ "primenjen_porez_komitenta" ] = s.odabraniKomitentData.ppk;
		
		return p;
	}

	$.ajax({
 		type: 'get',
 		url: s.base.config.baseUri + "zakljucnice/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			$( "#preview-zakljucnice-btn" ).click( s.prikaziZakljucnice );
			$( "#obrisi-kopiju-zakljucnice-btn" ).click( s.obrisiKopijuZakljucnice );
			
			var buttons = {};
				
			buttons[ s.base.app.config.lang.trazi ]	= function() {
										s.advancedSearch = true;
										s.zakljucniceGrid.trigger( "reloadGrid" );	
									};
									
			buttons[ s.base.app.config.lang.resetuj ] = function() {
										
										s.advancedSearch = false;
										$('#zakljucnice-pretraga-form').each (function(){
											  this.reset();
										});
										
										setTimeout(function(){
											
											s.zakljucniceGrid.trigger( "reloadGrid" );
										}, 100 );
										
				};											
			
			s.naprednaPretragaDialog = $( "#zakljucnice-napredna-pretraga-dialog" ).dialog({
							title: s.base.app.config.lang.napredna_pretraga,
							autoOpen: false,
							height: 370,
							width: 500,
							modal: false,
							buttons:buttons
			});

			$( "#napredna-pretraga-zakljucnice-button" ).click(function() {
				s.naprednaPretragaDialog.dialog( "open" );
			});

			s.kopijaCalendarOptions = { width:400, height:500, monthNames: s.base.config.monthNames };
			
			s.komitentSifraAutocompleteOptions = 
			{
				
				source: s.base.config.baseUri + "komitenti/suggestFromId/",
				width:300,
				minLength: 1,
				select: function( event, ui ) 
				{	
					if( ui.item )
					{
						$( "#naziv-komitenta-zakljucnice" ).attr( "value" ,ui.item.label );
						
						if( s.odabranKomitentId != ui.item.id )
						{
							s.odabraniKomitentData.ppk = ui.item.primenjen_porez_komitenta;
							s.odabraniKomitentData.rmf = ui.item.raspodela_maticna_firma;
							s.odabraniKomitentData.rp = ui.item.raspodela_prikazivac;
							s.odabraniKomitentData.trk = ui.item.tip_raspodele_komitenta;
							
							s.odabranKomitentId = ui.item.id;
							s.getKomitentBioskopi( s.odabranKomitentId );
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
							$( "#sifra-komitenta-zakljucnice" ).attr( "value", ui.item.id );
							
							if( s.odabranKomitentId != ui.item.id )
							{
								s.odabraniKomitentData.ppk = ui.item.primenjen_porez_komitenta;
								s.odabraniKomitentData.rmf = ui.item.raspodela_maticna_firma;
								s.odabraniKomitentData.rp = ui.item.raspodela_prikazivac;
								s.odabraniKomitentData.trk = ui.item.tip_raspodele_komitenta;
								
								s.odabranKomitentId = ui.item.id;	
								s.getKomitentBioskopi( s.odabranKomitentId );
							}
						}
						
						return true;
				}
			}
	
	
			 s.zakljucniceGrid = $("#zakljucnice-lista-grid").jqGrid({
				 
							width:1200,
							height:670,
							url: s.base.config.baseUri + "zakljucnice/read/",
							multiselect:true,
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							mtype: 'POST',
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
								  repeatitems:false,
								  id: "zakljucnica_id"
							},
							colModel :[ 
							  {
								  	label: s.base.app.config.lang.sifra,
									name: s.base.app.config.SCPN + 'zakljucnica_id', 
									index: 'zakljucnica_id', 
									xmlmap:'zakljucnica_id',
									width:10
							  }, 
							  {
								  	label: s.base.app.config.lang.sifra_komitenta,
									name: s.base.app.config.SCPN +  'komitent_id', 
									index:'komitent_id',
									xmlmap:'komitent_id', 
									width:10, 
									hidden:true 
							  }, 
							  {
								  label: s.base.app.config.lang.naziv_komitenta,
								  name:s.base.app.config.SCPN +  'naziv_komitenta', 
								  index:'naziv_komitenta',
								  xmlmap:'naziv_komitenta', 
								  width:50
							  },
							  {
								  label: s.base.app.config.lang.broj_dokumenta,
								  name: s.base.app.config.SCPN + 'broj_dokumenta_zakljucnice', 
								  index:'broj_dokumenta_zakljucnice',
								  xmlmap:'broj_dokumenta_zakljucnice', 
								  width:20
							  },
							   {
								  label: s.base.app.config.lang.datum,
								  name: s.base.app.config.SCPN + 'datum_zakljucnice_stampa', 
								  xmlmap:'datum_zakljucnice', 
								  formatter:'date',
								  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
								  width:20
							  },
							  {
								  label: s.base.app.config.lang.datum,
								  name: s.base.app.config.SCPN + 'datum_zakljucnice', 
								  xmlmap:'datum_zakljucnice',
								  hidden:true,
								  width:20
							  },
							  {
								  label: s.base.app.config.lang.primenjen_porez,
								  name: s.base.app.config.SCPN + 'primenjen_porez_komitenta', 
								  index:'primenjen_porez_komitenta',
								  xmlmap:'primenjen_porez_komitenta', 
								  width:20,
								  hidden:true
							  },
							  {
								  label: s.base.app.config.lang.raspodela_maticne_firme,
								  name: s.base.app.config.SCPN + 'raspodela_maticna_firma', 
								  index:'raspodela_maticna_firma',
								  xmlmap:'raspodela_maticna_firma', 
								  width:20,
								  hidden:true
							  },
							  {
								  label: s.base.app.config.lang.raspodela_prikazivac,
								  name: s.base.app.config.SCPN + 'raspodela_prikazivac', 
								  index:'raspodela_prikazivac',
								  xmlmap:'raspodela_prikazivac', 
								  width:20,
								  hidden:true
							  },
							  {
								  label: s.base.app.config.lang.tip_raspodele,
								  name: s.base.app.config.SCPN + 'tip_raspodele_komitenta', 
								  index:'tip_raspodele_komitenta',
								  xmlmap:'tip_raspodele_komitenta', 
								  width:20,
								  hidden:true
							  },
							  {
								  label: s.base.app.config.lang.broj_kopija,
								  name: s.base.app.config.SCPN + 'broj_kopija_zakljucnice', 
								  index:'broj_kopija_zakljucnice',
								  xmlmap:'broj_kopija_zakljucnice',
								  sortable:false, 
								  width:20
							  }  
							  
							],
							// end of col model

							onCellSelect:function( rowid, iCol, cellcontent, e ){
								
								if( rowid != s.odabranaZakljucnicaRowId )
								{
									s.odabranaZakljucnicaRowId = rowid;
									
									var rd = s.zakljucniceGrid.getRowData( s.odabranaZakljucnicaRowId );
									s.odabranaZakljucnicaId = rd[ s.base.app.config.SCPN + "zakljucnica_id"];
									
								}
								
							},
							
							loadComplete:function(){
								s.odabranaZakljucnicaRowId = null;
								s.odabranaZakljucnicaId = null;
							},
							
							serializeGridData:function( p ){
								
								if( s.advancedSearch == true )
								{
									console.log( 'serializing grid data' );
									p[ 'advanced_search' ] = true;
									$( "#zakljucnice-pretraga-form .pretraga_input" ).each(function(index, element) {
										p[ element.name ] = element.value;
									});
								}
								else
								{
									p[ 'advanced_search' ] = false;
									$( "#zakljucnice-pretraga-form .pretraga_input" ).each(function(index, element) {
										p[ element.name ] = '';
									});
								}
									
								return p;
							},
							
							pager: '#zakljucnice-grid-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[10,20,30, 60, 100, 200],
							sortname: 'zakljucnica_id',
							sortorder: 'desc',
							viewrecords: true,
							gridview: true,
							caption:  s.base.app.config.lang.zakljucnice
							
						  }).navGrid('#zakljucnice-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} ); ;
						  // END OF ZAKLJUCNICE LISTA GRID
				
				 $("#zakljucnice_pretraga_submit" ).click(function( e ) {
			
						s.zakljucniceGrid.trigger( "reloadGrid" );						
					});					  
					
					$("#zakljucnice_pretraga_reset" ).click(function( e ) {
			
						setTimeout(function(){
							s.zakljucniceGrid.trigger( "reloadGrid" );
						}, 100 );
						
					});
					
					 $( "#zakljucnice-pretraga-form .pretraga_input" ).keydown(function(e) {
                        
						if( e.keyCode  == 13 )
						{
							s.zakljucniceGrid.trigger( "reloadGrid" );
						}
                    });
					
				$( "#dodaj-kopiju-zakljucnice" ).attr( "disabled", true );
				$( "#promeni-kopiju-zakljucnice" ).attr( "disabled", true );
				$( "#obrisi-kopiju-zakljucnice" ).attr( "disabled", true );
					
				$( "#datum-zakljucnice-input-stampa" ).datepicker( { altField:'#datum-zakljucnice-input', altFormat: 'yy-mm-dd', dateFormat: 'dd/mm/yy', monthNames:s.base.config.monthNames } );
				$( "#datum-zakljucnice-input-stampa" ).attr( "autocomplete", "off" );
				
				$( "#datum_zakljucnice_pretraga_input" ).attr( "autocomplete", "off" );
				$( "#datum_zakljucnice_pretraga_input" ).datepicker( { dateFormat:"yy-mm-dd", monthNames:s.base.config.monthNames } );
				
				
		  		$( '#sifra-komitenta-zakljucnice' ).autocomplete( s.komitentSifraAutocompleteOptions );
				$( '#naziv-komitenta-zakljucnice' ).autocomplete( s.komitentImeAutocompleteOptions );
				
				s.novaZakljucnicaKalendar = $('#nova-zakljucnica-kalendar').fullCalendar({
										height:600,
										monthNames:s.base.config.monthNames
								});

				s.kopijeZakljucniceGrid = $("#kopije-zakljucnice-lista-grid").jqGrid({
									width:950,
									height:450,
									datatype: "xml",
									cellEdit:false,
									cellsubmit:'remote',
									mtype:"post",
									prmNames: s.base.app.config.paramNames,
									xmlReader: { 
								  		repeatitems:false,
										id: "kopije_zakljucnice_id"
									},
									colModel:[ 
									  {
										  	label:s.base.app.config.lang.sifra,
											name: 'zakljucnica_id', 
											index:'zakljucnica_id',
											xmlmap:'zakljucnica_id',
											width:30,
											sortable:false,
											hidden:true
									  }, 
									  {
											label:s.base.app.config.lang.sifra_kopije,
											name: s.base.app.config.SCPN +  'kopije_zakljucnice_id', 
											index:'kopije_zakljucnice_id',
											xmlmap:'kopije_zakljucnice_id',
											sortable:false,
											width:70
									  },
									  {
										  label:s.base.app.config.lang.sifra_bioskopa,
										  name: s.base.app.config.SCPN + 'bioskop_id', 
										  index:'naziv_bioskopa', 
										  xmlmap:'naziv_bioskopa',
										  width:100, 
										  editable:true,
										  edittype:"select",
										  editoptions:{ value: s.bioskopiKopijeEditSelectOptions  }
									  }, 
									  {
										  	label:s.base.app.config.lang.sifra_filma,
											name: s.base.app.config.SCPN + 'film_id', 
											index:'film_id',
											xmlmap:'film_id',
											editable:true,
											sortable:false,
											editrules:{required:true},
											width:50
									  }, 
									  {
										  label:s.base.app.config.lang.naziv_filma,
										  name: s.base.app.config.ICP + 'naziv_filma', 
										  index:'naziv_filma',
										  xmlmap:'naziv_filma',
										  editable:true,
										  sortable:false,
										  width:50
									  },
									  {
										  label:s.base.app.config.lang.serijski_broj_kopije,
										  name: s.base.app.config.ICP + 'serijski_broj_kopije', 
										  index:'serijski_broj_kopije',
										  xmlmap:'serijski_broj_kopije',
										  editable:false,
										  sortable:false,
										  width:50
									  },
									  {
										  label:s.base.app.config.lang.sifra_kopije,
										  name: 'kopija_id', 
										  index:'kopija_id',
										  xmlmap:'kopija_id',
										  editable:false,
										  hidden:true
									  },
									  {
										  label:s.base.app.config.lang.sifra_kopije,
										  name: s.base.app.config.SCPN + 'kopija_id', 
										  index:'kopija_id',
										  xmlmap:'kopija_id',
										  editable:true,
										  hidden:true,
										  width:60,
										  sortable:false,
										  edittype:'select', 
										  formatter:"select",
										  editrules:{ edithidden:true, required:true },
										  editoptions:{ value:{}, id:"kopija_filma_zakljucnice_input"}
									  },
									  {
										  label:s.base.app.config.lang.tip_raspodele,
										  name: s.base.app.config.SCPN + 'tip_raspodele', 
										  index:'tip_raspodele',
										  xmlmap:'tip_raspodele',
										  editable:true,
										  sortable:false,
										  width:60,
										  edittype:'select', 
										  formatter:"select",
										  editoptions:{ value:{ 1: s.base.app.config.lang.minimalna_garancija, 2: s.base.app.config.lang.ugovoren_iznos, 3: s.base.app.config.lang.raspodela } }
									  },
									  {
										  label:s.base.app.config.lang.valuta,
										  name: s.base.app.config.SCPN + 'valuta_raspodele', 
										  index:'valuta_raspodele',
										  xmlmap:'valuta_raspodele',
										  editable:true,
										  sortable:false,
										  width:60,
										  edittype:'select', 
										  formatter:"select",
										  editoptions:{ value:{ 1:"RSD", 2:"EUR" } }
									  },
									  {
										  label:s.base.app.config.lang.raspodela_iznos,
										  name: s.base.app.config.SCPN + 'raspodela_iznos', 
										  index:'raspodela_iznos',
										  xmlmap:'raspodela_iznos',
										  editable:true,
										  sortable:false,
										  width:50
									  },
									  {
										  label:s.base.app.config.lang.raspodela_prikazivac,
										  name: s.base.app.config.SCPN + 'raspodela_prikazivac', 
										  index:'raspodela_prikazivac',
										  xmlmap:'raspodela_prikazivac',
										  editable:true,
										  sortable:false,
										  width:50
									  },
									  {
										  label:s.base.app.config.lang.datum_od,
										  name:s.base.app.config.SCPN + 'datum_kopije_od', 
										  index:'datum_kopije_od',
										  xmlmap:'datum_kopije_od',
										  editable:true,
										  sortable:false, 
										  editrules:{required:true},
										  width:40
									  },
									  {
										  label:s.base.app.config.lang.datum_do,
										  name: s.base.app.config.SCPN + 'datum_kopije_do', 
										  index:'datum_kopije_do',
										  xmlmap:'datum_kopije_do',
										  editable:true,
										  sortable:false,
										  editrules:{required:true},
										  width:40
									  },
									  {
										  label:s.base.app.config.lang.boja,
										  name: s.base.app.config.SCPN + 'boja_kopije_zakljucnice', 
										  index:'boja_kopije_zakljucnice',
										  xmlmap:'boja_kopije_zakljucnice',
										  editable:true,
										  sortable:false,
										  width:30,
										  cellattr:function( rowId, val, rawObject, cm, rdata ){
											return "style='background-color:" + s.base.app.getCssColor( val ) + ";'";
										  },
										  editoptions:{ style:"background-image:none !important;color:#000000" }
									  }, 
									],
									// END OF COL MODEL
									
									
									loadComplete:function(){
					
										s.odabranaKopijaZakljucniceRowId = null;
										s.novaZakljucnicaKalendar.fullCalendar( "removeEvents" );
										s.novaZakljucnicaKalendar.fullCalendar( "render" );
										
										var events = new Array();
										
										$( s.kopijeZakljucniceGrid.getDataIDs() ).each(function(index, element) {
                                                    
												var d = s.kopijeZakljucniceGrid.getRowData( element );
													
												events.push( {
													id: d.zakljucnica_id,
													title: d[ s.base.app.config.ICP + "naziv_filma" ] + " - " + d[ s.base.app.config.ICP + "serijski_broj_kopije" ],
													start:  Math.round( new Date( d[ s.base.app.config.SCPN + "datum_kopije_od" ] ).getTime() /1000 ),
													end: Math.round( new Date( d[ s.base.app.config.SCPN + "datum_kopije_do" ] ).getTime()  / 1000 ),
													backgroundColor: s.base.app.getCssColor(  d[ s.base.app.config.SCPN + "boja_kopije_zakljucnice" ] )
												});
												
											} );
													
										s.novaZakljucnicaKalendar.fullCalendar( "addEventSource", events );	
									},
									
									onCellSelect:function( rowid, iCol, cellcontent, e ){
								
										var rd = s.kopijeZakljucniceGrid.getRowData( rowid );
										s.odabranaKopijaZakljucniceId = rd[ "kopija_id"]; 
										s.odabranaKopijaZakljucniceRowId = rowid;
										s.odabraniFilmId = rd[ s.base.app.config.SCPN + 'film_id' ];

									},
									viewrecords: true,
									gridview: true,
									sortable:false,
									caption: s.base.app.config.lang.kopije_zakljucnice
									
								  }); // end of KOPIJE ZAKLJUCNICE GRID
								  
								  
								
				var buttons = {};
					buttons[ s.base.app.config.lang.sacuvaj_zakljucnicu ] = function() {
										
										$( ".ui-dialog-buttonset button" ).attr( "disabled", true );
										s.sacuvajZakljucnicu();	
									};
			
									  				  	
				s.kopijeZakljucniceDialog = $( "#nova-zakljucnica-dialog" ).dialog({
							height: 820,
							width: 1600,
							autoOpen: false,
							closeOnEscape:false,
							modal:true,
							beforeClose:function( e, el ){
								return true;
							},
							create:function( e, ui ){
									  
							},
							buttons:buttons,
								
							close: function() {
								
								s.kopijeZakljucniceGrid.clearGridData( false );
								s.novaZakljucnicaKalendar.fullCalendar( "removeEvents" );
								s.novaZakljucnicaKalendar.fullCalendar( "render" );
														
								if( s.zakljucnicaSacuvana )						
									s.zakljucniceGrid.trigger( "reloadGrid" );						
									
								return false;

							}
				});
					
					
				$( "#nova-zakljucnica-button" ).click(function() {
						
						$( "#datum-zakljucnice-input-stampa" ).removeAttr( "disabled" );
						$( "#sifra-komitenta-zakljucnice" ).removeAttr( "disabled" );
						$( "#naziv-komitenta-zakljucnice" ).removeAttr( "disabled" );
						$( "#tip-zakljucnice" ).removeAttr( "disabled" );
		
						$( "#dodaj-kopiju-zakljucnice" ).attr( "disabled", true );
						$( "#promeni-kopiju-zakljucnice" ).attr( "disabled", true );
						$( "#obrisi-kopiju-zakljucnice" ).attr( "disabled", true );
				
						$( ".ui-dialog-buttonset button" ).removeAttr( "disabled" );
						s.base.app.setInfoText( $( "#zakljucnica-info-text" ), "" );
						
						$( "#datum-zakljucnice-input-stampa" ).val( '' );
		  				$( '#sifra-komitenta-zakljucnice' ).val( '' );
						$( '#naziv-komitenta-zakljucnice' ).val( '' );
						$( "#tip-zakljucnice" ).val( '' );
						
						s.zakljucnicaOperation = "create";
						s.zakljucnicaSacuvana = false;
						
						s.kopijeZakljucniceDialog.dialog( "option", "title", s.base.app.config.lang.nova_zakljucnica );
						s.kopijeZakljucniceDialog.dialog( "open" );
						
						s.novaZakljucnicaKalendar.fullCalendar( "removeEvents" );
						s.novaZakljucnicaKalendar.fullCalendar( "render" );
						
				});	
				
				$( "#promeni-zakljucnicu-button" ).click(function() {
					
					if( s.odabranaZakljucnicaId )
					{
						var dzakel = $( "#datum-zakljucnice-input-stampa" );
						
						$( dzakel ).attr( "disabled", true );
						
						$( "#sifra-komitenta-zakljucnice" ).attr( "disabled", true );
						$( "#naziv-komitenta-zakljucnice" ).attr( "disabled", true );
						$( "#tip-zakljucnice" ).attr( "disabled", true );
						
						$( "#dodaj-kopiju-zakljucnice" ).removeAttr( "disabled" );
						$( "#promeni-kopiju-zakljucnice" ).removeAttr( "disabled" );
						$( "#obrisi-kopiju-zakljucnice" ).removeAttr( "disabled" );
						
						$( ".ui-dialog-buttonset button" ).removeAttr( "disabled" );
						s.base.app.setInfoText( $( "#zakljucnica-info-text" ), "" );
						s.kopijeZakljucniceGrid.setGridParam( { url: s.base.config.baseUri + "zakljucnice/readZakljucniceKopije/" + s.odabranaZakljucnicaId } );
						s.kopijeZakljucniceGrid.trigger( "reloadGrid" );
						
						s.zakljucnicaOperation = "update";
						s.kopijeZakljucniceDialog.dialog( "option", "title", s.base.app.config.lang.promeni_zakljucnicu );
						s.kopijeZakljucniceDialog.dialog( "open" );
						
						s.novaZakljucnicaKalendar.fullCalendar( "removeEvents" );
						s.novaZakljucnicaKalendar.fullCalendar( "render" );
							
							
						var d = s.zakljucniceGrid.getRowData( s.odabranaZakljucnicaRowId );
						
						s.odabranKomitentId = d[ s.base.app.config.SCPN + 'komitent_id' ];
						
						
						s.odabraniKomitentData.ppk = d[ s.base.app.config.SCPN + 'primenjen_porez_komitenta' ];
						s.odabraniKomitentData.rmf = d[ s.base.app.config.SCPN + 'raspodela_maticna_firma' ];
						s.odabraniKomitentData.rp = d[ s.base.app.config.SCPN + 'raspodela_prikazivac' ];
						s.odabraniKomitentData.trk = d[ s.base.app.config.SCPN + 'tip_raspodele_komitenta' ];
						
						$( dzakel ).val( d[ s.base.app.config.SCPN + 'datum_zakljucnice_stampa' ] );
		  				$( '#sifra-komitenta-zakljucnice' ).val( s.odabranKomitentId );
						$( '#naziv-komitenta-zakljucnice' ).val( d[ s.base.app.config.SCPN + 'naziv_komitenta' ] );
						
						s.novaZakljucnicaKalendar.fullCalendar( "gotoDate", new Date( Date.parse( d[ s.base.app.config.SCPN + 'datum_zakljucnice' ] ) ) );
						
						s.getKomitentBioskopi( d[ s.base.app.config.SCPN + 'komitent_id' ] );
						
					}
					else
					{
						alert( s.base.app.config.lang.odaberite_zakljucnicu );
					}
					
				});	
				

				$( "#dodaj-kopiju-zakljucnice" ).click(function() {
					
					
					var selEl = $( "#" + s.base.app.config.SCPN + "kopija_id" ).empty();
					
					
					
					s.novaKopijaModal = s.kopijeZakljucniceGrid.editGridRow( "new", {
									addCaption: s.base.app.config.lang.nova_kopija,
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									bClose: s.base.app.config.lang.zatvori,
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.zatvori,
									width:950,
									resizable:false,
									serializeEditData:s.kopijaZakljucnicePostData,
									url: s.base.config.baseUri + "zakljucnice/createKopijaZakljucnice/" + s.odabranaZakljucnicaId,
									zIndex:1500,
									afterShowForm:function(){
										s.initKopijaKalendar( false );
										s.kopijePreviewKalendar.fullCalendar( "removeEvents" );
										
										if( s.kopijaEventsUri )
												s.kopijePreviewKalendar.fullCalendar( 'removeEventSource', s.kopijaEventsUri );
										
										$( "#" + s.base.app.config.SCPN + "kopija_id" ).empty();
												
										/*		
										s.odabraniKomitentData.ppk = ui.item.primenjen_porez_komitenta;
										s.odabraniKomitentData.rmf = ui.item.raspodela_maticna_firma;
										s.odabraniKomitentData.rp = ui.item.raspodela_prikazivac;
										s.odabraniKomitentData.trk = ui.item.tip_raspodele_komitenta;
										*/
									
										if( s.odabraniKomitentData.trk == 2 )
										{
											$( "#" + s.base.app.config.SCPN + "tip_raspodele" ).val(3);
											$( "#" + s.base.app.config.SCPN + "raspodela_iznos" ).val(s.odabraniKomitentData.rmf);
											$( "#" + s.base.app.config.SCPN + "raspodela_prikazivac" ).val(s.odabraniKomitentData.rp);
										}
										
										$( "#" + s.base.app.config.SCPN + "boja_kopije_zakljucnice" ).css( "background-color", "#FFFFFF");
									},
									afterSubmit : function( response, postdata ){
										
										if( response.responseText == 0 )
										{
											$( "#" + s.base.app.config.SCPN + "kopija_id" ).empty();
											s.odabraniFilmId = null;
											
											if( s.kopijaEventsUri )
												s.kopijePreviewKalendar.fullCalendar( 'removeEventSource', s.kopijaEventsUri );
				
											return [ true, "" ];
										}
										else
										{
											return [ false, s.base.app.config.lang.dogodila_se_greska ];
										}
									} 
							} );
					/**		
					$( "#" + s.base.app.config.SCPN + "film_id" ).removeAttr( "disabled" );
					$( "#" + s.base.app.config.ICP + "naziv_filma" ).removeAttr( "disabled" );
					$( "#" + s.base.app.config.SCPN + "kopija_id" ).removeAttr( "disabled" );
					**/
					
																											
					s.setKopijeEdit();

							
				});	
				
				
				$( "#promeni-kopiju-zakljucnice" ).click( function(){
						
					if( ! s.odabranaKopijaZakljucniceId )
					{
						alert( s.base.app.config.lang.odaberi_kopiju );
						return;
					}
					
					s.novaKopijaModal = s.kopijeZakljucniceGrid.editGridRow( s.odabranaKopijaZakljucniceRowId, {
									addCaption: s.base.app.config.lang.promeni_kopiju,
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									bClose: s.base.app.config.lang.zatvori,
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.zatvori,
									width:950,
									serializeEditData:s.kopijaZakljucnicePostData,
									url: s.base.config.baseUri + "zakljucnice/updateZakljucniceKopije/" + s.odabranaZakljucnicaId,
									afterclickPgButtons:function( whichbutton, formid, rowid ){
										
										var rd = s.kopijeZakljucniceGrid.getRowData( rowid );
											s.odabranaKopijaZakljucniceId = rd[ "kopija_id"];
										
										$( "#" + s.base.app.config.SCPN + "boja_kopije_zakljucnice" ).css( "background-color", 
																											s.base.app.getCssColor( rd[ s.base.app.config.SCPN + "boja_kopije_zakljucnice"] ) );
										s.getFilmKopije( rd[ s.base.app.config.SCPN + "film_id"] );
		
									},
									zIndex:1500,
									
									afterShowForm:function(){
										
										s.initKopijaKalendar( true );
										s.getFilmKopije( s.odabraniFilmId );
										
										/**
										$( "#" + s.base.app.config.SCPN + "film_id" ).attr( "disabled", true );
										$( "#" + s.base.app.config.ICP + "naziv_filma" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "kopija_id" ).attr( "disabled", true );
										**/
										
										var rd = s.kopijeZakljucniceGrid.getRowData( s.odabranaKopijaZakljucniceRowId );
											
										$( "#" + s.base.app.config.SCPN + "boja_kopije_zakljucnice" ).css( "background-color", 
																											s.base.app.getCssColor( rd[ s.base.app.config.SCPN + "boja_kopije_zakljucnice"] ) );
									},
									
									afterSubmit : function( response, postdata ){
									
										if( response.responseText == 0 )
										{
											if( s.kopijaEventsUri )
												s.kopijePreviewKalendar.fullCalendar( 'removeEventSource', s.kopijaEventsUri );
												
											s.kopijaEventsUri = s.base.config.baseUri + "zakljucnice/readKopijaEvents/" + s.odabranaKopijaZakljucniceId + "/";
											s.kopijePreviewKalendar.fullCalendar( "addEventSource", s.kopijaEventsUri );
			
											return [ true, "" ];
										}
										else
										{
											return [ false, s.base.app.config.lang.dogodila_se_greska ];
										}
									}									
							} );
						
						s.setKopijeEdit();
				});
				
				$( "#obrisi-kopiju-zakljucnice" ).click(function() {

					//s.kopijeZakljucniceGrid.delRowData( s.kopijeZakljucniceGrid.getGridParam( "selrow" ) );

				});	
	  
		}
		
	});
}


Zakljucnice.prototype.getKomitentBioskopi = function( id ){
	
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "komitenti/getBioskopiSelectOptions/" + id,
 		success: function(data)
 		{
			 
			var options =  data.getElementsByTagName("option");
			 
			if( s.novaKopijaTableElement )
			{
				var el = $( s.novaKopijaTableElement ).find( "#" + s.base.app.config.SCPN + "bioskop_id" );
				$( el ).empty();
					
				 $.each(options, function(key, value)
				 {   
					 $( el ).
						  append($("<option></option>").
						  attr("value", $( value ).attr( "value" )).
						  text( $( value ).text() )); 
				 });

			}
			else
			{
				s.bioskopiKopijeEditSelectOptions = {};
					
				$( options ).each(function(index, element) {
					  s.bioskopiKopijeEditSelectOptions[ $( element ).attr( "value" ) ] = $( element ).text();
				});
			
				s.kopijeZakljucniceGrid.setColProp( s.base.app.config.SCPN + 'bioskop_id', { editoptions:{ value:s.bioskopiKopijeEditSelectOptions } } );	
			}
			
			/**
			var options =  data.getElementsByTagName("option");
			
			
			$( selEl ).empty().removeAttr( "disabled" );
			
			$.each(options, function(key, value)
			{   
				 $( selEl ).
					  append($("<option></option>").
					  attr("value", $( value ).attr( "value" )).
					  text( $( value ).text() )); 
			});
			
			
			if( s.zakljucnicaOperation == "update" )
			{
				var d = s.zakljucniceGrid.getRowData( s.odabranaZakljucnicaRowId );
				$( '#bioskop-komitenta-zakljucnice' ).val( d[ s.base.app.config.SCPN + 'bioskop_id' ] );
			}
			**/
			
		}
	}); 
}


Zakljucnice.prototype.getFilmKopije = function( id ){
	
	var s = this;
	
	if( this.kopijeFilmaJqXHR ) this.kopijeFilmaJqXHR.abort();
	
	$( "#" + s.base.app.config.SCPN + "kopija_id" ).empty().append( "<option>Учитавање...</option>");
	
	this.kopijeFilmaJqXHR = $.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "filmovi/getKopijeFilmaSelectOptions/" + id,
 		success: function(data)
 		{
			var options =  data.getElementsByTagName("option");
			
			var selEl = $( "#" + s.base.app.config.SCPN + "kopija_id" );
			$( selEl ).empty();
						
			$.each(options, function(key, value)
			{   
				 $( selEl ).
					  append($("<option></option>").
					  attr("value", $( value ).attr( "value" )).
					  text( $( value ).text() )); 
			});
			
			
			$( selEl ).val( s.odabranaKopijaZakljucniceId );
			
			if( s.kopijaEventsUri )
				s.kopijePreviewKalendar.fullCalendar( 'removeEventSource', s.kopijaEventsUri );
				
			s.odabranaKopijaZakljucniceId = $( selEl ).val();
												
			s.kopijaEventsUri = s.base.config.baseUri + "zakljucnice/readKopijaEvents/" + s.odabranaKopijaZakljucniceId + "/";
			s.kopijePreviewKalendar.fullCalendar( "addEventSource", s.kopijaEventsUri );
		}
	}); 
}



Zakljucnice.prototype.sacuvajZakljucnicu = function( )
{	
	var postData = {};
	
	var datum = $( "#datum-zakljucnice-input" ).val();
	var tip = $( "#tip-zakljucnice" ).val();
	var komitent_id = $( "#sifra-komitenta-zakljucnice" ).val();
	
	postData[ this.base.app.config.SCPN + "datum_zakljucnice" ] 		= datum;
	postData[ this.base.app.config.SCPN + "komitent_id" ]	= komitent_id;
	postData[ this.base.app.config.SCPN + "tip" ]	= tip;
	
	console.log

	if( ! datum || ! komitent_id  || tip == 0  )
	{
		alert( this.base.app.config.lang.forma_nije_validna );
		$( ".ui-dialog-buttonset button" ).removeAttr( "disabled" );
		return;
	}

	this.base.app.setInfoText( $( "#zakljucnica-info-text" ), this.base.app.config.lang.ucitavanje );
	
	var s = this;
	switch( this.zakljucnicaOperation )
	{
		case "create":

			$.ajax({
				type: 'post',
				url: this.base.config.baseUri + "zakljucnice/createZakljucnica/",
				data:postData,
				success: function(data)
 				{
					$( ".ui-dialog-buttonset button" ).removeAttr( "disabled" );
					
					var zakljucnicaIdReg = new RegExp("^[0-9]+$");
					var zakIdEl = $( data ).find( "zakljucnica_id" );
					
					if( zakIdEl && zakljucnicaIdReg.test( $( zakIdEl ).text() ) )
					{
						s.odabranaZakljucnicaId = $( zakIdEl ).text();
						s.zakljucnicaOperation = "update";
						s.zakljucnicaSacuvana = true;
						s.base.app.setInfoText( $( "#zakljucnica-info-text" ), s.base.app.config.lang.sacuvana_zakljucnica );
						
						s.kopijeZakljucniceGrid.setGridParam( { url: s.base.config.baseUri +  "zakljucnice/readZakljucniceKopije/" + s.odabranaZakljucnicaId } );
															
						$( "#datum-zakljucnice-input-stampa" ).attr( "disabled", true );
						$( "#tip-zakljucnice" ).attr( "disabled", true );
						$( "#sifra-komitenta-zakljucnice" ).attr( "disabled", true );
						$( "#naziv-komitenta-zakljucnice" ).attr( "disabled", true );
										
						$( "#dodaj-kopiju-zakljucnice" ).removeAttr( "disabled" );
						$( "#promeni-kopiju-zakljucnice" ).removeAttr( "disabled" );
						s.zakljucnicaSacuvana = true;
					}
					else
					{
						s.base.app.setInfoText( $( "#zakljucnica-info-text" ), s.base.app.config.lang.dogodila_se_greska, true );
						$( ".ui-dialog-buttonset button" ).removeAttr( "disabled" );
					}
				}
			});
			
		break;
		
		case "update":
						
		/**
		$.ajax({
			
				type: 'post',
				url: this.base.config.baseUri + "zakljucnice/updateZakljucnica/" + s.odabranaZakljucnicaId,
				data:postData,
				success: function(data)
 				{
					$( ".ui-dialog-buttonset button" ).removeAttr( "disabled" );
					
					if( data == 0 )
					{
						s.zakljucnicaSacuvana = true;
						s.base.app.setInfoText( $( "#zakljucnica-info-text" ), "Успешно сте сачували закључницу!" );
					}
					else
					{	
						s.base.app.setInfoText( $( "#zakljucnica-info-text" ), "Догодила се грешка, молимо вас покушајте поново.", true );
					}
				}
			});
		**/
		break;
	}
}


Zakljucnice.prototype.setKopijeEdit = function(){
	
	
	var s = this;
	
	$( "#" + s.base.app.config.SCPN + "film_id" ).autocomplete( {
		
			source: s.base.config.baseUri + "filmovi/suggestFromId/",
			width:300,
			minLength: 1,
			select: function( event, ui ) 
			{	
				if( ui.item )
				{
					s.odabraniFilmId = ui.item.id;
					$( "#" + s.base.app.config.ICP + "naziv_filma" ).attr( "value" ,ui.item.label );
					s.getFilmKopije( s.odabraniFilmId );
					
				}
				
					
				return true;
			}

		} );
						
		$( "#" + s.base.app.config.ICP + "naziv_filma" ).autocomplete( {
			
										source: s.base.config.baseUri + "filmovi/suggestFromName/",
										width:300,
										minLength: 2,
										select: function( event, ui ) 
										{	
											if( ui.item )
											{
												s.odabraniFilmId = ui.item.id;
												$( "#" + s.base.app.config.SCPN + "film_id" ).attr( "value", ui.item.id );
												s.getFilmKopije( s.odabraniFilmId );
											}
											return true;
										}


			} );
					
		$( "#" + s.base.app.config.SCPN + "datum_kopije_od" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
		$( "#" + s.base.app.config.SCPN + "datum_kopije_do" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
		$( "#" + s.base.app.config.SCPN + "boja_kopije_zakljucnice" ).ColorPicker({
			
			
					color: '#0000ff',
					onShow: function (colpkr) {
						$(colpkr).fadeIn(200);
						return false;
					},
					
					onHide: function (colpkr) {
						$(colpkr).fadeOut(200);
						return false;
					},
					
					onChange: function (hsb, hex, rgb) {
						//console.log( "color is: " + hex );
					},
					
					onSubmit:function(hsb, hex, rgb){
						this.el.style.backgroundColor = "#" + hex;
						var n = "0x" + hex;
						
						eval("var c_num=0x" + hex.toString() );
						this.el.value = c_num;
						
						$( "#" + s.base.app.config.SCPN + "boja_kopije_zakljucnice" ).ColorPickerHide();
						
					}
					
				}); 
					
}



Zakljucnice.prototype.initKopijaKalendar = function( update )
{
	if( ! this.kopijePreviewKalendar )
	{
		var s = this;
		
		s.novaKopijaTableElement = $( "#FrmGrid_kopije-zakljucnice-lista-grid" );
		var formData  = $( s.novaKopijaTableElement ).html();
		
		if( update )
		{
			var rd = this.kopijeZakljucniceGrid.getRowData( this.odabranaKopijaZakljucniceRowId );
				rd[ this.base.app.config.SCPN + "zakljucnica_id"];
			
			var tipRaspodele = 				rd[ this.base.app.config.SCPN + "tip_raspodele"];
			var filmId = 					rd[ this.base.app.config.SCPN + "film_id"];
			var nazivFilma = 				rd[ this.base.app.config.ICP + "naziv_filma"];
			var bioskop 	= 				rd[ this.base.app.config.SCPN + "biopskop_id"];
			var	kopijaId = 					rd[ "kopija_id"];
			var	raspodelaIznos = 			rd[ this.base.app.config.SCPN + "raspodela_iznos"];	
			var	raspodelaPrikazivac = 		rd[ this.base.app.config.SCPN + "raspodela_prikazivac"];	
			var dateOd = 					rd[ this.base.app.config.SCPN + "datum_kopije_od"];	
			var dateDo =					rd[ this.base.app.config.SCPN + "datum_kopije_do"];
			var boja = 						rd[ this.base.app.config.SCPN + "boja_kopije_zakljucnice"];
		}
			
			$( s.novaKopijaTableElement ).empty().append( '<table width="100%"><tbody><tr><td id="kopije-zakljucnice-form-cnt"></td><td width="100%"><div id="kopije-preview-callendar-cnt"></div></td></tr></tbody></table>' );
			
			$( "#kopije-zakljucnice-form-cnt" ).append( formData );
			
			if( update )
			{
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "film_id" ).val( filmId );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.ICP + "naziv_filma" ).val( nazivFilma );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "tip_raspodele" ).val( tipRaspodele );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "bioskop_id" ).val( bioskop );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "raspodela_iznos" ).val( raspodelaIznos );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "raspodela_prikazivac" ).val( raspodelaPrikazivac );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "datum_kopije_od" ).val( dateOd );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "datum_kopije_do" ).val( dateDo );
				$( s.novaKopijaTableElement ).find( "#" + this.base.app.config.SCPN + "boja_kopije_zakljucnice" ).val( boja ).css( "background-color", boja );

			}
			
			this.kopijePreviewKalendar = $( "#kopije-preview-callendar-cnt" ).fullCalendar( this.kopijaCalendarOptions );	
			
			
		$( "#" + this.base.app.config.SCPN + "kopija_id" ).change( function(){
			s.kopijePreviewKalendar.fullCalendar( "removeEvents" );
			
			if( s.kopijaEventsUri )
				s.kopijePreviewKalendar.fullCalendar( 'removeEventSource', s.kopijaEventsUri );
			
			s.odabranaKopijaZakljucniceId = $( this ).val();
			s.kopijaEventsUri = s.base.config.baseUri + "zakljucnice/readKopijaEvents/" + s.odabranaKopijaZakljucniceId + "/";
			s.kopijePreviewKalendar.fullCalendar( "addEventSource", s.kopijaEventsUri );
		});
									
	}
}
