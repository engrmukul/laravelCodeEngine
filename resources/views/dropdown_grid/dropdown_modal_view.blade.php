<div class="wrapper animated fadeInRight" id="customSearchPanel">
    <div class="row">
        <div class="col-lg-12 no-padding">
            <div class="ibox">
                <div class="ibox-title">
                    <h2 class="">
                        <i class="fa fa-list"></i> {{$page_data['page_title']}}
                    </h2>
                    <div class="ibox-tools">
                        @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                        <a class="collapse-link">
                            <span class="fa fa-search"></span> Filter Options
                        </a>
                        &nbsp;
                        &nbsp;
                        @endif
                        <a class="" id="closegrid">
                            <i class="fa fa-times text-danger"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content no-display">
                    @if(isset($page_data['custom_search']) && !empty($page_data['custom_search']))
                        <form class="" id="customSearchPanel">
                            <div class="row">
                                {!! __getCustomSearch($page_data['custom_search'], $searched_value = [],true) !!}
                                <div class="col-md-3 search_box_submit no-display" style="display: block;">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive showSearchData">
                    <table class="table table-sm table-striped table-bordered table-hover" id="table-to-print"
                           data-slug="{{$page_data['slug']}}" data-selected_items="" data-multiple="{{@$page_data['multiple']}}" data-addbuttonid="{{$page_data['addbuttonid']}}"
                           data-dependent_data="{{$page_data['dependent_data']}}">
                        <thead>
                        @if(!empty($page_data['header']))
                            @foreach ($page_data['header'] as $rownum => $header_row)
                                <tr>
                                    @foreach ($header_row as $header_data)
                                        <th colspan="{{isset($header_data['colspan']) ? $header_data['colspan'] : 0}}">
                                            {{strtoupper(str_replace('_', ' ', $header_data['column']))}}
                                        </th>
                                        @if($rownum === 'last')
                                            @php
                                                $columnname[] = ['data' => $header_data['column']]
                                            @endphp
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div id="selection-panel" style="overflow: auto;">
                            <hr/>
                            <h3>Selected Items</h3>
                            <input type="hidden" class="form-control" value="{{$page_data['selected_value']}}" name="selected_items" id="selected_items"/>
                            <table class="table table-bordered" id="selected-items-area">
                                @if(!empty($page_data['header']))
                                    @foreach ($page_data['header'] as $rownum => $header_row)
                                        <tr>
                                            @foreach ($header_row as $header_data)
                                                <th colspan="{{isset($header_data['colspan']) ? $header_data['colspan'] : 0}}">
                                                    {{strtoupper(str_replace('_', ' ', $header_data['column']))}}
                                                </th>
                                                @if($rownum === 'last')
                                                    @php
                                                        $columnname[] = ['data' => $header_data['column']]
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                                @if(!empty($page_data['selected_data']))
                                    @foreach ($page_data['selected_data'] as $row)
                                        <tr>
                                            @foreach ($row as $index=>$row_data)
                                                <td>
                                                    @if($index == $page_data['value_field'])
                                                        <label data-selected_id='{{$row_data}}'
                                                               class="label remove_selection label-danger select-{{$row_data}}">
                                                            <i class="fa fa-trash"></i> </label>
                                                        @elseif ($index == $page_data['option_field'])
                                                            <span class='option-field'>{{$row_data}}</span>
                                                        @else
                                                        @if(strpos($index,'_date'))
                                                            {{toDated($row_data)}}
                                                        @elseif(strpos($index,'_price')||strpos($index,'_amount')||strpos($index,'_qty'))
                                                            <span class="number-format">{{datatable_moneyFormat($row_data)}}</span>
                                                        @else
                                                            {{$row_data}}
                                                        @endif
                                                    @endif
                                                </td>

                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                        <button type="button" id="{{$page_data['addbuttonid']}}" class="btn btn-primary pull-right modal_selected_btn">
                            Add
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $('.multi').multiselect({
        buttonWidth: '100%',
        enableFiltering: true,
        filterPlaceholder: 'Search',
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
//            dropRight: true,
        maxHeight: 300
    });
    $('.daterange').daterangepicker({
        locale: {
            format: 'Y-M-DD'
        },
        autoApply: true,
    });
    $('.daterange').val('');
    $('.input-group.date').datepicker({format: "yyyy-mm-dd", autoclose: true});
</script>