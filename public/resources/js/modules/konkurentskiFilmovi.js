// JavaScript Document

var KonkurentskiFilmovi = function( app )
{
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.kFilmoviGrid = null;
	this.kFilmoviGridSelectedRow;
}

KonkurentskiFilmovi.prototype.init = function()
{
	var s = this;
	
	 $.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "konkurentskiFilmovi/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			s.kFilmoviGrid = $("#konkurentski-film-grid").jqGrid({
						width:1440,
						height:570,
						url: s.base.config.baseUri + "konkurentskiFilmovi/read/",
						cellEdit:true,
						datatype: 'xml',
						cellsubmit:'remote',
						cellurl: s.base.config.baseUri + 'konkurentskiFilmovi/updateFilm/',
						mtype: 'POST',
						colNames:['Шифра','Назив', 'Оригинални Назив','Дневна Бруто Зарада', 'Викенд Бруто Зарада', 'Недељна Бруто Зарада', 'Укупна Зарада у Кал. год.', 'Укупна Зарада До Краја Дист.'  ],
						xmlReader: { 
							  repeatitems:false,
							  id:"id_konkurentskog_filma"
						},
						colModel :[ 
						  {
								name: s.base.app.config.SCPN + 'id_konkurentskog_filma', 
								index: 'id_konkurentskog_filma', 
								xmlmap:'id_konkurentskog_filma',
								width:55
						  }, 
						  {
								name: s.base.app.config.SCPN + 'naziv_konkurentskog_filma', 
								index: 'naziv_konkurentskog_filma', 
								xmlmap:'naziv_konkurentskog_filma',
								editable:true,
								width:90, 
								editable:true
						  }, 
						  {
							  name: s.base.app.config.SCPN + 'originalni_naziv_konkurentskog_filma', 
							  index: 'originalni_naziv_konkurentskog_filma',
							  xmlmap:'originalni_naziv_konkurentskog_filma', 
							  editable:true,
							  width:80
						  },
						  {
							  name: s.base.app.config.SCPN + 'dnevna_bruto_zarada', 
							  index: 'dnevna_bruto_zarada',
							  xmlmap:'dnevna_bruto_zarada', 
							  width:80,
							  editable:true	 
						  },
						  {
							  name: s.base.app.config.SCPN + 'vikend_bruto_zarada', 
							  index: 'vikend_bruto_zarada',
							  xmlmap:'vikend_bruto_zarada', 
							  width:80,
							  editable:true	 
						  },
						  {
							  name: s.base.app.config.SCPN + 'nedeljna_bruto_zarada', 
							  index: 'nedeljna_bruto_zarada',
							  xmlmap:'nedeljna_bruto_zarada', 
							  width:80,
							  editable:true	 
						  },
						  {
							  name: s.base.app.config.SCPN + 'ukupna_zarada_u_kalendarskoj_godini', 
							  index:'ukupna_zarada_u_kalendarskoj_godini',
							  xmlmap:'ukupna_zarada_u_kalendarskoj_godini', 
							  width:80,
							  editable:true	 
						  }
						  ,
						  {
							  name: s.base.app.config.SCPN + 'ukupna_zarada_do_kraja_distribucije', 
							  index: 'ukupna_zarada_do_kraja_distribucije',
							  xmlmap:'ukupna_zarada_do_kraja_distribucije', 
							  width:80,
							  editable:true	 
						  }
						  
						],
						// end of col model
						serializeGridData:function( p ){
							
							$( "#konkurentskiFilm-pretraga .pretraga_input" ).each(function(index, element) {
								p[ element.name ] = element.value;
                            });
								
							return p;
						},
						
						loadComplete:function(){
							
							s.kFilmoviGridSelectedRow = null; 
							
						},
						
						onCellSelect:function( rowid, iCol, cellcontent, e ){
							
							if( rowid != s.kFilmoviGridSelectedRow )
							{
								s.kFilmoviGridSelectedRow = rowid;
							}
							
						},
						pager: '#konkurentski-film-grid-pager',
						emptyrecords: "Нема података",
						prmNames: s.base.app.config.paramNames,
						rowNum:30,
						rowList:[10,20,30],
						sortname: 'id_konkurentskog_filma',
						sortorder: 'asc',
						viewrecords: true,
						gridview: true,
						caption: 'Конкурентски Филмови'
						
					  }).navGrid('#konkurentski-film-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} ); 
					  // END OF KONKURENTSKI FILMOVI GRID
					  
					 $("#konkurentskiFilm_pretraga_submit" ).click(function( e ) {
			
						s.kFilmoviGrid.trigger( "reloadGrid" );						
					});
		 
		 			$("#konkurentskiFilm_pretraga_reset" ).click(function( e ) {
			
						setTimeout(function(){
							s.kFilmoviGrid.trigger( "reloadGrid" );
						}, 100 );
						
					});
					
					 $( "#konkurentskiFilm-pretraga .pretraga_input" ).keydown(function(e) {
                        
						if( e.keyCode  == 13 )
						{
							s.kFilmoviGrid.trigger( "reloadGrid" );
						}
                    });
					
					
		
				$("#novi-konkurentskiFilm" ).click(function( e ) {
					
						s.kFilmoviGrid.editGridRow( "new", {
										width:500,
										addCaption: "Нови филм",
										bSubmit: "Сачувај",
										bCancel: "Откажи",
										bClose: "Затвори",
										saveData: "Data has been changed! Save changes?",
										bYes : "Да",
										bNo : "Бе",
										bExit : "Откажи",
										url: s.base.config.baseUri + "konkurentskiFilmovi/createFilm/",
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
				
				
				$("#update-konkurentskiFilm" ).click(function( e ) {
					
					if( s.kFilmoviGridSelectedRow )
					{
						s.kFilmoviGrid.editGridRow( s.kFilmoviGridSelectedRow, {
										width:500,
										addCaption: "Промени филм",
										bSubmit: "Сачувај",
										bCancel: "Откажи",
										bClose: "Затвори",
										saveData: "Data has been changed! Save changes?",
										bYes : "Да",
										bNo : "Бе",
										bExit : "Откажи",
										url: s.base.config.baseUri + "konkurentskiFilmovi/updateFilm/",
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
						alert( "Одаберите филм који желите да промените!" );
					}
	
				});  
				
				
				
 		}
	});
}


