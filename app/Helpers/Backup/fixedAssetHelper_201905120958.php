<?php

function generateAssetTypeFormFields($products_id, $is_multiple = false, $multiple_no = 1, $cols = 2)
{
  $rows = DB::table('product_spec_standards')
          ->where('products_id', '=', $products_id)
          ->where('status', '=', 'Active');
  if ($is_multiple == 'true') {
    $rows->where('is_trackable', '=', 1);
  }
          $rows->orderBy('product_spec_standards_id', 'asc');
  $rows = $rows->get();
  if ($rows->count() > 0) {
    $col = (12 / $cols);
    ?>
<div class="panel-group">
<?php
    for($n = 1; $n <= $multiple_no; $n++) {
    ?>
<div class="panel panel-default">
  <div class="panel-body">
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
              <input type="hidden" name="products_id[<?php echo $n; ?>][]" value="<?php echo $products_id; ?>">
              <input type="hidden" name="standards_name[<?php echo $n; ?>][]" value="<?php echo $row->product_spec_standards_name; ?>">
              <input type="hidden" name="spec_unit[<?php echo $n; ?>][]" value="<?php echo $row->spec_unit; ?>">
              <input type="hidden" name="default_spec[<?php echo $n; ?>][]" value="<?php echo $row->default_spec; ?>">
              <input type="hidden" name="description[<?php echo $n; ?>][]" value="<?php echo $row->description; ?>">
              <?php
              switch ($row->input_type) {
                case "textarea":
                  ?>
                  <textarea name="analysis_value[<?php echo $n; ?>][]" class="form-control"><?php echo @$analysis_value; ?></textarea>
                  <?php
                  break;
                case "date":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="analysis_value[<?php echo $n; ?>][]" class="form-control" value="<?php echo @$analysis_value; ?>" autocomplete="off">
                  </div>
                  <?php
                  break;
                case "datetime":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="analysis_value[<?php echo $n; ?>][]" class="form-control" value="<?php echo @$analysis_value; ?>" autocomplete="off">
                  </div>
                  <?php
                  break;
                default:
                  ?>
                  <input type="<?php echo $row->input_type; ?>" name="analysis_value[<?php echo $n; ?>][]" class="form-control" value="<?php echo @$analysis_value; ?>">
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
  } else {
    echo 'No fields found!';
  }
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
