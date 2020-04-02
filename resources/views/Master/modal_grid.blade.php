<div class="modal-header">
    <link href="{{asset('assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <script src="{{asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <link href="{{asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <script src="{{asset('assets/js/plugins/iCheck/icheck.min.js')}}"></script>
    {{-------------------------------------------------------------------------------------------}}
    <button type="button" class="close text-danger" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">
        {!! ucfirst(str_replace('_', ' ', $data['grid_title'])) !!}
    </h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            @if(!empty($data))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 1%"></th>
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
                            {{--@php(dd($primary_key_field))--}}
                            <tr class="gradeA">
                                <td style="width: 1%">
                                    <input type="checkbox" class="i-checks grid_selection" value="{{$grid_data->$primary_key_field}}" name="input[]">
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
            @else
                <div class="widget red-bg p-lg text-center">
                    <div class="m-b-md">
                        <i class="fa fa-warning fa-4x"></i>
                        <h3 class="font-bold no-margins">
                            No Data Found
                        </h3>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
<script>
    $('.dataTables-example').dataTable();
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
    $(document).on('click', '.load_form', function(){
        Ladda.bind(this);
        var load = $(this).ladda();
        var url = $(this).data('formurl');
        makeAjaxText(url,load).done((response)=>{
            $('#large_modal .modal-content').html(response);
            $('#large_modal').modal('show')
        });
    });

    $(document).on('click', '.load_grid', function(){
        Ladda.bind(this);
        var load = $(this).ladda();
        var url = $(this).data('gridurl');
        makeAjaxText(url,load).done((response)=>{
            $('#large_modal .modal-content').html(response);
            $('#large_modal').modal('show')
        });
    });
</script>
