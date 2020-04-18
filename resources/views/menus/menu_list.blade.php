@extends('layouts.app')
@section('content')
    <script src="{{asset('assets/js/plugins/nestable/jquery.nestable.js')}}"></script>
    <link href="{{asset('assets/js/plugins/nestable/menu_drug.css')}}" rel="stylesheet">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Nestable custom theme list</h5>
                        <button class="btn btn-info btn-xs pull-right" id="save_menu_order">UPDATE MENU ORDER <i
                                class="fa fa-save"></i></button>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group has-feedback">
                            <label for="moduleFor" class="control-label">Module For</label>
                            {!! __combo($data['combo_slug'],$data['combo_array']) !!}
                        </div>
                        @foreach($data['modules'] as $modules)
                            <div class="dd" id="mod{{$modules->id}}">
                                {!! $data['menu_list'][$modules->id] !!}
                            </div>
                        @endforeach

                        <div class="m-t-md">
                            <h5>Serialised Output</h5>
                        </div>
                        <textarea id="menu_sorting" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Menu Information
                            <small>Create Menu</small>
                        </h5>
                        <div class="ibox-tools">

                            <a href="<?=URL::to('menu_list')?>">
                                <button class="btn btn-info btn-xs">New Menu <i class="fa fa-plus"></i></button>
                            </a>

                        </div>
                    </div>
                    <div class="ibox-content">
                        {{ Form::open(['id'=>'menu_entry']) }}
                        <div class="form-group has-feedback">
                            <label for="name" class="control-label">Name</label>
                            <input type="hidden" name="pkid" value="" id="pkid">
                            <input maxlength="100" data-error="Please Enter Menu Name" type="text" name="name"
                                   class="form-control name" id="name" placeholder="Enter Menu Name" required>
                            <div class="help-block with-errors has-feedback"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="menuLink" class="control-label">Route Name</label>
                            <input maxlength="100" data-error="Please Enter Menu Link" type="text" name="menu_url"
                                   class="form-control menu_url" id="menu_link" placeholder="Enter Route Name" required>
                            <div class="help-block with-errors has-feedback"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="iconClass" class="control-label">Icon Class</label>
                            <input name="icon_class" maxlength="100" data-error="Please Enter Icon Class"
                                   data-toggle="modal" data-target="#myModal" type="text"
                                   class="form-control icon_class"
                                   id="icon_class" placeholder="Enter Icon Class" required>
                            <div class="help-block with-errors has-feedback"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="moduleFor" class="control-label">Module For</label>
                            {!! __combo($data['combo_slug'],$data['combo_array']) !!}
                        </div>
                        <div class="form-group has-feedback">
                            <label for="iconClass" class="control-label">Menu Description</label>
                            <textarea data-error="Please Enter Menu Description"
                                      data-placeholder="Enter Menu Description" name="menus_description"
                                      class="form-control menus_description" required></textarea>
                            <div class="help-block with-errors has-feedback"></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 800px;">
                <div class="modal-body">
                    @include('menus.iconClass')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#menu_entry').validator();
            $("#module_sort").on("change", function () {
                var mod_id = $(this).val();
                moduleSortedMenu(mod_id);
            });
            $(document).ready(function () {
                var mod_id = $(this).val();
                moduleSortedMenu(mod_id);
            });

            function moduleSortedMenu(mod_id) {
                if (mod_id) {
                    //$("#mod1").hide();
                    $(".dd").hide();
                    $("#mod" + mod_id).show();
                } else {
                    $(".dd").hide();
                    $("#mod1").show();
                }
            }

            /********/
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target), output = list.data('output');
                $('#menu_sorting').text(window.JSON.stringify(list.nestable('serialize')));
            };

            $('.dd').nestable({
                group: 1
            }).on('change', updateOutput);


            $("#save_menu_order").on('click', function () {
                $.ajax({
                    url: '<?php echo URL::to('saveMenuOrder'); ?>',
                    type: 'post',
                    data: {data: $('#menu_sorting').text()},
                    success: function (data) {
                        if (data === 'saved') {
                            location.reload();
                        }
                    },
                    error: function () {
                        alert('error');
                    }
                });
            });

            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            //icon class js
            $('.menu-icon').attr('style', 'cursor: pointer;color:#357ebd;');
            $(document).on("click", ".menu-icon", function () {
                var icon = $.trim($(this).text());
                $('input[name="icon_class"]').val(icon);
                $('.display_icon').html('<i class="fa ' + icon + '"></i>');
                $('#myModal').modal('hide');
            });

            //form submit
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $("form").submit(function (e) {
                e.preventDefault();
                var url = '<?php echo URL::to('menu_entry');?>'
                var formData = $('form').serialize() + "&id=" + $("#pkid").val();
                var data = formData;
                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType: "json",
                    url: url,
                    data: data,
                    success: function (data) {
                        console.log(data.status);
                        if (data.status == 'success') {
                            window.location.href = '<?php echo URL::to('menu_list');?>';
                        } else {

                        }
                    }
                });
            });

            //get data for edit
            $(".menuUpdate").on('click', function (e) {
                e.preventDefault();
                console.log($(this).attr('href'));
                var id = $(this).attr('href');
                $("#submit").removeClass("btn btn-primary").addClass("btn btn-warning").text("Update");
                $("#pkid").val(id)
                var url = '<?php echo URL::to('getMenuRaw');?>';
                var data = {'id': id};
                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType: "json",
                    url: url,
                    data: data,
                    success: function (data) {
                        var obj = jQuery.parseJSON(JSON.stringify(data));
                        console.log(obj);
                        $.each(obj, function (key, value) {
                            console.log(key);
                            var className = '.' + key;
                            $(className).val(value);
                        });
                    }
                });
            });

            //delete menu
            $(".menuDelete").on('click', function (e) {
                e.preventDefault();
                var id = $(this).attr('href');
                $("#pkid").val(id)
                var url = '<?php echo URL::to('menuDelete');?>';
                var data = {'id': id};
                swalConfirm("Your will not be able to recover this imaginary file!").then(function (s) {
                    if (s.value){
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            dataType: "json",
                            url: url,
                            data: data,
                            success: function (data) {
                                swalRedirect('', "Your imaginary file has been deleted.",  "success");
                            }
                        });
                    }
                });


                /*swal({
                        title: "Are you sure?",
                        text: "Your will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                       // confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel plx!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                function (isConfirm) {
                    if (isConfirm) {
                        swal("Deleted!", "Your imaginary file has been deleted.", "success");
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            dataType: "json",
                            url: url,
                            data: data,
                            success: function (data) {
                                location.reload();
                            }
                        });
                    } else {
                        swal("Cancelled", "Your imaginary file is safe :)", "error");
                        location.reload();
                    }
                });*/
            })
        });
    </script>
@endsection
