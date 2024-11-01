(function ($) {
  "use strict";

  $(document).ready(function () {
    /**
     * Add the zipcodes to the database
     * - General Settings
     */
    $("#usz-configuaration-loader").hide();

    $("#usz-config-database").click(function (e) {
      e.preventDefault();
      $("body").append(
        '<div id="us-zipcode-plugin-loader"><div id="usz-configuaration-loader" class="loader"></div><p>Please wait! It will take a few minutes. Do not refresh this window</p></div>'
      );
      $.ajax({
        url: ajaxurl.url,
        data: { action: "configure_usz" },
        beforeSend: function () {
          $("#usz-configuaration-loader").show();
        },
        complete: function (data) {
          if (data.status == 200 || data.status == "200") {
            setTimeout(() => {
              $("#us-zipcode-plugin-loader").remove();
              location.reload();
              $("#usz-configuaration-loader").hide();
              $("#usz-configure-message").text(
                "Database Configured Successfully!"
              );
            }, 120000);
          }
        },
      });
    });
  });
})(jQuery);
