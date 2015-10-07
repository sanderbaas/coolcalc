(function ( $ ) {
  "use strict";

  $(function () {

    var calculationData = {};
    /**
    *  model-list
    **/
    $('#coolcalc-model-list button.coolcalc-calculation').on('click', function(){
      // todo: get page from settings
      $(location).attr('href','/offerte/?cc_model=' + $(this).closest('tr').data('cid'));
    });

    var money = function(num, forceDecimals) {
      return $.number( num, 2, ',', '.' );
    }

    var renderCalculation = function renderCalculation(hideImage) {
      hideImage = hideImage || false;
      if (params.options.data.options) {
        params.options.data.options.forEach(function(option){
          option.meta.price_f = money(option.meta.price);
        });
      }

      if (params.options.data.installOptions) {
        params.options.data.installOptions.forEach(function(option){
          option.meta.price_f = money(option.meta.price);
        });
      }

      var json = {
        "l10n": {},
        "modelPrice": money(params.options.data.modelPrice/100),
        "optionsPrice": money(params.options.data.optionsPrice/100),
        "installOptionsPrice": money(params.options.data.installOptionsPrice/100),
        "totalPrice": money(params.options.data.totalPrice/100),
        "tax": money(params.options.data.tax/100),
        "totalInclPrice": money(params.options.data.totalInclPrice/100),
        "models": params.options.data.models,
        "showOptions": params.options.model_options && params.options.model_options.length > 0,
        "options": params.options.model_options && params.options.model_options.length > 0 ? params.options.data.options : [],
        "showInstallOptions": params.options.install_options && params.options.install_options.length > 0,
        "installOptions": params.options.install_options && params.options.install_options.length > 0 ? params.options.data.installOptions : [],
        "hideImage": hideImage
      };

      $.get(params.options.plugins_url + '/public/templates/mustache/calculation.html', function(template) {
        var rendered = Mustache.render( template, json );
        $('.coolcalc-calculation-container').html( rendered );
      } );
    };

    var calculateTotals = function calculateTotals(cb) {
      params.options.data.modelPrice = 0;
      params.options.data.models.forEach(function(model){
        if (model.ID == params.options.selected_model) {
          params.options.data.modelPrice = parseFloat(model.meta.price[0])*100;
          return;
        }
      });

      params.options.data.optionsPrice = 0;
      if (params.options.data.options) {
        params.options.data.options.forEach(function(option){
          if (params.options.model_options.indexOf(option.ID)>-1) {
            params.options.data.optionsPrice += parseFloat(option.meta.price[0])*100;
          }
        });
      }

      params.options.data.installOptionsPrice = 0;
      if (params.options.data.installOptions) {
        params.options.data.installOptions.forEach(function(option){
          if (params.options.install_options.indexOf(option.ID)>-1) {
            params.options.data.installOptionsPrice += parseFloat(option.meta.price[0])*100;
          }
        });
      }

      params.options.data.totalPrice = params.options.data.modelPrice + params.options.data.optionsPrice + params.options.data.installOptionsPrice;
      params.options.data.tax = 0.21 * params.options.data.totalPrice;
      params.options.data.totalInclPrice = 1.21 * params.options.data.totalPrice;

      cb();
    }

    var attachFeatherlight = function attachFeatherlight(targetEl){
      // attach featherlight
      $(targetEl).find('a>img').closest('a').featherlight({type:'image'});
      $('#coolcalc-step-3').find('a').filter(function(index, el){
        return $(this).find('img').length == 0;
      }).on('click', function(){
        window.open($(this).attr('href'));
        return false;
      });
    };

    var selectModel = function selectModel(id) {
      var diff = params.options.selected_model !== id
      params.options.selected_model = id;
      if (diff) {
        step2Fiddled = false;
        step2bFiddled = false;
        params.options.model_options = [];
        params.options.install_options = [];
        params.options.data.models.forEach(function(model){
          model.selected = model.ID === id;
        });
      }

      calculateTotals(renderCalculation);
    }

    var toggleOption = function toggleOption(id, onlyOn) {
      onlyOn = onlyOn || false;
      params.options.model_options = params.options.model_options || [];
      var index = params.options.model_options.indexOf(id);
      if (index<0) {
        params.options.model_options.push(id);
      } else {
        if (!onlyOn) { params.options.model_options.splice(index, 1); }
      }

      params.options.data.options.forEach(function(option){
        option.selected = params.options.model_options.indexOf(option.ID) > -1;
      });

      calculateTotals(renderCalculation);
    }

    var toggleInstallOption = function toggleInstallOption(id, onlyOn) {
      onlyOn = onlyOn || false;
      params.options.install_options = params.options.install_options || [];
      var index = params.options.install_options.indexOf(id);
      if (index<0) {
        params.options.install_options.push(id);
      } else {
        if (!onlyOn) { params.options.install_options.splice(index, 1); }
      }

      params.options.data.installOptions.forEach(function(option){
        option.selected = params.options.install_options.indexOf(option.ID) > -1;
      });

      calculateTotals(renderCalculation);
    }

    var getModels = function getModels(data, cb) {
      if (!cb && typeof data === "function") {
        cb = data;
        data = {};
      }

      data.action = 'cc_get_models';

      $.post(params.options.ajaxurl, data, function(models) {
        models.forEach(function(model){
          model.selected = model.ID == params.options.selected_model;
        });

        params.options.data = params.options.data || {};
        params.options.data.models = models;
        cb(models);
      });
    }

    var getOptions = function getOptions(data, cb) {
      if (!cb && typeof data === "function") {
        cb = data;
        data = {};
      }

      data.action = 'cc_get_options';

      $.post(params.options.ajaxurl, data, function(options) {
        params.options.data = params.options.data || {};
        params.options.data.options = options;
        cb(options);
      });
    }

    var getInstallOptions = function getInstallOptions(data, cb) {
      if (!cb && typeof data === "function") {
        cb = data;
        data = {};
      }

      data.action = 'cc_get_install_options';

      $.post(params.options.ajaxurl, data, function(installOptions) {
        params.options.data = params.options.data || {};
        params.options.data.installOptions = installOptions;
        cb(installOptions);
      });
    }

    var renderStep1 = function renderStep1(targetEl, cb) {
      disableStepPdf();
      getModels(function(models) {
        var json = {
          "l10n": {
            "col_model": params.l10n.model,
            "col_capacity": params.l10n.capacity,
            "col_price": params.l10n.price_excl
          },
          "models": models
        };

        $.get(params.options.plugins_url + '/public/templates/mustache/step1.html', function(template) {
          var rendered = Mustache.render( template, json );
          $(targetEl).html( rendered );
          attachFeatherlight(targetEl);

          selectModel(params.options.selected_model);
          if (cb) { cb(); }
        });
      });
    };

    var step2Fiddled = false;
    var step2bFiddled = false;

    var renderStep2 = function renderStep2(targetEl) {
      disableStepPdf();
      renderCalculation();
      params.options.model_options = params.options.model_options || [];
      params.options.install_options = params.options.install_options || [];

      var data = {
        machine: params.options.selected_model
      };
      getOptions(data, function(options) {
        options.forEach(function(option){
          if (option.meta && option.meta.default && option.meta.default == '1') {
            if (!step2Fiddled) { toggleOption(option.ID, true); }
          }
          option.selected = params.options.model_options.indexOf(option.ID)>-1;
          option.hasContent = option.post_content !== '';
        });
        var json = {
          "l10n": {
            "machine_options": params.l10n.machine_options,
            "select_install_options_q": params.l10n.select_install_options_q,
            "install_options": params.l10n.install_options
          },
          "options": options
        };

        getInstallOptions(data, function(installOptions) {
          installOptions.forEach(function(option){
            if (option.meta && option.meta.default && option.meta.default == '1') {
              if (!step2bFiddled) { toggleInstallOption(option.ID, true); }
            }
            option.selected = params.options.install_options.indexOf(option.ID)>-1;
            option.hasContent = option.post_content !== '';
          });
          json.installOptions = installOptions;

          $.get(params.options.plugins_url + '/public/templates/mustache/step2.html', function(template) {
            var rendered = Mustache.render( template, json );
            $(targetEl).html( rendered );
            attachFeatherlight(targetEl);
          } );
        });
      });
    };

    var renderStep3 = function renderStep3(targetEl) {
      renderCalculation(true);
      var json = {
        "l10n": {
          "install_options": params.l10n.install_options,
          "col_capacity": params.l10n.capacity
        },
        "model": {},
        "hasOptions": false,
        "options": [],
        "hasInstallOptions": false,
        "installOptions": []
      };

      params.options.data.models.forEach(function(model){
        if (model.selected) { json.model = model; }
      });

      params.options.data.options.forEach(function(option){
        if (option.selected) { json.options.push(option); }
      });
      json.hasOptions = json.options.length > 0;

      params.options.data.installOptions.forEach(function(option){
        if (option.selected) { json.installOptions.push(option); }
      });
      json.hasInstallOptions = json.installOptions.length > 0;

      $.get(params.options.plugins_url + '/public/templates/mustache/step3.html', function(template) {
        var rendered = Mustache.render( template, json );
        $(targetEl).html( rendered );
        attachFeatherlight(targetEl);
        enableStepPdf();
      } );
    };

    var enableStepPdf = function enableStepPdf() {
      console.log('enabled')
      $('#pdfstep').removeClass('disabled');
    };

    var disableStepPdf = function disableStepPdf() {
      $('#pdfstep').addClass('disabled');
    };

    var initCalculator = function initCalculator() {
      params.options.data = params.options.data || {};

      renderStep1('#coolcalc-step-1', function() {
        if (params.options.selected_model && params.options.selected_model>0) {
          //selectModel(params.options.selected_model);
          renderStep2('#coolcalc-step-2');
          $('#coolcalc-pagination a[data-step=2]').tab('show');
          disableStepPdf();
        }
      });

      $('span.money').number( true, 2, ',', '.' );

      $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).data('target');
        switch(target) {
          case '#coolcalc-step-1':
            renderStep1(target);
            break;
          case '#coolcalc-step-2':
            renderStep2(target);
            break;
          case '#coolcalc-step-3':
            renderStep3(target);
            break;
        }
      });


      // attach listeners step 1
      $('.coolcalc-step-container .step1-item').live('click', function(e) {
        var id = $(this).data('cid');
        $('.coolcalc-step-container .step1-item').removeClass('active');
        $(this).addClass('active');
        selectModel(id);
        return false;
      });

      // attach listeners step 2
      $('.coolcalc-step-container .step2-item').live('click', function(e) {
        step2Fiddled = true;
        var id = $(this).data('cid');
        $(this).toggleClass('active');
        $(this).find('i.fa').toggleClass('fa-check-square-o');
        $(this).find('i.fa').toggleClass('fa-square-o');

        toggleOption(id);
        return false;
      });

      $('.step2-item span.info, .step3-item span.info').live('click', function(e) {
        return false;
      });

      // attach listeners step 2b
      $('.coolcalc-step-container .step2b-item').live('click', function(e) {
        step2bFiddled = true;
        var id = $(this).data('cid');
        $(this).toggleClass('active');
        $(this).find('i.fa').toggleClass('fa-check-square-o');
        $(this).find('i.fa').toggleClass('fa-square-o');
        toggleInstallOption(id);
        return false;
      });

      // attach listeners step 3
      $('#coolcalc-pagination #pdf').live('click', function(e) {
        var url = params.options.ajaxurl;
        url += '?action=cc_get_pdf';
        url += '&machine=' + params.options.selected_model;
        url += '&options=' + params.options.model_options.join(',');
        url += '&install_options=' + params.options.install_options.join(',');
        window.open(url);

        return false;
      });

      renderCalculation();
    }


    $(document).ready(function($){
      initCalculator();
    });
  });

}(jQuery));