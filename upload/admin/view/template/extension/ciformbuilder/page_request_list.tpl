<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" class="btn btn-primary button-download-pdf" form="form-information" formaction="<?php echo $download_pdf; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_download_pdf; ?>" ><i class="fa fa-file-pdf-o"></i> <?php echo $button_download_pdf; ?></button>

        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-information').submit() : false;"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></button>
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
                <label class="control-label" for="input-pageform-title"><?php echo $entry_page_form_title; ?></label>
                <input type="text" name="filter_page_form_title" value="<?php echo $filter_page_form_title; ?>" placeholder="<?php echo $entry_page_form_title; ?>" id="input-pageform-title" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" placeholder="<?php echo $entry_ip; ?>" id="input-ip" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" ><?php echo $entry_filter_product; ?></label>
                <div class="input-group">
                  <input type="text" name="filter_product" value="<?php echo $filter_product_name; ?>" placeholder="<?php echo $entry_filter_product; ?>" id="input-product" class="form-control" />
                  <span class="input-group-btn" style="width: 20%;">
                    <input type="text" name="filter_product_id" class="form-control" value="<?php echo $filter_product_id; ?>" style="font-weight: bold; text-align: center;" />
                  </span>
                </div>
              </div>
              <?php if(!$config_submission_status) { ?>
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
              <?php } ?>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
                </div>
              </div>
            </div>

            <?php if($config_submission_status) { ?>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-pageform-status"><?php echo $entry_form_status; ?></label>
                <select name="filter_page_form_status" id="input-pageform-status" class="form-control">
                    <option value="*"></option>
                    <?php foreach($form_statuses as $form_status) { ?>
                    <option value="<?php echo $form_status['form_status_id']; ?>" <?php if($filter_page_form_status == $form_status['form_status_id']) { ?>selected="selected"<?php } ?>><?php echo $form_status['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
            <?php } ?>
          </div>
        </div>
        <div class="well well-sm">
          <div class="row">
            <div class="col-sm-5">
              <div class="form-group">
                <div class="input-group date">
                  <input type="text" name="export_date_start" value="<?php echo date('Y'); ?>-<?php echo date('m'); ?>-01" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <div class="input-group date">
                  <input type="text" name="export_date_end" value="<?php echo date('Y-m-d'); ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <button class="btn btn-success btn-block" data-toggle="tooltip" title="<?php echo $button_export; ?>" id="button-export"><i class="fa fa-download"></i> <?php echo $button_export; ?></button>
              </div>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-information">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left" style="width: 20%;"><?php if ($sort == 'id.title') { ?>
                    <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                    <?php } ?></td>

                    <?php if($config_submission_status) { ?>
                    <td class="text-left"><?php if ($sort == 'form_status') { ?>
                      <a href="<?php echo $sort_form_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_form_status; ?></a>
                      <?php } else { ?>
                      <a href="<?php echo $sort_form_status; ?>"><?php echo $column_form_status; ?></a>
                      <?php } ?>
                    </td>
                    <?php } ?>

                    <td class="text-left" style="width: 15%;"><?php if ($sort == 'pg.product_name') { ?>
                    <a href="<?php echo $sort_product_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_product_name; ?>"><?php echo $column_product_name; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'pg.ip') { ?>
                    <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'pg.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right" style="width: 20%;"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($page_requests) { ?>
                  <?php foreach ($page_requests as $page_request) { ?>
                    <tr id="request<?php echo $page_request['page_request_id']; ?>">
                      <td class="text-center"><?php if (in_array($page_request['page_request_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $page_request['page_request_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $page_request['page_request_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $page_request['page_form_title']; ?></td>

                      <?php if($config_submission_status) { ?>
                      <td class="text-left form_status"><span class="label" style="background-color: <?php echo $page_request['form_status_bgcolor']; ?>;color: <?php echo $page_request['form_status_textcolor'] ? $page_request['form_status_textcolor'] : '#545454'; ?>"><?php echo $page_request['form_status']; ?></span>
                      </td>
                      <?php } ?>

                      <td class="text-left">
                        <?php if($page_request['product_name']) { ?>
                        <?php echo $page_request['product_name']; ?>
                        <?php } ?>
                      </td>
                      <td class="text-right"><?php echo $page_request['customer']; ?></td>
                      <td class="text-right"><?php echo $page_request['ip']; ?></td>
                      <td class="text-right"><?php echo $page_request['date_added']; ?></td>
                      <td class="text-right">
                          <?php if($config_submission_status) { ?>
                            <?php if($page_request['form_statuses']) { ?>
                            <a class="btn btn-success btn_status_detail" rel="<?php echo $page_request['page_request_id']; ?>" data-toggle="tooltip" title="<?php echo $button_status; ?>"><i class="fa fa-arrow-circle-right"></i> <i class="fa fa-envelope"></i></a>
                            <?php } ?>
                          <?php } ?>

                          <a href="<?php echo $page_request['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                          <a href="<?php echo $page_request['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info btn-request-view"><i class="fa fa-eye"></i>
                            <?php if(!$page_request['read_status']) { ?>
                            <span class="label label-danger request-counter">1</span>
                            <?php } ?>
                          </a>
                      </td>
                    </tr>

                    <?php if($config_submission_status) { ?>
                    <tr>
                      <td colspan="10" class="td-code">
                        <div class="html-code form-horizontal" id="history<?php echo $page_request['page_request_id']; ?>" style="display: none;">
                            <div class="clearfix">
                                <div class="col-sm-12">
                                    <?php if($page_request['form_statuses']) { ?>
                                    <div class="form-group">
                                      <label class="control-label col-sm-3"><?php echo $text_notify; ?></label>
                                      <div class="col-sm-3">
                                        <div class="radio">
                                          <input type="checkbox" name="notify" value="1" checked />
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="control-label col-sm-3"><?php echo $text_choose_form_status; ?></label>
                                      <div class="col-sm-4">
                                        <div class="input-group">
                                          <select name="form_status_id" class="form-control">
                                            <?php foreach($page_request['form_statuses'] as $form_status) { ?>
                                              <option value="<?php echo $form_status['form_status_id']; ?>" <?php echo $form_status['form_status_id'] == $page_request['form_status_id'] ? 'selected="selected"' : ''; ?>><?php echo $form_status['name']; ?></option>
                                            <?php } ?>
                                          </select>
                                          <span class="input-group-btn"><a class="btn btn-success btn-changestatus" type="button" data-loading-text="<?php echo $text_loading; ?>" rel="<?php echo $page_request['page_request_id']; ?>"><i class="fa fa-plus-circle"></i> <?php echo $text_send_now; ?></a></span>
                                        </div>
                                      </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                      </td>
                    </tr>
                    <?php } ?>

                  <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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

<?php if($config_submission_status) { ?>
<script type="text/javascript">
$('.btn_status_detail').click(function() {
  var rel = $(this).attr('rel');
  $('#history'+ rel).slideToggle();
});

$('.btn-changestatus').on('click', function(e) {
  var id = $(this).attr('rel');

  $.ajax({
    url: 'index.php?route=extension/ciformbuilder/page_request/addPageRequestHistory&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&id='+id,
    type: 'post',
    dataType: 'json',
    data: $('#history'+ id +' select[name="form_status_id"], #history'+ id +' input[name="notify"]:checked'),
    beforeSend: function() {
      $('#history'+ id+' .btn-changestatus').button('loading');
    },
    complete: function() {
      $('#history'+ id+' .btn-changestatus').button('reset');
    },
    success: function (json) {
      $('.alert').remove();
      if(json['error']){
        $('#form-information').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      } else {
        $('#form-information').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        if(json['form_status']) {
          $('#request'+id+' .form_status span').text(json['form_status']);
          $('#request'+id+' .form_status span').css('background-color',json['bgcolor']);
          $('#request'+id+' .form_status span').css('color',json['textcolor']);
        }

        if(json['sent_date']){
          $('#request'+id+' .sent_date').text(json['sent_date']);
        }
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});
//--></script>
<?php } ?>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  url = 'index.php?route=extension/ciformbuilder/page_request&<?php echo $module_token; ?>=<?php echo $ci_token; ?>';

  var filter_page_form_title = $('input[name=\'filter_page_form_title\']').val();
  if (filter_page_form_title) {
    url += '&filter_page_form_title=' + encodeURIComponent(filter_page_form_title);
  }

  var filter_page_form_status = $('select[name=\'filter_page_form_status\']').val();

  if (filter_page_form_status != '*' && typeof filter_page_form_status != 'undefined') {
    url += '&filter_page_form_status=' + encodeURIComponent(filter_page_form_status);
  }

  var filter_customer = $('input[name=\'filter_customer\']').val();
  if (filter_customer) {
    url += '&filter_customer=' + encodeURIComponent(filter_customer);
  }

  var filter_ip = $('input[name=\'filter_ip\']').val();
  if (filter_ip) {
    url += '&filter_ip=' + encodeURIComponent(filter_ip);
  }

  var filter_date_added = $('input[name=\'filter_date_added\']').val();
  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }

  var filter_product_id = $('input[name=\'filter_product_id\']').val();
  if (filter_product_id) {
    url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
  }

  location = url;
});
//--></script>
<script type="text/javascript">
$('input[name=\'filter_product\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        json.unshift({
          product_id: 0,
          name: '<?php echo $text_none; ?>'
        });

        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_product\']').val((item['label'] == '<?php echo $text_none; ?>' ? '' : item['label']));
    $('input[name=\'filter_product_id\']').val((item['value'] == 0 ? '' : item['value']));
  }
});
</script>
<script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=<?php $customer_action; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_customer\']').val(item['label']);
  }
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
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});
//--></script>

<script type="text/javascript"><!--
$('#button-export').click(function() {
  var url = '<?php echo $export_url; ?>';

  var filter_page_form_title = $('input[name=\'filter_page_form_title\']').val();
  if (filter_page_form_title) {
    url += '&filter_page_form_title=' + encodeURIComponent(filter_page_form_title);
  }

  var filter_customer = $('input[name=\'filter_customer\']').val();
  if (filter_customer) {
    url += '&filter_customer=' + encodeURIComponent(filter_customer);
  }

  var filter_ip = $('input[name=\'filter_ip\']').val();
  if (filter_ip) {
    url += '&filter_ip=' + encodeURIComponent(filter_ip);
  }

  var filter_date_added = $('input[name=\'filter_date_added\']').val();
  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }

  var export_date_start = $('input[name=\'export_date_start\']').val();
  if (export_date_start) {
    url += '&export_date_start=' + encodeURIComponent(export_date_start);
  }

  var export_date_end = $('input[name=\'export_date_end\']').val();
  if (export_date_end) {
    url += '&export_date_end=' + encodeURIComponent(export_date_end);
  }

  var sort = getUrlVars()["sort"];
  if (sort) {
    url += '&sort=' + encodeURIComponent(sort);
  }

  var sort = getUrlVars()["order"];
  if (sort) {
    url += '&order=' + encodeURIComponent(sort);
  }

  location = url;
});

function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
      vars[key] = value;
  });
  return vars;
 }
//--></script>
<script type="text/javascript"><!--
$('.button-download-pdf').prop('disabled', true);

$('input[name^=\'selected\'], input[name=\'all_selected\']').on('change', function() {
  $('.button-download-pdf').prop('disabled', true);

  var selected = $('input[name^=\'selected\']:checked');

  if (selected.length) {
    $('.button-download-pdf').prop('disabled', false);
  }
});

$('.button-download-pdf').on('click', function(e) {
  $('#form-information').attr('action', this.getAttribute('formAction'));
});
//--></script>
<style type="text/css">
.btn-request-view { position: relative; }

.request-counter { position: absolute !important; top: -6px !important; right: -8px; font-size: 11px; }
</style>
</div>

<?php echo $footer; ?>