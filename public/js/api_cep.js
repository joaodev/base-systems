$(function() {
    function cleanPostalCodeForm() {
        // Limpa valores do formulário de cep.
        $("#address").val("");
        $("#neighborhood").val("");
        $("#city").val("");
        $("#state").val("");
        
        $("#postal_code").val("").focus();
        //$("#ibge").val("");
    }
    
    //Quando o campo cep perde o foco.
    $("#postal_code").blur(function() {
        //Nova variável "cep" somente com dígitos.
        let postal_code = $(this).val().replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (postal_code != "") {
            //Expressão regular para validar o CEP.
            let validatePostalCode = /^[0-9]{8}$/;
            //Valida o formato do CEP.
            if(validatePostalCode.test(postal_code)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                $("#address").val("...");
                $("#neighborhood").val("...");
                $("#city").val("...");
                $("#state").val("...");
                //$("#ibge").val("...");
                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ postal_code +"/json/?callback=?", function(data) {
                    if (!("erro" in data)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#address").val(data.logradouro);
                        $("#neighborhood").val(data.bairro);
                        $("#city").val(data.localidade);
                        $("#state").val(data.uf);
                        //$("#ibge").val(dados.ibge);
                    } else {
                        //CEP pesquisado não foi encontrado.
                        cleanPostalCodeForm();
                        alert("CEP não encontrado.");
                    }
                });
            } else {
                //cep é inválido.
                cleanPostalCodeForm();
                alert("Formato de CEP inválido.");
            }
        } else {
            //cep sem valor, limpa formulário.
            cleanPostalCodeForm();
        }
    });
});