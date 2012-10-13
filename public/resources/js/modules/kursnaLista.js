// JavaScript Document

var KursnaLista = function( app )
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.kurnsaListaGrid = null;
	this.kurnsaListaGridSelectedRow;
}

KursnaLista.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "kursnaLista/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			$( "#datum_kursa_pretraga_input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames} );
			
			s.kursnaListaGrid = $("#kursna-lista-grid").jqGrid({
						width:800,
						height:600,
						url: s.base.config.baseUri + "kursnaLista/read/",
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						mtype: 'POST',
						cellurl: s.base.config.baseUri + 'kursnaLista/updateKurs/',
						xmlReader: { 
							  repeatitems:false,
							  id:"kurs_id"
						},
						colModel :[ 
						  {
								label:s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'kurs_id', 
								index:'kurs_id', 
								xmlmap: 'kurs_id',
								width:30
						  }, 
						  {
							  	label:s.base.app.config.lang.datum,
								name: s.base.app.config.SCPN +  'datum_kursa', 
								index:'datum_kursa', 
								xmlmap: 'datum_kursa',
								editable:true,
								editrules:{ required:true, date:true },
								editoptions:{ disabled:1},
							  	width:50
						  }, 
						  {
							  label:'RSD',
							  name: s.base.app.config.SCPN +  'rsd', 
							  index:'rsd', 
							  xmlmap:'rsd',
							  editrules:{ number:true, edithidden:true },
							  editoptions:{ value:1, disabled:"disabled" },
							  hidden:true,
							  editable:true,
							  formatter:"number",
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5},
							  width:40
						  },
						  {
							  label:'EUR',
							  name: s.base.app.config.SCPN + 'eur', 
							  index:'eur', 
							  xmlmap:'eur',
							  editable:true,
							  formatter:"number",
							  editrules:{ number:true },
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5},
							  width:40
						  },
						  {
							  label:'KM',
							  name: s.base.app.config.SCPN + 'km', 
							  index:'km', 
							  xmlmap:'km',
							  editrules:{ number:true, required:true },
							  editable:true,
							  formatter:"number",
							  editrules:{ number:true },
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5},
							  width:40
						  },
						  {
							  label: 'KM EUR',
							  name: s.base.app.config.SCPN + 'km_eur', 
							  index:'km_eur', 
							  xmlmap:'km_eur',
							  editrules:{ number:true, required:true },
							  editable:true,
							  formatter:"number",
							  editrules:{ number:true },
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5},
							  width:50
						  },
						   {
							  label:'Faktor RSD', 
							  name: s.base.app.config.SCPN + 'faktor_rsd', 
							  index:'faktor_rsd', 
							  xmlmap:'faktor_rsd',
							  formatter:"number",
							  hidden:true,
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5}
						  },
						  {
							  label:'Faktor EUR',
							  name: s.base.app.config.SCPN + 'faktor_eur', 
							  index:'faktor_eur', 
							  xmlmap:'faktor_eur',
							  formatter:"number",
							  hidden:true,
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5}
						  },
						  {
							  label:'Faktor KM',
							  name: s.base.app.config.SCPN + 'faktor_km', 
							  index:'faktor_km', 
							  xmlmap:'faktor_km',
							  edittype:'text',
							  formatter:"number",
							  hidden:true,
							  formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 5}
							  
						  }
						],
						// end of col model
						
							// end of col model
						serializeGridData:function( p ){
							
							$( "#kursnaLista-pretraga-form .pretraga_input" ).each(function(index, element) {
								p[ element.name ] = element.value;
                            });
								
							return p;
						},
						
						loadComplete:function(){
							s.kurnsaListaGridSelectedRow = null; 
						},
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
							
							if( rowid != s.kurnsaListaGridSelectedRow )
							{
								s.kurnsaListaGridSelectedRow = rowid;
							}
							
						},
						
						afterEditCell:function( rowid, cellname, value, iRow, iCol ){
							//$( "#" + iRow + "_" + cellname ).datepicker( { dateFormat: "yy-mm-dd" } );
						},
						
						afterSubmitCell:function( response, rowid, cellname, value, iRow, iCol ){
														
							if( response.responseText == 0 )
							{
								return [ true, "Успешно сте сачували курс" ];
							}
							else if( response.responseText == s.base.config.errorCodes.alreadyExists )
							{
								return[ false, s.base.app.config.lang.datum_postoji ];
							}
							else
							{
								return[ false, s.base.app.config.lang.dogodila_se_greska ];
							}
						},
						
						pager: '#kursna-lista-grid-pager',
						emptyrecords: "Нема података",
						prmNames: s.base.app.config.paramNames,
						rowNum:30,
						rowList:[10,20,30],
						sortname: 'datum_kursa',
						sortorder: 'desc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.kursna_lista
						
					  }).navGrid('#kursna-lista-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} );
					  // END OF KURSNA LISTA GRID
					  
					  
					$("#kursnaLista_pretraga_submit" ).click(function( e ) {
			
						s.kursnaListaGrid.trigger( "reloadGrid" );						
					});
		 
		 			$("#kursnaLista_pretraga_reset" ).click(function( e ) {
			
						setTimeout(function(){
							s.kursnaListaGrid.trigger( "reloadGrid" );
						}, 100 );
						
					});
					
					$( "#kursnaLista-pretraga-form .pretraga_input" ).keydown(function(e) {
                        
						if( e.keyCode  == 13 )
						{
							s.kursnaListaGrid.trigger( "reloadGrid" );
						}
                    });
					
					
				$("#novi-kurs-button" ).click(function( e ) {
					
						s.kursnaListaGrid.editGridRow( "new", {
										width:500,
										addCaption: s.base.app.config.lang.novi_kurs,
										bSubmit: s.base.app.config.lang.sacuvaj,
										bCancel: s.base.app.config.lang.otkazi,
										bClose: s.base.app.config.lang.zatvori,
										bYes : s.base.app.config.lang.da,
										bNo : s.base.app.config.lang.ne,
										bExit : s.base.app.config.lang.otkazi,
										url: s.base.config.baseUri + "kursnaLista/createKurs/",
										afterSubmit : function (response, postdata ){
									
											if( response.responseText == 0 )
											{
												return [ true, "" ];
											}
											else if( response.responseText == s.base.config.errorCodes.alreadyExists )
											{
												return[ false, s.base.app.config.lang.datum_postoji ];
											}
											else
											{
												return[ false, s.base.app.config.lang.dogodila_se_greska ];
											}
											
										},
										
										onInitializeForm:function( formid ){
											$( "#" + s.base.app.config.SCPN + "datum_kursa" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );		
										} 
								} );
						
						$( "#" + s.base.app.config.SCPN + "datum_kursa" ).removeAttr( "disabled" );
						$( "#" + s.base.app.config.SCPN + "datum_kursa" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );
						$( "#" + s.base.app.config.SCPN + "km_eur" ).val( 0.51130 );
						
				});  
	  
		}
		
	});
}





