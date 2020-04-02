@extends('layouts.app')
@section('content')
    <link href="{{asset('assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <script src="{{asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-lg-12 no-padding grid_form no-display">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 no-padding">
                <div class="ibox">
                    <div class="ibox-title">
                        <h2><i class="fa fa-list"></i>  {!! ucwords(str_replace('_', ' ', $data['grid_title'])) !!}</h2>
                        @php($system_entry_form = url('entryform/'.$data['master_entry_url']))
                        <div class="ibox-tools">
                            @if(isSuperUser())
                                @if($data['enable_form'] == 1)
                                    @if(!empty($data['sys_master_entry_name']))
                                        <button class="btn btn-primary btn-xs" id="new-entry" data-style="zoom-in" data-formurl="{!! $system_entry_form !!}"><i class="fa fa-plus"></i> New Item</button>
                                        <button class="btn btn-info btn-xs" id="edit-row" data-style="zoom-in" data-formurl="{!! $system_entry_form !!}"><i class="fa fa-pencil"></i> Edit Item</button>
                                    @else
                                        <button class="btn btn-primary btn-xs" id="url-link" data-style="zoom-in" data-formurl="{!! url($data['master_entry_url']) !!}"><i class="fa fa-plus"></i> New Item</button>
                                        <button class="btn btn-info btn-xs" id="url-link-edit" data-style="zoom-in" data-formurl="{!! url($data['master_entry_url']) !!}"><i class="fa fa-pencil"></i> Edit Item</button>
                                    @endif
                                @endif
                                <button class="btn btn-danger btn-xs" id="delete-rows" data-formurl="{!! $system_entry_form !!}"><i class="fa fa-minus-circle"></i> Delete Item</button>
                            @endif
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover master-grid">
                                <thead>
                                <tr>
                                    <th style="width: 1%" class="no-display">#</th>
                                    @foreach($data['get_grid_data'] as $field => $grid_data)
                                        @foreach($grid_data as $col => $val)
                                            @if($data['primary_key_hide'] == 1 && $col == $data['primary_key_field'])
                                            @else
                                                <th>{!! ucfirst(str_replace('_', ' ', $col)) !!}</th>
                                            @endif
                                        @endforeach
                                        @break
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['get_grid_data'] as $field => $grid_data)
                                        @php($primary_key_field = $data['primary_key_field'])
                                        <tr class="gradeA">
                                            <td class="text-center no-display">
                                                {{$grid_data->$primary_key_field}}
                                            </td>
                                            @foreach($grid_data as $col => $val)
                                                @if($data['primary_key_hide'] == 1 && $col == $primary_key_field)
                                                @else
                                                    <td class="center">
                                                        {!! $grid_data->$col !!}
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row"></div>
    </div>
    <script>
        var ids = [];
        $(document).ready(function() {
            $('#edit-row').hide();
            $('#url-link-edit').hide();
            $(document).ready(function() {
                var dtTable = $('.master-grid').DataTable({
                    "aaSorting": []
                });
            });
        });
        /*----------------------------------------------------*/
        $('.master-grid tbody').on('click', 'tr', function(){
            if($(this).toggleClass('selected')){
                var id = $(this).find('td:eq(0)').text();
                if($(this).hasClass('selected')) {
                    ids.push(id);
                } else {
                    ids.splice(ids.indexOf(id),1);
                }
                if(ids.length == 1) {
                    $('#edit-row').show();
                    $('#url-link-edit').show();
                } else {
                    $('#edit-row').hide();
                    $('#url-link-edit').hide();
                }
            }
        });
        /*----------------------------------------------------*/
        $(document).on('click', '.close-link', function(){
            $(this).closest('.grid_form').slideUp();
        });
        /*----------------------------------------------------*/
        $('#delete-rows').on('click',function () {
            Ladda.bind(this);
            var load = $(this).ladda();
            var deleted_rows = ids.length;
            if(deleted_rows === 0) {
                swalError("No row selected");
                load.ladda('stop');
            } else {
                swalConfirm("You will not be able to Recover these " + deleted_rows + " row (s)").then(function (s) {
                    if(s.value){
                        var id_list = ids;
                        var url = '<?php echo url('delete-record');?>';
                        var data = {
                            'table_name' : "<?php echo $data['action_table']; ?>",
                            'primary_key_field' : "<?php echo $data['primary_key_field']; ?>",
                            'deleted_ids' : id_list,
                            "_token" : "<?php echo csrf_token() ?>"
                        };
                        makeAjaxPost(data, url, load).done(function (data) {
                            if(data == 'failed') {
                                swalError("Sorry, Data not deleted");
                            } else {
                                swalRedirect('', data + "row(s) has been deleted");
                            }
                        });
                    }else{
                        load.ladda('stop');
                    }
                })
            }
        });
        /*----------------------------------------------------*/
        $('#new-entry').on('click', function(){
            $('.grid_form').empty();
            Ladda.bind(this);
            var load = $(this).ladda();
            var url = $(this).data('formurl');
            makeAjaxText(url, load).done(function (response) {
                $('.grid_form').html(response);
                $('.grid_form').slideDown();
            });
        });
        $('#edit-row').on('click', function(){
            var selected_id = $.trim(ids.slice(-1)[0]);
            $('.grid_form').empty();
            Ladda.bind(this);
            var load = $(this).ladda();
            var url = $(this).data('formurl')+'/<?php echo $data['action_table']; ?>/<?php echo $data['primary_key_field']; ?>/' +selected_id;
            makeAjaxText(url, load).done(function (response) {
                $('.grid_form').html(response);
                $('.grid_form').slideDown();
            });
        });
        $('#url-link').on('click', function(){
            var url = $(this).data('formurl');
            window.location.href = url;
        });
        $('#url-link-edit').on('click', function(){
            var selected_id = $.trim(ids.slice(-1)[0]);
            var url = $(this).data('formurl')+'/'+selected_id;
            window.location.href = url;
        });
    </script>
@endsection
