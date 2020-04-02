<div class="modal-header">
    <button type="button" class="close text-danger" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">
        {!! ucfirst(str_replace('_', ' ', $data['mainform']['page_info'][0]->master_entry_title)) !!}
    </h4>
</div>
<div class="modal-body">
    <div class="col-lg-12">
        @include('master.generateMasterFormHtml', $data['mainform'])
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger" id="closeformmodal">Close</button>
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
