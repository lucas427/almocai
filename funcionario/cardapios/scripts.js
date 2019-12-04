document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.sidenav');
      var instances = M.Sidenav.init(elems);
    });
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.datepicker');
      var data = new Date();
      var dateOffset = (24*60*60*1000) * 5; // 5 dias
      var dataMin = new Date();
      dataMin.setTime(dataMin.getTime() - dateOffset);
      var options = {
          format: 'dd/mm/yyyy',
          yearRange: [data.getFullYear(), data.getFullYear() + 1],
          minDate: dataMin,
          disableWeekends: true,
          disableDayFn: function(date) {
            return date.getDay() == 1 ? false : true;
          },
          i18n: {
          cancel: 'Cancelar',
          clear: 'Limpar',
          months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
          monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
          weekdays: ['Domingo', 'Segunda Feira', 'Terça Feira', 'Quarta Feira', 'Quinta Feira', 'Sexta Feira', 'Sábado'],
          weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
          weekdaysAbbrev: ['D','S','T','Q','Q','S','S'] 
          }
      };
      var instances = M.Datepicker.init(elems, options);
    });

    function mascaraData(val) {
      var pass = val.value;
      var expr = /[0123456789]/;

      for (i = 0; i < pass.length; i++) {
        // charAt -> retorna o caractere posicionado no índice especificado
        var lchar = val.value.charAt(i);
        var nchar = val.value.charAt(i + 1);

        if (i == 0) {
          // search -> retorna um valor inteiro, indicando a posição do inicio da primeira
          // ocorrência de expReg dentro de instStr. Se nenhuma ocorrencia for encontrada o método retornara -1
          // instStr.search(expReg);
          if ((lchar.search(expr) != 0) || (lchar > 3)) {
            val.value = "";
          }

        } else if (i == 1) {

          if (lchar.search(expr) != 0) {
            // substring(indice1,indice2)
            // indice1, indice2 -> será usado para delimitar a string
            var tst1 = val.value.substring(0, (i));
            val.value = tst1;
            continue;
          }

          if ((nchar != '/') && (nchar != '')) {
            var tst1 = val.value.substring(0, (i) + 1);

            if (nchar.search(expr) != 0)
              var tst2 = val.value.substring(i + 2, pass.length);
            else
              var tst2 = val.value.substring(i + 1, pass.length);

            val.value = tst1 + '/' + tst2;
          }

        } else if (i == 4) {

          if (lchar.search(expr) != 0) {
            var tst1 = val.value.substring(0, (i));
            val.value = tst1;
            continue;
          }

          if ((nchar != '/') && (nchar != '')) {
            var tst1 = val.value.substring(0, (i) + 1);

            if (nchar.search(expr) != 0)
              var tst2 = val.value.substring(i + 2, pass.length);
            else
              var tst2 = val.value.substring(i + 1, pass.length);

            val.value = tst1 + '/' + tst2;
          }
        }

        if (i >= 6) {
          if (lchar.search(expr) != 0) {
            var tst1 = val.value.substring(0, (i));
            val.value = tst1;
          }
        }
      }
    }