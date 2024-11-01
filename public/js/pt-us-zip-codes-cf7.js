(function ($) {
  $(document).ready(function () {
    /**
     * For Contact Form 7
     */

    // Detect change in address field
    $("#usz_address_code").on("blur", function () {
      var address_information = $("#usz_address_code").val();

      // Check if input value in address field is valid
      if (checkAddressValidity(address_information) == true) {
        var result = address_information.split(",");
        $("#zc_zip_id").val(address_information);
        $("#usz_zip_code").val(result[0]);
        $("#usz_city_code").val(result[1]);
        $("#usz_state_code").val(result[2]);
        $("#usz_address_code").val(result);
        $("#usz_address_code").css("border", "inherit");
        $(".usz-invalid-address").remove();
      } else {
        $("#usz_zip_code").val("");
        $("#usz_city_code").val("");
        $("#usz_state_code").val("");
        $("#usz_address_code").css("border", "1px solid #0024d4");
        if ($(".usz-invalid-address").length === 0) {
          $("#usz_address_code").after(
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
    $("#usz_address_code").on("keyup", function () {
      setTimeout(() => {
        var val = $(this).val();
        $("#usz_address_code").autocomplete({
          source: function (request, response) {
            // Call to find the US Address information
            $.ajax({
              type: "POST",
              url: usz_cf7.admin_url,
              data: {
                action: "pt_address_search",
                val: val,
                form_type: 01,
              },
              success: function (result) {
                var out = [];
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
                  out.push(e);
                  response(out);
                });
              },
            });
          },
        });
      }, 100);
    });
  });
})(jQuery);
