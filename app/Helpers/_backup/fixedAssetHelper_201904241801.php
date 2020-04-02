<?php

function generateAssetTypeFormFields($products_id, $cols = 3)
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
              <?php
              switch ($row->input_type) {
                case "textarea":
                  ?>
                  <textarea name="description" class="form-control"></textarea>
                  <?php
                  break;
                case "date":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="purchase_date" class="form-control" autocomplete="off">
                  </div>
                  <?php
                  break;
                case "datetime":
                  ?>
                  <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="purchase_date" class="form-control" autocomplete="off">
                  </div>
                  <?php
                  break;
                default:
                  ?>
                  <input type="<?php echo $row->input_type; ?>" name="fam_items_name" class="form-control">
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
