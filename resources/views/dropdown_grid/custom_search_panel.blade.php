@php
    $search_panel_detail = $custom_search;
@endphp
<form method="post" class="row" autocomplete="off" id="search_panel_form">
    @foreach($search_panel_detail as $key => $form_element)
        @php($panel_id = $form_element->sys_search_panel_details_id)
        @php($input_name = $form_element->input_name)
        @php($label_name = ucwords(str_replace('_',' ', $form_element->label_name)))
        @php($placeholder_name = $form_element->placeholder)
        @php($class_name = $form_element->input_class)
        @php($id_name = $form_element->input_id)
        @php($input_type = $form_element->input_type)
        @php($input_text_type = $form_element->input_text_type)
        @php($dropdown_slug = $form_element->dropdown_slug)
        @php($dropdown_multiselect = $form_element->dropdown_multiselect)
        @php($group_type = $form_element->group_type)
        @if($input_type == 'text')
            <div class="col-md-3" id="area-{{$panel_id}}">
                <div class="form-group">
                    <label class="form-label">{{$label_name}}</label>
                    <input type="{{$input_text_type}}" id="{{$id_name}}"
                           name="{{$input_name}}"
                           value="" class="form-control {{$class_name}}"
                           placeholder="{{$placeholder_name}}">
                    <div class="help-block with-errors has-feedback"></div>
                </div>
            </div>
        @elseif($input_type == 'dropdown')
            <div class="col-md-3" id="area-{{$panel_id}}">
                <div class="form-group">
                    <label class="form-label">{{$label_name}}</label>
                    @php($arr = array('name'=>$input_name, 'selected_value'=>'','multiple'=>$dropdown_multiselect))
                    {{ __combo($dropdown_slug, $arr)}}
                    <div class="help-block with-errors has-feedback"></div>
                </div>
            </div>
        @elseif($input_type == 'date')
            <div class="col-md-3" id="area-{{$panel_id}}">
                <div class="form-group date">
                    <label class="form-label">{{$label_name}}</label>
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" placeholder="{{$placeholder_name}}" autocomplete="off"
                               class="form-control {{$class_name}} date"
                               name="{{$input_name}}" value=""/>
                    </div>

                    <div class="help-block with-errors has-feedback"></div>
                </div>
            </div>
        @elseif($input_type == 'daterange')
            <div class="col-md-3" id="area-{{$panel_id}}">
                <div class="form-group">
                    <label class="form-label">{{$label_name}}</label>
                    <div class="input-group">
                        <input type="text" placeholder="{{$placeholder_name}}" autocomplete="off"
                               class="form-control {{$class_name}} daterange"
                               name="{{$input_name}}" value=""/>
                    </div>

                    <div class="help-block with-errors has-feedback"></div>
                </div>
            </div>
        @elseif($input_type == 'groupinput')
            <div class="col-md-3 groupinput" id="area-{{$panel_id}}">
                <div class="form-group">
                    <label class="form-label">{{$label_name}}</label>
                    <div class="input-group">
                        <div class="col-md-4 nopadding">
                            <div class="form-group">
                                <select class="form-control between_type" name="{{'between-'.$input_name}}"
                                        style="height: 35px;">
                                    <option value="">Range</option>
                                    <option value="BETWEEN">BETWEEN</option>
                                    <option value="<">LESS THEN</option>
                                    <option value=">">GREATER THEN</option>
                                    <option value="<=">LESS THEN EQUAL</option>
                                    <option value=">=">GREATER THEN EQUAL</option>
                                    <option value="=">EQUAL</option>
                                </select>
                                <div class="help-block with-errors has-feedback"></div>
                            </div>
                        </div>
                        @if($group_type == 'text_range')

                            <div class="col-md-4 nopadding">
                                <div class="form-group">
                                    <input type="text" id=""
                                           name="from-{{$input_name}}"
                                           value="" class="form-control"
                                           placeholder="From">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 nopadding">
                                <div class="form-group">
                                    <input type="text" id=""
                                           name="to-{{$input_name}}"
                                           value="" class="form-control"
                                           placeholder="To">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                        @elseif($group_type == 'date_range')
                                <div class="col-md-4 nopadding">
                                    <div class="form-group">
                                        <input type="text" id=""
                                               name="from-{{$input_name}}"
                                               value="" class="form-control date"
                                               placeholder="From Date">
                                        <div class="help-block with-errors has-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 nopadding">
                                    <div class="form-group">
                                        <input type="text" id=""
                                               name="to-{{$input_name}}"
                                               value="" class="form-control date"
                                               placeholder="To Date">
                                        <div class="help-block with-errors has-feedback"></div>
                                    </div>
                                </div>
                        @elseif($group_type == 'age_range')
                            <div class="col-md-3 nopadding">
                                <div class="form-group">
                                    <input type="text" id=""
                                           name="from-{{$input_name}}"
                                           value="" class="form-control"
                                           placeholder="From">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-2 nopadding">
                                <div class="form-group">
                                    <input type="text" id=""
                                           name="to-{{$input_name}}"
                                           value="" class="form-control"
                                           placeholder="To">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-3 nopadding">
                                <div class="form-group">
                                    <select name="rangetype-{{$input_name}}" class="form-control" style="height: 35px;">
                                        <option value="YEAR">YEAR</option>
                                        <option value="MONTH">MONTH</option>
                                        <option value="DAY">DAY</option>
                                        <option value="HOUR">HOUR</option>
                                        <option value="MINUTE">MINUTE</option>
                                        <option value="SECOND">SECOND</option>
                                    </select>
                                </div>
                            </div>
                        @elseif($group_type == 'num_range')
                            <div class="col-md-4 nopadding">
                                <div class="form-group">
                                    <input type="number" id=""
                                           name="from-{{$input_name}}"
                                           value="" class="form-control"
                                           placeholder="From">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 nopadding">
                                <div class="form-group">
                                    <input type="number" id=""
                                           name="to-{{$input_name}}"
                                           value="" class="form-control"
                                           placeholder="To">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="help-block with-errors has-feedback"></div>
                </div>
            </div>
        @endif
    @endforeach

    <div class="col-md-1">
        <div class="form-group">
            <label></label>
            <div class="input-group">
                <button type="submit" class="btn btn-xs btn-success search"><i class="fa fa-search"></i> Search</button>
            </div>

        </div>

    </div>
</form>

<style>
    .nopadding {
        padding: 0 !important;
        margin: 0 !important;
    }

    #search_by_area .btn-group {
        width: 250px !important;
    }
</style>
<script>
    $(document).ready(function () {
        $('.date').datepicker({format: "yyyy-mm-dd", autoclose: true});
        var default_search_by = "{{implode(',',$page_data['default_search_by'])}}";
        search_items = "{{implode(',',$page_data['search_items'])}}";

        $.each(search_items.split(','), function (i, v) {
            if (default_search_by.split(',').includes(v)) {
                $('#area-' + v).show();
            } else {
                $('#area-' + v).hide();

            }
        });
    });
</script>