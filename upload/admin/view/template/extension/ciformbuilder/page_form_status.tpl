<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-page" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?> </a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><label class="label label-default"><i class="fa fa-arrow-circle-right"></i><i class="fa fa-wrench"></i></label> <?php echo $text_assign_status; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-page" class="form-horizontal">
          <ul class="nav nav-tabs" id="status-email">
            <?php foreach($form_statuses as $skey => $form_status) { ?>
              <li <?php if($skey < 1 ) { ?>class="active"<?php } ?>><a href="#tab-status-<?php echo $form_status['form_status_id']; ?>" data-toggle="tab"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo $form_status['name']; ?> <?php if(!empty($page_form_status_email[$form_status['form_status_id']]['status'])) { ?><span class="enable"></span><?php } else { ?><span class="disable"></span><?php } ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <?php foreach($form_statuses as $skey => $form_status) { ?>
              <div class="tab-pane<?php if($skey < 1 ) { ?> active<?php } ?>" id="tab-status-<?php echo $form_status['form_status_id']; ?>">
                <div class="col-sm-9">
                  <fieldset>
                    <legend><i class="fa fa-cog"></i> <?php echo $form_status['name']; ?> <?php echo $leg_status_setting; ?></legend>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-mail-alert-email"><span data-toggle="tooltip" title="<?php echo $entry_status; ?>"><?php echo $entry_status; ?></span></label>
                      <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons" style="margin-right: 0;">
                          <label class="btn btn-default <?php echo !empty($page_form_status_email[$form_status['form_status_id']]['status']) ? 'active' : ''; ?>">
                          <input name="page_form_status_email[<?php echo $form_status['form_status_id']; ?>][status]" <?php echo !empty($page_form_status_email[$form_status['form_status_id']]['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio" rel="<?php echo $form_status['form_status_id']; ?>"><?php echo $text_yes; ?>
                          </label>
                          <label class="btn btn-default <?php echo empty($page_form_status_email[$form_status['form_status_id']]['status']) ? 'active' : ''; ?>">
                          <input name="page_form_status_email[<?php echo $form_status['form_status_id']; ?>][status]" <?php echo empty($page_form_status_email[$form_status['form_status_id']]['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio" rel="<?php echo $form_status['form_status_id']; ?>"> <?php echo $text_no; ?>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="page_form_status_email[<?php echo $form_status['form_status_id']; ?>][sort_order]" value="<?php echo !empty($page_form_status_email[$form_status['form_status_id']]['sort_order']) ? $page_form_status_email[$form_status['form_status_id']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                      </div>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend><i class="fa fa-envelope"></i> <?php echo $form_status['name']; ?> <?php echo $leg_status_email; ?></legend>
                    <ul class="nav nav-tabs" id="status-email-language<?php echo $form_status['form_status_id']; ?>">
                      <?php foreach ($languages as $key => $language) { ?>
                      <li <?php if($key < 1 ) { ?>class="active"<?php } ?>><a href="#status-email-language<?php echo $language['language_id']; ?>-<?php echo $form_status['form_status_id']; ?>" data-toggle="tab"><?php if(VERSION >= '2.2.0.0') { ?>
                      <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                      <?php } else{ ?>
                      <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                      <?php } ?> <?php echo $language['name']; ?></a></li>
                      <?php } ?>
                    </ul>
                    <div class="tab-content">
                      <?php foreach ($languages as $key => $language) { ?>
                      <div class="tab-pane<?php if($key < 1 ) { ?> active<?php } ?>" id="status-email-language<?php echo $language['language_id']; ?>-<?php echo $form_status['form_status_id']; ?>">
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status-subject<?php echo $language['language_id']; ?>-<?php echo $form_status['form_status_id']; ?>"><?php echo $entry_customer_subject; ?></label>
                        <div class="col-sm-10">
                        <input type="text" name="page_form_status_email[<?php echo $form_status['form_status_id']; ?>][desc][<?php echo $language['language_id']; ?>][subject]" value="<?php echo isset($page_form_status_email[$form_status['form_status_id']]['desc'][$language['language_id']]) ? $page_form_status_email[$form_status['form_status_id']]['desc'][$language['language_id']]['subject'] : ''; ?>" placeholder="<?php echo $entry_customer_subject; ?>" id="input-status-subject<?php echo $language['language_id']; ?>-<?php echo $form_status['form_status_id']; ?>" class="form-control" />
                         <?php if (isset($error_form_status[$form_status['form_status_id']][$language['language_id']]['subject'])) { ?>
                          <div class="text-danger"><?php echo $error_form_status[$form_status['form_status_id']][$language['language_id']]['subject']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status-message<?php echo $language['language_id']; ?>-<?php echo $form_status['form_status_id']; ?>"><?php echo $entry_customer_message; ?></label>
                        <div class="col-sm-10">
                        <textarea name="page_form_status_email[<?php echo $form_status['form_status_id']; ?>][desc][<?php echo $language['language_id']; ?>][message]" placeholder="<?php echo $entry_customer_message; ?>" id="input-status-message<?php echo $language['language_id']; ?>-<?php echo $form_status['form_status_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang=""><?php echo isset($page_form_status_email[$form_status['form_status_id']]['desc'][$language['language_id']]) ? $page_form_status_email[$form_status['form_status_id']]['desc'][$language['language_id']]['message'] : ''; ?></textarea>
                        <?php if (isset($error_form_status[$form_status['form_status_id']][$language['language_id']]['message'])) { ?>
                          <div class="text-danger"><?php echo $error_form_status[$form_status['form_status_id']][$language['language_id']]['message']; ?></div>
                          <?php } ?>
                      </div>
                      </div>
                      </div>
                      <?php } ?>
                    </div>
                  
                    <div class="form-group upload-wise">
                      <label class="col-sm-2 control-label">Upload</label>
                      <div class="col-sm-10">
                        <div class="dropzone dropzone-file-area" id="dropzone-upload<?php echo $form_status['form_status_id']; ?>">
                          <div class="dz-default dz-message">
                          <h4 class="sbold"><i class="fa fa-cloud-upload"></i></h4>
                          <label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
                          </div>
                        </div>
                        <?php if(isset($page_form_status_email[$form_status['form_status_id']]['attachment'])) { ?>
                          <?php foreach($page_form_status_email[$form_status['form_status_id']]['attachment'] as $key => $attachment) { ?>
                            <div class="alert alert-info file-vname text-center" id="file-<?php echo $attachment['code']; ?>">
                              <button type="button" class="btn btn-primary" style="cursor: inherit;">
                              <input type="hidden" name="page_form_status_email[<?php echo $form_status['form_status_id']; ?>][attachment][]" value="<?php echo $attachment['code']; ?>" class="form-control" id="input-field<?php echo $key; ?>">
                              <?php echo $attachment['name']; ?>  <a class="btn btn-danger btn-xs" onclick="$('#file-<?php echo $attachment['code']; ?>').remove();"><i class="fa fa-times"></i></a>
                              </button>
                            </div>
                          <?php } ?>
                        <?php } ?>
                      </div>
                    </div>
                </div>
                <div class="col-sm-3">
                  <br/>
                  <table class="table table-bordered">
                    <thead>
                    <tr><td><?php echo $const_names; ?></td><td><?php echo $const_short_codes; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><?php echo $const_logo; ?></td><td>{LOGO}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_store_name; ?></td><td>{STORE_NAME}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_store_link; ?></td><td>{STORE_LINK}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_name; ?></td><td>{INFORMATION}</td>
                    </tr>
                    <?php foreach($form_statuses as $skey => $form_status) { ?>
                    <tr>
                      <td><?php echo $form_status['name']; ?></td><td><?php echo $form_status['shortcode']; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                 </div>
              </div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
  function openNav() {
    document.getElementById("exmple-table").style.width = "300px";
    document.getElementById("maincontent").style.marginRight = "300px";
  }

  /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
  function closeNav() {
    document.getElementById("exmple-table").style.width = "0";
    document.getElementById("maincontent").style.marginRight = "0";
  }
</script>
<script type="text/javascript">
$('.producttype').click(function() {
  var producttype = $(this).find('input').val();

  if(producttype == 'no') {
    $('.product-group').addClass('hide');
    $('.pbutton_title').addClass('hide');
  } else if(producttype == 'all') {
    $('.product-group').addClass('hide');
    $('.pbutton_title').removeClass('hide');
  } else if(producttype == 'choose') {
    $('.product-group').removeClass('hide');
    $('.pbutton_title').removeClass('hide');
  }
});

</script>
  <script type="text/javascript"><!--
  $('#language a:first').tab('show');
  $('#customer-email-language a:first').tab('show');
  $('#admin-email-language a:first').tab('show');
  $('#success-language a:first').tab('show');

  $('#email a:first').tab('show');
  //--></script>
  <script type="text/javascript"><!--
  $('.admin_email_status').click(function() {
    if($(this).find('input').val() == 1) {
      $('.adminemaillanguage-group').slideDown(300);
    }else{
      $('.adminemaillanguage-group').slideUp(300);
    }
  });
  $('input[name=\'admin_email_status\']:checked').trigger('click');

  $('.customer_email_status').click(function() {
    if($(this).find('input').val() == 1) {
      $('.customeremaillanguage-group').slideDown(300);
    }else{
      $('.customeremaillanguage-group').slideUp(300);
    }
  });
  $('input[name=\'customer_email_status\']:checked').trigger('click');
  //--></script>
<?php if(VERSION <= '2.2.0.0') { ?>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-bottom-description<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-customer-message<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-admin-message<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-success-description<?php echo $language['language_id']; ?>').summernote({ height: 300 });
<?php } ?>
//--></script>
<?php } else if(VERSION <= '2.3.0.2') { ?>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php } else if(VERSION >= '3.0.0.0') { ?>
<link href="view/javascript/codemirror/lib/codemirror.css" rel="stylesheet" />
<link href="view/javascript/codemirror/theme/monokai.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/codemirror/lib/codemirror.js"></script>
<script type="text/javascript" src="view/javascript/codemirror/lib/xml.js"></script>
<script type="text/javascript" src="view/javascript/codemirror/lib/formatting.js"></script>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/summernote-image-attributes.js"></script>
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
  if ($('#column-left').hasClass('active')) {
    $('#button-menu').trigger('click');
  }
});
</script>
<script type="text/javascript">
$(document).ready(function(){
  $('.btn-group .btn').on('click',function(){
    var id = $(this).find('input').attr('rel');
     var value = $(this).find('input').val();
     console.log(id);
    if (value == 1) {
      $('a[href="#tab-status-' + id + '"] span').addClass('enable');
      $('a[href="#tab-status-' + id + '"] span').removeClass('disable');
    }else{
      $('a[href="#tab-status-' + id + '"] span').addClass('disable');
      $('a[href="#tab-status-' + id + '"] span').removeClass('enable');
    }
  });
});
</script>
<script>
 Dropzone.autoDiscover = false;
 var i = 0;
  $('.dropzone').each(function() {
    var node = $(this);
    var node_this = this;
    var thisid = '#'+ node.attr('id');
  var id = node.attr('id').replace('dropzone-upload','');
  var myDropzone = new Dropzone(thisid, {
      url: 'index.php?route=tool/upload/upload&<?php echo $module_token; ?>=<?php echo $ci_token; ?>',
      maxFiles: 10,
      init: function() {
        this.on("maxfilesexceeded", function(file) {
            myDropzone.removeAllFiles();
            myDropzone.addFile(file);
        });
      },
      success: function(file, json) {
        $(node_this).parent().find('.text-message').remove();

        if (json['warning']) {
          $(node_this).after('<div class="text-message alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '</div>');
        }
        if (json['success']) {
          $(node_this).after('<div class="text-message alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

          $(node_this).find('.dz-preview').append('<i class="fa fa fa-trash-o delete-file" onclick="deleteZoneFile(this,'+i+');"></i>');
    
      $(node_this).parent().after('<input type="hidden" name="page_form_status_email['+ id +'][attachment][]" value="'+ json['code'] +'" id="input-field'+ i +'" />');
      i++;
        }
      }
    });
  });
  /* Remove File */
function deleteZoneFile(mynode,j) {
  $(mynode).parent().parent().removeClass('dz-started');

  $(mynode).parent().parent().parent().find('.text-message').remove();

  $(mynode).parent().parent().parent().find('input').val('');
  $('#input-field'+j).remove();

  $(mynode).parent().remove();
}
</script>
  <style>
#status-email li .enable{
  width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #00c853;
    display: block;
    float: right;
    margin-left: 10px;
    margin-top: 6px;
    opacity: 0.4;
}
#status-email li.active .enable{
  opacity: 1;
}
#status-email li .disable{
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #DD0612;
    display: block;
    float: right;
    margin-left: 10px;
    margin-top: 6px;
     opacity: 0.4;
}
#status-email li.active .disable{
  opacity: 1;
}
</style>
</div>
<?php echo $footer; ?>