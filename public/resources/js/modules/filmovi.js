// JavaScript Documents

var Filmovi = function( app )
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	
	this.filmoviGrid = null;
	this.kopijeGrid = null;
	this.zanroviGrid = null;
	this.glumciFilma = null;
	this.filmoviGridSelectedRow = null;
	this.zanroviEditSelectOptions  = null;
	this.selectedFilmId = null;
	this.odabraniGlumacId = null;
	this.naprednaPretragaDialog = null;
	
	this.advancedSearch = false;
}



Filmovi.prototype.init = function()
{
	var s = this;
	
	 this.getZanroviSelectEditData();
	 
	 $.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "filmovi/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.checkIsModuleReady();

			s.kopijeGrid =  $("#kopije-grid").jqGrid({
							width:600,
							height:450,
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							mtype: 'POST',
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id:"kopija_id"
							},
							colModel :[ 
							  {
								  	label: s.base.app.config.lang.sifra, 
									name: s.base.app.config.SCPN + 'kopija_id', 
									index:'kopija_id',
									xmlmap:'kopija_id', 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.serijski_broj_kopije,
									name: s.base.app.config.SCPN +  'serijski_broj_kopije', 
									index:'serijski_broj_kopije', 
									xmlmap:'serijski_broj_kopije',
									width:50, 
									editable:true, 
									editoptions:{ required:true }
							  }, 
							  {
								  
								  	label: s.base.app.config.lang.tehnika,
									name: s.base.app.config.SCPN + 'tehnika_kopije_filma', 
									index:'tehnika_kopije_filma', 
									xmlmap:'tehnika_kopije_filma',
									width:50, 
									editable:true,
									required:true,
									edittype:'select',
									formatter:"select", 
									editoptions:{ value:{1:'35mm',2:'3D',3:'2D' }  }
							  },
							  {
								  label: s.base.app.config.lang.oznaka,
								  name: s.base.app.config.SCPN + 'oznaka_kopije_filma', 
								  index:'oznaka_kopije_filma', 
								  xmlmap: 'oznaka_kopije_filma',
								  width:50,
								  editable:true
							  },
							],
							// end of col model
							
							afterSubmitCell:function( response, rowid, cellname, value, iRow, iCol ){
														
								if( response.responseText == 0 )
								{
									return [ true, "" ];
								}
								else
								{
									return[ false, s.base.app.config.lang.dogodila_se_greska ];
								}
							},
						
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[10,20,30],
							sortname: 'kopija_id',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.kopije_filma,
							
						  });
					  // END OF KOPIJE GRID
				
				$("#novа-kopija" ).click(function( e ) {
					
					
					if( s.selectedFilmId && s.selectedFilmId > 0 )
					{
						s.kopijeGrid.editGridRow( "new", {
										addCaption: s.base.app.config.lang.nova_kopija,
										editCaption: "Edit Record",
										bSubmit: s.base.app.config.lang.sacuvaj,
										bCancel: s.base.app.config.lang.otkazi,
										bClose: s.base.app.config.lang.zatvori,
										bYes : s.base.app.config.lang.da,
										bNo : s.base.app.config.lang.ne,
										bExit : s.base.app.config.lang.otkazi,
										url:s.base.config.baseUri + "filmovi/createFilmKopija/",
										beforeSubmit:function( postdata, formid ){
											
											postdata[ "broj_kopija" ] = s.kopijeGrid.getGridParam( "reccount" );
											postdata[  s.base.app.config.SCPN + "film_id" ] = s.selectedFilmId;
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
								} );
								
								
						$(  "#" + s.base.app.config.SCPN + "tehnika_kopije_filma" ).removeAttr( "disabled" );		

					}
					else
					{
						alert( s.base.app.config.lang.odaberite_film );
					}
					
				});
				
								
				
				s.zanroviGrid =  $("#zanrovi-grid").jqGrid({
							width:600,
							height:450,
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							mtype: 'POST',
							cellurl: s.base.config.baseUri + "filmovi/updateZanroviColumn/",
							url: s.base.config.baseUri + "filmovi/readZanroviFilma/",
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false,
							  id: 'zanr_filma_id'
							},
							colModel :[ 
							  {
								  	label: s.base.app.config.lang.sifra_zanra,
									name: s.base.app.config.SCPN + 'zanr_filma_id', 
									index:'zanr_filma_id',
									xmlmap:'zanr_filma_id', 
									width:25
							  }, 
							  {
									label: s.base.app.config.lang.naziv, 
									name: s.base.app.config.SCPN + 'naziv_zanra',
									index:'naziv_zanra', 
									xmlmap:'naziv_zanra',
									width:80, 
									editable:true 
							  }
							],
							// end of col model
							
							afterSaveCell:function( data ){
								s.getZanroviSelectEditData( true );
							},
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[10,20,30],
							sortname: 'zanr_filma_id',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.zanrovi_filma,
							
						  });
					  // END OF ZANROVI GRID

				
				
				$("#novi-zanr" ).click(function( e ) {
					
					s.zanroviGrid.editGridRow( "new", {
									addCaption: s.base.app.config.lang.novi_zanr,
									editCaption: "Edit Record",
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									bClose: s.base.app.config.lang.zatvori,
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.zatvori,
									url:s.base.config.baseUri + "filmovi/createFilmZanr/",
									afterSubmit : function (response, postdata ){
								
										if( response.responseText == 0 )
										{
											s.getZanroviSelectEditData( true );
											return [ true, "" ];
										}
										else
										{
											return[ false, s.base.app.config.lang.dogodila_se_greska ];
										}
									} 
							} );
							
							
					$(  "#" + s.base.app.config.SCPN + "tehnika" ).removeAttr( "disabled" );		
	
				});


				s.glumciFilma =  $("#glumci-filma-grid").jqGrid({
							width:600,
							height:450,
							cellEdit:false,
							datatype: 'xml',
							cellsubmit:'remote',
							mtype: 'POST',
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
							  repeatitems:false
							},
							colModel :[ 
							  {
								    label: s.base.app.config.lang.sifra,
									name: 'glumac_id', 
									index:'glumac_id',
									xmlmap:'glumac_id', 
									width:25
							  }, 
							  {
								  	label: s.base.app.config.lang.ime,
									name: 'ime_glumca', 
									index:'ime_glumca', 
									xmlmap:'ime_glumca',
									width:80, 
									editable:true 
							  },
							  {
								  	label: s.base.app.config.lang.prezime,
									name: 'prezime_glumca', 
									index:'prezime_glumca', 
									xmlmap:'prezime_glumca',
									width:80, 
									editable:true 
							  }
							],
							// end of col model
							
							
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:30,
							rowList:[10,20,30],
							sortname: 'ime_glumca',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.glumci_filma,
							
						  });
					  // END OF GLUMCI GRID
					  
					  
					  $( "#filmovi-pretraga .pretraga_input" ).keydown(function(e) {
                        
						if( e.keyCode  == 13 )
						{
							s.filmoviGrid.trigger( "reloadGrid" );
						}
                    });
					
					
					
					
						
				var dodajGlumcaACOptions = {
					
					source: s.base.config.baseUri + "glumci/suggest/",
					width:300,
					minLength: 2,
					select: function( event, ui ) {	
							if( ui.item )
								s.odabraniGlumacId = ui.item.id;
								
							return true;
					}
				};
						 
		  		s.dodajGlumcaAutoComplete = $( '#suggest-glumac-txt' ).autocomplete( dodajGlumcaACOptions );
				
				 $("#novi-glumac-filma" ).click(function( e ){
					 
					 console.log( "Click dodaj glumca. film id: " + s.selectedFilmId, "glumac id: " + s.odabraniGlumacId );
					 
					 if( s.odabraniGlumacId && s.selectedFilmId )
					 {
						 $( "#suggest-glumac-txt" ).attr( "disabled", "true" );
						 $( "#novi-glumac-filma" ).attr( "disabled", "true" );
						 
						 var pdata = {};
						 	 pdata.film_id = s.selectedFilmId;
							 pdata.glumac_id = s.odabraniGlumacId;
						 
						 
						 $.ajax({
								url: s.base.config.baseUri + "filmovi/dodajGlumca/",
								type: 'post',
								data: pdata,
								success: function(data)
								{
									$( "#suggest-glumac-txt" ).removeAttr('disabled').val("");
							 		$( "#novi-glumac-filma" ).removeAttr('disabled');
									s.glumciFilma.trigger("reloadGrid");
									
									if( data == s.base.config.errorCodes.alreadyExists )
									{
										alert( s.base.app.config.lang.glumac_je_dodat );
									}
								}
						});
					 }
						
				});
				
				$( "#opcije-filma-cnt" ).accordion({autoHeight:true});
 		
		
		}// END OF GET AJAX
	});
}


