@extends('layouts.app')
@section('content')
{{-- <link rel="stylesheet" href="{{asset('master/css/plugins/dataTables/datatables.min.css')}}">
<script src="{{asset('master/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('master/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('master/js/dataTables.fixedColumns.min.js') }}"></script> --}}
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12 no-padding grid_form no-display">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 no-padding">
            <div class="ibox">
                <div class="ibox-title">
                    <h2 class="master-grid-title">
                        <i class="fa fa-spinner fa-pulse"></i> {{$page_data['page_title']}}
                    </h2>
                    <div class="ibox-tools">
                        @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                            <a class="btn btn-default btn-xs collapse-link">
                                <i class="fa fa-search"></i> Filter Options
                            </a>
                        @endif
                        {{-- @if(isSuperUser()) --}}
                            @php($system_entry_form = url('entryform/'.$page_data['master_entry_url']))

                            @if($page_data['enable_form'] == 1)
                                @if(!empty($page_data['sys_master_entry_name']))
                                    <button class="btn btn-primary btn-xs" id="new-entry" data-style="zoom-in" data-formurl="{!! $system_entry_form !!}"><i class="fa fa-plus"></i> New Item</button>
                                    <button class="btn btn-info btn-xs" id="edit-row" data-style="zoom-in" data-formurl="{!! $system_entry_form !!}"><i class="fa fa-pencil"></i> Edit Item</button>
                                @else
                                    <button class="btn btn-primary btn-xs" id="url-link" data-style="zoom-in" data-formurl="{!! url($page_data['master_entry_url']) !!}"><i class="fa fa-plus"></i> New Item</button>
                                    <button class="btn btn-info btn-xs" id="url-link-edit" data-style="zoom-in" data-formurl="{!! url($page_data['master_entry_url']) !!}"><i class="fa fa-pencil"></i> Edit Item</button>
                                @endif
                            @endif
                            <button class="btn btn-danger btn-xs" id="delete-rows" data-formurl="{!! $system_entry_form !!}"><i class="fa fa-minus-circle"></i> Delete Item</button>
                        {{-- @endif --}}
                    </div>
                </div>
                <div class="ibox-content no-display">
                    @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                        <form class="" id="customSearchPanel">
                            <div class="row">
                                {!! __getCustomSearch($page_data['custom_search'], $searched_value = [], true) !!}
                                <div class="col-md-3 search_box_submit no-display" style="display: block;">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive showSearchData">
                    <table class="table table-sm table-striped table-bordered table-hover" id="table-to-print">
                        <thead>
                            @if(!empty($page_data['header']))
                                @foreach ($page_data['header'] as $rownum => $header_row)
                                    <tr>
                                        @foreach ($header_row as $header_data)
                                            <th colspan="{{isset($header_data['colspan']) ? $header_data['colspan'] : 0}}">
                                                {{strtoupper(str_replace('_', ' ', $header_data['column']))}}
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ids = [];
    $(document).ready(function(){
        generate_datatable();
        $('div.dataTables_length select').addClass('custom-datatable-length-menu');
        $('#edit-row').hide();
        $('#url-link-edit').hide();
    });
    $("body").on("submit", "#customSearchPanel", function (e) {
        e.preventDefault();
        var search_data = $(this).serializeArray();
        generate_datatable(search_data);
    });
    /*----------------------------------------------------*/
    $(document).on('click', '.close-link', function(){
        $(this).closest('.grid_form').slideUp();
    });
    /*----------------------------------------------------*/
    $(document).on('click', '#table-to-print tbody tr', function(){
        if($(this).toggleClass('selected')){
            var id = $(this).attr('id');
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
        var url = $(this).data('formurl')+'/<?php echo $page_data['action_table']; ?>/<?php echo $page_data['primary_key_field']; ?>/' +selected_id;
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
                        'table_name' : "<?php echo $page_data['action_table']; ?>",
                        'primary_key_field' : "<?php echo $page_data['primary_key_field']; ?>",
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
    /*********************************************************/
    function generate_datatable(search_data){
        $('#table-to-print').DataTable({
            "processing": true,
            "serverSide": true,
            "aaSorting": [],
            "order": [],
            "bDestroy": true,
            "language": {
                "lengthMenu": " _MENU_ records per page",
                "zeroRecords": "No Data Found !!",
                "info": "Showing page _PAGE_ of _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)"
            },
            "ajax": {
                "url": "<?php echo URL::to('get-grid-data')?>",
                "type": "POST",
                "data": function(data) {
                    data._token = "{{csrf_token()}}";
                    data.slug = '<?php echo $page_data['slug']; ?>';
                    data.search_data = search_data;
                }
            },
            'initComplete': function(settings, json) {
                $('.master-grid-title').find('i').removeClass('fa-spinner fa-pulse');
                $('.master-grid-title').find('i').addClass('fa-list');
            },
            'createdRow': function( row, data, dataIndex ) {
                console.log(data);
                data[0].map(function (i,k) {
                    if(k === 0){
                        var primary_key_value = i.value;
                        $(row).attr('id', primary_key_value);
                    }else{
                        $(row).attr('data-'+i.attr, i.value);
                    }
                });
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false
                },
                {
                    "targets": [<?php echo $page_data['column_hide']; ?>],
                    "visible": false
                }
            ],

        });
    }
</script>
<style>
    table.dataTable {
        clear: both;
        margin-top: 0px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
        border-collapse: separate !important;
    }
    .custom-datatable-length-menu{
        margin-bottom: -10px;
        height: 31px !important;
        padding: inherit;
    }
    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        padding: 0.5px;
    }
</style>
@endsection
