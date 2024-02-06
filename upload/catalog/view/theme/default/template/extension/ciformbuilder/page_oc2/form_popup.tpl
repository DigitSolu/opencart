<div id="FormModal" class="modal fade" role="dialog">
  <div class="modal-dialog <?php echo $popup_size; ?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $heading_title; ?></h4>
      </div>
      <div class="modal-body">
        <?php echo trim($description); ?>
        <div class="form-horizontal">
          <fieldset id="mainpageform" class="pageform">
            <?php if($fieldset_title) { ?>
            <legend><?php echo $fieldset_title; ?></legend>
            <?php } ?>

            <div class="hide" style="display: none">
              <span class="text_processing"><?php echo $text_processing; ?></span>
              <span class="text_select"><?php echo $text_select; ?></span>
              <span class="text_none"><?php echo $text_none; ?></span>
            </div>

            <div class="row">
              <?php echo $include_fields_file; ?>
            </div>

            <?php if($termcondition_text) { ?>
            <div class="termcondition_text"><?php echo $termcondition_text; ?>
              <input type="checkbox" name="termcondition_agree" value="1" class="pageform_terms" />
            </div>
            <?php } ?>

            <div class="cicaptcha">
              <?php echo $captcha; ?>
            </div>

            <div class="buttons">
              <div class="pull-right">
                <?php if($reset_button) { ?>
                <button type="button" class="btn btn-default button" id="button-popup-formreset"><i class="fa fa-refresh"></i> <?php echo $button_reset; ?></button>
                <?php } ?>
                <button type="button" class="btn btn-primary button" id="button-submit-formpopup" onclick="FORMBUILDER.add('<?php echo $page_form_id; ?>');<?php if($google_analytic) { ?><?php echo $google_analytic; ?><?php } ?>" <?php echo $product_id ? 'data-product-id='. $product_id : ''; ?>><?php echo $button_continue; ?></button>
              </div>
            </div>
          </fieldset>
        </div>
        <?php echo trim($bottom_description); ?>
      </div>
    </div>
  </div>
<script type="text/javascript">
$('#FormModal .date').datetimepicker({
  pickTime: false
});

$('#FormModal .datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});

$('#FormModal .time').datetimepicker({
  pickDate: false
});

$('#FormModal').on('hidden.bs.modal', function() {
  $(this).remove();
});


$(document).ready(function () {
  var page_form_id = $('.core-formid').text();

  /* Add File */
  Dropzone.autoDiscover = false;
  $('#FormModal .dropzone').each(function() {
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
          $('#FormModal #uploaded-media'+ fieldid).append( $('<input type="hidden" name="field['+ fieldid +'][]" id="media-ids[]" class="media-ids" value="' + json['code'] +'">') );
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
</script>
<style type="text/css">
<?php echo $css; ?>
</style>
</div>