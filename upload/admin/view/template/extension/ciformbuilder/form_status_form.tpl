<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-form-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><label class="label label-default"><i class="fa fa-arrow-circle-right"></i></label> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-form-status" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="form_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($form_status[$language['language_id']]) ? $form_status[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
		      <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_shortcode; ?></label>
            <div class="col-sm-10">
              <input type="text" name="shortcode" value="<?php echo isset($shortcode) ? $shortcode : ''; ?>" placeholder="<?php echo $entry_shortcode; ?>" class="form-control" />
              <?php if ($error_shortcode) { ?>
              <div class="text-danger"><?php echo $error_shortcode; ?></div>
              <?php } ?>
              </div>
            </div>
			     <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-footer-bgcolor"><?php echo $entry_bgcolor; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="bgcolor" value="<?php echo $bgcolor; ?>" class="form-control color-picker" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-footer-textcolor"><?php echo $entry_textcolor; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="textcolor" value="<?php echo $textcolor; ?>" class="form-control color-picker" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
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
</script>
<?php echo $footer; ?>