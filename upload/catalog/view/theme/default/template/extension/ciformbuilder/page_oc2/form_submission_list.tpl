<?php echo $header; ?>
<div class="container">
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
      <h1><?php echo $heading_title; ?></h1>
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-pageform-title"><?php echo $entry_page_form_title; ?></label>
                <input type="text" name="filter_page_form_title" value="<?php echo $filter_page_form_title; ?>" placeholder="<?php echo $entry_page_form_title; ?>" id="input-pageform-title" class="form-control" />
              </div>
           </div>

           <?php if($config_submission_status) { ?>
           <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-pageform-status"><?php echo $entry_status; ?></label>
                <select name="filter_page_form_status" id="input-pageform-status" class="form-control">
                    <option value="*"></option>
                    <?php foreach($form_statuses as $form_status) { ?>
                    <option value="<?php echo $form_status['form_status_id']; ?>" <?php if($filter_page_form_status == $form_status['form_status_id']) { ?> selected="selected" <?php } ?>><?php echo $form_status['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
           </div>
           <?php } ?>

            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                <th class="text-left"><?php if ($sort == 'id.title') { ?>
                  <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                  <?php } ?></th>

                  <?php if($config_submission_status) { ?>
                  <th class="text-left"><?php if ($sort == 'form_status') { ?>
                    <a href="<?php echo $sort_form_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_form_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?>
                  </th>
                  <?php } ?>

                  <th class="text-right"><?php if ($sort == 'pg.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?>
                  </th>

                  <th class="text-right"><?php echo $column_action; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($page_requests) { ?>
                  <?php foreach ($page_requests as $page_request) { ?>
                  <tr>
                    <td class="text-left"><?php echo $page_request['page_form_title']; ?></td>

                    <?php if($config_submission_status) { ?>
                    <td class="text-left"><span class="label" style="background-color: <?php echo $page_request['form_status_bgcolor']; ?>;color: <?php echo $page_request['form_status_textcolor'] ? $page_request['form_status_textcolor'] : '#545454'; ?>"><?php echo $page_request['form_status']; ?></span>
                    </td>
                    <?php } ?>

                    <td class="text-right"><?php echo $page_request['date_added']; ?></td>
                    <td class="text-right">
                       <a href="<?php echo $page_request['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info btn-request-view"><i class="fa fa-eye"></i>
                        </a>
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
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  url = 'index.php?route=extension/ciformbuilder/form_submission';

  var filter_page_form_title = $('input[name=\'filter_page_form_title\']').val();
  if (filter_page_form_title) {
    url += '&filter_page_form_title=' + encodeURIComponent(filter_page_form_title);
  }

  var filter_page_form_status = $('select[name=\'filter_page_form_status\']').val();
  if (filter_page_form_status != '*' && typeof filter_page_form_status != 'undefined') {
    url += '&filter_page_form_status=' + encodeURIComponent(filter_page_form_status);
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
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});
//--></script>
<style type="text/css">
.btn-request-view { position: relative; }

.request-counter { position: absolute !important; top: -6px !important; left: -8px; font-size: 11px; }
</style>
</div>
<?php echo $footer; ?>