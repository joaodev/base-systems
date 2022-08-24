// ------------------------------------ DATA --------------------------------------//
// --------------------------------------------------------------------------------//
// ------------------------------------ DATA --------------------------------------//

var isNav4 = false, isNav5 = false, isIE4 = false
var strSeperator = "/"; 
// If you are using any Java validation on the back side you will want to use the / because 
// Java date validations do not recognize the dash as a valid date separator.
var vDateType = 3; // Global value for type of date format
//                1 = mm/dd/yyyy
//                2 = yyyy/dd/mm  (Unable to do date check at this time)
//                3 = dd/mm/yyyy
var vYearType = 4; //Set to 2 or 4 for number of digits in the year for Netscape
var vYearLength = 4; // Set to 4 if you want to force the user to enter 4 digits for the year before validating.
var err = 0; // Set the error code to a default of zero
if(navigator.appName == "Netscape") {
if (navigator.appVersion < "5") {
isNav4 = true;
isNav5 = false;
}
else
if (navigator.appVersion > "4") {
isNav4 = false;
isNav5 = true;
   }
}
else {
isIE4 = true;
}

function DateFormat(vDateName, vDateValue, e, dateCheck, dateType) {
vDateType = dateType;
// vDateName = object name
// vDateValue = value in the field being checked
// e = event
// dateCheck 
// True  = Verify that the vDateValue is a valid date
// False = Format values being entered into vDateValue only
// vDateType
// 1 = mm/dd/yyyy
// 2 = yyyy/mm/dd
// 3 = dd/mm/yyyy
//Enter a tilde sign for the first number and you can check the variable information.
if (vDateValue.length >=10) {
    dateCheck = true;
}

if (vDateValue == "~") {
alert("AppVersion = "+navigator.appVersion+" \nNav. 4 Version = "+isNav4+" \nNav. 5 Version = "+isNav5+" \nIE Version = "+isIE4+" \nYear Type = "+vYearType+" \nDate Type = "+vDateType+" \nSeparator = "+strSeperator);
vDateName.value = "";
vDateName.focus();
return true;
}
var whichCode = (window.Event) ? e.which : e.keyCode;
// Check to see if a seperator is already present.
// bypass the date if a seperator is present and the length greater than 8
if (vDateValue.length > 8 && isNav4) {
if ((vDateValue.indexOf("-") >= 1) || (vDateValue.indexOf("/") >= 1))
return true;
}
//Eliminate all the ASCII codes that are not valid
var alphaCheck = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/-";
if (alphaCheck.indexOf(vDateValue) >= 1) {
if (isNav4) {
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
else {
//comentado em 19/06/07
//vDateName.value = vDateName.value.substr(0, (vDateValue.length-1));
return false;
   }
}
if (whichCode == 8) //Ignore the Netscape value for backspace. IE has no value
return false;
else {
//Create numeric string values for 0123456789/
//The codes provided include both keyboard and keypad values
var strCheck = '47,48,49,50,51,52,53,54,55,56,57,58,59,95,96,97,98,99,100,101,102,103,104,105';
if (strCheck.indexOf(whichCode) != -1) {
if (isNav4) {
if (((vDateValue.length < 6 && dateCheck) || (vDateValue.length == 7 && dateCheck)) && (vDateValue.length >=1)) {
alert("Data Inválida\nPor Favor corrija este campo.");
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
if (vDateValue.length == 6 && dateCheck) {
var mDay = vDateName.value.substr(2,2);
var mMonth = vDateName.value.substr(0,2);
var mYear = vDateName.value.substr(4,4)
//Turn a two digit year into a 4 digit year
if (mYear.length == 2 && vYearType == 4) {
var mToday = new Date();
//If the year is greater than 30 years from now use 19, otherwise use 20
var checkYear = mToday.getFullYear() + 50; 
var mCheckYear = '20' + mYear;
if (mCheckYear >= checkYear)
mYear = '19' + mYear;
else
mYear = '20' + mYear;
}
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (!dateValid(vDateValueCheck)) {
alert("Data Inválida\nCaso a data não seja arrumada, o Sistema irá ignorar esta resposta");
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
return true;
}
else {
// Reformat the date for validation and set date type to a 1
if (vDateValue.length >= 8  && dateCheck) {
if (vDateType == 1) // mmddyyyy
{
var mDay = vDateName.value.substr(2,2);
var mMonth = vDateName.value.substr(0,2);
var mYear = vDateName.value.substr(4,4)
vDateName.value = mMonth+strSeperator+mDay+strSeperator+mYear;
}
if (vDateType == 2) // yyyymmdd
{
var mYear = vDateName.value.substr(0,4)
var mMonth = vDateName.value.substr(4,2);
var mDay = vDateName.value.substr(6,2);
vDateName.value = mYear+strSeperator+mMonth+strSeperator+mDay;
}
if (vDateType == 3) // ddmmyyyy
{
var mMonth = vDateName.value.substr(2,2);
var mDay = vDateName.value.substr(0,2);
var mYear = vDateName.value.substr(4,4)
vDateName.value = mDay+strSeperator+mMonth+strSeperator+mYear;
}
//Create a temporary variable for storing the DateType and change
//the DateType to a 1 for validation.
var vDateTypeTemp = vDateType;
vDateType = 1;
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (!dateValid(vDateValueCheck)) {
alert("Data Inválida\nPor Favor corrija este campo.");
vDateType = vDateTypeTemp;
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
vDateType = vDateTypeTemp;
return true;
}
else {
if (((vDateValue.length < 8 && dateCheck) || (vDateValue.length == 9 && dateCheck)) && (vDateValue.length >=1)) {
alert("Data Inválida\nPor Favor corrija este campo.");
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
         }
      }
   }
}
else {
// Non isNav Check
if (((vDateValue.length < 8 && dateCheck) || (vDateValue.length == 9 && dateCheck)) && (vDateValue.length >=1)) {
alert("Data Inválida\nPor Favor corrija este campo.");
vDateName.value = "";
vDateName.focus();
return true;
}
// Reformat date to format that can be validated. mm/dd/yyyy
if (vDateValue.length >= 8 && dateCheck) {
// Additional date formats can be entered here and parsed out to
// a valid date format that the validation routine will recognize.
if (vDateType == 1) // mm/dd/yyyy
{
var mMonth = vDateName.value.substr(0,2);
var mDay = vDateName.value.substr(3,2);
var mYear = vDateName.value.substr(6,4)
}
if (vDateType == 2) // yyyy/mm/dd
{
var mYear = vDateName.value.substr(0,4)
var mMonth = vDateName.value.substr(5,2);
var mDay = vDateName.value.substr(8,2);
}
if (vDateType == 3) // dd/mm/yyyy
{
var mDay = vDateName.value.substr(0,2);
var mMonth = vDateName.value.substr(3,2);
var mYear = vDateName.value.substr(6,4)
}
if (vYearLength == 4) {
if (mYear.length < 4) {
alert("Data Inválida\nPor Favor corrija este campo.");
vDateName.value = "";
vDateName.focus();
return true;
   }
}
// Create temp. variable for storing the current vDateType
var vDateTypeTemp = vDateType;
// Change vDateType to a 1 for standard date format for validation
// Type will be changed back when validation is completed.
vDateType = 1;
// Store reformatted date to new variable for validation.
var vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
if (mYear.length == 2 && vYearType == 4 && dateCheck) {
//Turn a two digit year into a 4 digit year
var mToday = new Date();
//If the year is greater than 30 years from now use 19, otherwise use 20
var checkYear = mToday.getFullYear() + 50; 
var mCheckYear = '20' + mYear;
if (mCheckYear >= checkYear)
mYear = '19' + mYear;
else
mYear = '20' + mYear;
vDateValueCheck = mMonth+strSeperator+mDay+strSeperator+mYear;
// Store the new value back to the field.  This function will
// not work with date type of 2 since the year is entered first.
if (vDateTypeTemp == 1) // mm/dd/yyyy
vDateName.value = mMonth+strSeperator+mDay+strSeperator+mYear;
if (vDateTypeTemp == 3) // dd/mm/yyyy
vDateName.value = mDay+strSeperator+mMonth+strSeperator+mYear;
} 
if (!dateValid(vDateValueCheck)) {
alert("Data Inválida\nPor Favor corrija este campo.");
vDateType = vDateTypeTemp;
vDateName.value = "";
vDateName.focus();
return true;
}
vDateType = vDateTypeTemp;
return true;
}
else {
if (vDateType == 1) {
if (vDateValue.length == 2) {
vDateName.value = vDateValue+strSeperator;
}
if (vDateValue.length == 5) {
vDateName.value = vDateValue+strSeperator;
   }
}
if (vDateType == 2) {
if (vDateValue.length == 4) {
vDateName.value = vDateValue+strSeperator;
}
if (vDateValue.length == 7) {
vDateName.value = vDateValue+strSeperator;
   }
} 
if (vDateType == 3) {
if (vDateValue.length == 2) {
vDateName.value = vDateValue+strSeperator;
}
if (vDateValue.length == 5) {
vDateName.value = vDateValue+strSeperator;
   }
}
return true;
   }
}
if (vDateValue.length == 10&& dateCheck) {
if (!dateValid(vDateName)) {
// Un-comment the next line of code for debugging the dateValid() function error messages
//alert(err);  
alert("Data Inválida\nPor Favor corrija este campo.");
vDateName.focus();
vDateName.select();
   }
}
return false;
}
else {
// If the value is not in the string return the string minus the last
// key entered.
if (isNav4) {
vDateName.value = "";
vDateName.focus();
vDateName.select();
return false;
}
else
{
// comentado em 19/06/07
//vDateName.value = vDateName.value.substr(0, (vDateValue.length-1));
return false;
         }
      }
   }
}
function dateValid(objName) {
var strDate;
var strDateArray;
var strDay;
var strMonth;
var strYear;
var intday;
var intMonth;
var intYear;
var booFound = false;
var datefield = objName;
var strSeparatorArray = new Array("-"," ","/",".");
var intElementNr;
// var err = 0;
var strMonthArray = new Array(12);
strMonthArray[0] = "Jan";
strMonthArray[1] = "Feb";
strMonthArray[2] = "Mar";
strMonthArray[3] = "Apr";
strMonthArray[4] = "May";
strMonthArray[5] = "Jun";
strMonthArray[6] = "Jul";
strMonthArray[7] = "Aug";
strMonthArray[8] = "Sep";
strMonthArray[9] = "Oct";
strMonthArray[10] = "Nov";
strMonthArray[11] = "Dec";
//strDate = datefield.value;
strDate = objName;
if (strDate.length < 1) {
	return true;
}

for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
	if (strDate.indexOf(strSeparatorArray[intElementNr]) != -1) {
		strDateArray = strDate.split(strSeparatorArray[intElementNr]);
		if (strDateArray.length != 3) {
			err = 1;
			return false;
		} else {
			strDay = strDateArray[0];
			strMonth = strDateArray[1];
			strYear = strDateArray[2];
		}
		booFound = true;
	}
}

if (booFound == false) {
	if (strDate.length>5) {
		strDay = strDate.substr(0, 2);
		strMonth = strDate.substr(2, 2);
		strYear = strDate.substr(4);
	}
}

//Adjustment for short years entered
if (strYear.length == 2) {
	strYear = '20' + strYear;
}

strTemp = strDay;
strDay = strMonth;
strMonth = strTemp;
intday = parseInt(strDay, 10);
if (isNaN(intday)) {
	err = 2;
	return false;
}
intMonth = parseInt(strMonth, 10);
if (isNaN(intMonth)) {
	for (i = 0;i<12;i++) {
		if (strMonth.toUpperCase() == strMonthArray[i].toUpperCase()) {
			intMonth = i+1;
			strMonth = strMonthArray[i];
			i = 12;
		}
	}
	if (isNaN(intMonth)) {
		err = 3;
		return false;
	}
}
	
	intYear = parseInt(strYear, 10);
	if (isNaN(intYear)) {
		err = 4;
		return false;
	}
	
	if (intYear < 1900) {
		err = 11;
		return false;
	}
	
	if (intMonth>12 || intMonth<1) {
		err = 5;
		return false;
	}
	
	if ((intMonth == 1 || intMonth == 3 || intMonth == 5 || intMonth == 7 || intMonth == 8 || intMonth == 10 || intMonth == 12) && (intday > 31 || intday < 1)) {
		err = 6;
		return false;
	}
	
	if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && (intday > 30 || intday < 1)) {
		err = 7;
		return false;
	}
	
	if (intMonth == 2) {
		if (intday < 1) {
			err = 8;
			return false;
		}
		
		if (LeapYear(intYear) == true) {
			if (intday > 29) {
				err = 9;
				return false;
			}
		} else {
			if (intday > 28) {
				err = 10;
				return false;
			}
		}
	}
	return true;
}

function LeapYear(intYear) {
	if (intYear % 100 == 0) {
		if (intYear % 400 == 0) { return true; }
	} else {
		if ((intYear % 4) == 0) { return true; }
	}
	return false;
}
// ------------------------------------ DATA --------------------------------------//
// --------------------------------------------------------------------------------//
// ------------------------------------ DATA --------------------------------------//

var isNS4 = (navigator.appName=="Netscape") ? 1 : 0;

function numberMask(event) {
	if (!isNS4) tecla = event.keyCode;
	else tecla = event.which;
	if ((tecla > 0) && (tecla != 8) && (tecla < 44 || tecla > 57)) return false;
	return true;
}

function formatarRG(fld) {
	valor = fld.value;
	valor_2 = "";
	a = 0;
	
	for (i=0; i < valor.length; i++) {
		k = valor.charCodeAt(i);
		c = valor.charAt(i);
		if (k >= 48 && k <= 57) {
			a++;
			if (a == 2 || a == 5) mod = "."
			else if (a == 8) mod = "-";
			else mod = "";
			valor_2 = valor_2 + c + mod;
		}
	}
	fld.value = valor_2;
}

function formatarCPF(fld) {
	valor = fld.value;
	valor_2 = "";
	a = 0;
	for (i=0; i < valor.length; i++) {
		k = valor.charCodeAt(i);
		c = valor.charAt(i);
		if (k >= 48 && k <= 57) {
			a++;
			if (a == 3 || a == 6) mod = "."
			else if (a == 9) mod = "-";
			else mod = "";
			valor_2 = valor_2 + c + mod;
		}
	}
	fld.value = valor_2;
}

function validarCPF(cpf) {
 
    cpf = cpf.replace(/[^\d]+/g,'');
 
    if(cpf == '') return false;
 
    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11 || 
        cpf == "00000000000" || 
        cpf == "11111111111" || 
        cpf == "22222222222" || 
        cpf == "33333333333" || 
        cpf == "44444444444" || 
        cpf == "55555555555" || 
        cpf == "66666666666" || 
        cpf == "77777777777" || 
        cpf == "88888888888" || 
        cpf == "99999999999")
        return false;
     
    // Valida 1o digito
    add = 0;
    for (i=0; i < 9; i ++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(9)))
        return false;
     
    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i ++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(10)))
        return false;
         
    return true;
    
}

