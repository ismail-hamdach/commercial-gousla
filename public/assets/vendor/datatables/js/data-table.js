jQuery(document).ready(function ($) {
    'use strict';

    var minDate, maxDate;
 
    // Custom filtering function which will search data in column four between two values
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date( data[8] );
     
            if (
                ( min === null && max === null ) ||
                ( min === null && date <= max ) ||
                ( min <= date   && max === null ) ||
                ( min <= date   && date <= max )
            ) {
                return true;
            }
            return false;
        }
    );





    if ($("table.first").length) {

        $(document).ready(function () {
            $('table.first').DataTable();
        });
    }

    /* Calender jQuery **/

    if ($("table.second").length) {

        $(document).ready(function () {
            minDate = new DateTime($('#min'), {
                format: 'MMMM Do YYYY'
            });
            maxDate = new DateTime($('#max'), {
                format: 'MMMM Do YYYY'
            });

            var dats = $('#dataa').val();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = mm + '/' + dd + '/' + yyyy;
            var table = $('table.second').DataTable({
                "order":[[0,"desc"]],
                lengthChange: false,
                "language": {
                    "sProcessing": "Traitement en cours ...",
                    "sLengthMenu": "Afficher _MENU_ lignes",
                    "sZeroRecords": "Aucun résultat trouvé",
                    "sEmptyTable": "Aucune donnée disponible",
                    "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                    "sInfoEmpty": "Aucune ligne affichée",
                    "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                    "sInfoPostFix": "",
                    "sSearch": "Chercher:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Chargement...",
                    "oPaginate": {
                        "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                    }
                },
                buttons: [{ extend: 'excel', title: dats, exportOptions: { columns: ':visible:not(:last-child)' } }, {
                    extend: 'print',
                    title: "",

                    exportOptions: { columns: ':visible:not(:last-child)' },
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '15pt')
                            .prepend(
                                '<div class="width:100;background-color: red; display: flex;justify - content: space - between;"><div>LOGO</div><div>' + today + '</div></div>' +
                                '<div style="font-size:40px;text-align:center">' + dats + '</div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }, 'colvis']
            });
            $('#min, #max').on('change', function () {
                table.draw();
               
            });

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    }

  
    if ($("#example2").length) {

        $(document).ready(function () {
            $(document).ready(function () {
                var groupColumn = 2;
                var table = $('#example2').DataTable({
                    "columnDefs": [
                        { "visible": false, "targets": groupColumn }
                    ],
                    "order": [
                        [groupColumn, 'asc']
                    ],
                    "displayLength": 25,
                    "drawCallback": function (settings) {
                        var api = this.api();
                        var rows = api.rows({ page: 'current' }).nodes();
                        var last = null;

                        api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                                );

                                last = group;
                            }
                        });
                    }
                });

                // Order by the grouping
                $('#example2 tbody').on('click', 'tr.group', function () {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                        table.order([groupColumn, 'desc']).draw();
                    } else {
                        table.order([groupColumn, 'asc']).draw();
                    }
                });
            });
        });
    }

    if ($("#example3").length) {

        $('#example3').DataTable({
            select: {
                style: 'multi'
            }
        });

    }
    if ($("#example4").length) {

        $(document).ready(function () {
            var table = $('#example4').DataTable({
                fixedHeader: true
            });
        });
    }

});



$(".imgAdd").click(function () {
    $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
});
$(document).on("click", "i.del", function () {
    $(this).parent().remove();
});
$(function () {
    $(document).on("change", ".uploadFile", function () {
        var uploadFile = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

        if (/^image/.test(files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function () { // set image data as background of div
                //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
            }
        }

    });
});