// JavaScript Document

var Rokovnici = function( app )
{
	
	var s = this;
	
	this.base = new ModuleBase( this );
	this.base.setApp( app );
	this.rokovniciGrid = null;
	this.naprednaPretragaDialog = null;
	this.selectedRokovnikRowId = null;
	this.zvanicnaGledanostDialog = null;
	this.IzvestajDialog = null;
	
	this.advancedSearch = false;
	
	this.sacuvajZvanicnuGledanost = function(){
		
		var dod = $( "#datum_z_gledanosti_od" ).val();
		var ddo = $( "#datum_z_gledanosti_do" ).val();
		
	
		var rd = s.rokovniciGrid.getRowData( s.selectedRokovnikRowId );
		
		var data = { datum_od:dod, datum_do:ddo, rokovnik_id:rd[ s.base.app.config.SCPN + "rokovnik_id"] };
			data[ 'komitent_id' ] = rd[ s.base.app.config.SCPN + "komitent_id"];
			data[ 'film_id' ] = rd[ s.base.app.config.SCPN + "film_id"];
			
		var b = this;
		$.ajax({
			type: 'post',
			url: s.base.config.baseUri + "zvanicnaGledanost/create/",
			data:data,
			success: function(data)
			{
				if( data == 0 )
				{
					$( b ).attr( "disabled", true );
					s.base.app.setInfoText( $( "#zgledanost-info-text" ), s.base.app.config.lang.sacuvana_zg );
				}
				else
				{
					s.base.app.setInfoText( $( "#zgledanost-info-text" ), s.base.app.config.lang.dogodila_se_greska, true );
				}
			}
		});
	}
	
	
	this.prikaziZvanicnuGledanost = function(){
		
		window.open( s.base.config.baseUri + "zvanicnaGledanost/prikaziGledanost/", "_blank" );
	}
	
	this.prikaziRokovnike = function()
	{
		var fids = 	s.rokovniciGrid.jqGrid( "getGridParam", "selarrrow" );
		var fsids = "";
		
		$( fids ).each(function(index, element){
            fsids += fids[index] + ",";
        });
		
		$.ajax({
				type: 'post',
				url: s.base.config.baseUri + "rokovnici/encodeIds/",
				data:{ rokovniciIds: fsids.substring( -1, fsids.length - 1 ) },
				success: function(data)
				{
					window.open( s.base.config.baseUri + "rokovnici/prikaziRokovnike/?rokovnici=" + data, "_blank" );
				}
			});
	}
}

