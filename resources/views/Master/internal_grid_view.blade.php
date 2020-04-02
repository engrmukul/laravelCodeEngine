<link rel="stylesheet" href="{{asset('assets/css/plugins/dataTables/datatables.min.css')}}">
<script src="{{asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/js/dataTables.fixedColumns.min.js') }}"></script>
@php($system_entry_form = url('entryform/'.$page_data['master_entry_url']))

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12 no-padding grid_form no-display">
        </div>
    </div>
    <div class="col-md-12">
        @if($page_data['enable_search'] == 1)
            @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                <div id="custom-search" class="row no-display">
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
                    <hr/>
                </div>
            @endif
        @endif
        <div class="row">
            <input type="hidden" class="form-control" name="master-entry-url" value="{{$system_entry_form}}">
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
<script>
    $(document).on('click', '#show-custom-search', function(){
        $(this).find('i').toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        $('#custom-search').slideToggle(200);
    });
    var ids = [];
    $(document).ready(function(){
        generate_datatable();
        $('div.dataTables_length select').addClass('custom-datatable-length-menu');
    });
    $("body").on("submit", "#customSearchPanel", function (e) {
        e.preventDefault();
        var search_data = $(this).serializeArray();
        generate_datatable(search_data);
    });
    /*----------------------------------------------------*/
    /*$(document).on('click', '#table-to-print tbody tr', function(){
        if($(this).toggleClass('selected')){
            var id = $(this).attr('id');
            if($(this).hasClass('selected')) {
                ids.push(id);
            } else {
                ids.splice(ids.indexOf(id),1);
            }
        }
    });*/
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
                data[0].map(function (i,k) {
                    if(k === 0){
                        var primary_key_value = i.value;
                        $(row).attr('id', primary_key_value);
                    }else{
                        $(row).attr('data-'+i.attr, i.value);
                    }
                })
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
