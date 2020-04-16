<form method="post" action="{{url('users')}}" id='user_level_entry'>
    {{csrf_field()}}
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">USERNAME</label>
        <div class="col-sm-10">
            <input type="text" name="username" placeholder="Please Enter User Name" class="form-control username" required>
            <input type="hidden" name="pkid" value="" id="pkid">
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">FIRST NAME</label>
        <div class="col-sm-10">
            <input type="text" name="first_name" placeholder="Please Enter First Name" class="form-control " required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">LAST NAME</label>
        <div class="col-sm-10">
            <input type="text" name="last_name" placeholder="Please Enter Last Name" class="form-control" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">EMAIL ADDRESS</label>
        <div class="col-sm-10">
            <input type="email" name="email" placeholder="Please Enter email" class="form-control email" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">PASSWORD</label>
        <div class="col-sm-10">
            <input type="password" name="password" placeholder="Please Enter password" class="form-control" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">CONFIRM PASSWORD</label>
        <div class="col-sm-10">
            <input type="password" name="confirm_password" placeholder="Please Enter Confirm password" class="form-control" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
     <div class="form-group  row">
        <label class="col-sm-2 col-form-label">DEFAULT URL</label>
        <div class="col-sm-10">
            <input type="text" name="default_url" placeholder="Default URL" class="form-control default_url" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">MODULE PRIVILEDGE</label>
        <div class="col-sm-10">
            {!! __combo($module_previlege['combo_slug'],$module_previlege['combo_array']) !!}
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">DEFAULT MODULE</label>
        <div class="col-sm-10">
            {!! __combo($default_module['combo_slug'],$default_module['combo_array']) !!}
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">LEVEL PRIVILEDGE</label>
        <div class="col-sm-10">
            {!! __combo($level_priviledge['combo_slug'],$level_priviledge['combo_array']) !!}
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label ">RELIGION</label>
        <div class="col-sm-10">
            <select class="form-control m-b religion" name="religion" required>
                <option value="">select</option>
                <option value="Muslim">Muslim</option>
                <option value="Hindu">Hindu</option>
                <option value="Christian">Christian</option>
                <option value="Buddhist">Buddhist</option>
            </select>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group  row">
        <label class="col-sm-2 col-form-label">GENDER</label>
        <div class="col-sm-10">
            <select class="form-control m-b gender" name="gender" required>
                <option value="">select</option>
                <option value="Male">MALE</option>
                <option value="Female">FEMALE</option>
            </select>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">ADDRESS</label>
        <div class="col-sm-10">
            <input type="text" name="address" class="form-control address" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">CONTACT NUMBER</label>
        <div class="col-sm-10">
            <input type="number" name="mobile" class="form-control mobile" required>
            <div class="help-block with-errors has-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary btn-sm" type="submit">SAVE DATA</button>
        </div>
    </div>
</form>
