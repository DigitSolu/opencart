<?php echo $header; ?>
<style type="text/css">
<?php echo $css; ?>
</style>
<div id="container" class="container">
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
      <h1 class="secondary-title"><?php echo $heading_title; ?></h1>
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
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>

<script type="text/javascript"><!--
$('#button-buildersubmit').on('click', function() {
  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/form/add&page_form_id=<?php echo $page_form_id; ?>',
    type: 'post',
    data: $('#pageform input[type=\'text\'], #pageform input[type=\'hidden\'], #pageform input[type=\'password\'], #pageform input[type=\'radio\']:checked, #pageform input[type=\'checkbox\']:checked, #pageform select, #pageform textarea').serialize(),

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
          $('.breadcrumb').after('<div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

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
<?php echo $footer; ?>