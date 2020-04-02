<?php

function generateAssetTypeFormFields($fam_items_id, $products_id, $is_multiple = false, $multiple_no = 1, $cols = 2)
{
  if ($is_multiple == 'true') {
    // Not trackable
    $rows = getProductSpecStandards($products_id, $is_multiple);
    generateAssetSpecification($fam_items_id, $products_id, $rows, 'ntr');
    
    echo '<div class="section-divider"></div>';
    
    // Then trackable
    $rows = getProductSpecStandards($products_id, $is_multiple, 1);
    generateAssetSpecification($fam_items_id, $products_id, $rows, 'tra', $multiple_no);
  } else {
    // Both trackable and not trackable
    $rows = getProductSpecStandards($products_id, $is_multiple);
    generateAssetSpecification($fam_items_id, $products_id, $rows, 'tnt');
  }
}

function getProductSpecStandards($products_id, $is_multiple = false, $is_trackable = 0)
{
  $rows = DB::table('product_spec_standards')
          ->where('products_id', '=', $products_id)
          ->where('status', '=', 'Active');
  if ($is_multiple == 'true') {
    $rows->where('is_trackable', '=', $is_trackable);
  }
          $rows->orderBy('product_spec_standards_id', 'asc');
  $rows = $rows->get();
  return $rows;
}

function generateAssetSpecification($fam_items_id, $products_id, $rows, $index, $multiple_no = 1, $cols = 2)
{
  if ($rows->count() > 0) {
    $col = (12 / $cols);
    ?>
<div class="panel-group">
<?php
    for($n = 1; $n <= $multiple_no; $n++) {
      $indx = "$index-$n";
    ?>
<div class="panel panel-default">
  <?php
  if ($index == 'tra') {
    echo '<div class="panel-heading"><strong>Asset-' . $n . '</strong></div><br>';
  }
  ?>
  <div class="panel-body">
    <div class="row">
      <?php
      $i = 0;
      foreach ($rows as $row) {
        $analysis_value = getAnalysisValue($fam_items_id, $products_id, $row->product_spec_standards_name);
        if ($i == $cols) {
          $i = 0;
          ?>
        </div>
        <div class="row">
          <?php
        }
        ?>
        <div class="col-md-<?php echo $col; ?>">
          <?php
          if (!empty($row->input_type)) {
            ?>
            <label class="font-normal"><strong><?php echo $row->product_spec_standards_name; ?></strong></label>
            <div class="form-group">
              <input type="hidden" name="fam_items_id_arr[<?php echo $indx; ?>][]" value="<?php echo $fam_items_id; ?>">
              <input type="hidden" name="products_id[<?php echo $indx; ?>][]" value="<?php echo $products_id; ?>">
              <input type="hidden" name="standards_name[<?php echo $indx; ?>][]" value="<?php echo $row->product_spec_standards_name; ?>">
              <input type="hidden" name="spec_unit[<?php echo $indx; ?>][]" value="<?php echo $row->spec_unit; ?>">
              <input type="hidden" name="default_spec[<?php echo $indx; ?>][]" value="<?php echo $row->default_spec; ?>">
              <input type="hidden" name="description[<?php echo $indx; ?>][]" value="<?php echo $row->description; ?>">
              <input type="hidden" name="table_name[<?php echo $indx; ?>][]" value="<?php echo $row->dropdown_table; ?>">
              <?php
              switch ($row->input_type) {
                case "textarea":
                  ?>
                  <textarea name="analysis_value[<?php echo $indx; ?>][]" class="form-control"><?php echo @$analysis_value; ?></textarea>
                  <?php
                  break;
                case "date":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="analysis_value[<?php echo $indx; ?>][]" class="form-control" value="<?php echo @$analysis_value; ?>" autocomplete="off">
                  </div>
                  <?php
                  break;
                case "datetime":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="analysis_value[<?php echo $indx; ?>][]" class="form-control" value="<?php echo @$analysis_value; ?>" autocomplete="off">
                  </div>
                  <?php
                  break;
                case "select":
                  $attr_arr = array(
                    'selected_value' => @$analysis_value,
                    'attributes' => array(
                      'class' => 'form-control'
                    ),
                    'name' => 'analysis_value['.$indx.'][]'
                  );
                  echo __combo($row->dropdown_slug, $attr_arr);
                  break;
                default:
                  ?>
                  <input type="<?php echo $row->input_type; ?>" name="analysis_value[<?php echo $indx; ?>][]" class="form-control" value="<?php echo @$analysis_value; ?>">
                <?php
              }
              ?>
            </div>
          <?php } ?>
        </div>
        <?php
        $i++;
      }
      ?>
        </div>
    </div>
  </div>
    <?php
    }
    ?>
</div>
  <?php
  }
}