Filmovi.prototype.loadMainList = function(){
	
	var s = this;
	
	s.filmoviGrid = $("#filmovi-grid").jqGrid({
						width: 1050,
						height: 650,
						url:s.base.config.baseUri + "filmovi/read/",
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						cellurl:s.base.config.baseUri + "filmovi/updateFilm/",
						mtype: 'POST',
						prmNames: s.base.app.config.paramNames,
						xmlReader: { 
							  repeatitems:false,
							  id:"film_id"
						},
						colModel :[ 
						  {
							  	label: s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'film_id', 
								index:'film_id', 
								xmlmap:'film_id',
								width:55
						  }, 
						  {
							  	label: s.base.app.config.lang.naziv,
								name: s.base.app.config.SCPN + 'naziv_filma', 
								index:'naziv_filma', 
								xmlmap:'naziv_filma',
								width:120, 
								editable:true,  
						  }, 
						  {
							  label: s.base.app.config.lang.originalni_naziv,
							  name: s.base.app.config.SCPN + 'originalni_naziv_filma', 
							  index:'originalni_naziv_filma', 
							  xmlmap:'originalni_naziv_filma',
							  width:150, 
							  editable:true,
							  
						  }, 
						  {
							  label: s.base.app.config.lang.zanr,
							  name: s.base.app.config.SCPN + 'zanr_filma', 
							  index:'naziv_zanra', 
							  xmlmap:'naziv_zanra',
							  width:100, 
							  align:'left', 
							  editable:true,
							  edittype:"select",
							  editoptions:{ value: s.zanroviEditSelectOptions  }
						  }, 
						  {
							  label: s.base.app.config.lang.trajanje,
							  name: s.base.app.config.SCPN + 'trajanje_filma', 
							  index:'trajanje_filma', 
							  xmlmap:'trajanje_filma',
							  width:80,
							  editable:true, 
							  align:'right',
							  hidden:true,
							  editoptions:{ style:"text-align:right" },
							  editrules:{ edithidden:true }
						  }, 
						  {
							  label: s.base.app.config.lang.broj_cinova,
							  name: s.base.app.config.SCPN + 'broj_cinova_filma', 
							  index:'broj_cinova_filma',
							  xmlmap:'broj_cinova_filma', 
							  width:150,
							  editable:true,
							  align:"center", 
							  editable:true,
							  hidden:true,
							  editoptions:{ style:"text-align:center" },
							  editrules:{ edithidden:true }
							  
						  }, 
						  {
							  label: s.base.app.config.lang.tehnika,
							  name: s.base.app.config.SCPN + 'tehnika_filma', 
							  index:'tehnika_filma',
							  xmlmap:'tehnika_filma', 
							  width:150, 
							  editable:true,
							  hidden:true,
							  edittype:"select",
							  formatter:"select" ,
							  editoptions:{value:{ 1: "Color", 2: "B/W", 3: "HD", 4: "Scope" } },
							  editrules:{ edithidden:true } 
						  },
						  {
							  label: s.base.app.config.lang.start_filma,
							  name: s.base.app.config.SCPN + 'start_filma', 
							  index:'start_filma',
							  xmlmap:'start_filma', 
							  width:50, 
							  editable:true,
							  hidden:true,
							  editrules:{ edithidden:true } 
						  },  
						  {
							  label: s.base.app.config.lang.producent,
							  name: s.base.app.config.SCPN + 'producent_filma', 
							  index:'producent_filma', 
							  xmlmap:'producent_filma',
							  width:150, 
							  editable:true,
							  
						  },   
						  {
							  label: s.base.app.config.lang.reziser,
							  name: s.base.app.config.SCPN + 'reziser', 
							  index:'reziser', 
							  xmlmap:'reziser',
							  width:150, 
							  editable:true,
							  
						  }, 
						  {
							  label: s.base.app.config.lang.godina,
							  name: s.base.app.config.SCPN + 'godina_filma', 
							  index:'godina_filma', 
							  xmlmap:'godina_filma',
							  align:"center",
							  width:150, 
							  editable:true,
							  hidden:true,
							  editoptions:{ style:"text-align:center" },
							  editrules:{ edithidden:true }
							 
						  }, 
						  {
							  label: s.base.app.config.lang.studio,
							  name: s.base.app.config.SCPN + 'studio_filma', 
							  index:'studio_filma', 
							  xmlmap:'studio_filma',
							  width:150,  
							  editable:true,
							 
						  }, 
						  {
							  label: s.base.app.config.lang.broj_kopija,
							  name: s.base.app.config.SCPN + 'broj_kopija', 
							  index:'broj_kopija', 
							  xmlmap:'broj_kopija',
							  hidden:true,
							  editrules:{ edithidden:true },
							  editоptions:{ disabled:"disabled" },
							  width:60,  
						  },
						  {
							  label: s.base.app.config.lang.napomena,
							  name: s.base.app.config.SCPN + 'napomena_filma', 
							  index:'napomena_filma', 
							  xmlmap:'napomena_filma',
							  width:150, 
							  editable:true,
							  hidden:true,
						  }
						   
						],
						// end of col model
						
						pager: '#filmovi-grid-pager',
						emptyrecords: s.base.app.config.lang.nema_podataka,
						rowNum:25,
						rowList:[10,25,60, 100],
						sortname: 'film_id',
						sortorder: 'desc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.filmovi,
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
							
							if( rowid != s.filmoviGridSelectedRow )
							{
								s.filmoviGridSelectedRow = rowid;
								
								var rd = s.filmoviGrid.getRowData( s.filmoviGridSelectedRow );
								
								s.selectedFilmId = rd[ s.base.app.config.SCPN + "film_id"];
								 
								s.getKopijeFilma( rd[ s.base.app.config.SCPN + "film_id"] );
								s.getGlumciFilma( rd[ s.base.app.config.SCPN + "film_id"] );
								
								 $( "#suggest-glumac-txt" ).removeAttr( "disabled" );
								 $( "#novi-glumac-filma" ).removeAttr( "disabled" );
							}
							
						},
						
						loadComplete:function(){
						
							$( "#suggest-glumac-txt" ).val( "");
							$( "#suggest-glumac-txt" ).attr( "disabled", true );
							$( "#novi-glumac-filma" ).attr( "disabled", true );
							
							s.kopijeGrid.clearGridData( false );
							s.glumciFilma.clearGridData( false );
							
							s.selectedFilmId = null;
							s.odabraniGlumacId = null; 
							s.filmoviGridSelectedRow = null;
							
						},
						
						
						onSortCol:function(){
							console.log( "ON SORT COL" );
							
							s.selectedFilmId = null;
							s.filmoviGridSelectedRow = null;
							s.kopijeGrid.clearGridData( false );
							s.glumciFilma.clearGridData( false );
						},
						
						serializeGridData:function( p ){
							
							
							if( s.advancedSearch == true )
							{
								p[ 'advanced_search' ] = true;
								$( "#filmovi-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = element.value;
								});
							}
							else
							{
								p[ 'advanced_search' ] = false;
								$( "#filmovi-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = '';
								});
							}
							
							return p;
						}
						
					  }).navGrid('#filmovi-grid-pager',{view:true, search: false, edit:false, del:false, refresh:true, add:false} ); 
					  // END OF FILMOVI GRID
			
			//s.filmoviGrid.setGridParam( { serializeGridData: s.getFilmoviPostData } );
						
								  
			$("#novi-film" ).click(function( e ) {
				
					s.filmoviGrid.editGridRow( "new", {
									addCaption: s.base.app.config.lang.novi_film,
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									bClose: s.base.app.config.lang.zatvori,
									saveData: "",
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.otkazi,
									url: s.base.config.baseUri + "filmovi/createFilm/"
									
							} );
							
					$( "#" + s.base.app.config.SCPN + "start_filma" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );	
					
				});
				
		
		$("#promeni-film" ).click(function( e ) {
			
			if( s.filmoviGridSelectedRow )
			{
					s.filmoviGrid.editGridRow( s.filmoviGridSelectedRow, {
									editCaption: s.base.app.config.lang.promeni_film,
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									modal:true,
									bClose: s.base.app.config.lang.zatvori,
									saveData: "",
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.otkazi,
									url: s.base.config.baseUri + "filmovi/updateFilm/"
							} );
							
				$( "#" + s.base.app.config.SCPN + "start_filma" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames } );					
			}
			else
			{
				alert( s.base.app.config.lang.odaberite_film );
			}
					
				});
						
		var buttons = {};
			buttons[ s.base.app.config.lang.trazi ] = function() {
										s.advancedSearch = true;
										s.filmoviGrid.trigger( "reloadGrid" );	
			};
									
									
			buttons[ s.base.app.config.lang.resetuj ] = function() {
										s.advancedSearch = false;
										s.naprednaPretragaDialog.dialog( "close" );
	
										$('#filmovi-pretraga-form').each (function(){
											  this.reset();
										});
										
										setTimeout(function(){
											s.filmoviGrid.trigger( "reloadGrid" );
										}, 100 );
			}
						
		s.naprednaPretragaDialog = $( "#filmovi-napredna-pretraga-dialog" ).dialog({
							title: s.base.app.config.lang.napredna_pretraga,
							autoOpen: false,
							height: 540,
							width: 600,
							modal: false,
							buttons: buttons,
								
							close: function() {
								
							}
		});


		$( "#napredna-pretraga-filmova" ).click(function() {
				s.naprednaPretragaDialog.dialog( "open" );
			});
					
				
}

