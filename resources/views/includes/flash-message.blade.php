@if (Session::has('success'))
    @php($message = Session::get('success'))
    <script>
        var popupId = "{{ uniqid() }}";
        if(!sessionStorage.getItem('shown-' + popupId)) {
            swal.fire("Successfull!", '{{$message}}', "success");
        }
        sessionStorage.setItem('shown-' + popupId, '1');
    </script>
@elseif (Session::has('error'))
    @php($message = Session::get('error'))
    <script>
        swal.fire("Sorry!", '<?php echo $message; ?>', "danger");
    </script>
@elseif (Session::has('warning'))
    @php($message = Session::get('warning'))
    <script>
        swal.fire("Cautions!", '<?php echo $message; ?>', "warning");
    </script>

@elseif (Session::has('info'))
    @php($message = Session::get('info'))
    <script>
        swal.fire("Note it!", '<?php echo $message; ?>', "info");
    </script>
@else
@endif