// JavaScript Document
function formataCNPJ(fld, e) {
//var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (e.which) ? e.which : event.keyCode;
if (whichCode == 13 || whichCode == 8 || whichCode == 46) return true;  // Enter
key = String.fromCharCode(whichCode);  // Get key value from key code
if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
len = fld.value.length;

if((len+1) > fld.maxLength || (len+1)>18)
	return false;

switch(len)
{
	case 2:
	case 6:
		fld.value = fld.value+'.';
		break;
	case 10:
		fld.value = fld.value + '/';
		break;
	case 15:
		fld.value = fld.value + '-';
		break;
	default:
		break;
}

return true;
}


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

function valores_percent(fld, milSep, decSep, e) {
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
len = 6;
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
			if (aux > 10000) return false;

			fld.value = '';
			len2 = aux2.length;

		
		for (i = len2 - 1; i >= 0; i--)
			fld.value += aux2.charAt(i);
		fld.value += decSep + aux.substr(len - 2, len);
		}
		
	return false;
}


function limite(e) {		
	try{var element = e.target		  }
	catch(er){};		
	try{var element = event.srcElement  }catch(er){};
				try{var ev = e.which	   }catch(er){};
		try{var ev = event.keyCode }catch(er){};
		if((ev!=0) && (ev!=8) &&(ev!=13))
			if  (! RegExp(/[0-9]/gi).test(String.fromCharCode(ev))) return false;
						if(element.value + String.fromCharCode(ev) > 10000) return false;
			}
			
			

