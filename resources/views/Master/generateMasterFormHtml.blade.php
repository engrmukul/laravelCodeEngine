@php($table_name = $form_elements[0]->table_name)
@php($master_entry_name = $form_elements[0]->sys_master_entry_name)
@php($form_information = $page_info[0])
{{----------------------------}}
@if($form_information->form_column == 2)
    @php($gridVal = 'col-md-6')
@elseif($form_information->form_column == 3)
    @php($gridVal = 'col-md-4')
@elseif($form_information->form_column == 4)
    @php($gridVal = 'col-md-3')
@else
    @php($gridVal = 'col-md-12')
@endif

{{-----------------------------}}
@php($add_more = $form_information->form_add_more)
@php($button_array = [])
@if(!empty($form_information->form_action))
    @php($action_route = $form_information->form_action)
@else
    @php($action_route = 'masterFormDataStore')
@endif
{{-----------------------------}}
<div class="row"></div>
<form action="{{url($action_route)}}"
      method="{{$form_information->method}}"
      id="{{$form_information->form_id}}"
      class="form master-form {{$form_information->form_class}}"
      enctype="multipart/form-data">

    @php($form_addmore = $add_more == 1 ? 'add_'.$form_information->form_id : '')
    <div class="col-md-12 row p-0">
        {{csrf_field()}}
        <input type="hidden" name="route_name" value="{{$form_information->route_name}}">
        <input type="hidden" name="tableName" value="{{$table_name}}">
        <input type="hidden" name="submit_method" value="{{$form_information->form_action_mode}}">
        @if(isset($edit_data) && !empty($edit_data))
            <input type="hidden" name="edit_field_key" value="{{$table_name.'_id'}}">
            <input type="hidden" name="edit_field_value" value="{{$edit_data_key}}">
        @endif
        <div class="{{$form_addmore}} col-md-12 row">
            <div class="clone_div col-md-12 row">
                @foreach($form_elements as $key => $form_element)
                    @php($sys_master_entry_details_id = $form_element->sys_master_entry_details_id)
                    @php($field_name = $form_element->field_name)
                    @php($label_name = ucwords(str_replace('_',' ', $form_element->label_name)))
                    @php($placeholder = $form_element->placeholder)
                    @php($class_name = $form_element->input_class)
                    @php($label_class = $form_element->label_class)
                    @php($id_name = $form_element->input_id)
                    @php($input_type = $form_element->input_type)
                    @php($required = $form_element->required)
                    @php($dropdown_slug = $form_element->dropdown_slug)
                    @php($dropdown_options = $form_element->dropdown_options)
                    @php($dropdown_view = $form_element->dropdown_view)
                    @php($field_value = isset($edit_data) && !empty($edit_data) ? $edit_data->$field_name : '')
                    @php($autocomplete_validity = isset($form_element->autocomplete_query) && !empty($form_element->autocomplete_query) ? 1 : 0)
                    @if($input_type == 'checkbox')
                        <div class="{{$gridVal}}">
                            <div class="form-group">
                                <br/>
                                <input type="checkbox" id="{{$id_name}}" name="" value="{{$field_value}}" class="custom-check" {{$field_value == 1 ? 'checked': ''}}>
                                <label class="{{$label_class}}" for="{{$id_name}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                <input class="{{$id_name}}" type="hidden" name="{{$field_name}}" value="{{$field_value}}">
                            </div>

                        </div>
                    @elseif($input_type == 'date')
                        <div class="{{$gridVal}}">
                            <div class="form-group">
                                <label class="{{$label_class}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                <div class="input-group">
                                    <input type="text"
                                           name="{{$field_name.'[]'}}"
                                           value="{{$field_value}}" class="form-control {{$class_name}} datepicker"
                                           placeholder="{{$placeholder}}"
                                            {{ $required == 1 ? 'required' : '' }}>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                                <div class="help-block with-errors has-feedback"></div>
                            </div>
                        </div>
                    @elseif($input_type == 'dropdown')
                        @if($dropdown_view == 'grid')
                            <div class="{{$gridVal}} ">
                                <div class="form-group">
                                    <label class="{{$label_class}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                    <div class="input-group">
                                        @if($autocomplete_validity)
                                            <input type="text" id="{{$id_name}}"
                                                   class="form-control autocomplete-master-entry {{$class_name}}"
                                                   data-slug="{{$id_name}}"
                                                   data-name="{{$field_name}}"
                                                   data-value="{{$field_value}}"
                                                   data-editvalueurl="<?php echo URL::to('get-autocomplete-query/edit/'.$sys_master_entry_details_id);?>"
                                                   data-autocompleteurl="<?php echo URL::to('get-autocomplete-query/search/'.$sys_master_entry_details_id);?>"/>

                                            <div class="input-group-addon btn btn-default" id="open-{{$id_name}}"><i class="fa fa-list"></i></div>
                                        @else
                                            <input type="text" id="{{$id_name}}"
                                                   class="form-control {{$class_name}}"
                                                   readonly
                                                   value="{{$field_value ? getSelectedOptionForMasterGrid($dropdown_slug, $field_value) : ''}}"/>
                                            <input type="hidden" name="{{$field_name}}[]" value="{{$field_value}}" id="value-{{$id_name}}"/>
                                            <div class="input-group-addon btn btn-default" id="open-{{$id_name}}"><i class="fa fa-list"></i></div>
                                        @endif
                                        <span class="input-group-append">
                                            {{__dropdown_grid(
                                                $slug = $dropdown_slug,
                                                $data = array(
                                                    'selected_value' => $field_value,
                                                    'selected_value_tag_id' => $id_name.'-add',
                                                    'multiple' => 'NO',
                                                    'addbuttonid'=> 'selection-'.$id_name,
                                                )
                                            )}}
                                        </span>
                                    </div>
                                </div>
                                <script>
                                    /**--------Dropdown Grid Functions---------**/
                                    $(document).on('click', '#open-<?php echo $id_name; ?>', function (e) {
                                        grid_modal_show($('#<?php echo $id_name; ?>-add'));
                                    });
                                    $('body').on('click', '#selection-<?php echo $id_name; ?>', function () {
                                        var gridselectedItems = getSelectedItems();
                                        var gridselectedItemsArray = getSelectedItemsArray();
                                        $('#<?php echo $id_name; ?>-add').data('selected_value', gridselectedItems);
                                        gridselectedItemsArray.map((data) => {
                                            $('#<?php echo $id_name; ?>').val(data.option);
                                            $('#value-<?php echo $id_name; ?>').val(data.value);
                                        })
                                    });
                                    /**------------------**/
                                </script>
                            </div>
                        @else
                            @php($dropdown_name = $field_name.'[]')
                            @if($dropdown_slug != '')
                                <div class="{{$gridVal}} ">
                                    <div class="form-group">
                                        <label class="{{$label_class}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                        @php($arr = array(
                                            'name'=>$dropdown_name,
                                            'selected_value'=>$field_value,
                                            'attributes'=> array(
                                                'class' => $class_name,
                                                'required' => $required == 1 ? 'required' : ''
                                            )
                                        ))
                                        {!! __combo($dropdown_slug, $arr) !!}
                                        <div class="help-block with-errors has-feedback"></div>
                                    </div>
                                </div>
                            @else
                                @php($option_arr = [])
                                <div class="{{$gridVal}}">
                                    <div class="form-group">
                                        <label class="{{$label_class}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                        <?php
                                        $enum_options = $dropdown_options;
                                        $option_arr[''] = '--Select an option--';
                                        if (!empty($enum_options)) {
                                            foreach (explode(',', $enum_options) as $options) {
                                                $option_arr[$options] = $options;
                                            }
                                        }
                                        $attr_arr['class'] = $class_name;
                                        if($required == 1){
                                            $attr_arr['required'] = 'required';
                                        }
                                        ?>
                                        {{Form::select($dropdown_name, $option_arr, $field_value, $attr_arr)}}
                                        <div class="help-block with-errors has-feedback"></div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @elseif($input_type == 'textarea')
                        <div class="{{$gridVal}}">
                            <div class="form-group ">
                                <label class="{{$label_class}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                <textarea name="{{$field_name.'[]'}}"
                                          rows = "1"
                                        class="form-control {{$class_name}}"
                                        {{ $required == 1 ? 'required' : '' }}>{{$field_value}}</textarea>
                                <div class="help-block with-errors has-feedback"></div>
                            </div>
                        </div>
                    @elseif($input_type == 'submit')
                        @php($button_array[$key]['label_class'] = $label_class)
                        @php($button_array[$key]['class_name'] = 'submit_button '.$class_name)
                        @php($button_array[$key]['button_text'] = $placeholder)
                    @elseif($input_type == 'button')
                        @php($button_array[$key]['label_class'] = $label_class)
                        @php($button_array[$key]['class_name'] = $class_name)
                        @php($button_array[$key]['button_text'] = $placeholder)
                    @else
                        <div class="{{$gridVal}}">
                            <div class="form-group">
                                <label class="{{$label_class}}">{!! $required == 1 ? '<span class="required">*</span>' : '' !!}{{$label_name}}</label>
                                <input type="text"
                                       name="{{$field_name.'[]'}}"
                                       value="{{$field_value}}" class="form-control {{$class_name}}"
                                       placeholder="{{$placeholder}}"
                                        {{ $required == 1 ? 'required' : '' }}>
                                <div class="help-block with-errors has-feedback"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="col-md-12 form-group remove_form_div" id="" style="display:none">
                    <span class="btn btn-xs btn-danger pull-right remove_button" data-form-id="" type=""><i class="fa fa-minus-circle"></i> Remove Form</span>
                </div>
            </div>
        </div>
        @if($add_more == 1)
            <div class="col-md-12 row" id="added_{{$form_information->form_id}}"></div>
        @endif
        @if(!empty($button_array))
            <div class="col-md-12">
                <div class="form-group">
                    @foreach($button_array as $buttons)
                        {{--@php(dd($button_array))--}}
                        <button class="{{$buttons['class_name']}}"
                            data-style="expand-right"
                            data-form-id="{{$form_information->form_id}}"
                            data-submit-type = "{{$form_information->form_action_mode}}"
                            type="">{{$buttons['button_text']}}</button>
                    @endforeach
                    @php($grid_url = url('modalgrid/'.$master_entry_name))
                    @if(!in_array($mode, ['entry', 'edit']))
                        @if($add_more == 1)
                            <span class="btn btn-primary btn-xs pull-right add_button" data-form-id="{{$form_information->form_id}}" type=""><i class="fa fa-plus"></i> Add More</span>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</form>
<div class="row"></div>
<script src="{{asset('assets/js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/js/plugins/daterangepicker/daterangepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/plugins/datepicker/datepicker3.css')}}"/>
<script>
    $('[type="checkbox"]').change(function(){
        var check_id = $(this).attr('id');
        if ($(this).is(':checked')) {
            $('.'+check_id).val(1);
        }else{
            $('.'+check_id).val(0);
        }
    });
    $('.datepicker').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        autoApply:true,
        format: "yyyy-mm-dd"
    });
</script>
