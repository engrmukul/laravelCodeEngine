@extends('layouts.app')
@section('content')
    <link href="{{asset('assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <script src="{{asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-md-12 no-padding">
                <div class="ibox">
                    <div class="ibox-title">
                        <h2>User List</h2>
                    </div>

                    <div class="ibox-title">
                        <div class="ibox-tools">
                            @if(isSuperUser())
                                <a href="{{url('user-entry')}}" class="btn btn-primary btn-xs"><i class="fa fa-plus-circle" aria-hidden="true"></i> New User</a>
                                <button class="btn btn-warning btn-xs" id="item_edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                                {{--<button class="btn btn-primary btn-xs" id="item_view"><i class="fa fa-eye" aria-hidden="true"></i> View</button>--}}
                            @endif
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover checkbox-clickable" id="userTable">
                                <thead>
                                <tr>
                                    <th>User Code</th>
                                    <th>Full Name</th>
                                    <th>User Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($userlist))
                                @foreach($userlist as $list)
                                    <tr class="row-select-toggle" data-id="{{$list->id}}" id="{{ $list->id ?? ''}}">
                                        <td>{{ $list->user_code ?? 'N/A'}}</td>
                                        <td>{{ $list->name ?? 'N/A'}}</td>
                                        <td>{{ $list->username ?? 'N/A'}}</td>
                                        <td>{{ $list->mobile ?? 'N/A'}}</td>
                                        <td>{{ $list->email ?? 'N/A'}}</td>
                                        <td>{{ $list->status ?? 'N/A'}}</td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
$('#userTable').dataTable();
var selected_item = [];
$(document).on('click','.checkbox-clickable tbody tr',function (e) {
    $obj = $(this);
    if(!$(this).attr('id')){
        return true;
    }
    $obj.toggleClass('selected');
    var id = $obj.attr('id');
    if ($obj.hasClass( "selected" )){
        selected_item.push(id);
    }else{
        var index = selected_item.indexOf(id);
        selected_item.splice(index,1);
    }
    if(selected_item.length==1){
        $('#item_edit, #item_view').show();
    }else if(selected_item.length==0){
        $('#item_edit, #item_view').hide();
    }else{
        $('#item_edit, #item_view').hide();
    }

});

$(document).on('click','#item_view', function (e) {
    var $row = $('.checkbox-clickable tbody');
    var sys_users_id = $row.find('.selected').data('id');
    var data = {'sys_users_id':sys_users_id};
    var url = '<?php echo URL::to('get-user-profile');?>';
    Ladda.bind(this);
    var load = $(this).ladda();
    if (selected_item.length == 1) {
        makeAjaxPostText(data,url,load).done(function (response) {
            if(response){
                $('#medium_modal .modal-content').html(response);
                $('#medium_modal').modal('show');
            }
        });

    } else {
        swalWarning("Please select single item");
        return false;

    }
});

$(document).on('click','#item_edit', function (e) {
    var $row = $('.checkbox-clickable tbody');
    var sys_users_id = $row.find('.selected').data('id');
    var url = '<?php echo URL::to('user-entry');?>/'+sys_users_id;
    Ladda.bind(this);
    var load = $(this).ladda();
    if (selected_item.length == 1) {
        window.location.replace(url);
    } else {
        swalWarning("Please select single item");
        return false;

    }
});

    </script>
@endsection