function getAnalysisValue($fam_items_id, $products_id, $product_spec_standards_name)
{
  $analysis_value = '';
  if (!empty($fam_items_id)) {
    $analysis_value = DB::table('fam_item_spec_details')
                  ->join('fam_items', 'fam_item_spec_details.fam_items_id', '=', 'fam_items.fam_items_id')
                  ->where('fam_item_spec_details.fam_items_id', '=', $fam_items_id)
                  ->where('fam_items.products_id', '=', $products_id)
                  ->where('fam_item_spec_details.standards_name', '=', $product_spec_standards_name)
                  ->value('fam_item_spec_details.analysis_value');
  }
  return $analysis_value;
}

function generateAssetTypeFormFields_20190509($items_id, $products_id, $cols = 3)
{
  $rows = DB::table('product_spec_standards')
          ->where('products_id', '=', $products_id)
          ->where('status', '=', 'Active')
          ->orderBy('product_spec_standards_id', 'asc')
          ->get();
  if ($rows->count() > 0) {
    $col = (12 / $cols);
    ?>
    <div class="row">
      <?php
      $i = 0;
      foreach ($rows as $row) {
        $analysis_value = DB::table('fam_item_spec_details')
                ->join('fam_items', 'fam_item_spec_details.fam_items_id', '=', 'fam_items.fam_items_id')
                ->where(['products_id' => $products_id, 'standards_name' => $row->product_spec_standards_name])
                ->value('analysis_value');
        if ($i == $cols) {
          $i = 0;
          ?>
        </div>
        <div class="row">
          <?php
        }
        ?>
        <div class="col-md-<?php echo $col; ?>">
          <?php
          if (!empty($row->input_type)) {
            ?>
            <label class="font-normal"><strong><?php echo $row->product_spec_standards_name; ?></strong></label>
            <div class="form-group">
              <input type="hidden" name="items_id[]" value="<?php echo $items_id; ?>">
              <input type="hidden" name="products_id[]" value="<?php echo $products_id; ?>">
              <input type="hidden" name="standards_name[]" value="<?php echo $row->product_spec_standards_name; ?>">
              <input type="hidden" name="spec_unit[]" value="<?php echo $row->spec_unit; ?>">
              <input type="hidden" name="default_spec[]" value="<?php echo $row->default_spec; ?>">
              <input type="hidden" name="description[]" value="<?php echo $row->description; ?>">
              <?php
              switch ($row->input_type) {
                case "textarea":
                  ?>
                  <textarea name="analysis_value[]" class="form-control"><?php echo @$analysis_value; ?></textarea>
                  <?php
                  break;
                case "date":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="analysis_value[]" class="form-control" value="<?php echo @$analysis_value; ?>" autocomplete="off">
                  </div>
                  <?php
                  break;
                case "datetime":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="analysis_value[]" class="form-control" value="<?php echo @$analysis_value; ?>" autocomplete="off">
                  </div>
                  <?php
                  break;
                default:
                  ?>
                  <input type="<?php echo $row->input_type; ?>" name="analysis_value[]" class="form-control" value="<?php echo @$analysis_value; ?>">
                <?php
              }
              ?>
            </div>
          <?php } ?>
        </div>
        <?php
        $i++;
      }
      ?>
    </div>

    <div class="row">
      <div class="col-md-12">
        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check"></i>&nbsp;Submit</button>
      </div>
    </div>
    <?php
  } else {
    echo 'No fields found!';
  }
}

function approveMaintenanceRequisition($fam_maint_requests_id)
{
  DB::table('fam_maint_requests')
          ->where('fam_maint_requests_id', $fam_maint_requests_id)
          ->update(['maint_req_statuses_id' => 76]);
}

function maintenanceDelegationProcess($fam_maint_requests_id, $delegation_type, $comments = '', $additional_data = '')
{
  $fam_requests_no = DB::table('fam_maint_requests')
          ->where('fam_maint_requests_id', '=', $fam_maint_requests_id)
          ->value('fam_requests_no');
  $post['slug'] = 'req_code'; // sys_unique_id_logic table slug
  $post['code'] = array($fam_requests_no);
  $post['delegation_type'] = $delegation_type;
  $post['comments'] = $comments;
  $post['additional_data'] = $additional_data;
  $result = goToDelegationProcess($post);
  //debug($result,1);
}
