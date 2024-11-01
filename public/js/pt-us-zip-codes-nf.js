(function ($) {
  $(document).ready(function () {
    /**
     * For Ninja Forms
     * Setting time out because Ninja form displays late on the front-end screen
     */
    setTimeout(() => {
      var nf_iid = $(".usz_address-wrap input").attr("id");

      /**
       * Making the field readonly
       */
      $(".usz_zip-wrap input").attr("readonly", true);
      $(".usz_city-wrap input").attr("readonly", true);
      $(".usz_state-wrap input").attr("readonly", true);

      // Detect change in address field
      $("#" + nf_iid).on("change", function () {
        var address_information = $(".usz_address-wrap input").val();

        // Check if input value in address field is valid
        if (checkAddressValidity(address_information) == true) {
          var result = address_information.split(",");
          $("#zc_zip_id").val(address_information);
          $(".usz_zip-wrap input").val(result[0]);
          $(".usz_city-wrap input").val(result[1]);
          $(".usz_state-wrap input").val(result[2]);
          $(this).val(result);
          $(this).css("border", "inherit");
          $(".usz-invalid-address").remove();
        } else {
          $(".usz_zip-wrap input").val("");
          $(".usz_city-wrap input").val("");
          $(".usz_state-wrap input").val("");
          $(this).css("border", "1px solid #0024d4");
          if ($(".usz-invalid-address").length === 0) {
            $(this).after(
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
      $(".usz_address-wrap input").on("keyup", function () {
        setTimeout(() => {
          var val = $(this).val();

          var input_id = $(".usz_address-wrap input").attr("id");

          // Using jQuery autocomplete library
          $("#" + input_id).autocomplete({
            source: function (request, response) {
              // Call to find the US Address information
              $.ajax({
                type: "POST",
                url: usz_nf.admin_url,
                data: {
                  action: "pt_address_search",
                  val: val,
                  form_type: 02,
                },
                success: function (result) {
                  var outt = [];
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

                    outt.push(e);
                    response(outt);
                  });
                },
              });
            },
          });
        }, 100);
      });
    }, 100);
  });
})(jQuery);
