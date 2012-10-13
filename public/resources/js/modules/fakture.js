
var Fakture = function( app )
{
	var s = this;
	
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.faktureGrid = null;
	this.faktureGridStorno = null;
	
	this.faktureGridSelectedRow;
	this.faktureGridSelectedRowId;
	this.faktureGridStornoSelectedRowId;
	this.odabranaFakturaId = null;
	this.odabranaFakturaStornoId = null;
	
	this.faktureUplataGridSelectedRow;
	this.faktureUplataGridSelectedRowId;
	this.odabranaUplataId = null;
	
	this.promeniFakturuDialog = null;

	this.uplateFaktureGrid = null;
	
	this.selectedTotal = null;
	this.pretragaFaktureDialog = null;
	
	this.gridStorno = false;
	
	
	this.prikaziFakture = function()
	{
		if(  $('#fakture-mode-storno').attr('checked') == true )
		{
			
			var fids2 = s.faktureGridStorno.jqGrid( "getGridParam", "selarrrow" ); 
			var fsids2 = "";
			
			$( fids2 ).each(function(index, element){
				fsids2 += fids2[index] + ",";
			});
		
			$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "fakture/encodeIds/",
				data:{ faktureIds: fsids2.substring( -1, fsids2.length - 1 ) },
				success: function(data)
				{
					window.open( s.base.config.baseUri + "fakture/prikaziFakture/storno/?fakture=" + data, "_blank" );
				}
			});	
		}
		else
		{
			var fids = 	s.faktureGrid.jqGrid( "getGridParam", "selarrrow" );
			var fsids = "";
		
		
			$( fids ).each(function(index, element){
				fsids += fids[index] + ",";
			});
		
			$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "fakture/encodeIds/",
				data:{ faktureIds: fsids.substring( -1, fsids.length - 1 ) },
				success: function(data)
				{
					window.open( s.base.config.baseUri + "fakture/prikaziFakture/?fakture=" + data, "_blank" )
				}
			});
		}
	}
	
	
	this.stornirajFakturu = function()
	{
		if( s.odabranaFakturaId )
		{
			var rd = s.faktureGrid.getRowData( s.odabranaFakturaId );

			if( rd[ s.base.app.config.SCPN + 'stornirana' ] == 0 )
			{
				if( rd[ s.base.app.config.SCPN + 'z_gledanost_storno' ] == s.base.app.config.lang.ne )
				{
					alert( s.base.app.config.lang.zvanicna_nije_stornirana +  rd[ s.base.app.config.SCPN + 'broj_dokumenta_z_gledanosti' ] );
					return;
				}
					
				if( confirm( s.base.app.config.lang.storniraj_fakturu_upozorenje ) )
				{								
					$.ajax({
						type: 'post',
						url: s.base.config.baseUri + "fakture/stornirajFakturu/" + s.odabranaFakturaId,
						success: function( data )
						{
							if( data == 0 )
							{
								s.faktureGrid.trigger( "reloadGrid" );
								s.faktureGridStorno.trigger( "reloadGrid" );
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
				alert( s.base.app.config.lang.stornirana_faktura );
			}
		}
		else
		{
			alert( s.base.app.config.lang.odaberite_fakturu );
		}
	}
	
	this.povratiStornoFakture = function()
	{
		if( s.odabranaFakturaId )
		{
			if( confirm( s.base.app.config.lang.povrati_fakturu_upozorenje ) )
			{								
				$.ajax({
					type: 'post',
					url: s.base.config.baseUri + "fakture/povratiStornoFakture/" + s.odabranaFakturaId,
					data: s.faktureGrid.getRowData( s.odabranaFakturaId ),
					success: function( data )
					{
						if( data == 0 )
						{
							s.faktureGrid.trigger( "reloadGrid" );
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
			alert( s.base.app.config.lang.odaberite_fakturu );
		}
	}
	
	this.exportujFakturu = function()
	{
		var storno = $('#fakture-mode-storno').attr('checked');
		
		if( (  storno == true && s.odabranaFakturaStornoId ) || s.odabranaFakturaId )
		{			
			var rd = null;
			
			if( storno )
			{
				rd = s.faktureGridStorno.getRowData( s.odabranaFakturaId );
			}
			else
			{
				rd = s.faktureGrid.getRowData( s.odabranaFakturaId );
			}
			
			var url = s.base.config.baseUri + "fakture/exportFakture/";
			
			if( storno )
			{
				url +=  s.odabranaFakturaStornoId + "/storno/";
			}
			else
			{
				url +=  s.odabranaFakturaId;
			}
			
			window.open( url );
			
			if( storno )
			{
				s.faktureGridStorno.trigger("reloadGrid");
			}
			else
			{
				s.faktureGrid.trigger("reloadGrid");
			}
			
		}
		else
		{
			alert( s.odaberiteFakturuMsg );
		}
	}
	
	this.promeniFakturuClickHandler = function()
	{
		if( s.odabranaFakturaId )
		{
			var rd = s.faktureGrid.getRowData( s.odabranaFakturaId );
								
			if( rd[ s.base.app.config.SCPN + 'stornirana' ] == 1 )
			{
				alert( s.base.app.config.lang.faktura_stornirana_upozorenje );
				return;
			}
			
			if( rd[ s.base.app.config.SCPN + "tehnika_kopije_filma" ] == 2 && rd[ s.base.app.config.SCPN + "tip_raspodele" ] == 3 )
			{
				$( "#raspodela-naocare-fakture-input" ).val( rd[ s.base.app.config.SCPN + "raspodela_naocare" ] );
											
				s.promeniFakturuDialog.dialog( "open" );
			}
			else
			{
				alert( s.base.app.config.lang.promena_fakture_upozorenje );
			}
		}
		else
		{
			alert( s.base.app.config.lang.odaberite_fakturu );
		}
	}
	
	this.promeniFakturu = function()
	{
		var rd = s.faktureGrid.getRowData( s.odabranaFakturaId );
		
		var d = { faktura_id: rd[ s.base.app.config.SCPN + "faktura_id" ], 
				  raspodela_naocare: $( "#raspodela-naocare-fakture-input" ).val(),
				  tehnika_kopije_filma: rd[ s.base.app.config.SCPN + 'tehnika_kopije_filma' ] 
				};
		
		$.ajax({
			type: 'post',
			url: s.base.config.baseUri + "fakture/updateRaspodelaNaocareFakture/",
			data:d,
			success: function( data )
			{
				if( data == 0 )
				{
					alert( s.base.app.config.lang.faktura_promena_uspeh );
				}
				else
				{
					alert( s.base.app.config.lang.dogodila_se_greska );
				}
			}
		});
	}
	
	this.pretragaFakture = function()
	{
		if( ! s.pretragaFaktureDialog )
				{
					
					var buttons = {};
						buttons[ s.base.app.config.lang.trazi ] = function() {
											s.faktureGrid.trigger( "reloadGrid" );
											s.faktureGridStorno.trigger( "reloadGrid" );	
								};
					
						buttons[ s.base.app.config.lang.resetuj ] = function() {
											//s.naprednaPretragaDialog.dialog( "close" );
											
											
											$('#fakture-pretraga-form').each (function(){
												  this.reset();
											});
											
											
											setTimeout(function(){
												s.faktureGrid.trigger( "reloadGrid" );
												s.faktureGridStorno.trigger( "reloadGrid" );
											}, 100 );
										};
						
					
					s.pretragaFaktureDialog = $( "#fakture-napredna-pretraga-dialog" ).dialog({
								title: s.base.app.config.lang.napredna_pretraga,
								autoOpen: false,
								height: 440,
								width: 450,
								modal: false,
								create:function(){
									
									$( "#datum_unosa_fakture_pretraga_input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames} );
									
								},
								
								buttons: buttons
								
					});
				}
					
				s.pretragaFaktureDialog.dialog( "open" );
	}
	
}

Fakture.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "fakture/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			
			s.promeniFakturuDialog = $( "#promeni-fakturu-dialog" ).dialog({
								title: s.base.app.config.lang.promeni_fakturu,
								autoOpen: false,
								height: 150,
								width: 510,
								modal: true,
								resizable:false,
								draggable:true,
								create:function(){
																		
									$( "#update_faktura_button" ).click( s.promeniFakturu );
								},
								close:function(){
									s.faktureGrid.trigger( "reloadGrid" );
									s.faktureGrid.trigger( "reloadGrid" );
								}
			});
			
			
			$( "#preview-fakture-btn" ).click( s.prikaziFakture );
			
			$( "#storno-fakture-btn" ).click( s.stornirajFakturu );
			$( "#povrati-storno-fakture-btn" ).click( s.povratiStornoFakture );
			$( "#export-fakture-btn" ).click( s.exportujFakturu );
			$( "#promeni-fakturu-btn" ).click( s.promeniFakturuClickHandler );
			
			$( "#pratraga-fakture-btn" ).click( s.pretragaFakture );
			
			s.faktureGrid = $("#fakture-grid").jqGrid({
						width:1400,
						height:670,
						url: s.base.config.baseUri + "fakture/read/",
						multiselect:true,
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						mtype: 'POST',
						cellurl:s.base.config.baseUri + "fakture/updateFakture/",
						prmNames: s.base.app.config.paramNames,
						xmlReader: { 
							  repeatitems:false,
							  id: 'faktura_id'
						},
						colModel :[ 
						
						  {
								label: s.base.app.config.lang.sifra_fakture,
								name: s.base.app.config.SCPN + 'faktura_id', 
								index: 'faktura_id', 
								xmlmap:'faktura_id',
								width:60,
								hidden:true
						  }, 
						  {
							    label: s.base.app.config.lang.avansna_faktura,
								name: s.base.app.config.SCPN + 'a', 
								index: 'a', 
								xmlmap:'a',
								width:30,
								sortable:true,
								editable:true,
								edittype:"select",
								formatter:"select",
								editoptions:{ value:{ ne:"ne", da:"da"} }
						  }, 
						  {
								label: s.base.app.config.lang.avans,
								name: s.base.app.config.SCPN + 'avans', 
								index: 'avans', 
								xmlmap:'avans',
								width:60,
								editable:true
						  }, 
						  {
							    label: s.base.app.config.lang.primenjen_porez,
								name: s.base.app.config.SCPN + 'prim_porez', 
								index: 'prim_porez', 
								xmlmap:'prim_porez',
								width:40,
								sortable:true,
								editable:true
						  }, 
						  {
							    label: s.base.app.config.lang.sifra_fakture,
								name: s.base.app.config.SCPN + 'faktura_id', 
								index: 'faktura_id', 
								xmlmap:'faktura_id',
								width:45
						  }, 
						  {
							    label: s.base.app.config.lang.broj_dokumenta,
								name: s.base.app.config.SCPN + 'broj_dokumenta_fakture', 
								index: 'broj_dokumenta_fakture', 
								xmlmap:'broj_dokumenta_fakture',
								width:55
						  },{
							    label: s.base.app.config.lang.broj_dokumenta_zvanicne_gledanosti,
								name: s.base.app.config.SCPN + 'broj_dokumenta_z_gledanosti', 
								index: 'broj_dokumenta_z_gledanosti', 
								xmlmap:'broj_dokumenta_z_gledanosti',
								width:55
						  }, 
						  {
							    label: s.base.app.config.lang.datum_unosa,
								name: s.base.app.config.SCPN + 'datum_unosa_fakture', 
								index: 'datum_unosa_fakture', 
								xmlmap:'datum_unosa_fakture',
								width:65,
								formatter:'date', 
								formatoptions:{ srcformat:'Y-m-d', newformat:'m/d/Y' },
								editable:true
						  }, 
						  {
							  label: s.base.app.config.lang.naziv_komitenta,
							  name: s.base.app.config.SCPN + 'naziv_komitenta', 
							  index:'naziv_komitenta',
							  xmlmap:'naziv_komitenta',
							  width:120,
						  },
						  {
							  label: s.base.app.config.lang.naziv_bioskopa,
							  name: s.base.app.config.SCPN + 'naziv_bioskopa', 
							  index:'naziv_bioskopa',
							  xmlmap:'naziv_bioskopa', 
							  width:120
						  },
						  {
							  label: s.base.app.config.lang.raspodela_naocare,
							  name: s.base.app.config.SCPN + 'raspodela_naocare', 
							  index:'raspodela_naocare',
							  xmlmap:'raspodela_naocare', 
							  width:40
						  },
						   {
							  label: s.base.app.config.lang.valuta, 
							  name: s.base.app.config.SCPN + 'valuta_fakture', 
							  index:'valuta_fakture',
							  xmlmap:'valuta_fakture', 
							  width:40,
							  formatter:function myformatter ( cellvalue, options, rowObject ){

										// format the cellvalue to new format
										if( cellvalue == 1 )
										{
											return 'RSD';
										}
										else
										{
											return 'EUR';
										}
									}
						  },
						   {
							  label: s.base.app.config.lang.kurs_eur, 
							  name: s.base.app.config.SCPN + 'eur', 
							  index:'eur',
							  xmlmap:'eur', 
							  width:50
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod,
							  name: s.base.app.config.SCPN + 'ukupan_prihod_stampa', 
							  index:'ukupan_prihod_stampa',
							  xmlmap:'ukupan_prihod_stampa', 
							  width:30
						  },
						  {
							  label: s.base.app.config.lang.rok,
							  name: s.base.app.config.SCPN + 'rok', 
							  index:'rok',
							  xmlmap:'rok', 
							  width:20
						  },
						  {
							  formatter:'date', 
							  formatoptions:{ srcformat:'Y-m-d', newformat:'m/d/Y' }, 
							  label: s.base.app.config.lang.rok_placanja,
							  name: s.base.app.config.SCPN + 'rok_placanja', 
							  index:'rok_placanja',
							  xmlmap:'rok_placanja', 
							  width:80
						  },
						  {
							  formatter:'date', 
							  formatoptions:{ srcformat:'Y-m-d', newformat:'m/d/Y' },
							  label: s.base.app.config.lang.datum_prometa,
							  name: s.base.app.config.SCPN + 'datum_prometa', 
							  index:'datum_prometa',
							  xmlmap:'datum_prometa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.beleska,
							  name: s.base.app.config.SCPN + 'napomena', 
							  index:'napomena',
							  xmlmap:'napomena', 
							  width:80,
							  editable:true,
							  edittype:"textarea",
						      editoptions:{ rows:2, cols:30}
						  },
						  {
							  label: s.base.app.config.lang.grad,
							  name: s.base.app.config.SCPN + 'naziv_grada', 
							  index:'naziv_grada',
							  xmlmap:'naziv_grada', 
							  width:80,
							  editable:true
						  },
						  {
							  label: s.base.app.config.lang.storno,
							  name: s.base.app.config.SCPN + 'stornirana', 
							  index:'stornirana',
							  xmlmap:'stornirana', 
							  width:10,
							  cellattr:function( rowId, val, rawObject, cm, rdata )
							  {
								 if( val == 1 )
								 {
									return "style='background-color:#da0000;color:#da0000'";
								 }
								 else
								 {
									 return "style='background-color:#FFFFFF;color:#FFFFFF'";
								 }
							  },
						  },
						  {
							  label: s.base.app.config.lang.eksport,
							  name: s.base.app.config.SCPN + 'exportovana', 
							  index:'exportovana',
							  xmlmap:'exportovana', 
							  width:10,
							  cellattr:function( rowId, val, rawObject, cm, rdata )
							  {
								 if( val == 1 )
								 {
									return "style='background-color:#0090ff;color:#0090ff'";
								 }
								 else
								 {
									 return "style='background-color:#FFFFFF;color:#FFFFFF'";
								 }
							  },
						  },
						  {
							  label: s.base.app.config.lang.tehnika,
							  name: s.base.app.config.SCPN + 'tehnika_kopije_filma', 
							  index:'tehnika_kopije_filma',
							  xmlmap:'tehnika_kopije_filma', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.tip_raspodele,
							  name: s.base.app.config.SCPN + 'tip_raspodele', 
							  index:'tip_raspodele',
							  xmlmap:'tip_raspodele', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: '3D',
							  name: s.base.app.config.SCPN + 'moze_da_promeni', 
							  index:'moze_da_promeni',
							  xmlmap:'moze_da_promeni', 
							  width:10,
							  cellattr:function( rowId, val, rawObject, cm, rdata )
							  {
								 
								 var tp = $( rawObject ).find( "tip_raspodele" ).text();
								 var tkf = $( rawObject ).find( "tehnika_kopije_filma" ).text();
								 
								 if( tp == 3 && tkf == 2 )
								 {
									 return "style='background-color:#00eb90;color:#00eb90;'";
								 }
								 else
								 {
									 return "style='background-color:#FFFFFF;color:#FFFFFF'";
								 }
								 
							  },
						  },
						  {
							  label: s.base.app.config.lang.osnovica,
							  name: s.base.app.config.SCPN + 'osnovica', 
							  index:'osnovica',
							  xmlmap:'osnovica', 
							  width:30,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.ukupan_pdv,
							  name: s.base.app.config.SCPN + 'ukupan_pdv', 
							  index:'ukupan_pdv',
							  xmlmap:'ukupan_pdv', 
							  width:30,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod,
							  name: s.base.app.config.SCPN + 'ukupan_prihod', 
							  index:'ukupan_prihod',
							  xmlmap:'ukupan_prihod', 
							  width:30,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.za_placanje,
							  name: s.base.app.config.SCPN + 'za_placanje', 
							  index:'za_placanje',
							  xmlmap:'za_placanje', 
							  width:30,
							  hidden:true
						  },
						  {
							  label: s.base.app.config.lang.osnovica,
							  name: s.base.app.config.SCPN + 'osnovica_stampa', 
							  index:'osnovica_stampa',
							  xmlmap:'osnovica_stampa', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.ukupan_pdv,
							  name: s.base.app.config.SCPN + 'ukupan_pdv_stampa', 
							  index:'ukupan_pdv_stampa',
							  xmlmap:'ukupan_pdv_stampa', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.za_placanje,
							  name: s.base.app.config.SCPN + 'za_placanje_stampa', 
							  index:'za_placanje_stampa',
							  xmlmap:'za_placanje_stampa', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.naziv_filma,
							  name: s.base.app.config.SCPN + 'naziv_filma', 
							  index:'naziv_filma',
							  xmlmap:'naziv_filma', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },{
							  label: s.base.app.config.lang.stornirana_z_gledanost,
							  name: s.base.app.config.SCPN + 'z_gledanost_storno', 
							  index:'z_gledanost_storno',
							  xmlmap:'z_gledanost_storno', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true },
							  formatter:function myformatter ( cellvalue, options, rowObject ){


										// format the cellvalue to new format
										if( cellvalue == 1 )
										{
											return s.base.app.config.lang.da;
										}
										else
										{
											return s.base.app.config.lang.ne;
										}
							}
						  
						  }


						  
						],
						// end of col model
						
						afterEditCell:function( rowid, cellname, value, iRow, iCol ){
							if( cellname.indexOf( "datum" ) != -1 )
							{
								$( "#" + iRow + "_" + cellname ).datepicker( { dateFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
								$( "#" + iRow + "_" + cellname ).datepicker( "setDate", new Date( Date.parse( value ) ) );
							}
																					
						},	
																				
						onCellSelect:function( rowid, iCol, cellcontent, e ){
								
								if( rowid != s.faktureGridSelectedRowId )
								{
									$( "#dodaj-uplatu-fakture" ).removeAttr( "disabled" );
									$( "#obrisi-uplatu-fakture" ).removeAttr( "disabled" );
									
									s.faktureGridSelectedRowId = rowid;
									
									var rd = s.faktureGrid.getRowData( s.faktureGridSelectedRowId );
									
									s.odabranaFakturaId = rd[ s.base.app.config.SCPN + "faktura_id"];
									 
									s.getUplateFakture( rd[ s.base.app.config.SCPN + "faktura_id"] );
								}
								
							},
							
						loadComplete:function(){
						
							$( "#dodaj-uplatu-fakture" ).attr( "disabled", true );
							$( "#obrisi-uplatu-fakture" ).attr( "disabled", true );
							
							if( s.uplateFaktureGrid )
								s.uplateFaktureGrid.clearGridData( false );
							
							s.odabranaFakturaId = null;
							s.faktureGridSelectedRowId = null; 
							s.faktureGridSelectedRow = null;
						},
						
						onSortCol:function(){
							
							s.odabranaFakturaId = null;
							s.faktureGridSelectedRowId = null; 
							s.uplateFaktureGrid.clearGridData( false );
							
						},
						
						serializeGridData:function( p ){
								
								$( "#fakture-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = element.value;
								});
								
								var stornirana = $( "#fakture-pretraga-form" ).find( "input[name='" + s.base.app.config.ICP + "stornirana_faktura_pretraga']:checked" ).val() || "";
								
								p[ s.base.app.config.SCPN  + 'stornirana' ] = stornirana;
								
								
								return p;
						},
							
						pager: '#fakture-grid-pager',
						emptyrecords: s.base.app.config.lang.nema_podataka,
						rowNum:30,
						rowList:[10,20,30, 50, 100],
						sortname: 'faktura_id',
						sortorder: 'desc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.fakture
						
			}).navGrid('#fakture-grid-pager',{view:true, search: false, edit:false, del:false, refresh:true, add:false} ); 
			// END OF FAKTURE GRID
					  
			
			s.faktureGridStorno = $("#fakture-grid-storno").jqGrid({
						width:1270,
						height:670,
						url: s.base.config.baseUri + "fakture/read/storno/",
						multiselect:true,
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						mtype: 'POST',
						cellurl:s.base.config.baseUri + "fakture/updateFaktureStorno/",
						prmNames: s.base.app.config.paramNames,
						xmlReader: { 
							  repeatitems:false,
							  id: 'faktura_id'
						},
						colModel :[ 
						  {
							    label: s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'faktura_id', 
								index: 'faktura_id', 
								xmlmap:'faktura_id',
								width:45
						  }, 
						  {
							    label: s.base.app.config.lang.broj_dokumenta,
								name: s.base.app.config.SCPN + 'broj_dokumenta_fakture', 
								index: 'broj_dokumenta_fakture', 
								xmlmap:'broj_dokumenta_fakture',
								width:55
						  }, 
						  {
							    label: s.base.app.config.lang.datum_unosa,
								name: s.base.app.config.SCPN + 'datum_unosa_fakture', 
								index: 'datum_unosa_fakture', 
								xmlmap:'datum_unosa_fakture',
								editable:true,
								formatter:'date',
								formatoptions:{ srcformat:'Y-m-d', newformat:'m/d/Y' },
								width:65
						  }, 
						  {
							  label: s.base.app.config.lang.naziv_komitenta,
							  name: s.base.app.config.SCPN + 'naziv_komitenta', 
							  index:'naziv_komitenta',
							  xmlmap:'naziv_komitenta',
							  width:120,
						  },
						  {
							  label: s.base.app.config.lang.naziv_bioskopa,
							  name: s.base.app.config.SCPN + 'naziv_bioskopa', 
							  index:'naziv_bioskopa',
							  xmlmap:'naziv_bioskopa', 
							  width:120
						  },
						  {
							  label: s.base.app.config.lang.raspodela_naocare,
							  name: s.base.app.config.SCPN + 'raspodela_naocare', 
							  index:'raspodela_naocare',
							  xmlmap:'raspodela_naocare', 
							  width:40
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod,
							  name: s.base.app.config.SCPN + 'ukupan_prihod', 
							  index:'ukupan_prihod',
							  xmlmap:'ukupan_prihod', 
							  width:60
						  },
						  {
							  label: s.base.app.config.lang.rok,
							  name: s.base.app.config.SCPN + 'rok', 
							  index:'rok',
							  xmlmap:'rok', 
							  width:30
						  },
						   {
							  label: s.base.app.config.lang.rok_placanja,
							  name: s.base.app.config.SCPN + 'rok_placanja', 
							  index:'rok_placanja',
							  xmlmap:'rok_placanja', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.datum_prometa,
							  name: s.base.app.config.SCPN + 'datum_prometa', 
							  index:'datum_prometa',
							  xmlmap:'datum_prometa', 
							  width:80
						  },
						  {
							  label: s.base.app.config.lang.eksport,
							  name: s.base.app.config.SCPN + 'exportovana', 
							  index:'exportovana',
							  xmlmap:'exportovana', 
							  width:20,
							  cellattr:function( rowId, val, rawObject, cm, rdata )
							  {
								 if( val == 1 )
								 {
									return "style='background-color:#0090ff;color:#0090ff'";
								 }
								 else
								 {
									 return "style='background-color:#FFFFFF;color:#FFFFFF'";
								 }
							  },
						  },
						  {
							  label: s.base.app.config.lang.tehnika,
							  name: s.base.app.config.SCPN + 'tehnika_kopije_filma', 
							  index:'tehnika_kopije_filma',
							  xmlmap:'tehnika_kopije_filma', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.tip_raspodele,
							  name: s.base.app.config.SCPN + 'tip_raspodele', 
							  index:'tip_raspodele',
							  xmlmap:'tip_raspodele', 
							  width:80,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.eksport,
							  name: s.base.app.config.SCPN + 'exportovana', 
							  index:'exportovana',
							  xmlmap:'exportovana', 
							  width:20,
							  cellattr:function( rowId, val, rawObject, cm, rdata )
							  {
								 
								 var tp = $( rawObject ).find( "tip_raspodele" ).text();
								 var tkf = $( rawObject ).find( "tehnika_kopije_filma" ).text();
								 
								 if( tp == 3 && tkf == 2 )
								 {
									 return "style='background-color:#00eb90;color:#00eb90;'";
								 }
								 else
								 {
									 return "style='background-color:#FFFFFF;color:#FFFFFF'";
								 }
								 
							  },
						  },
						  {
							  label: s.base.app.config.lang.osnovica,
							  name: s.base.app.config.SCPN + 'osnovica', 
							  index:'osnovica',
							  xmlmap:'osnovica', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.ukupan_pdv,
							  name: s.base.app.config.SCPN + 'ukupan_pdv', 
							  index:'ukupan_pdv',
							  xmlmap:'ukupan_pdv', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.ukupan_prihod,
							  name: s.base.app.config.SCPN + 'ukupan_prihod', 
							  index:'ukupan_prihod',
							  xmlmap:'ukupan_prihod', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.za_placanje,
							  name: s.base.app.config.SCPN + 'za_placanje', 
							  index:'za_placanje',
							  xmlmap:'za_placanje', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  {
							  label: s.base.app.config.lang.naziv_filma,
							  name: s.base.app.config.SCPN + 'naziv_filma', 
							  index:'naziv_filma',
							  xmlmap:'naziv_filma', 
							  width:30,
							  hidden:true,
							  editrules:{ edithidden:true }
						  },
						  
						],
						// end of col model
						
						afterEditCell:function( rowid, cellname, value, iRow, iCol ){
							
							if( cellname.indexOf( "datum" ) != -1 )
							{
								$( "#" + iRow + "_" + cellname ).datepicker( { dateFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
								$( "#" + iRow + "_" + cellname ).datepicker( "setDate", new Date( Date.parse( value ) ) );
							}													
						},	
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
																
								if( rowid != s.faktureGridStornoSelectedRowId )
								{
									s.faktureGridStornoSelectedRowId = rowid;
									
									var rd = s.faktureGridStorno.getRowData( s.faktureGridStornoSelectedRowId );
									
									s.odabranaFakturaStornoId = rd[ s.base.app.config.SCPN + "faktura_id"];
									
								}
							},
							
						loadComplete:function(){
						
							s.odabranaFakturaId = null;
							s.faktureGridSelectedRowId = null; 
							s.faktureGridSelectedRow = null;
						},
						
						onSortCol:function(){
							
							s.odabranaFakturaId = null;
							s.faktureGridSelectedRowId = null; 
						},
						
						serializeGridData:function( p ){
							
							if( $( '#fakture-mode-storno' ).attr( 'checked' ) == true )
							{
								$( "#fakture-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = element.value;
								});
								
								p[ s.base.app.config.SCPN  + 'stornirana' ] = 1;
								
							}
							
							return p;
						},
							
						pager: '#fakture-grid-storno-pager',
						emptyrecords: s.base.app.config.lang.nema_podataka,
						rowNum:30,
						rowList:[ 10, 20, 30 ],
						sortname: 'faktura_id',
						sortorder: 'desc',
						viewrecords: true,
						gridview: true,
						hiddengrid:true,
						hidegrid:true,
						caption: s.base.app.config.lang.stornirane_fakture
						
			}).navGrid( '#fakture-grid-storno-pager', { view:true, search: false, edit:false, del:false, refresh:true, add:false } ); 
			// END OF FAKTURE GRID
			
				
			s.uplateFaktureGrid = $("#fakture-uplate-grid").jqGrid({
						width:480,
						height:640,
						url: s.base.config.baseUri + "fakture/readUplateFakture/",
						cellEdit:true,
						datatype: 'xml',
						mtype: 'POST',
						prmNames: s.base.app.config.paramNames,
						cellurl:s.base.config.baseUri + "fakture/updateUplateFakture/",
						xmlReader: { 
							  repeatitems:false,
							  id: 'uplata_id'
						},
						colModel :[ 
						  {
							  	label: s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'uplata_id', 
								index: 'uplata_id', 
								xmlmap:'uplata_id',
								width:55,
								hidden:false,
						  }, 
						  {
							  	label: s.base.app.config.lang.sifra_fakture,
								name: s.base.app.config.SCPN + 'faktura_id', 
								index: 'faktura_id', 
								xmlmap:'faktura_id',
								width:55,
								hidden:true,
						  }, 
						  {
							    label: s.base.app.config.lang.datum,
							  	name: s.base.app.config.SCPN + 'datum_uplate_fakture', 
							  	index:'datum_uplate_fakture',
							  	xmlmap:'datum_uplate_fakture',
							  	width:80,
							  	editable:true,
						  },
						  {
							    label: s.base.app.config.lang.vrednost,
							  	name: s.base.app.config.SCPN + 'vrednost_uplate_fakture', 
							  	index:'vrednost_uplate_fakture',
							  	xmlmap:'vrednost_uplate_fakture', 
							  	width:80,
							  	editable:true,
								editoptions:{ disabled:"disabled" },
						  },
						  {
							    label: s.base.app.config.lang.avansno,
								name:s.base.app.config.SCPN + 'avansno', 
								index:'avansno', 
								xmlmap:'avansno',
								width:35, 
								editable:true,
								edittype:"checkbox",
								editoptions:{ value:"1:0" }
						  },
						  {
							    label: s.base.app.config.lang.broj_fakture,
							  	name: s.base.app.config.SCPN + 'broj_fakture', 
							  	index:'broj_fakture',
							  	xmlmap:'broj_fakture', 
							  	width:80,
							  	editable:true,
						  },
						  
						],
						// end of col model
						
						loadComplete:function(){
							var t = 0;
							
							var d = s.uplateFaktureGrid.getRowData();
							
							$( d ).each(function(index, element) {
                                t += parseFloat( d[ index ][ s.base.app.config.SCPN + "vrednost_uplate_fakture" ] );
                            });
							
							s.selectedTotal  = t;
							
							$( "#faktura-uplaceno-total-input" ).val( t ); 
						},
							
						onCellSelect:function( rowid, iCol, cellcontent, e ){
								
								if( rowid != s.faktureUplataGridSelectedRowId )
								{
									s.faktureUplataGridSelectedRowId = rowid;
									
									var rd = s.uplateFaktureGrid.getRowData( s.faktureUplataGridSelectedRowId );
									s.odabranaUplataId = rd[ s.base.app.config.SCPN + "uplata_id"];
								}
								
						},
						
						onSortCol:function(){
							s.faktureUplataGridSelectedRow = null;
							s.faktureUplataGridSelectedRowId = null;	
						},
							
						emptyrecords: s.base.app.config.lang.nema_podatka,
						sortname: 'datum_uplate_fakture',
						sortorder: 'desc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.uplate_na_fakturi
						
				});
				// end of fakture uplate grid
					  
					  
			 $("#dodaj-uplatu-fakture" ).click(function( e ) {
				
				if( s.odabranaFakturaId && s.odabranaFakturaId > 0 )
				{
					var rd = s.faktureGrid.getRowData( s.odabranaFakturaId );
			
					if( rd[ s.base.app.config.SCPN + "stornirana" ] == 1 )
					{
						alert( s.fakturaStorniranaMsg );
						return;
					}
					
					s.uplateFaktureGrid.editGridRow( "new", {
									addCaption: s.base.app.config.lang.nova_uplata,
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									bClose: s.base.app.config.lang.zatvori,
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.otkazi,
									url:s.base.config.baseUri + "fakture/createUplatuFakture/",
									beforeSubmit:function( postdata, formid ){
										
										postdata[  s.base.app.config.SCPN + "faktura_id" ] = s.odabranaFakturaId;
										postdata[ "uplaceno_total" ] = s.selectedTotal;
										
										return [ true, "" ];
										//return postdata;
									},
									
									afterSubmit : function (response, postdata ){
								
										if( response.responseText == 0 )
										{
											return [ true, "" ];
										}
										else
										{
											return[ false, s.base.app.config.lang.dogodila_se_greska ];
										}
									} 
						});
						
						$( "#" + s.base.app.config.SCPN + "vrednost_uplate_fakture" ).removeAttr( "disabled" );
						$( "#" + s.base.app.config.SCPN + "datum_uplate_fakture" ).attr( "autocomplete", "off" );
						$( "#" + s.base.app.config.SCPN + "datum_uplate_fakture" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
				}
				else
				{
					alert( s.odaberiteFakturuMsg );
				}
				
				
			});
			
			 $("#obrisi-uplatu-fakture" ).click(function( e ) {
			 	
				if( ! s.odabranaUplataId )
				{
					alert( s.odaberiteFakturuMsg );
					return;
				}else
				{
					var rd = s.faktureGrid.getRowData( s.odabranaFakturaId );
					if( rd[ s.base.app.config.SCPN + "stornirana" ] == 1 )
					{
						alert( s.fakturaStorniranaMsg );
						return;
					}
				}
				
				if( confirm( s.base.app.config.lang.obrisi_uplatu_upozorenje ) )
				{
					var rd = s.uplateFaktureGrid.getRowData( s.faktureUplataGridSelectedRowId );
						
					var pdata = {};
					
					pdata.uplata_id = s.odabranaUplataId;
					pdata.faktura_id = rd[ s.base.app.config.SCPN + "faktura_id"];
					pdata.vrednost_uplate = rd[ s.base.app.config.SCPN + "vrednost_uplate_fakture"];
					pdata.uplaceno_total = s.selectedTotal;	    
					
					$( this ).attr( "disabled", true );
					
					var b = this;
					
					$.ajax({
						type: 'post',
						url: s.base.config.baseUri + "fakture/deleteUplatuFakture/",
						data: pdata,
						success: function(data)
						{
							$( b ).removeAttr( "disabled" );
							
							if( data == 0 )
							{
								s.uplateFaktureGrid.delRowData( s.odabranaUplataId );
								$( "#faktura-uplaceno-total-input" ).val( s.selectedTotal - parseFloat( rd[ s.base.app.config.SCPN + "vrednost_uplate_fakture"] ) );
								
								//s.uplateFaktureGrid.trigger( "reloadGrid" );
							}
							else
							{
								alert( s.base.app.config.lang.dogodila_se_greska );
							}
						}
					});
				}
				
			 });
				
 		}
		// end of success for main view loaded complete
	});
}


Fakture.prototype.getUplateFakture = function( id )
{
	var url = this.base.config.baseUri + "fakture/readUplateFakture/" +  id + "/";
	this.uplateFaktureGrid.setGridParam( { url: url} );
	this.uplateFaktureGrid.trigger("reloadGrid");
}