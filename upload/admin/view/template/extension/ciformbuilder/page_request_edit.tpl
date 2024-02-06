<?php echo $header; ?>
<style type="text/css">
<?php echo $css; ?>
</style>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" class="button-buildersubmit btn btn-success btn-lg button"><i class="fa fa-paper-plane"></i> <?php echo $button_continue; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default btn-lg"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?> </a>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <fieldset id="pageform" class="pageform">
            <span class="core-formid hide"><?php echo $page_form_id; ?></span>

            <legend><?php echo $page_form_title; ?></legend>

            <?php if($fieldset_title) { ?>
            <h4><?php echo $fieldset_title; ?></h4>
            <?php } ?>
            <div class="row">
              <?php echo $include_fields_file; ?>
            </div>
            <div class="buttons ci-buttons">
              <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary btn-lg"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?> </a>
              <button type="button" class="button-buildersubmit btn btn-success btn-lg button pull-right"><i class="fa fa-paper-plane"></i> <?php echo $button_continue; ?></button>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript"><!--
$('.button-buildersubmit').on('click', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/page_request/add&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&page_form_id=<?php echo $page_form_id; ?>&page_request_id=<?php echo $page_request_id; ?>',
    type: 'post',
    data: $('#pageform input[type=\'text\'], #pageform input[type=\'hidden\'], #pageform input[type=\'password\'], #pageform input[type=\'radio\']:checked, #pageform input[type=\'checkbox\']:checked, #pageform select, #pageform textarea').serialize(),

    dataType: 'json',
    beforeSend: function() {
      $('.button-buildersubmit').button('loading');
      $('.alerts, .text-danger').remove();
      $('.form-group').removeClass('has-error');
    },
    complete: function() {
      $('.button-buildersubmit').button('reset');
    },
    success: function(json) {
      $('.alerts, .text-danger').remove();
      $('.form-group').removeClass('has-error');

      if (json['error']) {

        if (json['error']['field']) {
          for (i in json['error']['field']) {
            var element = $('#pageform #input-field' + i.replace('_', '-'));
            if (element.parent().hasClass('input-group')) {
              element.parent().after('<div class="text-danger">' + json['error']['field'][i] + '</div>');
            } else {
              element.after('<div class="text-danger">' + json['error']['field'][i] + '</div>');
            }
          }
        }

        if (json['error']['warning']) {
          $('.form-horizontal').prepend('<div class="alerts alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          $('html, body').animate({ scrollTop: 0 }, 'slow');
        }

        // Highlight any found errors
        $('.text-danger').parent().addClass('has-error');
      }

      if (json['success']) {
        location = json['success'];
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});
//--></script>
<script type="text/javascript"><!--
$('#pageform .country_id').on('change', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/page_request/country&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&country_id=' + this.value,
    dataType: 'json',
    beforeSend: function() {
      $('#pageform .country_id').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function() {
      $('#pageform .fa-spin').remove();
    },
    success: function(json) {

      html = '<option value=""><?php echo $text_select; ?></option>';

      if (json['zone'] && json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
          html += '<option value="' + json['zone'][i]['zone_id'] + '"';
          html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
      }

      $('#pageform .zone_id').html(html);
    }
  });
});

// $('#pageform .country_id').trigger('change');
//--></script>
<script type="text/javascript"><!--
$(document).ready(function () {
  var page_form_id = $('.core-formid').text();

  /* Add File */
  Dropzone.autoDiscover = false;
  $('#pageform .dropzone').each(function() {
    var node = $(this);
    var node_this = this;
    var thisid = '#'+ node.attr('id');
    var fieldid = $(thisid).attr('data-fieldid');
    var file_limit = $(thisid).attr('data-limit');

    var myDropzone = new Dropzone(thisid, {
      url: 'index.php?route=extension/ciformbuilder/page_request/upload&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&page_form_id=<?php echo $page_form_id; ?>&page_request_id=<?php echo $page_request_id; ?>',
      parallelUploads: 1,
      /*uploadMultiple: true,*/
      maxFilesize: 200,
      /*acceptedFiles: 'image/*',*/
      addRemoveLinks: true,
      maxFiles: file_limit,
      /*autoDiscover: false,*/
      init: function() {
        /*this.on("addedfile", function(event) {
            while (this.files.length > this.options.maxFiles) {
                this.removedfile(this.files[0]);
            }
        });*/

        this.on("maxfilesexceeded", function(file) {
          // alert("No more files please!");
            // myDropzone.removeAllFiles();
            // myDropzone.addFile(file);
        });
      },
      success: function (file, json) {
        if(json['code']) {
          file.previewElement.classList.add("dz-success");
          file['attachment_id'] = json['code']; // push the id for future reference
          $('#uploaded-media'+ fieldid).append( $('<input type="hidden" name="field['+ fieldid +'][]" id="media-ids[]" class="media-ids" value="' + json['code'] +'">') );
        } else {
          file.previewElement.classList.add("dz-error");
        }
      },
      error: function (file, json) {
          alert(json);

          file.previewElement.classList.add("dz-error");
      },
      // update the following section is for removing image from library
      removedfile: function(file) {
          var attachment_id = file.attachment_id;
          /* jQuery.ajax({
              type: 'POST',
              url: dropParam.delete,
              data: {
                  media_id : attachment_id
              }
          }); */
          $('input.media-ids[type=hidden]').each(function() {
              if ($(this).val() == attachment_id) {
                  $(this).remove();
              }
          });
          var _ref;
          return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
      }
      /* success: function(file, json) {
        $(node_this).parent().find('.text-message').remove();

        if (json['warning']) {
          $(node_this).after('<div class="text-message alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '</div>');
        }

        if (json['success']) {
          $(node_this).after('<div class="text-message alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

          $(node_this).find('.dz-preview').append('<i class="fa fa fa-trash-o delete-file" onclick="deleteZoneFile(this);"></i>');

          $(node_this).parent().find('input').val(json['code']);

          $(node_this).parent().find('.file-vname').remove();
        }
      } */
    });
  });
});

/* Datetime Picker */
$('.date').datetimepicker({
  pickTime: false
});

$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});

$('.time').datetimepicker({
  pickDate: false
});

/* Color Picker */
var element = null;
$('.color-picker').ColorPicker({
  curr : '',
  onShow: function (colpkr) {
    $(colpkr).fadeIn(500);
    return false;
  },
  onHide: function (colpkr) {
    $(colpkr).fadeOut(500);
  return false;
  },
  onSubmit: function(hsb, hex, rgb, el) {
    $(el).val('#'+hex);
    $(el).ColorPickerHide();
  },
  onBeforeShow: function () {
    $(this).ColorPickerSetColor(this.value);
  },
  onChange: function (hsb, hex, rgb) {
    element.curr.parent().next().find('.preview').css('background', '#' + hex);
    element.curr.val('#'+hex);
  }
}).bind('keyup', function(){
  $(this).ColorPickerSetColor(this.value);
}).click(function(){
  element = this;
  element.curr = $(this);
});

$.each($('.color-picker'),function(key,value) {
  $(this).parent().next().find('.preview').css({'background': $(this).val()});
});


/* Remove File */
function deleteZoneFile(mynode) {
  $(mynode).parent().parent().removeClass('dz-started');

  $(mynode).parent().parent().parent().find('.text-message').remove();

  $(mynode).parent().parent().parent().find('input').val('');

  $(mynode).parent().remove();
}
//--></script>
</div>
<?php echo $footer; ?>