<?php

function fileUploadHtml($multiple = false, $reference = '', $reference_id = '')
{
  $mode = 'add';
  ?>
<div class="table-responsive">
  <table class="table table-bordered table-condensed" id="attach_table">
    <thead>
      <tr>
        <th class="text-center">SL</th>
        <th class="text-center">Attachment Tittle</th>
        <th class="text-center">File Name</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (!empty($reference) && !empty($reference_id)) {
        $mode = 'edit';
        $rows = getAttachments($reference, $reference_id);
        if ($rows->count() > 0) {
          $sl = 0;
          foreach ($rows as $row) {
            ?>
        <tr>
          <td class="text-center attach_sl"><?php echo ++$sl; ?></td>
          <td class="text-left"><?php echo $row->document_name; ?></td>
          <td class="text-center"><a title="View" class="btn btn-info btn-xs" href="<?php echo url($row->document_path); ?>"><i class="fa fa-eye"></i></a></td>
          <td class="text-center"><button type="button" class="btn btn-danger btn-xs attach_remove_ajax" attachments_id="<?php echo $row->attachments_id; ?>"><i class="fa fa-remove"></i></button></td>
        </tr>
        <?php
          }
        }
      }
      ?>
    </tbody>
  </table>
</div>

<?php
if ($multiple) {
  ?>
<button type="button" class="btn btn-primary" id="attach_add_more">Add More</button>
<?php
}
?>

<script type="text/javascript">
  jQuery(document).ready(function($){
    var tr = '<tr>\n\
      <td class="text-center attach_sl">1</td>\n\
      <td class="text-center"><input type="text" name="attach_title[]" class="form-control"></td>\n\
      <td class="text-center"><input type="file" name="attach_file[]" class="form-control-file"></td>\n\
      <td class="text-center"><button type="button" class="btn btn-danger btn-xs attach_remove"><i class="fa fa-remove"></i></button></td>\n\
    </tr>';
    <?php
    if ($mode == 'add') {
      ?>
    $("#attach_table tbody").append(tr);
    <?php
    }
    ?>
    
    $("#attach_add_more").click(function(){
      $("#attach_table tbody").append(tr);
      resetAttachSl();
    });
    
    $(".attach_remove_ajax").click(function(){
      var $this = $(this);
      var attachmentsId = $this.attr('attachments_id');
      //alert(attachmentsId);
      swalConfirm('Are you sure?').then(function(r){
        if(r.value){
          $.ajax({
            type: "POST",
            async: false,
            dataType: 'JSON',
            url: "<?php echo url('delete-attachments-ajax'); ?>",
            data: {"_token": "<?php echo csrf_token(); ?>", attachments_id: attachmentsId},
            success: function (data) {
              //console.log(data);
              if(data.result){
                $this.closest('tr').remove();
                resetAttachSl();
              }
            }
          });
        }
      });
    });
    
  });
  
  $(document).on("click", ".attach_remove", function(){
    $(this).closest('tr').remove();
    resetAttachSl();
  });
  
  function resetAttachSl(){
    $(".attach_sl").each(function(idx, elem) {
      $(elem).text(idx+1);
    });
  }
</script>
<?php
}

function fileUploadSave($request, $reference, $reference_id, $upload_dir = '')
{
  $table_name = 'attachments';
  if (empty($upload_dir)) {
    $upload_dir = "uploads/fams";
  }
  
  $upload_path = base_path($upload_dir);
  makeDirectory($upload_path);
  
  if($request->hasfile('attach_file')) {
    $data['reference'] = $reference;
    $data['reference_id'] = $reference_id;
    foreach($request->file('attach_file') as $key => $file) {
      $file_name = $reference . rand() . '.' . $file->getClientOriginalExtension();
      $file->move($upload_path, $file_name);
      $file_path = "$upload_dir/$file_name";
      $data['document_name'] = $request->attach_title[$key];
      $data['document_path'] = $file_path;
      $data['created_by'] = Auth::id();
      $data['created_at'] = mySqlDateTime();
      DB::table($table_name)->insert($data);
      $attachments_id = DB::getPdo()->lastInsertId();
    }
  }
}

