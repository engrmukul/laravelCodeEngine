@extends('layouts.app')
@section('content')
    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a class="nav-link active show" data-toggle="tab" href="#tab-{{$data['mainform']['page_info'][0]->sys_master_entry_id}}">
                                <h3>{!! ucwords(str_replace('_', ' ', $data['mainform']['page_info'][0]->master_entry_title)) !!}</h3>
                            </a>
                        </li>
                        @if(isset($data['subform']) && !empty($data['subform']))
                            @foreach($data['subform'] as $subformname => $subforms)
                                @if($data['subform'][$subformname]['page_info'][0]->form_view_mode == 'tab')
                                    <li>
                                        <a class="nav-link" data-toggle="tab" href="#tab-{{$data['subform'][$subformname]['page_info'][0]->sys_master_entry_id}}">
                                            <h3>{!! ucwords(str_replace('_', ' ', $data['subform'][$subformname]['page_info'][0]->master_entry_title)) !!}</h3>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-{{$data['mainform']['page_info'][0]->sys_master_entry_id}}" class="tab-pane active show">
                            <div class="panel-body">
                                @include('Master.generateMasterFormHtml', $data['mainform'])
                                @if(isset($data['subform']) && !empty($data['subform']))
                                    @foreach($data['subform'] as $subformname => $subforms)
                                        @if($subforms['page_info'][0]->form_view_mode == 'default')
                                            <div class="hr-line-dashed"></div>
                                            <h3>{!! ucwords(str_replace('_', ' ', $subforms['page_info'][0]->master_entry_title)) !!}</h3>
                                            @if(isset($subforms['form_elements']) && !empty($subforms['form_elements']))
                                                @include('Master.generateMasterFormHtml', $subforms)
                                            @else
                                                <h3 class="text-danger text-center">No Form Available</h3>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if(isset($data['subform']) && !empty($data['subform']))
                            @foreach($data['subform'] as $subformname => $subforms)
                                @if($data['subform'][$subformname]['page_info'][0]->form_view_mode == 'tab')
                                    <div role="tabpanel" id="tab-{{$subforms['page_info'][0]->sys_master_entry_id}}" class="tab-pane">
                                        <div class="panel-body">
                                            @if(isset($subforms['form_elements']) && !empty($subforms['form_elements']))
                                                @include('Master.generateMasterFormHtml', $subforms)
                                            @else
                                                <h3 class="text-danger text-center">No Form Available</h3>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        <div class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.load_grid', function(){
            Ladda.bind(this);
            var load = $(this).ladda();
            var url = $(this).data('gridurl');
            makeAjaxText(url,load).done((response)=>{
                $('#large_modal .modal-content').html(response);
                $('#large_modal').modal('show')
            });
        });

        $(document).on('click', '.add_button', function(){
            var target_form = $(this).data('form-id');
            var html = $('.add_'+target_form).html();
            $(html).appendTo($('#added_'+target_form));
            $('#added_'+target_form+' > .clone_div > .remove_form_div').show();
        });

        $(document).on('click', '.remove_form_div', function(){
            var rem_butt = $(this);
            swalConfirm.then((s)=>{
                if(s.value){
                    rem_butt.closest('.clone_div').remove();
                }
            });
        });


        $('.submit_button').on('click', function(e){
            e.preventDefault();
            var form_id = $(this).data('form-id');
            var submit_type = $(this).data('submit-type');
            var submit_url = $('#'+form_id).attr('action');
            if(submit_type == 'default'){
                $('#'+form_id).submit();
            }else if (submit_type == 'ajax') {
                Ladda.bind(this);
                var load = $(this).ladda();

                var formdata = $('#'+form_id).serialize();
                makeAjaxPost(formdata,submit_url,load).done((response) =>{
                    $('#'+form_id).trigger("reset");
                    swalSuccess("Operation Successfull");
                });
            } else {
                alert('Kivabe submit korbo bolen?');
            }
        });
    </script>
@endsection

