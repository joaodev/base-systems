jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "valoresemreais-asc": function ( t, u ) {
		var a =  t.replace(/.*>(.*)<.*/, '$1');
		var b =  u.replace(/.*>(.*)<.*/, '$1');
		var aa = a.replace("R$ ", "");
		var bb = b.replace("R$ ", "");
		var aaa = aa.replace(".", "");
		var bbb = bb.replace(".", "");
		var xx = (a == "-") ? 0 : aaa.replace(".", "");
		var yy = (b == "-") ? 0 : bbb.replace(".", "");
		var x = (a == "-") ? 0 : xx.replace(/,/, ".");
		var y = (b == "-") ? 0 : yy.replace(/,/, ".");
		x = parseFloat(x);
		y = parseFloat(y);
		return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    },
    "valoresemreais-desc": function ( t, u ) {
		var a =  t.replace(/.*>(.*)<.*/, '$1');
		var b =  u.replace(/.*>(.*)<.*/, '$1');
    	var aa = a.replace("R$ ", "");
		var bb = b.replace("R$ ", "");
		var aaa = aa.replace(".", "");
		var bbb = bb.replace(".", "");
  		var xx = (a == "-") ? 0 : aaa.replace(".", "");
		var yy = (b == "-") ? 0 : bbb.replace(".", "");
		var x = (a == "-") ? 0 : xx.replace(/,/, ".");
		var y = (b == "-") ? 0 : yy.replace(/,/, ".");
		x = parseFloat(x);
		y = parseFloat(y);
		return ((x > y) ? -1 : ((x < y) ? 1 : 0));
    }
} );