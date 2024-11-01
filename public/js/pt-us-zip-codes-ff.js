(function ($) {
  $(document).ready(function () {
    /**
     * For Formidable Forms
     * Setting time out because Formidable Form displays late on the front-end screen
     */

    setTimeout(() => {
      // Detect change in address field
      $("#usz-address-field").on("blur", function () {
        var address_information = $("#usz-address-field").val();

        // Check if input value in address field is valid
        if (checkAddressValidity(address_information) == true) {
          var result = address_information.split(",");
          $("#zc_zip_id").val(address_information);
          $("#usz-zip-field").val(result[0]);
          $("#usz-city-field").val(result[1]);
          $("#usz-state-field").val(result[2]);
          $("#usz_address_code").val(result);
          $("#usz-address-field").css("border", "inherit");
          $(".usz-invalid-address").remove();
        } else {
          $("#usz-zip-field").val("");
          $("#usz-city-field").val("");
          $("#usz-state-field").val("");
          $("#usz-address-field").css("border", "1px solid #0024d4");
          if ($(".usz-invalid-address").length === 0) {
            $("#usz-address-field").after(
              '<small class="usz-invalid-address">Please insert a valid zip code</small>'
            );
          }
        }
      });

      // check validation
      function checkAddressValidity(address_information) {
        var check = false;
        if (address_information.includes(",")) {
          check = true;
        }
        return check;
      }
      /**
       * Sending request on key up with 100ms delay
       */
      $("#usz-address-field").on("keyup", function (evt) {
        setTimeout(() => {
          var val = $(this).val();
          $("#usz-address-field").autocomplete({
            source: function (request, response) {
              // Call to find the US Address information
              $.ajax({
                type: "POST",
                url: usz_ff.admin_url,
                data: {
                  action: "pt_address_search",
                  val: val,
                  form_type: 04,
                },
                success: function (result) {
                  var outFr = [];
                  $.each(result.data, function (d, val) {
                    var e =
                      val.zip +
                      ", " +
                      val.city +
                      ", " +
                      val.state_name +
                      ", [" +
                      val.state_id +
                      "], USA";

                    outFr.push(e);
                    response(outFr);
                  });
                },
              });
            },
          });
        }, 100);
      });
    }, 500);
  });
})(jQuery);
