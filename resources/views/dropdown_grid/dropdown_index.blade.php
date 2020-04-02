@extends('layouts.app')
@section('content')
    @include('dropdown_grid.dropdown_grid')

    {{__dropdown_grid($slug = 'product-dropdown-grid',$data = array(
    'selected_value'=>'1,3','addbuttonid'=>'products_list_details','attributes'=>array('class'=>'btn btn-success','id'=>'id_products_list_details'),
    'dependent_data'=>array(array('id'=>'purchase_categorys_id','required'=>true,'dbcolumn'=>'notable.product_category_id'),array('id'=>'warehouses_id','required'=>false,'dbcolumn'=>'warehouses.warehouses_id')),
     'name'=>'<i class="fa fa-plus-circle"></i> Add Products Details'))}}

    {{__dropdown_grid($slug = 'products_list_details2',$data = array(
        'selected_value'=>'',
        'addbuttonid'=>'products_list_selection',
        'name'=>'<i class="fa fa-plus-circle"></i> Add Products',
        'dependent_data'=>array(
            array(
                'id'=>'purchase_categorys_id',
                'required'=>true,
                'dbcolumn'=>'notable.product_category_id'
            ),
            array(
                'id'=>'warehouses_id',
                'required'=>true,
                'dbcolumn'=>'warehouses.warehouses_id'
            )
        ),
        'attributes'=>array('class'=>'btn btn-primary','id'=>'id_products_list'))
    )}}

    {{__dropdown_grid($slug = 'products_list_details',$data = array(
        'selected_value'=>'', 'name'=>' Add Products Details',
        'addbuttonid'=>'details_product_selection',
        'attributes'=>array('class'=>'btn btn-success','id'=>'id_products_list_details2')))}}

    {{__dropdown_grid($slug = 'vendor_list_for_po',
    $data = array(
        'selected_value'=>'',
        'name'=> __lang('Add_Vendors'),
        'addbuttonid'=>'vendor_selection',
        'attributes'=>array('class'=>'btn btn-success','id'=>'add_vendor'))
        )
        }}
    {{__dropdown_grid($slug = 'ledger_grid',$data = array(
        'selected_value'=>'', 'name'=>' Add Head','addbuttonid'=>'head_selection','attributes'=>array('class'=>'btn btn-success','id'=>'add_head')))}}

    {{__dropdown_grid($slug = 'items_grid',$data = array(
        'selected_value'=>'', 'name'=>' Add Item','attributes'=>array('class'=>'btn btn-success','id'=>'add_item')))}}

    {{__dropdown_grid($slug = 'hr_emp_list',$data = array(
        'selected_value'=>'', 'name'=>' Add Employee','attributes'=>array('class'=>'btn btn-success','id'=>'add_item')))}}

    {{__dropdown_grid($slug = 'approved-pi-list',
                                                $data = array(
                                                    'selected_value'=>'',
                                                    'name'=>'<i class="fa fa-plus-circle"></i> Select PI',
                                                    'multiple'=>'YES',
                                                    'addbuttonid'=>'pi_list_selection',
                                                    'attributes'=>array('class'=>'btn btn-success','id'=>'btn_select_pi'))
                                                )
                                            }}

    <div class="input-group">
        <input type="text" id="product_grid_input" class="form-control" readonly>
            {{__dropdown_grid(
                $slug = 'products_list',
                $data = array(
                    'selected_value'=>'',
                    'selected_value_tag_id'=>'requisition-item-add',
                    'multiple'=>'NO',
                    'addbuttonid'=>'requisition-selection',
                )
            )}}
    </div>
    <div class="form-group col-md-6">
        <label class="font-bold">Products Category</label>
        {{__combo($slug = 'purchase_category', $data = array(
            'selected_value'=>'','multiple'=>false))}}
    </div>
    <div class="form-group col-md-6">
        <label class="font-bold">Warehouses</label>
        {{__combo($slug = 'warehouses', $data = array(
            'selected_value'=>'','multiple'=>false))}}
    </div>
    <script>
        $(document).on('click', '#id_products_list_details', function (e) {
            Ladda.bind(this);
            var l = $(this).ladda();
            // call grid function to show modal
            grid_modal_show($(this));
        });
        $(document).on('click', '#id_products_list_details', function (e) {
            Ladda.bind(this);
            var l = $(this).ladda();
            // call grid function to show modal

            grid_modal_show($(this));
        });
        $(document).on('click', '#id_products_list_details2', function (e) {
            Ladda.bind(this);
            var l = $(this).ladda();
            // call grid function to show modal

            grid_modal_show($(this));
        });
        $(document).on('click', '#id_products_list', function (e) {
            Ladda.bind(this);
            var l = $(this).ladda();
            // call grid function to show modal

            grid_modal_show($(this));
        });
        $(document).on('click', '#add_vendor', function (e) {
            grid_modal_show($(this));
        });

        $(document).on('click', '#add_head', function (e) {
            grid_modal_show($(this));
        });
        $(document).on('click', '#add_item', function (e) {
            grid_modal_show($(this));
        });

        $('body').on('click', '#products_list_details', function () {
            var gridselectedItems = getSelectedItems();
            alert(gridselectedItems);
            $('#id_products_list_details').data('selected_value', gridselectedItems);

        });

        $('body').on('click', '#products_list_selection', function () {
            var gridselectedItems = getSelectedItems();
            // alert(gridselectedItems);

        });

        $(document).on('click', '#product_grid_input', function (e) {
            grid_modal_show($('#requisition-item-add'));
        });

        $('body').on('click', '#head_selection', function () {
            var gridselectedItems = getSelectedItems();
            console.log(gridselectedItems);

        });
        $('body').on('click', '#details_product_selection', function () {
            var gridselectedItems = getSelectedItems();
            console.log(gridselectedItems);

        });
        $('body').on('click', '#requisition-selection', function () {
            var gridselectedItems = getSelectedItems();
            var gridselectedItemsArray = getSelectedItemsArray();

            $('#requisition-item-add').data('selected_value', gridselectedItems);
            $('#product_grid_input').val(gridselectedItemsArray[0].option);
            console.log(gridselectedItemsArray);

        });


        $('body').on('click', '#vendor_selection', function () {
            var gridselectedItems = getSelectedItems();
            var gridselectedItemsArray = getSelectedItemsArray();

            $('#add_vendor').data('selected_value',gridselectedItems);
            console.log(gridselectedItemsArray);

        });

        $(document).on('click', '#btn_select_pi', function (e) {
            grid_modal_show($(this));
        });

        $('body').on('click', '#pi_list_selection', function () {
            var gridselectedItems = getSelectedItems();
            var gridselectedItemsArray = getSelectedItemsArray();

            $('#btn_select_pi').data('selected_value',gridselectedItems);
            console.log(gridselectedItems);

        });
        $('body').on('click', '#add', function () {
            var gridselectedItems = getSelectedItems();
            var gridselectedItemsArray = getSelectedItemsArray();

            $('#add_item').data('selected_value',gridselectedItems);
            console.log(gridselectedItems);

        });
    </script>
@endsection