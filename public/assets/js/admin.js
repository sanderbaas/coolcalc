(function ( $ ) {
  "use strict";

  $(function () {
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function( elem ) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    var disableField = function disableField(id) {
      $('#'+id).prop('disabled', true);
    }

    var enableField = function enableField(id) {
      $('#'+id).prop('disabled', false);
    }

    var hideField = function hideField(id) {
      disableField(id);
      $('#'+id).closest('tr').css('display', 'none');
    }

    var showField = function showField(id) {
      enableField(id);
      $('#'+id).closest('tr').css('display', '');
    }

    var toggleProducttype = function toggleProducttype(producttype) {
      if (typeof producttype === undefined) {
        hideField('cc_product_machine');
        hideField('cc_product_capacity');
        hideField('cc_product_default');
        return;
      }

      switch(producttype) {
        default:
          hideField('cc_product_machine');
          hideField('cc_product_capacity');
          hideField('cc_product_default');
          break;
        case 'machine':
          hideField('cc_product_machine');
          hideField('cc_product_default');
          showField('cc_product_capacity');
          break;
        case 'machine-option':
        case 'install-option':
          showField('cc_product_machine');
          showField('cc_product_default');
          hideField('cc_product_capacity');
          break;
      }
    }

    var scrollIncrement = function scrollIncrement(increment, up) {
      up = up || false;
      if (up) { increment = -1 * increment; }

      var y = $(window).scrollTop();  //your current y position on the page
      $(window).scrollTop(y + increment);
    }
    var textScroll = 0;

    var toggleProducttext = function toggleProducttext(text) {
      if (textScroll == 0) { textScroll = $('#postdivrich').height(); }
      if (typeof text === undefined || text == '') {
        $('#postdivrich').css('display', '');
        scrollIncrement(textScroll);
        return;
      }

      $('#postdivrich').css('display', 'none');
      scrollIncrement(textScroll, true);
    }

    if (typeof typenow !== 'undefined' && typenow == 'cc_product') {
      console.log(typenow);
      toggleProducttype($('#cc_product_producttype').val());
      toggleProducttext($('#cc_product_text').val());

      // onchange
      $('#cc_product_producttype').on('change', function(){
        toggleProducttype($(this).val());
      });

      $('#cc_product_text').on('change', function(){
        toggleProducttext($(this).val());
      });
    }
  });

}(jQuery));