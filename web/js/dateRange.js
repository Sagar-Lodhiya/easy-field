$(document).ready(function () {
  console.log("jQuery Version:", jQuery.fn.jquery); // Debugging
  console.log("Moment.js Version:", moment.version); // Debugging

  if (typeof moment !== "function" || typeof moment().format !== "function") {
    console.error("Moment.js is not loaded properly.");
    return;
  }

  if (typeof $.fn.daterangepicker === "undefined") {
    console.error("DateRangePicker is not loaded properly.");
    return;
  }

  if ($(".date_range").length === 0) {
    console.error("Error: .date_range input not found.");
    return;
  }

  console.log("Today:", moment().format()); 
console.log("Yesterday:", moment().subtract(1, "days").format());
console.log("Moment Test:", moment("2025-03-28", "YYYY-MM-DD", true).isValid());
// console.log("Moment Today:", moment().startOf("day"), " and ", moment().endOf("day"));


  $(".date_range").daterangepicker({
    // timePicker: true,
    showDropdowns: true,
    autoUpdateInput: false,
    locale: {
      cancelLabel: "Clear",
    },
    // ranges: {
      // 'Today': [moment(), moment()],
      // 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //   'This Month': [moment().startOf('month'), moment().endOf('month')],
    //   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    // },
  });

  $(".date_range").on("apply.daterangepicker", function (ev, picker) {
    $(this).val(
      picker.startDate.format("L") + " - " + picker.endDate.format("L")
    );
  });

  $(".date_range").on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
  });
});
