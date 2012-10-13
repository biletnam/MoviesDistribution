// JavaScript Document

var Komitenti = function( app )
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.komitentiGrid = null;
	this.bioskopiGrid = null;
	this.bAliasesGrid = null;
	this.selectedKomitentId = null;
	this.selectedKomitentRowId = null;
	this.naprednaPretragaDialog = null;
	this.aliasesEditSelectOptions  = null;
}

Komitenti.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "komitenti/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			s.komitentiGrid  = $("#komitenti-grid").jqGrid({
							width:1100,
							height:570,
							url:s.base.config.baseUri + "komitenti/read/",
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							cellurl:s.base.config.baseUri + "komitenti/updateKomitent/",
							mtype: 'POST',
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
								  repeatitems:false,
								  id:"komitent_id"
							},
							colModel :[ 
							  {
								  	label:s.base.app.config.lang.sifra,
									name: s.base.app.config.SCPN + 'komitent_id', 
									index:'komitent_id', 
									xmlmap:'komitent_id',
									sortable:true,
									width:90
							  }, 
							  {
								  	label:s.base.app.config.lang.naziv,
									name: s.base.app.config.SCPN + 'naziv_komitenta', 
									index:'naziv_komitenta', 
									xmlmap:'naziv_komitenta',
									width:220, 
									sortable:true,
									editable:true
							  },
							  {
								  	label:s.base.app.config.lang.primenjen_porez,
									name: s.base.app.config.SCPN + 'primenjen_porez_komitenta', 
									index:'primenjen_porez_komitenta', 
									xmlmap:'primenjen_porez_komitenta',
									width:150, 
									sortable:true,
									editable:true,
									edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 1:"0%", 2:"8%", 3:"18%", 4:"Без Пореза"} }
							  },
							  {
								  	label:s.base.app.config.lang.sifra_delatnosti,
									name: s.base.app.config.SCPN + 'sifra_delatnosti_komitenta', 
									index:'sifra_delatnosti_komitenta', 
									xmlmap:'sifra_delatnosti_komitenta',
									width:90, 
									sortable:true,
									editable:true,
									hidden:true,
								    editrules:{ edithidden:true}
							  },
							  {
								  	label:s.base.app.config.lang.sifra_firme,
									name: s.base.app.config.SCPN + 'sifra_firme_komitenta', 
									index:'sifra_firme_komitenta', 
									xmlmap:'sifra_firme_komitenta',
									width:90, 
									sortable:true,
									hidden:true,
									editable:true,
									editrules:{ edithidden:true }
									
							  },
							  {
								  	label:s.base.app.config.lang.tip_raspodele,
									name: s.base.app.config.SCPN + 'tip_raspodele_komitenta', 
									index:'tip_raspodele_komitenta', 
									xmlmap:'tip_raspodele_komitenta',
									width:90, 
									sortable:true,
									editable:true,
									edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 1:"Са Документа", 2:"Расподела"} }
							  }, 
							  {
								    label:s.base.app.config.lang.raspodela_maticne_firme,
									name: s.base.app.config.SCPN + 'raspodela_maticna_firma', 
									index:'raspodela_maticna_firma', 
									xmlmap:'raspodela_maticna_firma',
									width:90, 
									sortable:true,
									editable:true,
									hidden:true,
									editrules:{ edithidden:true}
							  },
							  {
								  	label:s.base.app.config.lang.raspodela_prikazivac,
									name: s.base.app.config.SCPN + 'raspodela_prikazivac', 
									index:'raspodela_prikazivac', 
									xmlmap:'raspodela_prikazivac',
									width:90, 
									sortable:true,
									editable:true,
									hidden:true,
									editrules:{ edithidden:true}
							  },
							  {
								  label:s.base.app.config.lang.adresa,
								  name: s.base.app.config.SCPN + 'adresa_komitenta', 
								  index:'adresa_komitenta',
								  xmlmap:'adresa_komitenta', 
								  width:80, 
								  sortable:true,
								  align:'right',
								  editable:true
							  },
							   {
								  label:s.base.app.config.lang.maticni_broj,
								  name: s.base.app.config.SCPN + 'maticni_broj_komitenta', 
								  index:'maticni_broj_komitenta',
								  xmlmap:'maticni_broj_komitenta', 
								  width:80, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editrules:{ edithidden:true} 
							  }, 
							  {
								  label:s.base.app.config.lang.pib,
								  name: s.base.app.config.SCPN + 'pib_komitenta', 
								  index:'pib_komitenta',
								  xmlmap:'pib_komitenta', 
								  width:80, 
								  sortable:true,
								  align:'right', 
								  editable:true 
							  }, 
							  {
								  label:s.base.app.config.lang.postanski_broj,
								  name: s.base.app.config.SCPN + 'zip_komitenta', 
								  index:'zip_komitenta', 
								  xmlmap:'zip_komitenta',
								  width:80, 
								  sortable:true,
								  align:'right',
								  editable:true
							  }, 
							  {
								  label:s.base.app.config.lang.grad,
								  name: s.base.app.config.SCPN + 'mesto_komitenta', 
								  index:'mesto_komitenta', 
								  xmlmap:'mesto_komitenta',
								  width:80, 
								  sortable:true,
								  align:'right',
								  editable:true
							  }, 
							  {
								  label:s.base.app.config.lang.telefon,
								  name: s.base.app.config.SCPN + 'tel1_komitenta', 
								  index:'tel1_komitenta', 
								  xmlmap:'tel1_komitenta',
								  width:150, 
								  sortable:true, 
								  editable:true
								  
							  }, 
							  {
								  label:s.base.app.config.lang.telefon2,
								  name: s.base.app.config.SCPN + 'tel2_komitenta', 
								  index:'tel2_komitenta', 
								  xmlmap:'tel2_komitenta',
								  width:150, 
								  sortable:true, 
								  editable:true,
								  hidden:true,
								  editrules:{ edithidden:true}
								  
							  }, 
							  {
								  label:s.base.app.config.lang.email,
								  name: s.base.app.config.SCPN + 'email_komitenta', 
								  index:'email_komitenta', 
								  xmlmap:'email_komitenta',
								  width:150, 
								  sortable:true, 
								  editable:true
								  
							  },
							  {
								  label:s.base.app.config.lang.valuta,
								  name: s.base.app.config.SCPN + 'gledanost_komitenta', 
								  index:'gledanost_komitenta', 
								  xmlmap:'gledanost_komitenta',
								  width:150, 
								  sortable:true, 
								  editable:true,
								  hidden:true,
								  editrules:{ edithidden:true},
								  edittype:"select", 
								  formatter:"select",
								  editoptions:{ value:{ 1:"RSD", 2: "KM", 3:"EUR" }  }
							  }, 
							  {
								  label:s.base.app.config.lang.kontakt_osoba,
								  name: s.base.app.config.SCPN + 'kontakt_osoba_komitenta', 
								  index:'kontakt_osoba_komitenta', 
								  xmlmap:'kontakt_osoba_komitenta',
								  width:150, 
								  sortable:true, 
								  editable:true
								  
							  }
							   
							],
							// end of col model
							
							onCellSelect:function( rowid, iCol, cellcontent, e ){
								
								if( rowid != s.filmoviGridSelectedRow )
								{
									$( "#novi-bioskop" ).removeAttr( "disabled" );
									
									s.selectedKomitentRowId = rowid;
									
									var rd = s.komitentiGrid.getRowData( s.selectedKomitentRowId );
									
									s.selectedKomitentId = rd[ s.base.app.config.SCPN + "komitent_id"];
									 
									s.getBioskopiKomitenta( rd[ s.base.app.config.SCPN + "komitent_id"] );
									
									s.getBioskopiAliasesSelectEditData();
								}
								
							},
							
							loadComplete:function(){
							
								$( "#novi-bioskop" ).attr( "disabled", true );
								
								s.bioskopiGrid.clearGridData( false );
								
								s.selectedKomitentId = null;
								s.selectedKomitentRowId = null; 
								
							},
							
							
							onSortCol:function(){
								
								s.selectedFilmId = null;
								s.selectedKomitentRowId = null;
								s.bioskopiGrid.clearGridData( false );
								
							},
							
							serializeGridData:function( p ){
								
								$( "#komitenti-pretraga-form .pretraga_input" ).each(function(index, element) {
									p[ element.name ] = element.value;
								});
									
								return p;
							},
	
							pager: '#komitenti-grid-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:25,
							rowList:[10,25,60, 100],
							sortname: 'naziv_komitenta',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.komitenti
							
						  }).navGrid('#komitenti-grid-pager',{view:true, search: false, edit:false, del:false, refresh:true, add:false} ); 
						  // END OF KOMITENTI GRID
					  
					  
			s.bioskopiGrid =  $("#bioskopi-grid").jqGrid({
							width:500,
							height:350,
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							cellurl:s.base.config.baseUri + "komitenti/updateBioskop/",
							mtype: 'POST',
							xmlReader: { 
								  repeatitems:false,
								  id:"bioskop_id"
							},
							prmNames: s.base.app.config.paramNames,
							colModel :[ 
							  {
								  	label: s.base.app.config.lang.sifra,
									name: s.base.app.config.SCPN + 'bioskop_id', 
									index:'bioskop_id',
									xmlmap:'bioskop_id', 
									width:35
							  }, 
							  {
								  	label:s.base.app.config.lang.naziv,
									name:s.base.app.config.SCPN + 'naziv_bioskopa', 
									index:'naziv_bioskopa', 
									xmlmap:'naziv_bioskopa',
									width:50, 
									editable:true
							  }, 
							  {
								  label: s.base.app.config.lang.tehnika,
								  name: s.base.app.config.SCPN + 'tehnika_bioskopa', 
								  index:'tehnika_bioskopa', 
								  xmlmap:'tehnika_bioskopa',
								  width:50,
								  editable:true,
								  formatter:"select",
								  edittype:"select", editoptions:{ value:{ 1:"35mm", 2: "3D" }  }
							  },
							  {
								  	label: s.base.app.config.lang.status,
									name:s.base.app.config.SCPN + 'status_bioskopa', 
									index:'status_bioskopa', 
									xmlmap:'status_bioskopa',
									width:35, 
									editable:true,
									edittype:"checkbox",
									editoptions:{ value:"1:0" }
							  },{
								  label: s.base.app.config.lang.lokalizacija,
								  name: s.base.app.config.SCPN + 'lokalizacija', 
								  index:'lokalizacija', 
								  xmlmap:'lokalizacija',
								  width:85,
								  editable:true,
								  formatter:"select",
								  edittype:"select", editoptions:{ value:{ 1:"Beograd", 2: "Unutrašnjost", 3:"Crna Gora",4:"BIH"}  }
							  },{
								  label:s.base.app.config.lang.alias,	  
								  name: s.base.app.config.SCPN + 'alias_bioskopa', 
								  index:'bioskop_alias_name', 
								  xmlmap:'bioskop_alias_name',
								  width:100, 
								  align:'left', 
								  editable:true,
								  edittype:"select",
								  editoptions:{ value: s.aliasesEditSelectOptions }
						  	 },{
								  label: s.base.app.config.lang.lokalizacija,
								  name: s.base.app.config.SCPN + 'podregioni', 
								  index:'podregioni', 
								  xmlmap:'podregioni',
								  width:85,
								  editable:true,
								  formatter:"select",
								  edittype:"select", editoptions:{ value:{ 0:"--", 1: "Republika Srpska", 2:"Federacija"}  }
							  }
							  
							],
							// end of col model
							
							
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:25,
							rowList:[10,25,30,60, 100],
							sortname: 'naziv_bioskopa',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.bioskopi
							
						  });
						  // END OF BIOSKOPI GRID
					  
					s.bAliasesGrid =  $("#bioskopi-aliases-grid ").jqGrid({
							width:500,
							height:350,
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							cellurl:s.base.config.baseUri + "komitenti/updateBioskopAlias/",
							mtype: 'POST',
							xmlReader: { 
								  repeatitems:false,
								  id:"bioskop_alias_id"
							},
							prmNames: s.base.app.config.paramNames,
							colModel :[ 
							  {
								  	label: s.base.app.config.lang.sifra,
									name: s.base.app.config.SCPN + 'bioskop_alias_id', 
									index:'bioskop_alias_id',
									xmlmap:'bioskop_alias_id', 
									width:35
							  }, 
							  {
								    label: s.base.app.config.lang.naziv,
									name:s.base.app.config.SCPN + 'bioskop_alias_name', 
									index:'bioskop_alias_name', 
									xmlmap:'bioskop_alias_name',
									width:100, 
									editable:true
							  }
							  
							],
							// end of col model
							
							afterSaveCell:function( data ){
								s.getBioskopiAliasesSelectEditData();
							},
							
							emptyrecords: s.base.app.config.lang.nema_podataka,
							sortname: 'bioskop_alias_id',
							sortorder: 'asc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.bioskop_alias
							
						  });
						  // END OF BIOSKOPI GRID
						  
					 
					$("#novi-komitent" ).click(function( e ) {
				
					s.komitentiGrid.editGridRow( "new", {
								    width:500,
									addCaption: s.base.app.config.lang.novi_komitent,
									bSubmit: s.base.app.config.lang.sacuvaj,
									bCancel: s.base.app.config.lang.otkazi,
									bClose: s.base.app.config.lang.zatvori,
									saveData: "Data has been changed! Save changes?",
									bYes : s.base.app.config.lang.da,
									bNo : s.base.app.config.lang.ne,
									bExit : s.base.app.config.lang.otkazi,
									url: s.base.config.baseUri + "komitenti/createKomitent/",
									afterComplete : function (response, postdata, formid){
									
									} 
							} );
							
							
						//$( ".FormData" ).css( "display", ""	);
					
				});
			
			$("#novi-bioskop-alias" ).click(function( e ) {
					
					if( s.selectedKomitentId && s.selectedKomitentId > 0 )
					{
						s.bAliasesGrid.editGridRow( "new", {
										addCaption: s.base.app.config.lang.novi_alias,
										bSubmit: s.base.app.config.lang.sacuvaj,
										bCancel: s.base.app.config.lang.otkazi,
										bClose: s.base.app.config.lang.zatvori,
										bYes : s.base.app.config.lang.da,
										bNo : s.base.app.config.lang.ne,
										bExit : s.base.app.config.lang.zatvori,
										url:s.base.config.baseUri + "komitenti/createBioskopAlias/",
										beforeSubmit:function( postdata, formid ){
											
											postdata[  s.base.app.config.SCPN + "komitent_id" ] = s.selectedKomitentId;
											return [ true, "" ];
											//return postdata;
										},
										
										afterSubmit : function (response, postdata ){
									
											if( response.responseText == 0 )
											{
												s.getBioskopiAliasesSelectEditData( false );
												return [ true, "" ];
											}
											else if( response.responseText == s.base.config.errorCodes.alreadyExists )
											{
												return [ false, s.base.app.config.lang.alias_postoji ];
											}
											else
											{
												return[ false, s.base.app.config.lang.dogodila_se_greska ];
											}
										} 
							});
					}
					else
					{
						alert( s.base.app.config.lang.odaberite_komitenta );
					}
					
		});
		
			
				
		
		$("#promeni-komitenta" ).click(function( e ) {
			
			if( s.selectedKomitentRowId )
			{
					s.komitentiGrid.editGridRow( s.selectedKomitentRowId, {
									width:500,
									editCaption: s.base.app.config.lang.promeni_komitenta,
									bSubmit: s.base.app.config.sacuvaj,
									bCancel: s.base.app.config.otkazi,
									modal:true,
									bClose: s.base.app.config.zatvori,
									bYes : s.base.app.config.da,
									bNo : s.base.app.config.ne,
									bExit : s.base.app.config.zatvori,
									url: s.base.config.baseUri + "komitenti/updateKomitent/",
									afterComplete : function (response, postdata, formid){
										
										//console.log( response.responseText );
									} 
							} );
							
			}
			else
			{
				alert( s.base.app.config.odaberite_komitenta );
			}
					
				});
		
			s.naprednaPretragaDialog = $( "#komitenti-napredna-pretraga-dialog" ).dialog({
							title:"Напредна Претрага Комитената",
							autoOpen: false,
							height: 400,
							width: 900,
							modal: false,
							buttons:{
									
									"Тражи": function() {
										s.komitentiGrid.trigger( "reloadGrid" );	
									},
									
									"Ресетуј": function() {
										
										$('#komitenti-pretraga-form').each (function(){
											  this.reset();
										});
										
										setTimeout(function(){
											s.komitentiGrid.trigger( "reloadGrid" );
										}, 100 );
									}
								}
		});


		$( "#napredna-pretraga-komitenta" ).click(function() {
				s.naprednaPretragaDialog.dialog( "open" );
			});
				
		$("#novi-bioskop" ).click(function( e ) {
					
					if( s.selectedKomitentId && s.selectedKomitentId > 0 )
					{
						s.bioskopiGrid.editGridRow( "new", {
										addCaption: s.base.app.config.novi_bioskop,
										bSubmit: s.base.app.config.sacuvaj,
										bCancel: s.base.app.config.otkazi,
										bClose: s.base.app.config.zatvori,
										bYes : s.base.app.config.da,
										bNo : s.base.app.config.ne,
										bExit : s.base.app.config.zatvori,
										url:s.base.config.baseUri + "komitenti/createKomitentBioskop/",
										beforeSubmit:function( postdata, formid ){
											
											postdata[  s.base.app.config.SCPN + "komitent_id" ] = s.selectedKomitentId;
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
												return[ false, s.base.app.config.dogodila_se_greska ];
											}
										} 
							});
					}
					else
					{
						alert( s.base.app.config.odaberite_komitenta );
					}
					
		});
				
						
				
				
 		}
	});
}

