(function ($, Drupal) {

  // alert("heyy");
  $(document).ready(function() {
      var $haslastname = $('#has-last-name');
      var $lastname = $('.form-item-last-name');

    if ($haslastname.is(':checked')) {
        $lastname.hide();
      }

      $haslastname.on('change', function() {
        if ($(this).is(':checked')) {
          $lastname.hide();
        }
        else {
          $lastname.show();
        }
      });

  });

}) (jQuery, Drupal);

