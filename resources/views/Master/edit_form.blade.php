<div class="ibox-title">
    <h2>{!! ucwords(str_replace('_', ' ', $data['mainform']['page_info'][0]->master_entry_title)) !!}</h2>
    <div class="ibox-tools">
        <a class="close-link text-danger">
            <i class="fa fa-times"></i>
        </a>
    </div>
</div>
<div class="ibox-content">
    @php($data['mainform']['mode'] = 'edit')
    @php($data['mainform']['edit_data'] = $data['mainform_data'][0])
    @php($data['mainform']['edit_data_key'] = $data['mainform_data_id'])
    @include('Master.generateMasterFormHtml', $data['mainform'])
</div>
<script>
    $(document).ready(function(){
        $('.multi').multiselect({
            buttonWidth: '100%',
            enableFiltering: true,
            filterPlaceholder: 'Search',
            enableCaseInsensitiveFiltering : true,
            includeSelectAllOption: true,
            templates: {
                filter: '<div class="form-group"><div class="input-group">' +
                '<input class="form-control multiselect-search" type="text">' +
                '</div></div>',
            },
            // dropRight: true,
            maxHeight: 300
        });
    });
    $(document).on('click', '.load_grid', function(){
        Ladda.bind(this);
        var l = $(this).ladda();
        var url = $(this).data('gridurl');
        makeAjaxText(url,l).done((response)=>{
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

    $('.submit_button').on('click', function (e) {
        var form_id = $(this).data('form-id');
        var submit_type = $(this).data('submit-type');
        var submit_url = $('#'+form_id).attr('action');
        Ladda.bind(this);
        var load = $(this).ladda();
        $('#'+form_id).validator().on('submit', function (e) {
            if (!e.isDefaultPrevented()) {
                e.preventDefault();
                var formdata = $('#'+form_id).serialize();
                makeAjaxPost(formdata,submit_url,load).done((response) =>{
                    $('#'+form_id).trigger("reset");
                    swalRedirect('',"Operation Successfull");
                });
            }
        });
    });
</script>


