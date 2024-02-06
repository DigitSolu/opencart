<style type="text/css">
.form-horizontal .control-label{ text-align: left; margin-bottom: 3px; }
.form-horizontal .text-help{ margin-bottom: 3px; font-size: 11px; }
<?php echo $css; ?>
</style>
<div class="information-form">
  <?php echo $description; ?>
  <div class="form-horizontal">
    <fieldset id="pageform" class="pageform">
      <span class="core-formid hide"><?php echo $page_form_id; ?></span>
      <?php if($fieldset_title) { ?>
      <legend><?php echo $fieldset_title; ?></legend>
      <?php } ?>
      <div class="row">
        <?php echo $include_fields_file; ?>
      </div>
      <div class="cicaptcha">
        <?php echo $captcha; ?>
      </div>

      <?php if($termcondition_text) { ?>
      <div class="termcondition_text"><?php echo $termcondition_text; ?>
        <input type="checkbox" name="termcondition_agree" value="1" class="pageform_terms" />
      </div>
      <?php } ?>

      <div class="buttons ci-buttons">
        <?php if($reset_button) { ?>
        <button type="button" class="btn btn-default btn-lg button" id="button-formreset"><i class="fa fa-refresh"></i> <?php echo $button_reset; ?></button>
        <?php } ?>
        <button type="button" class="btn btn-success btn-lg button" id="button-buildersubmit" <?php if($google_analytic) { ?>onclick="<?php echo $google_analytic; ?>"<?php } ?>><i class="fa fa-paper-plane"></i> <?php echo $button_continue; ?></button>
      </div>
    </fieldset>
  </div>
  <?php echo $bottom_description; ?>
<script type="text/javascript"><!--
$('#button-buildersubmit').on('click', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/form/add&page_form_id=<?php echo $page_form_id; ?>',
    type: 'post',
    data: $('#pageform input[type=\'text\'], #pageform input[type=\'hidden\'], #pageform input[type=\'password\'], #pageform input[type=\'radio\']:checked, #pageform input[type=\'checkbox\']:checked, #pageform select, #pageform textarea'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-buildersubmit').button('loading');
    },
    complete: function() {
      $('#button-buildersubmit').button('reset');
    },
    success: function(json) {
      $('.alert, .text-danger').remove();
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

        if(json['captcha']) {
          $('.cicaptcha').html(json['captcha']);
        }

        if (json['error']['warning']) {
          $('.information-form .form-horizontal').prepend('<div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          $('html, body').animate({ scrollTop: $('.information-form .form-horizontal').offset().top - 8 }, 'slow');
        }

        // Highlight any found errors
        $('.text-danger').parent().addClass('has-error');
      }

      if (json['success']) {
      	$('#buildersuccess-modal').remove();

        $('body').append('<div id="buildersuccess-modal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'+ json['success_title'] +'</h4></div><div class="modal-body">'+ json['success_description'] +'</div></div></div></div>');

        $('#buildersuccess-modal').modal('show');

        $('#pageform input[type=\'text\'], #pageform input[type=\'hidden\'], #pageform input[type=\'password\'],  #pageform textarea').val('');

        $('#pageform input[type=\'checkbox\']:checked, #pageform input[type=\'radio\']:checked').prop('checked', false);

        $('#pageform select').val('');
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
    url: 'index.php?route=extension/ciformbuilder/form/country&country_id=' + this.value,
    dataType: 'json',
    beforeSend: function() {
      $('#pageform .country_id').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function() {
      $('#pageform .fa-spin').remove();
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

      $('#pageform .zone_id').html(html);
    }
  });
});

$('#pageform .country_id').trigger('change');
//--></script>
</div>