(function ($) {
  $(document).ready(function () {
    /**
     * For Gravity Forms
     * Setting time out because Gravity Form displays late on the front-end screen
     */
    setTimeout(() => {
      // Detect change in address field
      $("#usz_address_field").on("blur", function () {
        var address_information = $("#usz_address_field").val();

        // Check if input value in address field is valid
        if (checkAddressValidity(address_information) == true) {
          var result = address_information.split(",");
          $("#zc_zip_id").val(address_information);
          $("#usz_zip_field").val(result[0]);
          $("#usz_city_field").val(result[1]);
          $("#usz_state_field").val(result[2]);
          $("#usz_address_code").val(result);
          $("#usz_address_field").css("border", "inherit");
          $(".usz-invalid-address").remove();
        } else {
          $("#usz_zip_field").val("");
          $("#usz_city_field").val("");
          $("#usz_state_field").val("");
          $("#usz_address_field").css("border", "1px solid #0024d4");
          if ($(".usz-invalid-address").length === 0) {
            $("#usz_address_field").after(
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
      $("#usz_address_field").on("keyup", function () {
        setTimeout(() => {
          var val = $(this).val();
          $("#usz_address_field").autocomplete({
            source: function (request, response) {
              // Call to find the US Address information
              $.ajax({
                type: "POST",
                url: usz_gf.admin_url,
                data: {
                  action: "pt_address_search",
                  val: val,
                  form_type: 03,
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
    }, 1000);
  });
})(jQuery);
