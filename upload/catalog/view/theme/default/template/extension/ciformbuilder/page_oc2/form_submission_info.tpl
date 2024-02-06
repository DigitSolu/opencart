<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <table class="table table-bordered">
        <thead>
            <tr>
              <th colspan="2"><?php echo $text_page_detail; ?></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $text_page_form_title; ?>: <a href="<?php echo $page_form_href; ?>" target="_blank"><?php echo $page_form_title; ?></a></td>
            <td><?php echo $text_date_added; ?>: <?php echo $date_added; ?></td>
          </tr>
         </tbody>
      </table>

      <?php if($product_id) { ?>
      <h3><?php echo $text_product_detail; ?></h3>
      <table class="table table-bordered">
        <tr>
            <td style="width:50%;"><?php echo $text_product_name; ?>: <a href="<?php echo $product_link; ?>"><?php echo $product_name; ?></a></td>
           <td><?php echo $text_product_model; ?>: <?php echo $product_model; ?></td>
          </tr>
      </table>
      <?php } ?>

      <h3><?php echo $text_fields; ?></h3>
      <table class="table table-bordered table-responsive">
        <thead>
          <tr>
            <th style="width: 20%;" class="text-left"><?php echo $text_field_name; ?></th>
            <th style="width: 80%;" class="text-left"><?php echo $text_field_value; ?></th>
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
      <?php } ?>

      <?php if($config_submission_status) { ?>
      <div id="history"></div>
      <?php } ?>
      
    </div>
  </div>


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
<script type="text/javascript"><!--
// Downlaod in Once Zip File
$('.download-all').on('click', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/form_submission/downloadall&page_request_id=<?php echo $page_request_id; ?>',
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
    $('#viewimage-modal .image').html('<img src="'+ image +'" class="img-responsive" />');
    $('#viewimage-modal').modal('show');
  }
});

<?php if($config_submission_status) { ?>
$('#history').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

  $('#history').load(this.href);
});

$('#history').load('index.php?route=extension/ciformbuilder/form_submission/history&page_request_id=<?php echo $page_request_id; ?>');
<?php } ?>
//--></script>
</div>
<?php echo $footer; ?> 