Filmovi.prototype.checkIsModuleReady = function(){
	
	if( this.zanroviEditSelectOptions && this.base.mainView )
	{
		this.base.app.moduleReadyHandler( this );
		
		this.loadMainList();
	}
}

Filmovi.prototype.getZanroviSelectEditData = function( reloadZanrovi ){
	
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "filmovi/getZanroviSelectOptions/",
 		success: function(data)
 		{
			var options =  data.getElementsByTagName("option");
			
			s.zanroviEditSelectOptions = {};
				
			$( options ).each(function(index, element) {
				  s.zanroviEditSelectOptions[ $( element ).attr( "value" ) ] = $( element ).text();
			});
			  
			if( ! reloadZanrovi )
			{	
				s.checkIsModuleReady();
			}
			else
			{
				s.filmoviGrid.setColProp( s.base.app.config.SCPN + 'zanr_filma', { editoptions:{ value:s.zanroviEditSelectOptions } } ); 
				s.filmoviGrid.trigger( "reloadGrid" );			
			}
		}
	});
	 
	
};

Filmovi.prototype.getGlumciFilma = function( id )
{
	var url = this.base.config.baseUri + "filmovi/getGlumciFilma/" +  id + "/";
	this.glumciFilma.setGridParam( { url: url} );
	this.glumciFilma.trigger("reloadGrid");
}

Filmovi.prototype.getKopijeFilma = function( id )
{	
	var url = this.base.config.baseUri + "filmovi/readKopijeFilma/" +  id + "/";
	var editUrl = this.base.config.baseUri + "filmovi/updateFilmKopijaColumn/" +  id + "/";
								
	this.kopijeGrid.setGridParam( { url: url} );
	this.kopijeGrid.setGridParam( { cellurl: editUrl} );	
	
	this.kopijeGrid.trigger("reloadGrid");
}

/**

Filmovi.prototype.zanroviEditSelectElement = function( value, options ) 
{
	var el = document.createElement( "select" );
  	$( el ).append( options.app.zanroviEditSelectOptions );
	
  	return el;
}
 
Filmovi.prototype.zanroviEditSelectValue =  function( elem, operation, value ) 
{
    if( operation === 'get' ) 
	{
       return elem.value;   
	}
}
**/


