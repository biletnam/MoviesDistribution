// JavaScript Document

var Glumci = function( app )
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.glumciGrid = null;
	this.glumciGridSelectedRow;
}

Glumci.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "glumci/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			s.glumciGrid = $("#glumci-grid").jqGrid({
						width:1200,
						height:570,
						url: s.base.config.baseUri + "glumci/read/",
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						cellurl: s.base.config.baseUri + 'glumci/updateGlumca/',
						mtype: 'POST',
						prmNames: s.base.app.config.paramNames,
						xmlReader: { 
							  repeatitems:false,
							  id:'glumac_id'
						},
						colModel :[ 
						  {
							  	label:s.base.app.config.lang.sifra,
								name: s.base.app.config.SCPN + 'glumac_id', 
								index: 'glumac_id', 
								xmlmap:'glumac_id',
								width:55
						  }, 
						  {
								label:s.base.app.config.lang.ime_glumca,
								name: s.base.app.config.SCPN + 'ime_glumca', 
								index: 'ime_glumca', 
								xmlmap:'ime_glumca',
								editable:true,
								width:90, 
								editable:true
						  }, 
						  {
							  label:s.base.app.config.lang.prezime_glumca,
							  name: s.base.app.config.SCPN + 'prezime_glumca', 
							  index:'prezime_glumca',
							  xmlmap:'prezime_glumca', 
							  editable:true,
							  width:80
						  },
						  {
							  label:s.base.app.config.lang.link,
							  name: s.base.app.config.SCPN + 'link_glumca', 
							  index:'link_glumca',
							  xmlmap:'link_glumca', 
							  width:80,
							  editable:true,
							  editrules:{ url:true },
							  formatter:"link",
							  formatoptions:{ target:"_blank" }
						  }
						],
						// end of col model
						
						serializeGridData:function( p ){
							
							$( "#glumci-pretraga-form .pretraga_input" ).each(function(index, element) {
								p[ element.name ] = element.value;
                            });
								
							return p;
						},
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
							
							if( rowid != s.glumciGridSelectedRow )
							{
								s.glumciGridSelectedRow = rowid;
							}
							
						},
						
						loadComplete:function(){
							
							s.glumciGridSelectedRow = null; 
							
						},
						
						pager: '#glumci-grid-pager',
						emptyrecords: s.base.app.config.lang.nema_podataka,
						rowNum:30,
						rowList:[10,20,30],
						sortname: 'ime_glumca',
						sortorder: 'asc',
						viewrecords: true,
						gridview: true,
						caption: s.base.app.config.lang.glumci
						
					  }).navGrid('#glumci-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} ); 
					  // END OF FILMOVI GRID
					  
					 $("#glumci_pretraga_submit" ).click(function( e ) {
			
						s.glumciGrid.trigger( "reloadGrid" );						
					});					  
					
					$("#glumci_pretraga_reset" ).click(function( e ) {
			
						setTimeout(function(){
							s.glumciGrid.trigger( "reloadGrid" );
						}, 100 );
						
					});
					
					 $( "#glumci-pretraga-form .pretraga_input" ).keydown(function(e) {
                        
						if( e.keyCode  == 13 )
						{
							s.glumciGrid.trigger( "reloadGrid" );
						}
                    });
				
					
					$("#novi-glumac" ).click(function( e ) {
					
						s.glumciGrid.editGridRow( "new", {
										addCaption: s.base.app.config.lang.novi_glumac,
										bSubmit: s.base.app.config.lang.sacuvaj,
										bCancel: otkazi,
										bClose: s.base.app.config.lang.zatvori,
										bYes : s.base.app.config.lang.da,
										bNo : s.base.app.config.lang.ne,
										bExit : s.base.app.config.lang.otkazi,
										url: s.base.config.baseUri + "glumci/createGlumac/",
										afterComplete : function (response, postdata, formid){
										
										/*
											for( var i in response )
											{
												console.log( i )
											}
										*/
										} 
								} );
	
				});  
				
				
				$("#update-glumac" ).click(function( e ) {
					
					if( s.glumciGridSelectedRow )
					{
						s.glumciGrid.editGridRow( s.glumciGridSelectedRow, {
										addCaption: s.base.app.config.lang.promeni_glumca,
										bSubmit: s.base.app.config.lang.sacuvaj,
										bCancel: s.base.app.config.lang.otkazi,
										bClose: s.base.app.config.lang.zatvori,
										bYes : s.base.app.config.lang.da,
										bNo : s.base.app.config.lang.ne,
										bExit : s.base.app.config.lang.otkazi,
										url: s.base.config.baseUri + "glumci/updateGlumca/",
										afterComplete : function (response, postdata, formid){
										
										/*
											for( var i in response )
											{
												console.log( i )
											}
										*/
										} 
								} );
					}
					else
					{
						alert( s.base.app.config.lang.odaberite_glumca );
					}
					
	
				});  
				
				
				
 		}
	});
}


