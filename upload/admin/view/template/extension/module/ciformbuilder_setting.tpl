<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">

        <a href="<?php echo $page_form_href; ?>" data-toggle="tooltip" title="<?php echo $button_page_form; ?>" class="btn btn-primary"><i class="fa fa-file"></i> <?php echo $button_page_form; ?></a>

        <a href="<?php echo $page_request_href; ?>" data-toggle="tooltip" title="<?php echo $button_page_request; ?>" class="btn btn-primary"><i class="fa fa-list"></i></a>

        <button type="submit" form="form-module" data-toggle="tooltip" title="<?php echo $button_save_stay; ?>" class="btn btn-success"><i class="fa fa-check-circle"></i></button>

        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <?php if($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <?php if($action_enable_events) { ?>
    <div class="alert alert-warning inspect-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $info_disabled_events; ?> <button type="button" class="btn btn-primary btn-sm button-enable-event" onclick="enableEvents();"><i class="fa fa-cog"></i> <?php echo $button_enable_event; ?></button></div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-status" data-toggle="tab"><i class="fa fa-refresh"></i> <?php echo $tab_status; ?></a></li>
            <li><a href="#tab-customer" data-toggle="tab"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $tab_customer; ?></a></li>
            <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-life-ring" aria-hidden="true"></i> <?php echo $tab_support; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="module_ciformbuilder_setting_status" class="form-control">
                    <?php if($module_ciformbuilder_setting_status) { ?>
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
            <div class="tab-pane" id="tab-status">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_submission_status; ?></label>
                <div class="col-sm-10">
                  <select name="module_ciformbuilder_setting_submission_status" class="form-control">
                    <?php if($module_ciformbuilder_setting_submission_status) { ?>
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
            <div class="tab-pane" id="tab-customer">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_customer_record; ?></label>
                <div class="col-sm-10">
                  <select name="module_ciformbuilder_setting_customer_record" class="form-control">
                    <?php if($module_ciformbuilder_setting_customer_record) { ?>
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
            <div class="tab-pane text-center" id="tab-support">
              <div class="support-wrap">
                <div class="text-right profile-buttons">
                  <a href="https://www.opencart.com/index.php?route=marketplace/extension&sort=rating&filter_member=CodingInspect" target="_blank" class="btn btn-primary"><i class="fa fa-opencart"></i> More Extensions</a>
                </div>

                <div class="ci-support-icon">
                  <i class="fa fa-life-ring" aria-hidden="true"></i>
                </div>
                <div class="ciinfo">
                  <h4>For any type of support Please contact us at</h4>
                  <h3>codinginspect@gmail.com</h3>
                </div>
              </div>
              <br>
              <br>
              <div class="rating-wrap">
                <div class="text-right rating-buttons">
                  <a href="https://www.opencart.com/index.php?route=account/rating" target="_blank" class="btn btn-primary" data-toggle="tooltip" title=" Rate on Opencart Extension"><i class="fa fa-opencart"></i></a>
                  <a href="https://www.youtube.com/watch?v=5naoyOMyk5w" target="_blank" class="btn btn-danger" data-toggle="tooltip" title=" See Video How to rate an extension"><i class="fa fa-play"></i></a>
                </div>
                <div class="">
                  <h4>Please rate our extension for Opencart Marketplace</h4>
                  <div class="rating">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                  </div>
                  <h3>Opencart.com >> Account >> Rate Your Downloads</h3>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript">
$('.pageform').click(function() {
  $('.pageform').removeClass('active');
  $(this).addClass('active');
});
</script>

<?php if($action_enable_events) { ?>
<script type="text/javascript">
function enableEvents() {
  $.ajax({
    url: '<?php echo $action_enable_events; ?>',
    dataType: 'json',
    beforeSend: function() {
      $('.button-enable-event').attr('disabled', true);
    },
    complete: function() {
      $('.button-enable-event').attr('disabled', false);
    },
    success: function(json) {
      $('.inspect-warning, .inspect-danger, .inspect-success').remove();

      if(json['warning']) {
        $('.container-fluid > .panel').before('<div class="alert alert-danger inspect-alert"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if(json['success']) {
        location = json['success'];
      }
    }
  });
}
</script>
<?php } ?>

<style type="text/css">
.support-wrap { background: #eee none repeat scroll 0 0; border-bottom: 2px solid #ffc00d; border-radius: 10px; position: relative; padding: 35px 35px; width: 100%; }

.support-wrap .ciinfo{ display: inline-block; }

.support-wrap .profile-buttons { position: absolute; right: 20px; top: 20px; }

.support-wrap .ci-support-icon{ font-size: 50px; display: inline-block; padding-right: 20px; }



.rating-wrap{ background: #eee none repeat scroll 0 0; border-bottom: 2px solid #ffc00d; border-radius: 10px; position: relative; padding: 35px 15px 15px; width: 100%; }

.rating-wrap .rating-buttons { position: absolute; right: 20px; top: 20px; }
.rating-wrap .rating-buttons i { font-size: 20px; }

.rating-wrap .rating { margin: 20px; }

.rating-wrap i.fa-star{ font-size: 20px; display: inline-block; padding-right: 5px; color: #ffc00d; }
</style>

</div>
<?php echo $footer; ?>