function valores_corrente_4dgs(fld, milSep, decSep, e) {
	milSep = (milSep) ? milSep : '.';
	decSep = (decSep) ? decSep : ',';

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
        if (len == 1) fld.value = '0'+ decSep + '000' + aux;
		if (len == 2) fld.value = '0'+ decSep + '00' + aux;
		if (len == 3) fld.value = '0'+ decSep + '0' + aux;
		if (len == 4) fld.value = '0'+ decSep + aux;
		if (len > 4) {
         aux2 = '';
         for (j = 0, i = len - 5; i >= 0; i--) {
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
        fld.value += decSep + aux.substr(len - 4, len);
     }
     return false;
}  
//---------------------------------------------------------------//
//------------- fim: valida dinheiro ----------------------------//
//---------------------------------------------------------------//
//------------- inicio: valida hora --------------------------------//
//---------------------------------------------------------------//
function checahora(obj) {
	
	var param = obj.value;
	// Eliminate all the ASCII codes that are not valid
	//var alphaCheck = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/-:";
	//if (alphaCheck.indexOf(param) >= 1) {
	//	param = param.substr(0, (param.length-1));
	//}

	//if (param.length == 2 && !isNaN(param.charAt(1)) && !isNaN(param.charAt(2))) {
		//obj.value = hora+':';
	//}
	
	if(param.length == 5 && param.charAt(2) == ":") {
		try {
			
			var vDtaHra = param.split(':');	
			var horas, minutos;
			
			horas = vDtaHra[0];
			minutos = vDtaHra[1];
			var msg = '';
			
			if(!(horas >= 0 && horas <= 23))
				msg += 'As horas devem estar dentro do intervalo de 0 a 23\n';
			if(!(minutos >= 0 && minutos <= 59))
				msg += 'Os minutos devem estar dentro do intervalo de 0 a 59\n';
			
			if(msg.length > 1) {
				obj.value = '';
				alert(msg);
			}
			
		} catch(ex) {
			alert('Digite apenas numeros!');
		}
	}
}
//---------------------------------------------------------------//
//------------- fim: valida hora --------------------------------//
//---------------------------------------------------------------//
//------------- inicio: apenas numeros --------------------------//
//---------------------------------------------------------------//

//function apenasnumero()
//{
//	if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;
//}

function apenasnumero(e)
{
	evt = (e) ? e : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : 
	((evt.which) ? evt.which : 0));
	if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 0) {
		return false;
	} else {
		return true;	
	}

}

