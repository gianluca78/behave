var TableDatatablesManaged = function () {
    var e = function () {
        var e = $("#data_list");
        e.dataTable({language: {aria: {sortAscending: ": activate to sort column ascending", sortDescending: ": activate to sort column descending"}, emptyTable: "No data available in table", info: "Showing _START_ to _END_ of _TOTAL_ records", infoEmpty: "No records found", infoFiltered: "(filtered1 from _MAX_ total records)", lengthMenu: "Show _MENU_", search: "Search:", zeroRecords: "No matching records found", paginate: {previous: "Prev", next: "Next", last: "Last", first: "First"}}, bStateSave: !0, lengthMenu: [
            [20, 40, 50, -1],
            [20, 40, 50, "All"]
        ], pageLength: 20, pagingType: "bootstrap_full_number", columnDefs: [
            {orderable: !1, targets: [0, 4]},
            {searchable: !1, targets: [0, 4]},
            {className: "dt-right"}
        ], order: [
            [1, "asc"]
        ]});
        jQuery("#data_list_wrapper");
        e.find(".group-checkable").change(function () {
            var e = jQuery(this).attr("data-set"), t = jQuery(this).is(":checked");
            jQuery(e).each(function () {
                t ? ($(this).prop("checked", !0), $(this).parents("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).parents("tr").removeClass("active"))
            })
        }), e.on("change", "tbody tr .checkboxes", function () {
            $(this).parents("tr").toggleClass("active")
        })
    };
    return{init: function () {
        jQuery().dataTable && (e())
    }}
}();

App.isAngularJsApp() === !1 && jQuery(document).ready(function () {
    TableDatatablesManaged.init()
});