
$(document).ready(function(){
    $('div a').click(function() {
        $('a').removeClass('active');
        $(this).addClass('active');
    });

    $('#taskname').on('change', function() {
        if (this.value === 'newTaskOption') {
            $('#shownewtask').show()
            $('#description').prop("disabled", false).val('')
            $('#start_date').prop('disabled', false).val('')
            $('#end_date').val('')
        } else {
            $('#shownewtask').hide()
        }
    })

    $('#status').on('change', function() {
        console.log(this.value)
    })

    $( function() {
        $( "#start_date" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $( "#end_date" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    } );
});

let colorInput = document.querySelector('#color');
let hexInput = document.querySelector('#hex');
if (colorInput) {
    colorInput.addEventListener('input', () =>{
        let color = colorInput.value;
        hexInput.value = color;
    });
}