Komitenti.prototype.getBioskopiAliasesSelectEditData = function( reloadBioskopi ){
	
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "komitenti/getBioskopiAliasesSelectOptions/" + s.selectedKomitentId,
 		success: function(data)
 		{
			var options =  data.getElementsByTagName("option");
			
			s.aliasesEditSelectOptions = {};
				
			$( options ).each(function(index, element) {
				  s.aliasesEditSelectOptions[ $( element ).attr( "value" ) ] = $( element ).text();
			});
			  
			
			s.bioskopiGrid.setColProp( s.base.app.config.SCPN + 'alias_bioskopa', { editoptions:{ value:s.aliasesEditSelectOptions } } ); 
			
			if( reloadBioskopi )
			{
				s.bioskopiGrid.trigger( "reloadGrid" );			
			}
			
			
		}
	});
	 
	
};

Komitenti.prototype.getBioskopiKomitenta = function( id )
{
	var url_bioskopa = this.base.config.baseUri + "komitenti/readBioskopiKomitenta/" +  id + "/";
	var url_bioskop_alias = this.base.config.baseUri + "komitenti/readBioskopAliases/" +  id + "/";

	this.bioskopiGrid.setGridParam( { url: url_bioskopa} );
	this.bioskopiGrid.trigger("reloadGrid");
	
	this.bAliasesGrid.setGridParam( { url: url_bioskop_alias} );
	this.bAliasesGrid.trigger("reloadGrid");

}

