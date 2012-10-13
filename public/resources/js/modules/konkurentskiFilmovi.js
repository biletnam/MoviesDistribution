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
						width:640,
						height:330,
						url: s.base.config.baseUri + "konkurentskiFilmovi/read/",
						cellEdit:false,
						datatype: 'xml',
						cellsubmit:'remote',
						cellurl: s.base.config.baseUri + 'konkurentskiFilmovi/updateFilm/',
						mtype: 'POST',
						colNames:['Id','Excel list','Period','Pregled' ],
						xmlReader: { 
							  repeatitems:false,
							  id:"id"
						},
						colModel :[ 
						  {
								name: s.base.app.config.SCPN + 'id', 
								index: 'id', 
								xmlmap:'id',
								width:55
						  }, 
						  {
								name: s.base.app.config.SCPN + 'naziv_excel_lista', 
								index: 'naziv_excel_lista', 
								xmlmap:'naziv_excel_lista',
								editable:false,
								width:100, 
								editable:false
						  },
						  
						  {
								name: s.base.app.config.SCPN + 'period', 
								index: 'period', 
								xmlmap:'period',
								editable:false,
								width:250, 
								editable:false
						  },
						  
						  {
                               name:'id', 
                               index:'id', 
                               width:60, 
                               align:"center", 
                               editable: false, 
                               formatter:linkFormat, 
                               
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
						rowNum:15,
						rowList:[10,20,30],
						sortname: 'id',
						sortorder: 'asc',
						viewrecords: true,
						gridview: true,
						caption: 'Конкурентски Филмови'
						
					  }).navGrid('#konkurentski-film-grid-pager',{view:false, search: false, edit:false, del:false, refresh:true, add:false} ); 
					  
			function linkFormat( cellvalue, options, rowObject ){
				  return "<a target='_blank' href='excel/exlpregled.php?sheet=" + cellvalue +"'><u>Pregled</u></a>  ";
			}
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


