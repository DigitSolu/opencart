<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $href_download_pdf; ?>" target="_blank" class="btn btn-primary button-download-pdf"><?php echo $button_download_pdf; ?></a>

        <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $button_edit; ?></a>

        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_back; ?></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-file"></i> <?php echo $text_page_detail; ?></h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td style="width: 1%"><button data-toggle="tooltip" title="<?php echo $text_page_form_title; ?>" class="btn btn-primary"><i class="fa fa-file fa-fw"></i></button></td>
                <td><a href="<?php echo $page_form_href; ?>" target="_blank"><?php echo $page_form_title; ?></a></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_store; ?>" class="btn btn-primary"><i class="fa fa-sitemap fa-fw"></i></button></td>
                <td><a href="<?php echo $store_url; ?>" target="_blank"><?php echo $store_name; ?></a></td>
              </tr>
              <?php if($language_name) { ?>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_language_name; ?>" class="btn btn-primary"><i class="fa fa-language fa-fw"></i></button></td>
                <td><?php echo $language_name; ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_date_added; ?>" class="btn btn-primary"><i class="fa fa-calendar fa-fw"></i></button></td>
                <td><?php echo $date_added; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $text_customer_detail; ?></h3>
          </div>
          <table class="table">
            <tr>
              <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_customer; ?>" class="btn btn-primary"><i class="fa fa-user fa-fw"></i></button></td>
              <td><?php if ($customer) { ?>
                <a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> <?php echo $lastname; ?></a>
                <?php } else { ?>
                <?php echo $firstname; ?> <?php echo $lastname; ?>
                <?php } ?>
              </td>
            </tr>
            <?php if($customer_group) { ?>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_customer_group; ?>" class="btn btn-primary"><i class="fa fa-group fa-fw"></i></button></td>
              <td><?php echo $customer_group; ?></td>
            </tr>
            <?php } ?>
            <?php if($ip) { ?>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_ip; ?>" class="btn btn-primary"><i class="fa fa-desktop fa-fw"></i></button></td>
              <td><?php echo $ip; ?></td>
            </tr>
            <?php } ?>
            <?php if($user_agent) { ?>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_user_agent; ?>" class="btn btn-primary"><i class="fa fa-chrome fa-fw"></i></button></td>
              <td><?php echo $user_agent; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
    </div>
    <?php if($product_id) { ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-tag"></i> <?php echo $text_product_detail; ?></h3>
      </div>
      <table class="table table-bordered">
        <tr>
          <td><?php echo $text_product_id; ?>: <?php echo $product_id; ?></td>
          <td style="width:50%;"><?php echo $text_product_name; ?>: <a href="<?php echo $product_link; ?>"><?php echo $product_name; ?></a></td>
         <td><?php echo $text_product_model; ?>: <?php echo $product_model; ?></td>
        </tr>
      </table>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_fields; ?>
        </h3>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-responsive">
          <thead>
            <tr>
              <td style="width: 20%;" class="text-left"><?php echo $text_field_name; ?></td>
              <td style="width: 80%;" class="text-left"><?php echo $text_field_value; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach($page_request_options as $page_request_option) { ?>
            <tr>
              <td class="text-left"><label><?php echo $page_request_option['name']; ?></label></td>
              <td class="text-left">
                <?php if ($page_request_option['type'] != 'file') { ?>
                <?php echo $page_request_option['value']; ?>
                <?php } else { ?>
                  <?php foreach($page_request_option['value'] as $value_file) { ?>
                    <a href="<?php echo $value_file['href']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" title="<?php echo $value_file['filename']; ?>"><i class="fa fa-download"></i> <?php echo $value_file['filename']; ?></a>

                    <?php if($value_file['view_image_button']) { ?>
                    <button type="button" class="btn btn-info btn-sm button-view-image" data-image="<?php echo $value_file['view_image_src']; ?>" data-image-name="<?php echo $page_request_option['name']; ?>: <?php echo $value_file['filename']; ?>" ><i class="fa fa-eye"></i> <?php echo $button_view_image; ?></button>
                    <?php } ?>
                    <br/><br/>
                  <?php } ?>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

        <?php if($has_document) { ?>
        <div class="text-left">
          <button type="button" class="btn btn-primary btn-sm download-all"><i class="fa fa-download"></i> <?php echo $button_download_all; ?></button>
        </div>
        <br/>
        <?php } ?>
      </div>
    </div>

    <?php if($config_submission_status) { ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-comment-o"></i> <?php echo $text_history; ?></h3>
      </div>
      <div class="panel-body">
        <div id="history"></div>
        <br/>
        <fieldset>
          <legend><?php echo $text_history_add; ?></legend>
          <form class="form-horizontal addhistory">
            <div class="form-group">
              <label class="control-label col-sm-3"><?php echo $text_notify; ?></label>
              <div class="col-sm-3">
                <div class="radio">
                  <input type="checkbox" name="notify" value="1" checked />
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label"><?php echo $entry_form_status; ?></label>
              <div class="col-sm-9">
                <select name="form_status_id" class="form-control">
                  <?php foreach($form_statuses as $form_status) { ?>
                  <option value="<?php echo $form_status['form_status_id']; ?>" <?php echo $form_status['form_status_id'] == $form_status_id ? 'selected="selected"' : ''; ?>><?php echo $form_status['name']; ?></option>
                <?php } ?>
                </select>
              </div>
            </div>
          </form>
        </fieldset>
        <div class="text-right">
          <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
        </div>
      </div>
    </div>
    <?php } ?>

    <div class="modal" id="viewimage-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <h5 class="image-name"></h5>
            <div class="image"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
// Downlaod in Once Zip File
$('.download-all').on('click', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/page_request/downloadall&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&page_request_id=<?php echo $page_request_id; ?>',
    dataType: 'json',
    beforeSend: function() {
      $('.download-all').button('loading');
    },
    complete: function() {
    },
    success: function(json) {
      if(json['download_link']) {
          window.location = json['download_link'];
      }

      setTimeout(function(){
        $('.download-all').button('reset');
      }, 1000);
    }
  });
});

// View Image
$('.button-view-image').on('click', function() {
  $('#viewimage-modal .image, #viewimage-modal .image-name').html('');

  var image = $(this).attr('data-image');
  var image_name = $(this).attr('data-image-name');
  if(image) {
    $('#viewimage-modal .image-name').html(image_name);
    $('#viewimage-modal .image').html('<img src="'+ image +'" />');
    $('#viewimage-modal').modal('show');
  }
});

$('#history').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#history').load(this.href);
  });

  $('#history').load('index.php?route=extension/ciformbuilder/page_request/history&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&page_request_id=<?php echo $page_request_id; ?>');

  $('#button-history').on('click', function() {
    $.ajax({
      url: 'index.php?route=extension/ciformbuilder/page_request/addPageRequestHistory&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&id=<?php echo $page_request_id; ?>',
      type: 'post',
      dataType: 'json',
      data: $('.addhistory select[name="form_status_id"], .addhistory input[name="notify"]:checked'),
      beforeSend: function() {
        $('#button-history').button('loading');
      },
      complete: function() {
        $('#button-history').button('reset');
      },
      success: function(json) {
        $('.alert-dismissible').remove();

        if (json['error']) {
          $('#history').before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }

        if (json['success']) {
          $('#history').load('index.php?route=extension/ciformbuilder/page_request/history&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&page_request_id=<?php echo $page_request_id; ?>');

          $('#history').before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
//--></script>
</div>
<?php echo $footer; ?> 
