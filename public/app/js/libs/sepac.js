//---------------------------------------------------------------//
//------------- inicio: valida dinheiro -------------------------//
//---------------------------------------------------------------//
function valores_corrente(fld, milSep, decSep, e) {
	//milSep = (milSep) ? milSep : '.';
	//decSep = (decSep) ? decSep : ',';
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;

if (whichCode == 13) return true;  // Enter
if (whichCode == 8) return true;  // Delete
if (whichCode == 9) return true; // Tab
if (whichCode == 11) return true; // Tab
key = String.fromCharCode(whichCode);  // Get key value from key code
if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
//len = fld.value.length;
len = 19;
for(i = 0; i < len; i++)
	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
		if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
		aux += key;
		len = aux.length;
		if (len == 0) fld.value = '';
		if (len == 1) fld.value = '0'+ decSep + '0' + aux;
		if (len == 2) fld.value = '0'+ decSep + aux;
		if (len > 2) {
			aux2 = '';
			for (j = 0, i = len - 3; i >= 0; i--) {
				if (j == 3) {
					aux2 += milSep;
					j = 0;
				}
				aux2 += aux.charAt(i);
				j++;
			}
			fld.value = '';
			len2 = aux2.length;
		
		for (i = len2 - 1; i >= 0; i--)
			fld.value += aux2.charAt(i);
		fld.value += decSep + aux.substr(len - 2, len);
		}
	return false;
}


function number_format(string,decimals=2,decimal=',',thousands='.',pre='R$ '){
  var numbers = string.toString().match(/\d+/g).join([]);
  numbers = numbers.padStart(decimals+1, "0");
  var splitNumbers = numbers.split("").reverse();
  var mask = '';
  splitNumbers.forEach(function(d,i){
    if (i == decimals) { mask = decimal + mask; }
    if (i>(decimals+1) && ((i-2)%(decimals+1))==0) { mask = thousands + mask; }
    mask = d + mask;
  });
  return pre + mask;
}


// -- Formata o numero na entrada especificamente para o campo Valor Total do Item, que passa a aceitar
// -- valores negativos.
// -- Utilização:
// -- onKeyPress="return(currencyFormat(this,",",".",event))
function currencyFormatValorItem(fld, milSep, decSep, e) {
var sep = 0;
var key = "";
var i = j = 0;
var len = len2 = 0;
var strCheck = "-0123456789";
var aux = aux2 = "";
var whichCode = (window.Event) ? e.which : e.keyCode;
if (whichCode == 13) return true; // Enter
if (whichCode == 0) return true; // Delete
key = String.fromCharCode(whichCode); // Get key value from key code
if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
len = fld.value.length;
for(i = 0; i < len; i++)
if ((fld.value.charAt(i) != "0") && (fld.value.charAt(i) != decSep)) break;
aux = "";
for(; i < len; i++)
if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
aux += key;
len = aux.length;
if (len == 0) fld.value = "";
if (len == 1) fld.value = "0"+ decSep + "0" + aux;
if (len == 2) fld.value = "0"+ decSep + aux;
if (len > 2) {
aux2 = "";
for (j = 0, i = len - 3; i >= 0; i--) {
if (j == 3) {
aux2 += milSep;
j = 0;
}
aux2 += aux.charAt(i);
j++;
}
fld.value = "";
len2 = aux2.length;
for (i = len2 - 1; i >= 0; i--)
fld.value += aux2.charAt(i);
fld.value += decSep + aux.substr(len - 2, len);
}
return false;}










function mascara_num(obj){
  valida_num(obj)
  if (obj.value.match("-")){
    mod = "-";
  }else{
    mod = "";
  }
  valor = obj.value.replace("-","");
  valor = valor.replace(",","");
  if (valor.length >= 3){
    valor = poe_ponto_num(valor.substring(0,valor.length-2))+","+valor.substring(valor.length-2, valor.length);
  }
  obj.value = mod+valor;
}
function poe_ponto_num(valor){
  valor = valor.replace(/\./g,"");
  if (valor.length > 3){
    valores = "";
    while (valor.length > 3){
      valores = "."+valor.substring(valor.length-3,valor.length)+""+valores;
      valor = valor.substring(0,valor.length-3);
    }
    return valor+""+valores;
  }else{
    return valor;
  }
}
function valida_num(obj){
  numeros = new RegExp("[0-9]");
  while (!obj.value.charAt(obj.value.length-1).match(numeros)){
    if(obj.value.length == 1 && obj.value == "-"){
      return true;
    }
    if(obj.value.length >= 1){
      obj.value = obj.value.substring(0,obj.value.length-1)
    }else{
      return false;
    }
  }
}

function gravahistorico(modulo, tipo, msg) {
	$.ajax({
		type: "POST",
		url: "processa/processa_grava_historico.php",
		data: {modulo: modulo, tipo: tipo, msg: msg}
	});
}


