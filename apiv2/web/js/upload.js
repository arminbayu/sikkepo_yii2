$(document).on('change', '#events-category', function() {
    var val = $('#events-category').val();

    if (val == '1') {
        $('#events-status').val('4.500.000');
    }
});