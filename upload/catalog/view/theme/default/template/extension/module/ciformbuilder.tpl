<style type="text/css">
.layout-form .form-horizontal .control-label{ text-align: left; margin-bottom: 3px; }
.layout-form .form-horizontal .text-help{ margin-bottom: 3px; font-size: 11px; }
<?php echo $css; ?>
</style>
<div class="layout-form" id="layoutform<?php echo $module_row; ?>">
  <?php echo $description; ?>
  <div class="form-horizontal">
    <fieldset id="pageform<?php echo $module_row; ?>" class="pageform">
      <div class="row">
        <?php echo $include_fields_file; ?>
      </div>
      <div class="layout-cicaptcha">
        <?php echo $captcha; ?>
      </div>

      <?php if ($termcondition_text) { ?>
      <div class="termcondition_text"><?php echo $termcondition_text; ?>
        <input type="checkbox" name="termcondition_agree" value="1" class="pageform_terms" />
      </div>
     <?php } ?>

      <div class="buttons">
        <div class="pull-left">
          <?php if($reset_button) { ?>
          <button type="button" class="btn btn-default button" id="button-layout-formreset<?php echo $module_row; ?>"><i class="fa fa-refresh"></i> <?php echo $button_reset; ?></button>
          <?php } ?>
          <button type="button" class="btn btn-primary button" id="button-buildersubmit<?php echo $module_row; ?>"><?php echo $button_continue; ?></button>
        </div>
      </div>
    </fieldset>
  </div>
  <?php echo $bottom_description; ?>

<script type="text/javascript"><!--
$('#button-buildersubmit<?php echo $module_row; ?>').on('click', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/form/add&page_form_id=<?php echo $page_form_id; ?>',
    type: 'post',
    data: $('#layoutform<?php echo $module_row; ?> input[type=\'text\'], #layoutform<?php echo $module_row; ?> input[type=\'hidden\'], #layoutform<?php echo $module_row; ?> input[type=\'password\'], #layoutform<?php echo $module_row; ?> input[type=\'radio\']:checked, #layoutform<?php echo $module_row; ?> input[type=\'checkbox\']:checked, #layoutform<?php echo $module_row; ?> select, #layoutform<?php echo $module_row; ?> textarea'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-buildersubmit<?php echo $module_row; ?>').button('loading');
    },
    complete: function() {
      $('#button-buildersubmit<?php echo $module_row; ?>').button('reset');
    },
    success: function(json) {
      $('#layoutform<?php echo $module_row; ?> .alert, #layoutform<?php echo $module_row; ?> .text-danger').remove();
      $('#layoutform<?php echo $module_row; ?> .form-group').removeClass('has-error');

      if (json['error']) {
        if (json['error']['field']) {
          for (i in json['error']['field']) {
            var element = $('#layoutform<?php echo $module_row; ?> #input-field' + i.replace('_', '-'));
            if (element.parent().hasClass('input-group')) {
              element.parent().after('<div class="text-danger">' + json['error']['field'][i] + '</div>');
            } else {
              element.after('<div class="text-danger">' + json['error']['field'][i] + '</div>');
            }
          }
        }

        if(json['captcha']) {
          $('#layoutform<?php echo $module_row; ?> .layout-cicaptcha').html(json['captcha']);
        }

        if (json['error']['warning']) {
          $('#layoutform<?php echo $module_row; ?> .form-horizontal').prepend('<div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          $('html, body').animate({ scrollTop: $('#layoutform<?php echo $module_row; ?> .form-horizontal').offset().top - 8 }, 'slow');
        }

        // Highlight any found errors
        $('.text-danger').parent().addClass('has-error');
      }

      if (json['success']) {
        $('#buildersuccess-modal<?php echo $module_row; ?>').remove();

        $('body').append('<div id="buildersuccess-modal<?php echo $module_row; ?>" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'+ json['success_title'] +'</h4></div><div class="modal-body">'+ json['success_description'] +'</div></div></div></div>');

        $('#buildersuccess-modal<?php echo $module_row; ?>').modal('show');

        $('#layoutform<?php echo $module_row; ?> input[type=\'text\'], #layoutform<?php echo $module_row; ?> input[type=\'hidden\'], #layoutform<?php echo $module_row; ?> input[type=\'password\'],  #layoutform<?php echo $module_row; ?> textarea').val('');

        $('#layoutform<?php echo $module_row; ?> input[type=\'checkbox\']:checked, #layoutform<?php echo $module_row; ?> input[type=\'radio\']:checked').prop('checked', false);

        $('#layoutform<?php echo $module_row; ?> select').val('');

        $("#layoutform<?php echo $module_row; ?> .dropzone").each(function() {
          var id = $(this).attr('id');
          var myDropzone = Dropzone.forElement('#'+ id);
          myDropzone.removeAllFiles(true);
        });
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});
//--></script>
<script type="text/javascript"><!--
$('#layoutform<?php echo $module_row; ?> .country_id').on('change', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/form/country&country_id=' + this.value,
    dataType: 'json',
    beforeSend: function() {
      $('#layoutform<?php echo $module_row; ?> .country_id').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function() {
      $('#layoutform<?php echo $module_row; ?> .fa-spin').remove();
    },
    success: function(json) {
      var zone_id = $('#pageform .zone_id').attr('rel');

      html = '<option value=""><?php echo $text_select; ?></option>';

      if (json['zone'] && json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
          html += '<option value="' + json['zone'][i]['zone_id'] + '"';

          if(json['zone'][i]['zone_id'] == zone_id) {
            html += 'selected="selected"';
          }

          html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
      }

      $('#layoutform<?php echo $module_row; ?> .zone_id').html(html);
    }
  });
});

