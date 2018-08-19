jQuery(document).ready(function(){

    jQuery('input[name=phone]').mask('(999)999-99-99');
    jQuery('#edit-country').change(function(){
          var e = document.getElementById("edit-country");
          var mask = e.options[e.selectedIndex].getAttribute('data-code');
          if(mask){
              jQuery("input[name=code]").val(mask);
          }

    });
}); 


