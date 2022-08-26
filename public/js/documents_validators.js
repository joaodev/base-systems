function validateCPF() {
    $("#document_1").removeAttr('style');

    let inputCPF = $("#document_1").val();
    let strCPF = inputCPF.replace('.', '').replace('.', '').replace('-', '');

    let Soma = 0;
    let Resto;

    if (strCPF == "00000000000") {
        $("#document_1").val('').focus().attr('style', 'border: 1px solid red;');
        return false;
    } 

    for (i=1; i<=9; i++) {
        Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
    }

    Resto = (Soma * 10) % 11;
    if ((Resto == 10) || (Resto == 11)) { 
        Resto = 0; 
    }

    if (Resto != parseInt(strCPF.substring(9, 10)) ) {
        $("#document_1").val('').focus().attr('style', 'border: 1px solid red;');
        return false;
    }

    Soma = 0;
    for (i = 1; i <= 10; i++) { 
        Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    }
    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11)) {
        Resto = 0;
    } 

    if (Resto != parseInt(strCPF.substring(10, 11) ) ) {
        $("#document_1").val('').focus().attr('style', 'border: 1px solid red;');
        return false;
    }

    return true;
}

function validateCNPJ() {
    $("#document_2").removeAttr('style');

    let cnpj = $("#document_2").val();
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if (cnpj.length != 14) {
        $("#document_2").val('').focus().attr('style', 'border: 1px solid red;');
        return false;
    }
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999") {
            $("#document_2").val('').focus().attr('style', 'border: 1px solid red;');
            return false;
        }
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;

    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2) {
            pos = 9;
        }
    }

    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) {
        $("#document_2").val('').focus().attr('style', 'border: 1px solid red;');
        return false;
    }
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;

    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2) {
          pos = 9;
      }
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) {
        $("#document_2").val('').focus().attr('style', 'border: 1px solid red;');
        return false;
    }
           
    return true;
    
}