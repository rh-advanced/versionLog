//set Vars




var $rows = $('.versionlog');
var fieldId = $('#field').data("field-id");


//Autoreaload once a hour
setTimeout(function(){
    window.location.reload(1);
}, 60 * 60000);

//initiate multiselect for edit/create modals

$('.multi-select').multiSelect();

//search logic
$('#search').keyup(function() {

    var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
        reg = RegExp(val, 'i'),
        text;

    $rows.show().filter(function() {
        text = $(this).text().replace(/\s+/g, ' ');
        return !reg.test(text);
    }).hide();
});

$('#search').autocomplete({
    source: fieldId,
    minLength: 2,
    messages: {
        noResults: '',
        results: function() {}
    },
    open: function(event, ui) {
        $('.versionlog').css('top', $('.ui-autocomplete').height() + 8);
    },
    close: function(event, ui) {
        $('.versionlog').css('top', 0);
    }
});

$('.versionroll').append($('.ui-autocomplete'));

//show bootstrap modal
$(document).ready(function() {


    $('#create').on('click', function () {
        $('#createmodal')

            .modal({
                backdrop: 'static', keyboard: false })
            .one('click', '[data-value]', function () {

                if($(this).data('value')) {
                    alert('confirmed');
                } else {
                    alert('canceled');
                }
            });
    });

    $('#login').on('click', function () {
        $('#loginmodal')

            .modal({
                backdrop: 'static', keyboard: false })
            .one('click', '[data-value]', function () {

                if($(this).data('value')) {
                    alert('confirmed');
                } else {
                    alert('canceled');
                }
            });
    });




    $('.editbtn').on('click', function () {

        var id = $(this).next('#idfield').data("field-id");
        console.log(id);
        $.ajax({
            type: "POST",
            url: '/editid',
            data: {
                id : id
            },
            success: function (response) {

                    $('#editlayer').html(response);
                    $("#editmodal").modal();
                    $('.multi-select').multiSelect();
            }});
    });

});

