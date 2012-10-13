<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />

<title>Distribucija Filmova v.3.0</title>

<!-- INCLUDE CSS -->
<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>css/layout-default.css"/>
<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>css/main_menu.css"/>
<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>css/styles.css"/>


<!-- INCLUDE CSS THEME UI -->
<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>js/jquery-ui/css/smoothness/jquery-ui-1.8.24.custom.css"/>


<!-- INCLUDE CSS COMPONENTS -->
<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>js/components/colorPicker/css/colorpicker.css"/>

<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>js/components/fullCalendar/css/fullcalendar.css"/>

<!-- THIS INCLUDE ONLY WHEN PRINTING CALENDAR -->
<!-- <link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>js/components/fullCalendar/css/fullcalendar.print.css"/> -->

<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>js/components/jqGrid/css/ui.jqgrid.css"/>
<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>js/components/jqGrid/css/ui.multiselect.css"/>



<!-- INCLUDE JS LIBRARIES -->
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/jQuery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/common_functions.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/jquery.numeric.js"></script>


<!-- INCLUDE JS THEME UI -->
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/jquery-ui/jquery-ui-1.8.24.custom.min.js"></script>



<!-- INCLUDE JS COMPONENTS -->
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/components/colorPicker/colorpicker.js"></script>


<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/components/fullCalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/components/fullCalendar/gcal.js"></script>


<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/components/jqGrid/i18n/grid.locale-sr-latin.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/components/jqGrid/jquery.jqGrid.min.js"></script>




<!-- INCLUDE JS APS -->
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/app.js"></script>


<!-- INCLUDE JS MODULES -->
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/ModuleBase.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/filmovi.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/konkurentskiFilmovi.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/glumci.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/kursnaLista.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/komitenti.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/zakljucnice.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/rokovnici.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/gledanost.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/zvanicnaGledanost.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/fakture.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/settings.js"></script>
<script type="text/javascript" src="<?php echo BASE_URI_RESOURCE; ?>js/modules/izvestaji.js"></script>

<style type="text/css">


</style>
 
<!-- INIT JS APP -->
<script type="text/javascript">

var config = { baseUri: "<?php echo BASE_URI_SERVICE; ?>", SCPN:"<?php echo SAVE_CELL_PREFIX_NAME;?>", ICP: "<?php echo INDEX_CELL_PREFIX_NAME;?>" };
	config.paramNames = { 
							sort:  config.ICP + "sort_col_name", 
							order: config.ICP + "sort_order_name", 
							rows:  config.ICP + "rows_per_page" , 
							page:  config.ICP + "page_number"
						};  
	

	config.errorCodes = {

			accessDenied:<?php echo ErrorCodes::ACCESS_DENIED ?>,
			alreadyExists:<?php echo ErrorCodes::ALREADY_EXISTS ?>,
			app:<?php echo ErrorCodes::APP_ERROR ?>,
			database:<?php echo ErrorCodes::DATABASE_ERROR ?>,
			general:<?php echo ErrorCodes::GENERAL_ERROR ?>,
			invalidInput:<?php echo ErrorCodes::INVALID_INPUT ?>,
			serverFailed:<?php echo ErrorCodes::SERVER_FAILED ?>,
			session:<?php echo ErrorCodes::SESSION_ERROR ?>,
			sessionExpired:<?php echo ErrorCodes::SESSION_EXPIRED ?>,
			userNotFound:<?php echo ErrorCodes::USER_NOT_FOUND ?>,		
			userOrPassWrong:<?php echo ErrorCodes::USER_OR_PASS_WRONG ?>,
			gledanostSavedTerminiFailed:<?php echo ErrorCodes::GLEDANOST_SAVED_TERMINI_FAILED ?>
															
					
	};


	//config.monthNames = ['Јануар', 'Фебруар', 'Март', 'Април', 'Мај', 'Јун', 'Јул', 'Август', 'Септембар', 'Октобар', 'Новембар', 'Децембар'];
	config.monthNames = ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Jun', 'Jul', 'Avgus', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'];

var lang = {};

	  <?php 
	  
	  	foreach( $language as  $k => $v )
	  	{
	  		echo "lang[ '$k' ] = '$v';";
	  	}
			  
	  ?>
	  
	 config.lang = lang;
	  	
var app = new MoviesApp( config );

$( document ).ready( function() {


	var timeout = null;
	var initialMargin = parseInt($("#main-menu-cnt").css("margin-top"));
	 
	$("#main-menu-cnt").hover( function() {
		if (timeout) {
		clearTimeout(timeout);
		timeout = null;
		}
		
	$(this).animate({ marginTop: 0 }, 'fast');
	
	},

	function() {
		var menuBar = $(this);
		timeout = setTimeout(function() {
		timeout = null;
		menuBar.animate({ marginTop: initialMargin }, 'slow');
		}, 500);
		}
	);
	
	app.init();
	
});



</script>

</head>

<body>

<div id="content-all" align="center">

	<div id="main-menu-cnt">

		<ul id="main-menu">
			<li title="<?php echo $language[ 'filmovi' ]; ?>" class="main-menu-item" id="filmovi-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/filmovi.png";?>" /></li>
			<li title="<?php echo $language[ 'konkurentski_filmovi' ]; ?>" class="main-menu-item" id="konkurentskiFilmovi-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/konkurentskiFilmovi.png";?>" /></li>
			<li title="<?php echo $language[ 'glumci' ]; ?>" class="main-menu-item" id="glumci-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/glumci.png";?>" /></li>
			<li title="<?php echo $language[ 'komitenti' ]; ?>" class="main-menu-item" id="komitenti-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/komitenti.png";?>" /></li>
			<li title="<?php echo $language[ 'kursna_lista' ]; ?>" class="main-menu-item" id="kursnaLista-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/kursnaLista.png";?>" /></li>
			<li title="<?php echo $language[ 'zakljucnice' ]; ?>" class="main-menu-item" id="zakljucnice-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/zakljucnice.png";?>" /></li>
			<li title="<?php echo $language[ 'rokovnici' ]; ?>" class="main-menu-item" id="rokovnici-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/rokovnici.png";?>" /></li>
			<li title="<?php echo $language[ 'dnevna_gledanost' ]; ?>" class="main-menu-item" id="gledanost-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/gledanost.png";?>" /></li>
			<li title="<?php echo $language[ 'zvanicna_gledanost' ]; ?>" class="main-menu-item" id="zvanicnaGledanost-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/zvanicnaGledanost.png";?>" /></li>
			<li title="<?php echo $language[ 'fakture' ]; ?>" class="main-menu-item" id="fakture-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/fakture.png";?>" /></li>
			<li title="<?php echo $language[ 'izvestaji' ]; ?>" class="main-menu-item" id="izvestaji-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/izvestaji.png";?>" /></li>


			<li title="<?php echo $language[ 'podesavanja' ]; ?>"  class="main-menu-item" id="settings-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/podesavanja.png";?>" /></li>
			<li title="<?php echo $language[ 'izloguj_se' ]; ?>"  class="main-menu-item" id="logout-menu-item"><img src="<?php echo BASE_URI_RESOURCE . "images/moduleIcons/logout.png";?>" /></li>
			
		</ul>

	</div>
	
	<div id="module-cnt"></div>
	
</div>

</body>
</html>