function fileUploadView($reference, $reference_id)
{
  ?>
<div class="table-responsive">
  <table class="table table-bordered table-condensed" id="attach_table_view">
    <thead>
      <tr>
        <th class="text-center">SL</th>
        <th class="text-center">Attachment Tittle</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $rows = getAttachments($reference, $reference_id);
      if ($rows->count() > 0) {
        $sl = 0;
        foreach ($rows as $row) {
          ?>
      <tr>
        <td class="text-center"><?php echo ++$sl; ?></td>
        <td class="text-left"><?php echo $row->document_name; ?></td>
        <td class="text-center">
          <a download title="Download" class="btn btn-primary btn-xs" href="<?php echo url($row->document_path); ?>"><i class="fa fa-download"></i></a>
          <a title="View" class="btn btn-info btn-xs" href="<?php echo url($row->document_path); ?>"><i class="fa fa-eye"></i></a>
        </td>
      </tr>
      <?php
        }
      } else {
        ?>
      <tr>
        <td colspan="3">No attachments found!</td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>
<?php
}
function MenualApprovalProcessView($fam_maint_requests_id = '')
{
  ?>
<div class="table-responsive">
  <table class="table table-bordered table-condensed" id="attach_table_view">
    <thead>
      <tr>
        <th class="text-center">SL</th>
        <th class="text-center">Employee</th> 
      </tr>
    </thead>
    <tbody>
      <?php
     // echo 'hi';exit();
      $rows =  getMenualAppovalProcess($fam_maint_requests_id);
     // print_r($rows);exit();
      // echo 'hi';exit();
      if ($rows->count() > 0) {
        $sl = 0;
        foreach ($rows as $row) {
          ?>
      <tr>
        <td class="text-center"><?php echo ++$sl; ?></td>
        <td class="text-left"><?php echo $row->username; ?></td>
        
      </tr>
      <?php
        }
      } else {
        ?>
      <tr>
        <td colspan="3">No attachments found!</td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>
<?php
}

function  MenualApprovalProcess($multiple = false, $fam_maint_requests_id = ''){
  $mode = 'add';
    
  ?>
  <div class="table-responsive">
    <table class="table table-bordered  table-condensed" id="aproval_table">
      <thead>
        <tr>
          <th class="text-center">SL</th>
          <th class="text-center">Employee</th>         
          <th class="text-center">Action</th>
        </tr>
      </thead>
    <tbody>
      <?php
     // echo $fam_maint_requests_id;
      if (!empty($fam_maint_requests_id)) {
        //echo 'hieeee';
        $mode = 'edit';
        $rows = getMenualAppovalProcess($fam_maint_requests_id);
        //dd($rows);
      //  echo "<pre>" ;print_r($rows);exit();
        if ($rows->count() > 0) {
          $sl = 0;
          
          foreach ($rows as $row) {
            
        $sys_users_arr = array(
        'selected_value' => $row->id ?? old('id'),
        'attributes' => array(
        'class' => 'form-control',
        'id' => 'id'
        ),
        'name' => 'emp[]'
        );
         ?>
        <tr>
          <td class="text-center approval_sl"><?php echo ++$sl; ?></td>
          <td class="text-left"><?php echo   __combo("sys_users", $sys_users_arr) ?></td>
          <td class="text-center"><button type="button" class="btn btn-danger btn-xs approval_remove" ><i class="fa fa-remove"></i></button></td>
        </tr>
        <?php
          }
        }
      }
      ?>
    </tbody>
    </table>
  </div>
  <?php
if ($multiple) {
  ?>
&nbsp;<button type="button" class="btn btn-primary" id="approval_add_more">Add More</button>
<?php
} ?>
 <?php
    $sys_users_arr = array(
    'selected_value' => '',
    'attributes' => array(
    'class' => 'form-control',
    'id' => 'id'
    ),
    'name' => 'emp[]'
    );
  ?>
          
<script type="text/javascript">
  jQuery(document).ready(function($){
    var tr = '<tr>'+
      "<td class='text-center approval_sl'>1</td>"+
      '<td class="text-center"> <?= __combo("sys_users", $sys_users_arr) ?> </td>'+
      '<td class="text-center"><button type="button" class="btn btn-danger  btn-xs approval_remove"><i class="fa fa-remove"></i></button></td>'+
    '</tr>';
 
    <?php
    if ($mode == 'add') {
      ?>
      $("#aproval_table tbody").append(tr);
    <?php
    }
    ?>
     resetApprovalSl();
    $("#approval_add_more").click(function(){
      $("#aproval_table tbody").append(tr);
      resetApprovalSl();
    });  
  });  
  $(document).on("click", ".approval_remove", function(){
    $(this).closest('tr').remove();
    resetAttachSl();
  });
  function resetApprovalSl(){
    $(".approval_sl").each(function(idx, elem) {
      $(elem).text(idx+1);
    });
  }
</script>
<?php }
?>
<?php
function getAttachments($reference, $reference_id)
{
  $rows = DB::table('attachments')
          ->where('reference', $reference)
          ->where('reference_id', $reference_id)
          ->get();
  return $rows;
}
function getMenualAppovalProcess($fam_maint_requests_id = '')
{
      $rows = DB::table('fam_maint_requests') 
          ->select('delegation_manual_user')
          ->where('fam_maint_requests_id', $fam_maint_requests_id)
          ->first();
      $approvalperson_ids =  json_decode($rows->delegation_manual_user); 
      //$abc['abc'] = $approvalperson_ids;
      $new_daligation_array = array();
      $i = 0;
      foreach($approvalperson_ids as $approvalperson_ids  ) 
      {
          $new_daligation_array[$i]=$approvalperson_ids;
          $i++;
      }
    $approvalperson_names = DB::table('sys_users')
                          ->select('username',
                                  'id')
                          ->whereIn('id', $new_daligation_array)
                          ->get();
   $appovalperson_names['appovalperson_names'] = $approvalperson_names;
   return $approvalperson_names ;
}