function apenasnumero_e_ponto(e)
{
	evt = (e) ? e : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
	//if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 0) {
	if (charCode > 31 && ((charCode < 44 || charCode > 57) || charCode == 47 || charCode == 45) && charCode != 0) {
		return false;
	} else {
		return true;	
	}

}
//---------------------------------------------------------------//
//------------- fim: apenas numeros -----------------------------//
//---------------------------------------------------------------//
//------------- inicio: confrontar datas ------------------------//
//---------------------------------------------------------------//
function ConfrontarDatas (ctlDataInicial, ctlDataFinal, cMensagem, tipo)
{
  strDataInicial = ctlDataInicial;
  strDataFinal = ctlDataFinal;
	
	if (tipo == 'maior') {
		varSinal = '>';
	} else if (tipo == 'menor') {
		varSinal = '<';
	} else if (tipo == 'maiorouigual') {
		varSinal = '>=';
	} else if (tipo == 'menorouigual') {
		varSinal = '<=';
	}

  if (strDataFinal != '' && strDataInicial != '')
  {
    strDataInicial = strDataInicial.substring (6, 10) +
                     strDataInicial.substring (3, 5) +
                     strDataInicial.substring (0, 2);
    strDataFinal = strDataFinal.substring (6, 10) +
                   strDataFinal.substring (3, 5) +
                   strDataFinal.substring (0, 2);

    if (eval("parseInt (strDataFinal) "+varSinal+"parseInt (strDataInicial)"))
    {
      if (cMensagem !='') alert (cMensagem);
      return false;
    }
  }
  return true;
}
//---------------------------------------------------------------//
//------------- fim: confrontar datas ---------------------------//
//---------------------------------------------------------------//
function mascaraData(obj)
{              
var data = obj.value;
	if (obj.value.length == 2){                  
		data = data + '/';                  
		obj.value = data;      
		return true;                            }              
	if (data.length == 5){                  
		data = data + '/';                 
		obj.value = data;                 
		return true;              
}         
}
//---------------------------------------------------------------//
//------------- inicio: Left ------------------------------------//
//---------------------------------------------------------------//
function Left(str, n){
	if (n <= 0)
	    return "";
	else if (n > String(str).length)
	    return str;
	else
	    return String(str).substring(0,n);
}
//---------------------------------------------------------------//
//------------- fim: Left ---------------------------------------//
//---------------------------------------------------------------//

