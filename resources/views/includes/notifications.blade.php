@php($user_id = session()->get('USER_ID'))
<link href="{{asset('assets/css/plugins/toastr/toastr.css')}}" rel="stylesheet">
<script src="{{asset('assets/js/plugins/toastr/toastr.min.js')}}"></script>
<script src="https://js.pusher.com/5.1/pusher.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": 0,
        "extendedTimeOut": 0,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    };
    var user_id = '<?php echo $user_id; ?>';
    Pusher.logToConsole = true;
    var pusher = new Pusher('efc063b933b5a0f308ec', {
        cluster: 'ap1',
        forceTLS: true
    });


    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });

    var channel = pusher.subscribe('apsisengin-channel_00');
    channel.bind("App\\Events\\NotifyEvent", function(data) {
        var notification_data = data.notification.information;
        var total_unread = data.notification.total_unread;
        if(user_id == notification_data.notify_to){
            notificationAction(notification_data)
            $('.unread_counter').text(total_unread);
        }
    });
    function notificationAction(notification_data) {
        var alert_type = 'success';
        var noted_title = notification_data.sys_approval_modules_name;
        var content = notification_data.content;
        var data_property = 'data-url="'+notification_data.url_ref+'" data-slug="'+notification_data.event_for+'" ';
        if(notification_data.priority == 3){
            alert_type = 'info';
            content += '<button type="button" '+ data_property +'class="btn btn-block btn-'+alert_type+' btn-sm clear goto_module_url">View Notification</button>';
            toastr.info(content, '<i class="fa fa-star-o"></i> '+noted_title);
        }else if(notification_data.priority == 2){
            alert_type = 'warning';
            content += '<button type="button" '+ data_property +'class="btn btn-block btn-'+alert_type+' btn-sm clear goto_module_url">View Notification</button>';
            toastr.warning(content, '<i class="fa fa-star-half-empty"></i> '+noted_title);
        }else if(notification_data.priority == 1){
            alert_type = 'danger';
            content += '<button type="button" '+ data_property +'class="btn btn-block btn-'+alert_type+' btn-sm clear goto_module_url">View Notification</button>';
            toastr.error(content, '<i class="fa fa-star"></i> '+noted_title);
        }else{
            alert_type = 'success';
            content += '<button type="button" '+ data_property +'class="btn btn-block btn-'+alert_type+' btn-sm clear goto_module_url">View Notification</button>';
            toastr.success(content, '<i class="fa fa-warning"></i> '+noted_title);
        }

    }
    $(document).on('click','.goto_module_url', function (e) {
        e.preventDefault();
        var data = {
            "_token": "{{ csrf_token() }}",
            'route' : $(this).data('url'),
            'slug' : $(this).data('slug')
        };
        var url = "<?php echo URL::to('seen-approval-notification') ?>";
        makeAjaxPost(data, url).then(function (result) {
            if(result.message == 'Success'){
                window.location.href = result.url;
            }
        });
    });
//    $(document).ready(function () {
//        var noted_title = 'Nsnljknain sdger';
//        var noted_content = 'Nsnljknain sdger arsgbayuer Kawhaf yubulsrf';
//        var content = noted_content;
//    });
</script>
