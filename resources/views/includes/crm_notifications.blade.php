<script>
    var neededData;
    $.getJSON('{{URL::to("get-crm-notification-data")}}', function(jsonData) {
        neededData = jsonData;
        jQuery.each(neededData, function(i, val) {
            console.log(val);
            crmNotificationAction(val);
        });
    });



    function crmNotificationAction(notification_data) {
        var alert_type = 'danger';
        var noted_title = notification_data.activities_type +' schedule for '+ notification_data.activities_for;
        var subject = notification_data.subject;
        var content = '';
        content += '<a style="text-decoration: underline" href="{{URL::to('notification-activities-details')}}/'+notification_data.crm_activities_id+'">'+subject+'</a>';
        if(notification_data.from_scedule){
            var from = new Date(notification_data.from_scedule);
            content += '<br/> Time - '+from.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        }

        if(notification_data.to_scedule){
            var to = new Date(notification_data.to_scedule);
            content += ' To '+to.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        }


        if(notification_data.priority == 'High'){
            alert_type = 'danger';
            toastr.error(content, '<i class="fa fa-star-o"></i> '+noted_title);
        }else if(notification_data.priority == 'Normal'){
            alert_type = 'warning';
            toastr.warning(content, '<i class="fa fa-star-o"></i> '+noted_title);
        }else if(notification_data.priority == 'Low'){
            alert_type = 'info';
            toastr.info(content, '<i class="fa fa-star-o"></i> '+noted_title);
        }

    }
</script>