$('#layoutform<?php echo $module_row; ?> .country_id').trigger('change');
//--></script>
<script type="text/javascript"><!--
$(document).ready(function () {
  /* Add File */
  Dropzone.autoDiscover = false;
  $('#layoutform<?php echo $module_row; ?> .dropzone').each(function() {
    var node = $(this);
    var node_this = this;
    var thisid = '#'+ node.attr('id');
    var fieldid = $(thisid).attr('data-fieldid');
    var file_limit = $(thisid).attr('data-limit');

    var myDropzone = new Dropzone(thisid, {
      url: 'index.php?route=extension/ciformbuilder/form/upload&page_form_id=<?php echo $page_form_id; ?>',
      autoProcessQueue: true,
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
          $('#layoutform<?php echo $module_row; ?> #uploaded-media'+ fieldid).append( $('<input type="hidden" name="field['+ fieldid +'][]" id="media-ids[]" class="media-ids" value="' + json['code'] +'">') );
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

          $(node_this).find('.dz-preview:last-child').append('<i class="fa fa fa-trash-o delete-file" onclick="deleteZoneFile(this);"></i>');

          // $(node_this).parent().find('input').val(json['code']);
          $(node_this).after('<input type="hidden" name="field['+ fieldid +'][]" value="'+ json['code'] +'" mid="this"/>');
        }
      }*/
    });
  });
});
//--></script>
<script type="text/javascript"><!--
$('#button-layout-formreset<?php echo $module_row; ?>').on('click', function() {
  $('#layoutform<?php echo $module_row; ?> input[type=\'text\'], #layoutform<?php echo $module_row; ?> input[type=\'password\'], #layoutform<?php echo $module_row; ?> select, #layoutform<?php echo $module_row; ?> textarea').val('');

  $('#layoutform<?php echo $module_row; ?> input[type=\'radio\']:checked, #layoutform<?php echo $module_row; ?> input[type=\'checkbox\']:checked').prop('checked', false);

  $("#layoutform<?php echo $module_row; ?> .dropzone").each(function() {
    var id = $(this).attr('id');
    var myDropzone = Dropzone.forElement('#'+ id);
    myDropzone.removeAllFiles(true);
  });

});
//--></script>
</div>