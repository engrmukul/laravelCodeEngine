
<div class="row">
    <div class="col-md-12">
        <label class="font-normal"><strong>{{$reference}}  Address</strong> <span class="required">*</span></label>
        @if($reference =='Permanent') <label class="pl-3 same_as_present" for="same_as_present"><input class="float-left mt-1 mr-1" type="checkbox" id="same_as_present"> Same as Present</label> @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Address Line</label>
        <div class="form-group">
            <input type="text" name="{{$key}}_address_line" id="{{$key}}_address_line" placeholder="{{ucfirst($key)}} Address Line" class="form-control" value="{{$address_line}}"  autocomplete="off">
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">District <span class="required">*</span></label>
        <div class="form-group">
            {{__combo('address_districts_list', array('selected_value'=> $district, 'attributes'=> array( 'name'=>$key.'_district', 'required'=>'required', 'id'=>$key.'_district', 'class'=>'form-control multi', 'placeholder'=>$key." District")))}}
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">{{__dropdown_grid('address_upazilas_list', array('selected_value'=> '',
                                'name'=>'<i class="fa fa-plus-circle"></i> Select Thana','addbuttonid'=>$key.'_thana_list_selection','attributes'=> array( 'name'=>$key.'_thana_btn', 'required'=>'required', 'id'=>$key.'_thana_btn','style'=>'padding:0 5px;', 'class'=>'btn btn-success btn-xs', 'placeholder'=>$key." Thana"),
                                'dependent_data'=>array(array('id'=>$key.'_district','required'=>true,'dbcolumn'=>'address_districts.address_districts_name'))))}} <span class="required">*</span></label>
        <div class="form-group">
            <input type="text" class="form-control" required readonly name="{{$key}}_thana" id="{{$key}}_thana" value="{{$thana}}"/>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Post Office <span class="required">*</span></label>
        <div class="form-group">
            <input type="text" required name="{{$key}}_po" id="{{$key}}_po" placeholder="{{ucfirst($key)}} Post Office" class="form-control" value="{{$post_office}}"  autocomplete="off">
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Post Code</label>
        <div class="form-group">
            <input type="text" name="{{$key}}_post_code" id="{{$key}}_post_code" placeholder="{{ucfirst($key)}} Post Code" class="form-control" value="{{$post_code}}"  autocomplete="off">
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Village/House/Road No. <span class="required">*</span></label>
        <div class="form-group">
            <input name="{{$key}}_village" id="{{$key}}_village" class="form-control" placeholder="Village/House/Road No." value="{{$address}}" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
</div>

<script>
    $('#{{$key}}_district').change(function(){
        $('#thana_name').val('');
    });
    $(document).on('click', '#{{$key}}_thana_btn', function (e) {
        Ladda.bind(this);
        var load = $(this).ladda();
        var {{$key}}district = $('#{{$key}}_district').val();
        if ({{$key}}district == '') {
            swalWarning("Please Select {{$key}} District");
        } else {
            grid_modal_show($(this));
        }
    });

    $(document).on('click', '#{{$key}}_thana_list_selection', function () {
        Ladda.bind(this);
        var load = $(this).ladda();

        var selected = getSelectedItems();
        $('#{{$key}}_thana').val(selected.replace(/'/g,''));
    });
</script>