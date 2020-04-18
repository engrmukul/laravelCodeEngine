@extends('layouts.app')
@section('content')

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{$page_data['page_title']}}</h5>
                        @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                            <div class="ibox-tools">
                                <span class="advanchedSearchToggle" style="float: right;">
                                <button type="button"
                                        class="btn btn-primary btn-xs panel-controller"
                                        id="top_search">
                                        <i class="fa fa-search"></i> Advanced Search
                                </button>
                            </span>
                            </div>
                        @endif

                    </div>
                    <div class="ibox-content">
                        @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                            @include('search.search_area')
                        @endif
                        <div class="table-responsive showSearchData">
                            <table class="table table-sm table-striped table-bordered table-hover" id="posts">
                                <thead>
                                @if(!empty($page_data['header']))
                                    @foreach ($page_data['header'] as $rownum => $header_row)
                                        <tr>
                                            @foreach ($header_row as $header_data)
                                                <th colspan = "{{isset($header_data['colspan']) ? $header_data['colspan'] : 0}}">
                                                    {{strtoupper(str_replace('_', ' ', $header_data['column']))}}
                                                </th>
                                                @if($rownum === 'last')
                                                    @php
                                                        $columnname[] = ['data' => $header_data['column']]
                                                    @endphp
                                                @endif
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
    </div>

    <link rel="stylesheet" href="{{asset('assets/css/plugins/dataTables/datatables.min.css')}}">
    <script src="{{asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.fixedColumns.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            var dataTable = $('#posts').DataTable({
                "pageLength": 10,
                "responsive": true,
//                "dom": '<"html5buttons"B>lTfgitp',
                "scrollY": "400px",
                "scrollX": true,
                "scrollCollapse": true,
                "fixedColumns": {
                    leftColumns: '{{$page_data['column_fix_l'] or 0}}',
                    rightColumns: '{{$page_data['column_fix_r'] or 0}}'
                },
                "processing": true,
                "serverSide": true,
                "language": {
                    processing: '<span class="text-warning"><i class="fa fa-spinner fa-spin fa-fw"></i>&nbsp;&nbsp; Processing ...</span>'
                },
                "ajax":{
                    "url": "{{ url('searched-warehouse-data') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(data) {
                        // console.log(data);
                        data._token = "{{csrf_token()}}";
                        data.grid_function = "<?php echo $page_data['grid_function'] ?>";
                        data.custom_search = $('#custom_search').serializeArray();
                    }
                },
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv', title: '{{$page_data['grid_title'] or 'Report Grid'}}'},
                    {extend: 'excel', title: '{{$page_data['grid_title'] or 'Report Grid'}}'},
                    {extend: 'pdf', title: '{{$page_data['grid_title'] or 'Report Grid'}}'},
                    {extend: 'print',
                        customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ],
                "columns": '<?php echo json_encode($columnname); ?>'
            });



//            $('#custom_search').on( 'submit', function (event) {
//                event.preventDefault();
//                //console.log($('#custom_search').serialize());
//                dataTable.draw();
//            });
            $(document).on('click', '.search_submit', function (e) {
                e.preventDefault();

                var _token = '<?php echo csrf_token() ?>';
                var search_type = $(this).attr('search_type');
                var error = 0;

                Ladda.bind(this);
                var l = $(this).ladda();


                $('.mendatory').each(function(){
                    var val = $(this).val();
                    if(!val) {
                        error = 1;
                    }
                });
                if(error){
                    swal({
                        title: "Sorry!",
                        text: 'Star(*) marked fields are required.',
                        type: "warning"
                    });
                } else {
                    dataTable.draw();
                }
            });
        });

        $(document).on('click', '#top_search, .top_search', function () {
            $('#search_by').toggle();
            $('.advanchedSearchToggle').toggle();
        });

    </script>

@endsection
