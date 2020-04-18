@php
    $delegation_info = getDelegationHistory($ref_event,$ref_id);
@endphp

@if(!empty($delegation_info))
    <div class="ibox-content border-0">
        <div class="row">
            <div class="form-group col-md-12">
                @if($ref_event == 'req_cs')
                    @php
                        $board_members = boardMembers($ref_id);
                    @endphp
                    <h3>Board Members</h3>
                    @foreach($board_members as $bm)
                        <h5>{{$bm->role}} : <span>{{$bm->username}}({{$bm->designations_name}})</span></h5>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endif
        <div class="ibox-content border-0">
            <div class="row">
                <div class="form-group col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__lang('Approval_Person')}}</th>
                                <th>{{__lang('Action_Type')}}</th>
                                <th>{{__lang('Action_Time')}}</th>
                                <th>{{__lang('Step_Number')}}</th>
                                <th>{{__lang('Comments')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delegation_info as $di)
                                <tr>
                                    <td>{{$di->uname}}</td>
                                    <td>{{$di->act_status}}</td>
                                    <td>{{toDateTimed($di->created_at)}}</td>
                                    <td>{{$di->step_no}}</td>
                                    <td>{{$di->act_comments}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


<script>
    $(document).on('click','.dele-color',function(){
        $(".dele-color").removeClass("btn-primary");
        $(this).addClass("btn-primary");
    });
</script>
