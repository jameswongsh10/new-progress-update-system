
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

    // document.getElementById('monthbutton').addEventListener('click', function() {
    //     var date = calendar.getDate();
    //     var month = date.getMonth();
    //     var finalMonth = ++month;
    //     // $.ajax({
    //     //     url:  "{{ route('TaskController.getRequest') }}",
    //     //     type: "POST",
    //     //     data:{"myData":finalMonth}
    //     // }).done(function(data) {
    //     //     console.log(data);
    //     // });
    //     alert({{ route(TaskController.getRequest) }});
    // });
});




