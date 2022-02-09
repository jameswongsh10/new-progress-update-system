
$(document).ready(function(){
    $('div a').click(function() {
        $('a').removeClass('active');
        $(this).addClass('active');
    });

    $('#taskname').on('change', function() {
        if (this.value === 'newtask') {
            $('#shownewtask').show()
        }
    })

    $('#status').on('change', function() {
        console.log(this.value)
    })



});






