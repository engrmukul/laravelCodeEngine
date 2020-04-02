@include('dropdown_grid.dropdown_grid')
<div class="ibox-title">
    <h2 class="">
        <i class="fa fa-edit"></i> {!! ucfirst(str_replace('_', ' ', $mainform['page_info'][0]->master_entry_title)) !!}
    </h2>
    <div class="ibox-tools">
        <a class="close-link text-danger">
            <i class="fa fa-times"></i>
        </a>
    </div>
</div>
<div class="ibox-content">
    @if($mainform['mode'] == 'edit')
        @php($mainform['edit_data'] = $mainform_data[0])
        @php($mainform['edit_data_key'] = $mainform_data_id)
    @endif
    @include('Master.generateMasterFormHtml', $mainform)
</div>
<script>
    $(document).ready(function(){
        $('.multi').multiselect({
            buttonWidth: '100%',
            enableFiltering: true,
            filterPlaceholder: 'Search',
            enableCaseInsensitiveFiltering : true,
            includeSelectAllOption: true,
            nonSelectedText: 'Choose an option!',
            templates: {
                filter: '<div class="form-group"><div class="input-group">' +
                '<input class="form-control multiselect-search" type="text">' +
                '</div></div>',
            },
            // dropRight: true,
            maxHeight: 300
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
            console.log(form_id);
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

    $('.autocomplete-master-entry').each(function() {
        var $el = $(this);
        var slug = $el.data('slug');
        var value = $el.data('value');
        var name = $el.data('name');
        var source = $el.data('autocompleteurl');
        var hint_html = '';
        var shade_style = 'color: #CCC; width: 100%; position: absolute; background: transparent; z-index: 1;  box-shadow: none;border: none; top:1px; left:1px';
        hint_html += '<input class="form-control autocomplete-shade" id="shade-'+slug+'" disabled="disabled" style="'+shade_style+'"/>';
        hint_html += '<input type="hidden" name="'+name+'[]" class="autocomplete-value" value="'+value+'" id="value-'+slug+'" value=""/>';
        $el.after(hint_html);
        $el.autocomplete({
            selectFirst: true,
            autoFocus: true,
            serviceUrl: source,
            onSelect: function(suggestion) {
                var slug = $el.data('slug');
                $('#value-'+slug).val(suggestion.data);
                $('#'+slug+'-add').data('selected_value',suggestion.data);
            },
            onHint: function (hint) {
                var slug = $el.data('slug');
                $('#shade-'+slug).val(hint);
            },
            onInvalidateSelection: function() {
                console.log('invalids');
                $(this).val('');
            }
        });
    });
</script>