var TableDatatablesManaged = function () {
    var measureDatatable = function () {
        var e = $("#measure-datatable");
        e.dataTable({language: {aria: {sortAscending: ": activate to sort column ascending", sortDescending: ": activate to sort column descending"}, emptyTable: "No data available in table", info: "Showing _START_ to _END_ of _TOTAL_ records", infoEmpty: "No records found", infoFiltered: "(filtered1 from _MAX_ total records)", lengthMenu: "Show _MENU_", search: "Search:", zeroRecords: "No matching records found", paginate: {previous: "Prev", next: "Next", last: "Last", first: "First"}}, bStateSave: !0, lengthMenu: [
            [20, 40, 50, -1],
            [20, 40, 50, "All"]
        ], pageLength: 20, pagingType: "bootstrap_full_number", columnDefs: [
            {orderable: false, targets: 0 },
            {searchable: false, targets: 0 },
            {className: "dt-right"}
        ], order: [
            [1, "asc"]
        ]});
        jQuery("#measure-datatable_wrapper");
        e.find(".group-checkable").change(function () {
            var e = jQuery(this).attr("data-set"), t = jQuery(this).is(":checked");
            jQuery(e).each(function () {
                t ? ($(this).prop("checked", !0), $(this).parents("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).parents("tr").removeClass("active"))
            })

            if(t) {
                $('#delete-button').removeClass('opaque');
            } else {
                $('#edit-button').addClass('opaque');
                $('#delete-button').addClass('opaque');
            }


        }), e.on("change", "tbody tr .checkboxes", function () {

            if($('.checkboxes:checked').length == 1) {
                $('#edit-button').removeClass('opaque');
                $('#delete-button').removeClass('opaque');
            }

            if($('.checkboxes:checked').length > 1) {
                $('#edit-button').addClass('opaque');
            }

            if($('.checkboxes:checked').length == 0) {
                $('#edit-button').addClass('opaque');
                $('#delete-button').addClass('opaque');
            }



            $(this).parents("tr").toggleClass("active")
        })
    };

    var observationPhaseDatatable = function () {
        var e = $("#observation-phase-datatable");
        e.dataTable({language: {aria: {sortAscending: ": activate to sort column ascending", sortDescending: ": activate to sort column descending"}, emptyTable: "No data available in table", info: "Showing _START_ to _END_ of _TOTAL_ records", infoEmpty: "No records found", infoFiltered: "(filtered1 from _MAX_ total records)", lengthMenu: "Show _MENU_", search: "Search:", zeroRecords: "No matching records found", paginate: {previous: "Prev", next: "Next", last: "Last", first: "First"}}, bStateSave: !0, lengthMenu: [
            [20, 40, 50, -1],
            [20, 40, 50, "All"]
        ], pageLength: 20, pagingType: "bootstrap_full_number", columnDefs: [
            {orderable: false, targets: 0 },
            {searchable: false, targets: 0 },
            {className: "dt-right"}
        ], order: [
            [1, "asc"]
        ]});
        jQuery("#observation-phase-datatable_wrapper");
        e.find(".group-checkable").change(function () {
            var e = jQuery(this).attr("data-set"), t = jQuery(this).is(":checked");
            jQuery(e).each(function () {
                t ? ($(this).prop("checked", !0), $(this).parents("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).parents("tr").removeClass("active"))
            })

            if(t) {
                $('#delete-button').removeClass('opaque');
            } else {
                $('#edit-button').addClass('opaque');
                $('#delete-button').addClass('opaque');
            }


        }), e.on("change", "tbody tr .checkboxes", function () {

            if($('.checkboxes:checked').length == 1) {
                $('#edit-button').removeClass('opaque');
                $('#delete-button').removeClass('opaque');
            }

            if($('.checkboxes:checked').length > 1) {
                $('#edit-button').addClass('opaque');
            }

            if($('.checkboxes:checked').length == 0) {
                $('#edit-button').addClass('opaque');
                $('#delete-button').addClass('opaque');
            }

            $(this).parents("tr").toggleClass("active")
        })
    };

    var observationDatatable = function () {
        var e = $("#observation-datatable");
        e.dataTable({language: {aria: {sortAscending: ": activate to sort column ascending", sortDescending: ": activate to sort column descending"}, emptyTable: "No data available in table", info: "Showing _START_ to _END_ of _TOTAL_ records", infoEmpty: "No records found", infoFiltered: "(filtered1 from _MAX_ total records)", lengthMenu: "Show _MENU_", search: "Search:", zeroRecords: "No matching records found", paginate: {previous: "Prev", next: "Next", last: "Last", first: "First"}}, bStateSave: !0, lengthMenu: [
            [20, 40, 50, -1],
            [20, 40, 50, "All"]
        ], pageLength: 20, pagingType: "bootstrap_full_number", columnDefs: [
            {orderable: false, targets: 0 },
            {orderable: false, targets: 3 },
            {orderable: false, targets: 4 },
            {searchable: false, targets: 0 },
            {className: "dt-right"}
        ], order: [
            [1, "asc"]
        ]});
        jQuery("#observation-datatable_wrapper");
        e.find(".group-checkable").change(function () {
            var e = jQuery(this).attr("data-set"), t = jQuery(this).is(":checked");
            jQuery(e).each(function () {
                t ? ($(this).prop("checked", !0), $(this).parents("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).parents("tr").removeClass("active"))
            })

        }), e.on("change", "tbody tr .checkboxes", function () {

            if($('.checkboxes:checked').length == 1) {
                $('#edit-button').removeClass('opaque');
                $('#delete-button').removeClass('opaque');
                $('#download-button').removeClass('opaque');
                $('#share-button').removeClass('opaque');
                $('#data-analysis-button').removeClass('opaque');
                $('#phase-list-button').removeClass('opaque');
            }

            if($('.checkboxes:checked').length > 1) {
                $('#edit-button').addClass('opaque');
                $('#download-button').addClass('opaque');
                $('#share-button').addClass('opaque');
                $('#data-analysis-button').addClass('opaque');
                $('#phase-list-button').addClass('opaque');
            }

            if($('.checkboxes:checked').length == 0) {
                $('#edit-button').addClass('opaque');
                $('#delete-button').addClass('opaque');
                $('#download-button').addClass('opaque');
                $('#share-button').addClass('opaque');
                $('#data-analysis-button').addClass('opaque');
                $('#phase-list-button').addClass('opaque');
            }

            $(this).parents("tr").toggleClass("active")
        })
    };

    return{init: function () {
        jQuery().dataTable && (measureDatatable(), observationDatatable(), observationPhaseDatatable())
    }}
}();

App.isAngularJsApp() === !1 && jQuery(document).ready(function () {
    TableDatatablesManaged.init()
});