//---------------------------------------------------------------//
//------------- inicio: Right -----------------------------------//
//---------------------------------------------------------------//
function Right(str, n){
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
}
//---------------------------------------------------------------//
//------------- fim: Right --------------------------------------//
//---------------------------------------------------------------//
//---------------------------------------------------------------//
//------------- ini: Valida Hora --------------------------------//
//---------------------------------------------------------------//

function valores_hora(fld, milSep, decSep, e) {
	milSep = (milSep) ? milSep : ':';
	decSep = (decSep) ? decSep : ':';

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
len = 4;
//if (fld.maxLength) len = fld.maxLength -1;

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
//---------------------------------------------------------------//
//------------- fim: Valida Hora --------------------------------//
//---------------------------------------------------------------//
//------------- inicio: canguru ---------------------------------//
//---------------------------------------------------------------//
var isNN = (navigator.appName.indexOf("Netscape")!=-1);
function autoTab(input,len, e) {
var keyCode = (isNN) ? e.which : e.keyCode;
var filter = (isNN) ? [0,8,9] : [0,8,9,16,17,18,37,38,39,40,46];
var indice = -1;
var contador = 1;
if(input.value.length >= len && !containsElement(filter,keyCode)) {
input.value = input.value.slice(0, len);
indice = getIndex(input);
((indice+contador) >= input.form.length) ? contador=0 : contador=1;
while(input.form[indice+contador].disabled) {
   contador++;
}
//input.form[(getIndex(input)+1) % input.form.length].focus();
input.form[(indice+contador) % input.form.length].focus();
}
function containsElement(arr, ele) {
var found = false, index = 0;
while(!found && index < arr.length)
if(arr[index] == ele)
found = true;
else
index++;
return found;
}
function getIndex(input) {
var index = -1, i = 0, found = false;
while (i < input.form.length && index == -1)
if (input.form[i] == input)index = i;
else i++;
return index;
}
return true;
}
//---------------------------------------------------------------//
//------------- fim: canguru ------------------------------------//
//---------------------------------------------------------------//
//  End -->


function findPosX(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft;
}

function findPosY(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}