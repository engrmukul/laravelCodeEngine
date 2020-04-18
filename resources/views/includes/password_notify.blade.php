@if(Request::url() != URL::to('/reset_password'))
    @if(Session::get('PASSWORD_EXPIRY'))
        @if(Session::get('PASSWORD_EXPIRY_ACTION') == 'Force')
            <script>window.location = "reset_password";</script>
        @endif
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p>Your Password expired. Click here to <strong><a href="{{url('reset_password')}}">Reset</a></strong> password</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if(Session::get('PASSWORD_NOTIFY'))
        <div id="passwordNotify" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body">
                        <p>Password will expire within 6 days</p>
                        <strong><a href="{{url('reset_password')}}">Reset</a> </strong><p>by clicking here</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default notify_dismiss" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endif
<script type="text/javascript">
    $(window).on('load', function(){
        $('#passwordNotify').modal('show');
    });
    $(document).on('click','.notify_dismiss',function(){
        var _token = '{{csrf_token()}}';
        var url = '{{URL::to("notify-dismiss")}}';
        var data = {_token:_token};
        makeAjaxPostText(data, url).done(function (response) {
            $('#passwordNotify').modal('hide');
        });
    });
</script>