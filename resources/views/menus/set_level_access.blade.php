@extends('layouts.app')
@section('content')
    <script src="{{asset('assets/js/plugins/nestable/jquery.nestable.js')}}"></script>
    <link href="{{asset('assets/js/plugins/nestable/menu_drug.css')}}" rel="stylesheet">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Nestable custom theme list</h5>
                    </div>
                    <div class="ibox-content">
                        {{ Form::open(['route' => 'get_menu_for_level']) }}
                        <div class="form-group has-feedback">
                            <label for="moduleFor" class="control-label">Module For</label>
                            {!! __combo($module['combo_slug'],$module['combo_array']) !!}
                        </div>
                        <div class="form-group has-feedback">
                            <label for="levelFor" class="control-label">Level For</label>
                            {!! __combo($level['combo_slug'],$level['combo_array']) !!}
                        </div>
                        <div class="form-group has-feedback">
                            <button class="btn btn-info btn-xs pull-right">See Menu Privilege <i class="fa fa-save"></i></button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Menu Information
                            <small>Create Menu</small>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        {{ Form::open(['route' => 'get_menu_for_level']) }}
                        <?php if(isset($menu_list_array) && !empty($menu_list_array)){ ?>
                        <hr/>
                        <form action="<?php echo base_url('menu_manager/menu/set_menu_access_for_level') . getmenu();?>"
                              method="post">
                            <?php echo form_hidden('module_id', $selected_module); ?>
                            <?php echo form_hidden('level_id', $selected_level); ?>
                            <div class="row"></div>
                            <?php echo $menu_list_array; ?>
                            <div class="row"></div>
                            <button type="submit" name="mysubmit" class="btn btn-info btn-flat btn-block"> Save Menu
                                Priviledge
                            </button>
                        </form>
                        <?php } ?>
                        <div class="form-group">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
