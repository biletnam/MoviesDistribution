;(function($){
/**
 * jqGrid Serbian Translation
 * Александар Миловац(Aleksandar Milovac) aleksandar.milovac@gmail.com
 * http://trirand.com/blog/
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.jgrid = {
	defaults : {
		recordtext: "Pregled {0} - {1} od {2}",
		emptyrecords: "Ne postoji ni jedan zapis",
		loadtext: "Učitavanje...",
		pgtext : "Strana {0} od {1}"
	},
	search : {
		caption: "Trazenje...",
		Find: "Trazi",
		Reset: "Resetuj",
		odata : ['jednako', 'nije jednako', 'manje', 'manje ili jednako','vece','vece ili jednako', 'počinje sa','ne počinje sa','je u','nije u','završava sa','ne završava sa','sadrzi','ne sadrzi'],
		groupOps: [	{ op: "I", text: "svi" },	{ op: "ILI",  text: "svaki" }	],
		matchText: " poklapa sa",
		rulesText: " pravila"
	},
	edit : {
		addCaption: "Dodaj zapis",
		editCaption: "Izmeni zapis",
		bSubmit: "Pošalji",
		bCancel: "Odustani",
		bClose: "Zatvori",
		saveData: "Podatak je izmenjen! Sačuvaj izmene?",
		bYes : "Da",
		bNo : "Ne",
		bExit : "Odustani",
		msg: {
			required:"Polje je obavezno",
			number:"Molim, unesite ispravan broj",
			minValue:"vrednost mora biti veća ili jednaka sa",
			maxValue:"vrednost mora biti manja ili jednaka sa",
			email: "nije ispravna email adresa",
			integer: "Molim, unesite ispravnu celobrojnu vrednost ",
			date: "Molim, unesite ispravan datum",
			url: "nije ispravan URL. Potreban je prefix ('http://' ili 'https://')",
			nodefined : " nije definisan!",
			novalue : " zahtevana je povratna vrednost!",
			customarray : "Custom function should return skup!",
			customfcheck : "Custom function should be present in case of custom checking!"
			
		}
	},
	view : {
		caption: "Pogledaj zapis",
		bClose: "Zatvori"
	},
	del : {
		caption: "Izbriši",
		msg: "Izbriši izabran(е) zapis(е)?",
		bSubmit: "Izbriši",
		bCancel: "Odbaci"
	},
	nav : {
		edittext: "",
		edittitle: "Izmeni izabrani red",
		addtext:"",
		addtitle: "Dodaj novi red",
		deltext: "",
		deltitle: "Izbriši jedan red",
		searchtext: "",
		searchtitle: "Nadji zapise",
		refreshtext: "",
		refreshtitle: "Ponovo učitaj podatke",
		alertcap: "Upozorenje",
		alerttext: "Molim, izaberite red",
		viewtext: "",
		viewtitle: "Pogledaj izabrani red"
	},
	col : {
		caption: "Izaberi kolone",
		bSubmit: "OK",
		bCancel: "Odbaci"
	},
	errors : {
		errcap : "Greška",
		nourl : "Nije postavljen URL",
		norecords: "Nema zapisa za obradu",
		model : "Duzina modela ne odgovara duzini kolona!"
	},
	formatter : {
		integer : {thousandsSeparator: " ", defaultValue: '0'},
		number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'},
		currency : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, prefix: "", suffix:"", defaultValue: '0.00'},
		date : {
			dayNames:   [
				"Ned", "Pon", "Uto", "Sre", "Čet", "Pet", "Sub",
				"Nedelja", "Ponedeljak", "Utorak", "Sreda", "Četvrtak", "Petak", "Subota"
			],
			monthNames: [
				"Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Avg", "Sep", "Okt", "Nov", "Dec",
				"Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"
			],
			AmPm : ["am","pm","AM","PM"],
			S: function (j) {return j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th'},
			srcformat: 'Y-m-d',
			newformat: 'd/m/Y',
			masks : {
				ISO8601Long:"Y-m-d H:i:s",
				ISO8601Short:"Y-m-d",
				ShortDate: "n/j/Y",
				LongDate: "l, F d, Y",
				FullDateTime: "l, F d, Y g:i:s A",
				MonthDay: "F d",
				ShortTime: "g:i A",
				LongTime: "g:i:s A",
				SortableDateTime: "Y-m-d\\TH:i:s",
				UniversalSortableDateTime: "Y-m-d H:i:sO",
				YearMonth: "F, Y"
			},
			reformatAfterEdit : false
		},
		baseLinkUrl: '',
		showAction: '',
		target: '',
		checkbox : {disabled:true},
		idName : 'id'
	}
};
})(jQuery);
