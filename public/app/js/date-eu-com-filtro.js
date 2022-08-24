/**
 * Similar to the Date (dd/mm/YY) data sorting plug-in, this plug-in offers 
 * additional  flexibility with support for spaces between the values and
 * either . or / notation for the separators.
 *
 * Please note that this plug-in is **deprecated*. The
 * [datetime](//datatables.net/blog/2014-12-18) plug-in provides enhanced
 * functionality and flexibility.
 *
 *  @name Date (dd . mm[ . YYYY]) 
 *  @summary Sort dates in the format `dd/mm/YY[YY]` (with optional spaces)
 *  @author [Robert Sedovšek](http://galjot.si/)
 *  @deprecated
 *
 *  @example
 *    $('#example').dataTable( {
 *       columnDefs: [
 *         { type: 'date-eu-com-filtro', targets: 0 }
 *       ]
 *    } );
 */
  

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	"date-eu-com-filtro-pre": function ( date ) {
		//date = parseFloat(data.split('>')[1].split('<')[0].replace(/[' ']/g, '' ));
		//date = date.replace(" ", "");
		
		if ( !date ) {
			return 0;
		} else {
			 if (date.match('<')) {
				date = date.split('>')[1].split('<')[0];
				if (date.match('&nbsp') || date == '' || !date) {
					return 0;
				}
			}
		}
		
		if (date.match('Janeiro')){
			date = date.replace("Janeiro", '1');
		} else if (date.match('Fevereiro')) {
			date = date.replace("Fevereiro", '2');
		}else if (date.match('Mar\u00e7o')) {
			date = date.replace("Mar\u00e7o", '3');
		} else if (date.match('Abril')) {
			date = date.replace("Abril", '4');
		} else if (date.match('Maio')) {
			date = date.replace("Maio", '5');
		} else if (date.match('Junho')) {
			date = date.replace("Junho", '6');
		} else if (date.match('Julho')) {
			date = date.replace("Julho", '7');
		} else if (date.match('Agosto')) {
			date = date.replace("Agosto", '8');
		} else if (date.match('Setembro')) {
			date = date.replace("Setembro", '9');
		} else if (date.match('Outubro')) {
			date = date.replace("Outubro", '10');
		} else if (date.match('Novembro')) {
			date = date.replace("Novembro", '11');
		} else if (date.match('Dezembro')) {
			date = date.replace("Dezembro", '12');
		}

		var year;
		var eu_date = date.split(/[\.\-\/]/);

		/*year (optional)*/
		if ( eu_date[2] ) {
			year = eu_date[2];
		}
		else {
			year = 0;
		}

		/*month*/
		var month = eu_date[1];
		if ( month.length == 1 ) {
			month = 0+month;
		}

		/*day*/
		var day = eu_date[0];
		if ( day.length == 1 ) {
			day = 0+day;
		}

		return (year + month + day) * 1;
	},

	"date-eu-com-filtro-asc": function ( a, b ) {
		return ((a < b) ? -1 : ((a > b) ? 1 : 0));
	},

	"date-eu-com-filtro-desc": function ( a, b ) {
		return ((a < b) ? 1 : ((a > b) ? -1 : 0));
	}
} );