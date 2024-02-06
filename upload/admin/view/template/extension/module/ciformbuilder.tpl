<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if($setting) { ?>
        <a href="<?php echo $setting; ?>" data-toggle="tooltip" title="<?php echo $button_setting; ?>" class="btn btn-primary"><i class="fa fa-cogs"></i></a>
        <?php } ?>

        <a href="<?php echo $page_form_href; ?>" data-toggle="tooltip" title="<?php echo $button_page_form; ?>" class="btn btn-primary"><i class="fa fa-file"></i> <?php echo $button_page_form; ?></a>

        <a href="<?php echo $page_request_href; ?>" data-toggle="tooltip" title="<?php echo $button_page_request; ?>" class="btn btn-primary"><i class="fa fa-list"></i></a>

        <button type="submit" form="form-ciformbuilder" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-refresh"></i></button>

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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ciformbuilder" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <?php if($pageforms) { ?>
          <div class="form-group select-form">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_form; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <?php foreach($pageforms as $pageform) { ?>
                <label class="col-sm-4">
                  <?php if($pageform['page_form_id'] == $page_form_id || !$page_form_id) { ?>
                  <?php $page_form_id = $pageform['page_form_id']; ?>
                  <div class="pageform active"><?php echo $pageform['title']; ?></div>
                  <input type="radio" name="page_form_id" value="<?php echo $pageform['page_form_id']; ?>" checked="checked">
                  <?php } else { ?>
                  <div class="pageform"><?php echo $pageform['title']; ?></div>
                  <input type="radio" name="page_form_id" value="<?php echo $pageform['page_form_id']; ?>">
                  <?php } ?>
                </label>
                <?php } ?>
              </div>
            </div>
          </div>
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
<style type="text/css">    
.select-form input {
  opacity: 0;
}
.pageform {
  background: #585858;
  color: #fff;
  font-size: 14px;
  padding: 50px 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  cursor: pointer;
  text-align: center;
  min-height: 140px;
}
.pageform.active {
  background : #279fe0;
}
</style>
<script type="text/javascript">
$('.pageform').click(function() {
  $('.pageform').removeClass('active');
  $(this).addClass('active');
});
</script>
</div>
<?php echo $footer; ?>