Rokovnici.prototype.init = function()
{
	var s = this;
	
	$.ajax({
 		type: 'get',
 		url: this.base.config.baseUri + "rokovnici/",
 		success: function(data)
 		{
			s.base.mainView = data;
			s.base.app.moduleReadyHandler( s );
			
			$( "#preview-rokovnici-btn" ).click( s.prikaziRokovnike );
			
			s.zvanicnaGledanostDialog = $( "#create-zvanicna-gledanost-dialog" ).dialog({
							title: s.base.app.config.lang.nova_zvanicna_gledanost,
							autoOpen: false,
							height: 200,
							width: 680,
							modal: true,
							resizable:false,
							draggable:true,
							create:function(){
								
								$( "#datum_z_gledanosti_od" ).attr( "autocomplete", "off" ).datepicker( { dateFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
								$( "#datum_z_gledanosti_do" ).attr( "autocomplete", "off" ).datepicker( { dateFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
								
								$( "#preview-zvanicna-gledanost-btn" ).click( s.prikaziZvanicnuGledanost );
								$( "#save-zvanicna-gledanost-btn" ).click( s.sacuvajZvanicnuGledanost );
							}
				});
			
			
			
			
			s.IzvestajDialog = $( "#create-izvestaj-dialog" ).dialog({
				title: s.base.app.config.izvestaj,
				autoOpen: false,
				height: 230,
				width: 550,
				modal: true,
				resizable:false,
				draggable:false,
				create:function(){
					
					$( "#datum_izvestaj_od" ).attr( "autocomplete", "off" ).datepicker( { dateFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
					$( "#datum_izvestaj_do" ).attr( "autocomplete", "off" ).datepicker( { dateFormat: "yy-mm-dd", monthNames:s.base.config.monthNames } );
					
				}
	});
			
			s.rokovniciGrid  = $("#rokovnici-grid").jqGrid({
							width:1900,
							height:670,
							url:s.base.config.baseUri + "rokovnici/read/",
							multiselect:true,
							cellEdit:true,
							datatype: 'xml',
							cellsubmit:'remote',
							cellurl:s.base.config.baseUri + "rokovnici/updateRokovnik/",
							mtype: 'POST',
							prmNames: s.base.app.config.paramNames,
							xmlReader: { 
								  repeatitems:false,
								  id:"rokovnik_id"
							},
							colModel :[ 
							  {
								  
								  	label: s.base.app.config.lang.sifra,
									name: s.base.app.config.SCPN + 'rokovnik_id', 
									index:'rokovnik_id', 
									xmlmap:'rokovnik_id',
									sortable:true,
									hidden:true,
									editrules:{edithidden:true},
									editoptions:{ disabled:"disabled" },
									width:40
							  }, 
							  {
								  	label: s.base.app.config.lang.sifra_zakljucnice,
									name: s.base.app.config.SCPN + 'zakljucnica_id', 
									index:'zakljucnica_id', 
									xmlmap:'zakljucnica_id',
									width:40, 
									sortable:true,
									editable:true,
								   	hidden:true,
									editrules:{edithidden:true},
									editoptions:{ disabled:"disabled" }
							  },
							  {
									label: s.base.app.config.lang.broj_dokumenta,
									name: s.base.app.config.SCPN + 'broj_dokumenta_rokovnika', 
									index:'broj_dokumenta_rokovnika', 
									xmlmap:'broj_dokumenta_rokovnika',
									width:110, 
									sortable:true
							  },
							  
							  
							  {
								    label: s.base.app.config.lang.datum_kopije_od,
									name: s.base.app.config.SCPN + 'datum_kopije_od', 
									index:'datum_kopije_od', 
									xmlmap:'datum_kopije_od',
									formatter:'date',
									formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
									width:90, 
									sortable:true,
									editable:true,
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								  	label: s.base.app.config.lang.datum_kopije_do,
									name: s.base.app.config.SCPN + 'datum_kopije_do', 
									index:'datum_kopije_do', 
									xmlmap:'datum_kopije_do',
									formatter:'date',
									formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
									width:90, 
									sortable:true,
									editable:true,
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								  	label: s.base.app.config.lang.naziv_komitenta,
									name: s.base.app.config.SCPN + 'naziv_komitenta', 
									index:'naziv_komitenta', 
									xmlmap:'naziv_komitenta',
									width:90, 
									sortable:true,
									editable:true,
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								    label: s.base.app.config.lang.naziv_bioskopa,
									name: s.base.app.config.SCPN + 'naziv_bioskopa', 
									index:'naziv_bioskopa', 
									xmlmap:'naziv_bioskopa',
									width:90, 
									sortable:true,
									editable:true,
								    editoptions:{ disabled:"disabled" }
							  },
							  {
								    label: s.base.app.config.lang.naziv_filma,
									name: s.base.app.config.SCPN + 'naziv_filma', 
									index:'naziv_filma', 
									xmlmap:'naziv_filma',
									width:90, 
									sortable:true,
									editable:true,
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								    label: s.base.app.config.lang.primiti_od,
									name: s.base.app.config.SCPN + 'primiti_kopiju_od', 
									index:'primiti_kopiju_od', 
									xmlmap:'primiti_kopiju_od',
									width:90, 
									sortable:true,
									editable:true,
								    edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 0: "--", 1:"Складиште", 2:"Транзит" } }
							  },
							  {
									label: s.base.app.config.lang.grad_prijema,
									name: s.base.app.config.SCPN + 'grad_prijema', 
									index:'grad_prijema', 
									xmlmap:'grad_prijema',
									width:90, 
									sortable:true,
									editable:true
							  },  
							  {
								    label: s.base.app.config.lang.nacin_prijema,
									name: s.base.app.config.SCPN + 'nacin_prijema_kopije', 
									index:'nacin_prijema_kopije', 
									xmlmap:'nacin_prijema_kopije',
									width:100, 
									sortable:true,
									editable:true,
									edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 0: "--", 1:"Аутобусом", 2:"Лично", 3:"Шпедитером" } }
								
							  },
							   {
								    label: s.base.app.config.lang.datum_prijema,
									name: s.base.app.config.SCPN + 'datum_prijema_kopije', 
									index:'datum_prijema_kopije', 
									xmlmap:'datum_prijema_kopije',
									formatter:'date',
									formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
									width:100, 
									sortable:true,
									editable:true
							  },
							   {
								    label: s.base.app.config.lang.otpremiti_od,
									name: s.base.app.config.SCPN + 'otpremiti_kopiju_od', 
									index:'otpremiti_kopiju_od', 
									xmlmap:'otpremiti_kopiju_od',
									width:90, 
									sortable:true,
									editable:true,
								    edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 0: "--", 1:"Складиште", 2:"Транзит" } }
							  },
							  {
								    label: s.base.app.config.lang.grad_otpreme,
									name: s.base.app.config.SCPN + 'grad_otpreme', 
									index:'grad_otpreme', 
									xmlmap:'grad_otpreme',
									width:90, 
									sortable:true,
									editable:true
							  },
							  {
								    label: s.base.app.config.lang.nacin_otpreme,
									name: s.base.app.config.SCPN + 'nacin_otpreme_kopije', 
									index:'nacin_otpreme_kopije', 
									xmlmap:'nacin_otpreme_kopije',
									width:90, 
									sortable:true,
									editable:true,
									edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 0: "--", 1:"Аутобусом", 2:"Лично", 3:"Шпедитером" } }
									
							  }, 
							  {
								  	label: s.base.app.config.lang.datum_otpreme,
									name: s.base.app.config.SCPN + 'datum_otpreme_kopije', 
									index:'datum_otpreme_kopije', 
									xmlmap:'datum_otpreme_kopije',
									formatter:'date',
									formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
									width:90, 
									sortable:true,
									editable:true
							  },
							  {
								    label: s.base.app.config.lang.sifra_komitenta,
									name: s.base.app.config.SCPN + 'komitent_id', 
									index:'komitent_id', 
									xmlmap:'komitent_id',
									width:90, 
									sortable:true,
									editable:true,
									hidden:true,
								  	editrules:{edithidden:true},
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								    label: s.base.app.config.lang.sifra_bioskopa,
									name: s.base.app.config.SCPN + 'bioskop_id', 
									index:'bioskop_id', 
									xmlmap:'bioskop_id',
									width:90, 
									sortable:true,
									editable:true,
									hidden:true,
								  	editrules:{edithidden:true},
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								  	label: s.base.app.config.lang.sifra_filma,
									name: s.base.app.config.SCPN + 'film_id', 
									index:'film_id', 
									xmlmap:'film_id',
									width:90, 
									sortable:true,
									editable:true,
									hidden:true,
								  	editrules:{edithidden:true},
								  	editoptions:{ disabled:"disabled" }
							  },
							  {
								  label: s.base.app.config.lang.sifra_kopije,
								  name: s.base.app.config.SCPN + 'kopija_id', 
								  index:'kopija_id',
								  xmlmap:'kopija_id', 
								  width:80, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editrules:{edithidden:true},
								  editoptions:{ disabled:"disabled" }
							  },
							  {
								  label: s.base.app.config.lang.serijski_broj_kopije,
								  name: s.base.app.config.SCPN + 'serijski_broj_kopije', 
								  index:'serijski_broj_kopije',
								  xmlmap:'serijski_broj_kopije', 
								  width:100, 
								  sortable:true,
								  editable:true,
								  editoptions:{ disabled:"disabled" }
							  },
							   {
								    label: s.base.app.config.lang.status,
									name: s.base.app.config.SCPN + 'status_kopije', 
									index:'status_kopije', 
									xmlmap:'status_kopije',
									width:90, 
									sortable:true,
									editable:true,
								    edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 12:"Без попуста", 25:"Са Попустом" } }
							  },
							   {
								  label: s.base.app.config.lang.datum_kopije_od, 
								  name: s.base.app.config.SCPN + 'datum_kopije_od', 
								  index:'datum_kopije_od',
								  xmlmap:'datum_kopije_od', 
								  formatter:'date',
								  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
								  width:120, 
								  hidden:true,
								  editable:true,
								  editrules:{edithidden:true},
								  editoptions:{ disabled:"disabled" },
								  sortable:true
							  }, 
							  {
								  label: s.base.app.config.lang.datum_kopije_do,
								  name: s.base.app.config.SCPN + 'datum_kopije_do', 
								  index:'datum_kopije_do',
								  xmlmap:'datum_kopije_do', 
								  formatter:'date',
								  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
								  width:120, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editrules:{edithidden:true},
								  editoptions:{ disabled:"disabled" }
							  },
							   {
								    label: s.base.app.config.lang.primenjen_porez,
									name: s.base.app.config.SCPN + 'primenjen_porez_komitenta', 
									index:'primenjen_porez_komitenta', 
									xmlmap:'primenjen_porez_komitenta',
									width:60, 
									sortable:true,
									editable:true,
								    edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 1:"0%", 2:"8%", 3:"18%", 4:"Без Пореза"} }
							  },
							   {
								    label: s.base.app.config.lang.porez_inostranstvo,
									name: s.base.app.config.SCPN + 'porez_inostranstvo', 
									index:'porez_inostranstvo', 
									xmlmap:'porez_inostranstvo',
									width:60, 
									sortable:true,
									editable:true,
								    edittype:"select",
									formatter:"select",
									editoptions:{ value:{ 0:"0%", 7:"7%", 17:"17%"} }
							  },
							  {
								  label: s.base.app.config.lang.tip_raspodele,
								  name: s.base.app.config.SCPN + 'tip_raspodele', 
								  index:'tip_raspodele', 
								  xmlmap:'tip_raspodele',
								  width:120, 
								  sortable:true,
								  editable:true,
								  editoptions:{ disabled:"disabled" },
								  hidden:true,
								  editrules:{edithidden:true},
								  formatter:function( cellvalue, options, cellobject ){
				
									  var name = "";
									  switch( parseInt( cellvalue ) )
									  {
										  case 1:
										  name = "Мин. Гаранција";
										  break;
										  
										  case 2:
										  name = "Уговорени износ";
										  break;
										  
										  case 3:
										  name = "Расподела";
										  break;
									  }
									  return name;
								  }
							  }, 
							  {
								  label: s.base.app.config.lang.raspodela_iznos,
								  name: s.base.app.config.SCPN + 'raspodela_iznos', 
								  index:'raspodela_iznos', 
								  xmlmap:'raspodela_iznos',
								  width:70, 
								  sortable:true,
								  editable:true,
								  editoptions:{ disabled:"disabled" },
								  hidden:true,
								  editrules:{edithidden:true}
							  }, 
							  {
								  label: s.base.app.config.lang.raspodela_prikazivac,
								  name: s.base.app.config.SCPN + 'raspodela_prikazivac', 
								  index:'raspodela_prikazivac', 
								  xmlmap:'raspodela_prikazivac',
								  width:70, 
								  sortable:true,
								  editable:true,
								  editoptions:{ disabled:"disabled" },
								  hidden:true,
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.datum_unosa,
								  name: s.base.app.config.SCPN + 'datum_unosa', 
								  index:'datum_unosa', 
								  xmlmap:'datum_unosa',
								  formatter:'date',
								  formatoptions:{ srcformat:'Y-m-d', newformat:'d/m/Y' },
								  width:70, 
								  sortable:true,
								  editable:true,
								  editoptions:{ disabled:"disabled" },
								  hidden:true,
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.beleska,
								  name: s.base.app.config.SCPN + 'rokovnik_note', 
								  index:'rokovnik_note', 
								  xmlmap:'rokovnik_note',
								  width:70, 
								  sortable:false,
								  editable:true,
								  edittype:"textarea",
								  editoptions:{ rows:3, cols:25}
							  },
							  {
								  label: s.base.app.config.lang.suma_rsd,
								  name: s.base.app.config.SCPN + 'suma_zarada_rsd', 
								  index:'suma_zarada_rsd', 
								  xmlmap:'suma_zarada_rsd',
								  width:70, 
								  sortable:true
							  },
							  {
								  label: s.base.app.config.lang.suma_eur,
								  name: s.base.app.config.SCPN + 'suma_zarada_eur', 
								  index:'suma_zarada_eur', 
								  xmlmap:'suma_zarada_eur',
								  width:70, 
								  sortable:true
							  },
							  {
								  label: s.base.app.config.lang.suma_km,
								  name: s.base.app.config.SCPN + 'suma_zarada_km', 
								  index:'suma_zarada_km', 
								  xmlmap:'suma_zarada_km',
								  width:70, 
								  sortable:true
							  },
							  {
								  label: s.base.app.config.lang.suma_n_rsd,
								  name: s.base.app.config.SCPN + 'suma_zarada_naocare_rsd', 
								  index:'suma_zarada_naocare_rsd', 
								  xmlmap:'suma_zarada_naocare_rsd',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editoptions:{ disabled:"disabled" },
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.suma_n_eur,
								  name: s.base.app.config.SCPN + 'suma_zarada_naocare_eur', 
								  index:'suma_zarada_naocare_eur', 
								  xmlmap:'suma_zarada_naocare_eur',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editoptions:{ disabled:"disabled" },
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.suma_n_km,
								  name: s.base.app.config.SCPN + 'suma_zarada_naocare_km', 
								  index:'suma_zarada_naocare_km', 
								  xmlmap:'suma_zarada_naocare_km',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editoptions:{ disabled:"disabled" },
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.suma_gledanosti,
								  name: s.base.app.config.SCPN + 'suma_gledanosti_kopije', 
								  index:'suma_gledanosti_kopije', 
								  xmlmap:'suma_gledanosti_kopije',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editoptions:{ disabled:"disabled" },
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.suma_naocara,
								  name: s.base.app.config.SCPN + 'suma_prodatih_naocara_kopije', 
								  index:'suma_prodatih_naocara_kopije', 
								  xmlmap:'suma_prodatih_naocara_kopije',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editoptions:{ disabled:"disabled" },
								  editrules:{edithidden:true}
							  },
							  {
								  label: s.base.app.config.lang.eurocent,
								  name: s.base.app.config.SCPN + 'eurocent', 
								  index:'eurocent', 
								  xmlmap:'eurocent',
								  width:50, 
								  sortable:true,
								  editable:true
							  },
							  {
								  label: s.base.app.config.lang.eurocent_eur,
								  name: s.base.app.config.SCPN + 'naocare_eurocent_eur', 
								  index:'naocare_eurocent_eur', 
								  xmlmap:'naocare_eurocent_eur',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editable:true,
								  editrules:{edithidden:true},
								  editoptions:{ disabled:"disabled" }
							  },
							  {
								  label: s.base.app.config.lang.eurocent_rsd,
								  name: s.base.app.config.SCPN + 'naocare_eurocent_rsd', 
								  index:'naocare_eurocent_rsd', 
								  xmlmap:'naocare_eurocent_rsd',
								  width:70, 
								  sortable:true,
								  editable:true,
								  hidden:true,
								  editable:true,
								  editrules:{edithidden:true},
								  editoptions:{ disabled:"disabled" }
							  }
							],
							// end of col model
							
							onCellSelect:function( rowid, iCol, cellcontent, e ){
								
								s.selectedRokovnikRowId = rowid;
							},
							
							loadComplete:function(){
								s.selectedRokovnikRowId = null;
							},
							
							serializeGridData:function( p ){
								
								if( s.advancedSearch == true )
								{
									p[ 'advanced_search'] = true;
									$( "#rokovnici-pretraga-form .pretraga_input" ).each(function(index, element) {
										p[ element.name ] = element.value;
									});
								}
								else
								{
									p[ 'advanced_search'] = false;
									$( "#rokovnici-pretraga-form .pretraga_input" ).each(function(index, element) {
										p[ element.name ] = '';
									});
								}
									
								return p;
							},
							
							afterEditCell:function( rowid, cellname, value, iRow, iCol )
							{
								if(  cellname.indexOf( 'datum_kopije') == -1 && cellname.indexOf( "datum" ) != -1 )
								{
									$( "#" + iRow + "_" + cellname ).datepicker( { dateFormat: "yy-mm-dd", 
																					monthNames:s.base.config.monthNames,
																					onSelect:function( dayText, inst ){
																					
																						var dm = Date.parse( dayText );
																						var d = new Date( dm );
																						var nd = null;
																						
																						if( d.getDay() == 6 ||d.getDay() == 0 )
																						{
																							// its Saturday
																							if( d.getDay() == 6 )
																							{
																								nd = new Date( dm + 2 * 24 * 60 * 60 * 1000 );
																							}
																							else
																							{
																								//its Sunday
																								nd = new Date( dm + 24 * 60 * 60 * 1000 );
																							}
																							
																							var m = ( nd.getMonth() + 1 );
																							if( m.toString().length == 1 ) m = "0" + m;
																							
																							var d = nd.getDate();
																							if( d.toString().length == 1 ) d = "0" + d;
																							
																							$( this ).val( nd.getFullYear() + "-" + m + "-" + d );
																							
																							alert( s.base.app.config.lang.odabran_vikend );
																						}
																						
																					}} );
								}
							},
						
							pager: '#rokovnici-grid-pager',
							emptyrecords: s.base.app.config.lang.nema_podataka,
							rowNum:25,
							rowList:[10,25,60, 100],
							sortname: 'rokovnik_id',
							sortorder: 'desc',
							viewrecords: true,
							gridview: true,
							caption: s.base.app.config.lang.rokovnici
						
						  }).navGrid('#rokovnici-grid-pager',{view:true, search: false, edit:false, del:false, refresh:true, add:false} ); 
						  // END OF ROKOVNICI GRID

		/**
		$("#promeni-rokovnik" ).click(function( e ) {
			
			if( s.selectedRokovnikRowId )
			{
					s.rokovniciGrid.editGridRow( s.selectedRokovnikRowId, {
									width:500,
									editCaption: "Детаљи Роковника",
									modal:true,
									bYes : "Да",
									bNo : "Бе",
									bExit : "Откажи",
									onInitializeForm:function(){
										$( "#" + s.base.app.config.SCPN + "datum_prijema_kopije" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "datum_otpreme_kopije" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "nacin_prijema_kopije" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "nacin_otpreme_kopije" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "otpremiti_kopiju_od" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "primiti_kopiju_od" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "rokovnik_note" ).attr( "disabled", true );
										$( "#" + s.base.app.config.SCPN + "status_kopije" ).attr( "disabled", true );
										
										$( "#TblGrid_rokovnici-grid_2 .EditButton" ).empty();
									},
									afterComplete : function (response, postdata, formid){
										
										//console.log( response.responseText );
									} 
							} );
							
				
			}
			else
			{
				alert( "Одаберите роковник који желите да промените!" );
			}

		});
		**/
		
		$("#create-z_gledanost-btn" ).click(function( e ) {
			
			if( s.selectedRokovnikRowId )
			{
				s.base.app.setInfoText( $( "#zgledanost-info-text" ), "" );
				$( '#datum_z_gledanosti_od' ).val( "" );
				$( '#datum_z_gledanosti_do' ).val( " ");
				
				$( "#save-zvanicna-gledanost-btn" ).removeAttr( "disabled" );
				s.zvanicnaGledanostDialog.dialog( "open" );
			}
			else
			{
				alert( s.base.app.config.lang.odaberite_rokovnik );
			}
		});
		
		
		$("#create-izvestaj-btn" ).click(function( e ) {
			
			s.base.app.setInfoText( $( "#izvestaj-info-text" ), "" );
			$( '#datum_z_gledanosti_od' ).val( "" );
			$( '#datum_z_gledanosti_do' ).val( " ");
			s.IzvestajDialog.dialog( "open" );

	});
		
		
		
		
		
		
		$( "#napredna-pretraga-rokovnika" ).click(function() {
			
				if( ! s.naprednaPretragaDialog )
				{
					
					var buttons = {};
						buttons[ s.base.app.config.lang.trazi ] = function() {
											s.advancedSearch = true;
											s.rokovniciGrid.trigger( "reloadGrid" );	
						};
						
						buttons[ s.base.app.config.lang.resetuj ] = function() {
											//s.naprednaPretragaDialog.dialog( "close" );
											s.advancedSearch = false;
											$('#rokovnici-pretraga-form').each (function(){
												  this.reset();
											});
											
											setTimeout(function(){
												s.rokovniciGrid.trigger( "reloadGrid" );
											}, 100 );
						};
					
					s.naprednaPretragaDialog = $( "#rokovnici-napredna-pretraga-dialog" ).dialog({
								title: s.base.app.config.lang.napredna_pretraga,
								autoOpen: false,
								height: 480,
								width: 1000,
								modal: false,
								create:function(){
									console.log( "create" );
									
									$( "#datum_prijema_rokovnika_pretraga_input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames} );
									$( "#datum_otpreme_rokovnika_pretraga_input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames} );
									$( "#datum_kopije_od_rokovnika_pretraga_input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames} );
									$( "#datum_kopije_do_rokovnika_pretraga_input" ).datepicker( { dateFormat: 'yy-mm-dd', monthNames:s.base.config.monthNames} );
								},
								buttons:buttons,
									
								close: function() {
									
								}
								
					});
				}
					
				s.naprednaPretragaDialog.dialog( "open" );
			});
		
 		}
	});
}

