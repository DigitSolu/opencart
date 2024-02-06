<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $button_add; ?></a>
        <button type="submit" form="form-page-form" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i> <?php echo $button_copy; ?></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-page-form').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-id"><?php echo $entry_page_form_id; ?></label>
                <input type="text" name="filter_page_form_id" value="<?php echo $filter_page_form_id; ?>" placeholder="<?php echo $entry_page_form_id; ?>" id="input-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-pageform-title"><?php echo $entry_page_form_title; ?></label>
                <input type="text" name="filter_page_form_title" value="<?php echo $filter_page_form_title; ?>" placeholder="<?php echo $entry_page_form_title; ?>" id="input-pageform-title" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-page-form">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'p.page_form_id') { ?>
                    <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_id; ?>"><?php echo $column_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'pd.title') { ?>
                    <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'o.sort_order') { ?>
                    <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($page_forms) { ?>
                <?php foreach ($page_forms as $page_form) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($page_form['page_form_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $page_form['page_form_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $page_form['page_form_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left">#<?php echo $page_form['page_form_id']; ?></td>
                  <td class="text-left"><?php echo $page_form['title']; ?></td>
                  <td class="text-right"><?php echo $page_form['sort_order']; ?></td>
                  <td class="text-right">
                    <span class="label <?php echo $page_form['status_class']; ?>"><?php echo $page_form['status']; ?></span>
                  </td>
                  <td class="text-right">
                    <?php if($config_submission_status) { ?>
                    <a href="<?php echo $page_form['addstatus']; ?>" data-toggle="tooltip" title="<?php echo $button_status; ?>" class="btn <?php if($page_form['setupstatustotal']) { ?>btn-success<?php } else { ?>btn-default<?php } ?>"><i class="fa fa-arrow-circle-right"></i> <i class="fa fa-wrench"></i></a>
                    <?php } ?> 

                    <a target="_blank" href="<?php echo $page_form['link']; ?>" class="btn btn-warning" data-toggle="tooltip" title="<?php echo $button_view_form; ?>"><i class="fa fa-eye"></i></a>

                    <a class="btn btn-warning show-code" rel="<?php echo $page_form['page_form_id']; ?>" data-toggle="tooltip" title="<?php echo $button_code; ?>"><i class="fa fa-code"></i></a>
                    <a href="<?php echo $page_form['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <tr>
                  <td colspan="6" class="td-code">
                    <div style="display: none; " class="html-code html-code<?php echo $page_form['page_form_id']; ?>">
                      <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $alert_event; ?> <br><br><b>onclick="javascript:OpenFormBuilderPopup(<?php echo $page_form['page_form_id']; ?>);"</b>
                      </div>
                      <div class="alert alert-warning"><i class="fa fa-link"></i> Hyperlink: <b>javascript:OpenFormBuilderPopup(<?php echo $page_form['page_form_id']; ?>);</b>
                      </div>
                      <textarea class="form-control pickfrom"><a class="btn btn-primary" href="javascript:OpenFormBuilderPopup(<?php echo $page_form['page_form_id']; ?>);"><?php echo $page_form['title']; ?></a></textarea>
                    </div>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
$('.show-code').click(function() {
  var rel = $(this).attr('rel');
  $('.html-code'+ rel).slideToggle();
  // $('.html-code'+ rel).parent().toggle('show');
});
</script>


<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  url = 'index.php?route=extension/ciformbuilder/page_form&<?php echo $module_token; ?>=<?php echo $ci_token; ?>';

  var filter_page_form_id = $('input[name=\'filter_page_form_id\']').val();
  if (filter_page_form_id) {
    url += '&filter_page_form_id=' + encodeURIComponent(filter_page_form_id);
  }

  var filter_page_form_title = $('input[name=\'filter_page_form_title\']').val();
  if (filter_page_form_title) {
    url += '&filter_page_form_title=' + encodeURIComponent(filter_page_form_title);
  }

  var filter_status = $('select[name=\'filter_status\']').val();
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_page_form_title\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=extension/ciformbuilder/page_form/autocomplete&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&filter_title=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['title'],
            value: item['page_form_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_page_form_title\']').val(item['label']);
  }
});
//--></script>
</div>
<?php echo $footer; ?>