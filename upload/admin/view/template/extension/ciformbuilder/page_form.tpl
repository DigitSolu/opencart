<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-page" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?> </a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-page" class="form-horizontal">
          <ul class="nav nav-tabs maintabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-language" aria-hidden="true"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-fields" data-toggle="tab"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $tab_fields; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><i class="fa fa-cogs" aria-hidden="true"></i> <?php echo $tab_page; ?></a></li>
            <li><a href="#tab-link" data-toggle="tab"><i class="fa fa-link" aria-hidden="true"></i> <?php echo $tab_link; ?></a></li>
            <li><a href="#tab-email" data-toggle="tab"><i class="fa fa-bell" aria-hidden="true"></i> <?php echo $tab_email; ?></a></li>
            <li><a href="#tab-success-page" data-toggle="tab"><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $tab_success_page; ?></a></li>
            <li><a href="#tab-css" data-toggle="tab"><i class="fa fa-pencil" aria-hidden="true"></i> <?php echo $tab_css; ?></a></li>
            <li><a href="#tab-seo" data-toggle="tab"><i class="fa fa-search" aria-hidden="true"></i> <?php echo $tab_seo; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
               <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
                  <?php if(VERSION >= '2.2.0.0') { ?>
                    <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                    <?php } else{ ?>
                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                    <?php } ?> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="page_form_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang=""><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['description'] : ''; ?></textarea>
                      <?php if (isset($error_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-bottom-description<?php echo $language['language_id']; ?>"><?php echo $entry_bottom_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="page_form_description[<?php echo $language['language_id']; ?>][bottom_description]" placeholder="<?php echo $entry_bottom_description; ?>" id="input-bottom-description<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang=""><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['bottom_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="page_form_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="page_form_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                  <fieldset>
                    <legend><i class="fa fa-align-justify"></i> <?php echo $text_form_attributes; ?></legend>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-fieldset-title<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_fieldset_title; ?>"><?php echo $entry_fieldset_title; ?></span></label>
                      <div class="col-sm-10">
                        <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][fieldset_title]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['fieldset_title'] : ''; ?>" placeholder="<?php echo $entry_fieldset_title; ?>" id="input-fieldset-title<?php echo $language['language_id']; ?>" class="form-control" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-submit-button<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_submit_button; ?>"><?php echo $entry_submit_button; ?></span></label>
                      <div class="col-sm-10">
                        <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][submit_button]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['submit_button'] : ''; ?>" placeholder="<?php echo $entry_submit_button; ?>" id="input-submit-button<?php echo $language['language_id']; ?>" class="form-control" />
                      </div>
                    </div>
                  </fieldset>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <fieldset>
                <legend><i class="fa fa-cogs"></i> <?php echo $leg_logo; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
                  <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb_logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                    <input type="hidden" name="logo" value="<?php echo $logo; ?>" id="input-logo" />
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><i class="fa fa-cogs"></i> <?php echo $leg_setting_info; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="<?php echo $help_top; ?>"><?php echo $entry_top; ?></span></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo $top ? 'active' : ''; ?>">
                        <input name="top" <?php echo $top ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_show; ?>
                      </label>
                      <label class="btn btn-default <?php echo !$top ? 'active' : ''; ?>">
                        <input name="top" <?php echo !$top ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_hide; ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-bottom"><span data-toggle="tooltip" title="<?php echo $help_bottom; ?>"><?php echo $entry_bottom; ?></span></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo $bottom ? 'active' : ''; ?>">
                        <input name="bottom" <?php echo $bottom ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_show; ?>
                      </label>
                      <label class="btn btn-default <?php echo !$bottom ? 'active' : ''; ?>">
                        <input name="bottom" <?php echo !$bottom ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_hide; ?>
                      </label>
                    </div>
                  </div>
                </div>



                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_reset_button; ?></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo $reset_button ? 'active' : ''; ?>">
                        <input name="reset_button" <?php echo $reset_button ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_show; ?>
                      </label>
                      <label class="btn btn-default <?php echo !$reset_button ? 'active' : ''; ?>">
                        <input name="reset_button" <?php echo !$reset_button ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_hide; ?>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                  <div class="col-sm-2">
                    <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo $status ? 'active' : ''; ?>">
                        <input name="status" <?php echo $status ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-default <?php echo !$status ? 'active' : ''; ?>">
                        <input name="status" <?php echo !$status ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="form-group {{ config_submission_status ? '' : 'hide' }}">
                  <label class="col-sm-2 control-label"><?php echo $entry_form_status; ?></label>
                  <div class="col-sm-10">
                    <select name="default_form_status" class="form-control">
                      <option value=""><?php echo $text_please_select; ?></option>
                    <?php if ($form_statuses) { ?>
                     <?php foreach ($form_statuses as $form_status) { ?>
                    <option value="<?php echo $form_status['form_status_id']; ?>" <?php if($form_status['form_status_id'] == $default_form_status) { ?>selected="selected"<?php } ?>><?php echo $form_status['name']; ?></option>
                    <?php } ?>
                   <?php } ?>
                    </select>
                  </div>
                </div>
              </fieldset>
               <!-- Term Condition code start -->
              <fieldset>
                <legend><i class="fa fa-cog"></i> <?php echo $legend_terms; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_termcondition; ?></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo (isset($termcondition['status']) && $termcondition['status']) ? 'active' : ''; ?>">
                        <input name="termcondition[status]" <?php echo (isset($termcondition['status']) && $termcondition['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-default <?php echo (isset($termcondition['status']) && !$termcondition['status']) ? 'active' : ''; ?>">
                        <input name="termcondition[status]" <?php echo (isset($termcondition['status']) && !$termcondition['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_termcondition_info; ?>"><?php echo $entry_termcondition_info; ?></span></label>
                  <div class="col-sm-10">
                    <select name="termcondition[information_id]" class="form-control">
                      <option value="0"><?php echo $text_none; ?></option>
                      <?php foreach ($informations as $information) { ?>
                      <?php if (isset($termcondition['information_id']) && $information['information_id'] == $termcondition['information_id']) { ?>
                      <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_termcondition_text; ?>"><?php echo $entry_termcondition_text; ?></span></label>
                  <div class="col-sm-10">
                      <?php foreach ($languages as $language) { ?>
                      <div class="input-group">
                        <span class="input-group-addon"><?php if(VERSION >= '2.2.0.0') { ?>
                        <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                        <?php } else{ ?>
                        <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                        <?php } ?></span>
                        <input type="text" name="termcondition[desc][<?php echo $language['language_id']; ?>]" class="form-control" value="<?php echo (isset($termcondition['desc'][$language['language_id']]) ? $termcondition['desc'][$language['language_id']] : ''); ?>">
                      </div>
                      <br/>
                    <?php } ?>
                  </div>
                </div>
              </fieldset>
              <!-- Term Condition code end -->
              <fieldset>
                <legend><i class="fa fa-cog"></i> <?php echo $leg_captcha; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_captcha; ?></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo $captcha ? 'active' : ''; ?>">
                        <input name="captcha" <?php echo $captcha ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_show; ?>
                      </label>
                      <label class="btn btn-default <?php echo !$captcha ? 'active' : ''; ?>">
                        <input name="captcha" <?php echo !$captcha ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_hide; ?>
                      </label>
                    </div>
                  </div>
                </div>
                <br/>
                <br/>
              </fieldset>
              <fieldset>
                <legend><i class="fa fa-info-circle"></i> <?php echo $leg_upload_info; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_file_ext_allowed; ?>"><?php echo $entry_file_ext_allowed; ?></span></label>
                  <div class="col-sm-6">
                    <textarea name="file_ext_allowed" rows="5" placeholder="<?php echo $entry_file_ext_allowed; ?>" class="form-control"><?php echo $file_ext_allowed; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_file_mime_allowed; ?>"><?php echo $entry_file_mime_allowed; ?></span></label>
                  <div class="col-sm-6">
                    <textarea name="file_mime_allowed" rows="5" placeholder="<?php echo $entry_file_mime_allowed; ?>" class="form-control"><?php echo $file_mime_allowed; ?></textarea>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><i class="fa fa-info-circle"></i> <?php echo $leg_google_analytic; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_google_analytic_status; ?></label>
                  <div class="col-sm-10">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default <?php echo (isset($google_analytic['status']) && $google_analytic['status']) ? 'active' : ''; ?>">
                        <input name="google_analytic[status]" <?php echo (isset($google_analytic['status']) && $google_analytic['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>
                      </label>
                      <label class="btn btn-default <?php echo (isset($google_analytic['status']) && !$google_analytic['status']) ? 'active' : ''; ?>">
                        <input name="google_analytic[status]" <?php echo (isset($google_analytic['status']) && !$google_analytic['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_google_analytic; ?></label>
                  <div class="col-sm-6">
                    <textarea name="google_analytic[code]" rows="5" placeholder="<?php echo $entry_google_analytic; ?>" class="form-control"><?php echo (isset($google_analytic['code']) ? $google_analytic['code'] : ''); ?></textarea>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-link">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked maintabs">
                    <li class="active"><a href="#tab-link-setting" data-toggle="tab"><i class="fa fa-cogs" aria-hidden="true"></i> <?php echo $tab_link_setting; ?></a></li>
                    <li><a href="#tab-link-information" data-toggle="tab"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo $tab_information; ?></a></li>
                    <li><a href="#tab-link-products" data-toggle="tab"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo $tab_product; ?></a></li>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-link-setting">
                      <fieldset>
                        <legend><i class="fa fa-cogs"></i> <?php echo $leg_link; ?></legend>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $entry_show_guest; ?></label>
                          <div class="col-sm-10">
                            <div class="btn-group" data-toggle="buttons">
                              <label class="btn btn-default <?php echo $show_guest ? 'active' : ''; ?>">
                                <input name="show_guest" <?php echo $show_guest ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>
                              </label>
                              <label class="btn btn-default <?php echo !$show_guest ? 'active' : ''; ?>">
                                <input name="show_guest" <?php echo !$show_guest ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
                          <div class="col-sm-10">
                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                              <?php foreach ($customer_groups as $customer_group) { ?>
                              <div class="checkbox">
                                <label>
                                  <?php if (in_array($customer_group['customer_group_id'], $page_form_customer_group)) { ?>
                                  <input type="checkbox" name="page_form_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                                  <?php echo $customer_group['name']; ?>
                                  <?php } else { ?>
                                  <input type="checkbox" name="page_form_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                                  <?php echo $customer_group['name']; ?>
                                  <?php } ?>
                                </label>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                          <div class="col-sm-10">
                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                              <?php foreach ($stores as $store) { ?>
                              <div class="checkbox">
                                <label>
                                  <?php if (in_array($store['store_id'], $page_form_store)) { ?>
                                  <input type="checkbox" name="page_form_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                  <?php echo $store['name']; ?>
                                  <?php } else { ?>
                                  <input type="checkbox" name="page_form_store[]" value="<?php echo $store['store_id']; ?>" />
                                  <?php echo $store['name']; ?>
                                  <?php } ?>
                                </label>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane" id="tab-link-information">
                      <fieldset>
                        <legend><i class="fa fa-info-circle"></i> <?php echo $leg_information; ?></legend>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $entry_information; ?></label>
                          <div class="col-sm-10">
                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                              <?php foreach ($informations as $information) { ?>
                              <div class="checkbox">
                                <label>
                                  <?php if (in_array($information['information_id'], $page_form_information)) { ?>
                                  <input type="checkbox" name="page_form_information[]" value="<?php echo $information['information_id']; ?>" checked="checked" />
                                  <?php echo $information['title']; ?>
                                  <?php } else { ?>
                                  <input type="checkbox" name="page_form_information[]" value="<?php echo $information['information_id']; ?>" />
                                  <?php echo $information['title']; ?>
                                  <?php } ?>
                                </label>
                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="tab-pane" id="tab-link-products">
                      <fieldset>
                        <legend><i class="fa fa-tag"></i> <?php echo $leg_product; ?></legend>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $entry_producttype; ?></label>
                          <div class="col-sm-10">
                            <div class="btn-group" data-toggle="buttons">
                              <label class="producttype btn btn-default <?php echo $producttype == 'no' ? 'active' : ''; ?>">
                                <input name="producttype" <?php echo $producttype == 'no' ? 'checked="checked"' : ''; ?> autocomplete="off" value="no" type="radio"><?php echo $text_no_product; ?>
                              </label>
                              <label class="producttype btn btn-default <?php echo $producttype == 'all' ? 'active' : ''; ?>">
                                <input name="producttype" <?php echo $producttype == 'all' ? 'checked="checked"' : ''; ?> autocomplete="off" value="all" type="radio"><?php echo $text_all_product; ?>
                              </label>
                              <label class="producttype btn btn-default <?php echo $producttype == 'choose' ? 'active' : ''; ?>">
                                <input name="producttype" <?php echo $producttype == 'choose' ? 'checked="checked"' : ''; ?> autocomplete="off" value="choose" type="radio"><?php echo $text_choose_product; ?>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group product-group <?php echo $producttype != 'choose' ? 'hide' : ''; ?>">
                          <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
                          <div class="col-sm-10">
                            <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                            <div id="formbuilder-product" class="well well-sm" style="height: 300px; overflow: auto;">
                            <?php foreach ($page_form_products as $page_form_product) { ?>
                            <div id="formbuilder-product<?php echo $page_form_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $page_form_product['name']; ?>
                              <input type="hidden" name="page_form_product[]" value="<?php echo $page_form_product['product_id']; ?>" />
                            </div>
                            <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div class="form-group pbutton_title <?php echo $producttype == 'no' ? 'hide' : ''; ?>">
                          <label class="col-sm-2 control-label"><?php echo $entry_pbutton_title; ?></label>
                          <div class="col-sm-10">
                            <?php foreach ($languages as $language) { ?>
                            <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                              <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][pbutton_title]" value="<?php echo isset($page_form_description[$language['language_id']]['pbutton_title']) ? $page_form_description[$language['language_id']]['pbutton_title'] : ''; ?>" placeholder="<?php echo $entry_pbutton_title; ?>" class="form-control" />
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label"><?php echo $entry_popup_size; ?></label>
                          <div class="col-sm-10">
                            <div class="btn-group" data-toggle="buttons">
                              <label class="btn btn-default <?php echo $popup_size == 'small' ? 'active' : ''; ?>">
                                <input name="popup_size" <?php echo $popup_size == 'small' ? 'checked="checked"' : ''; ?> autocomplete="off" value="small" type="radio"><?php echo $text_small; ?>
                              </label>
                              <label class="btn btn-default <?php echo $popup_size == 'medium' ? 'active' : ''; ?>">
                                <input name="popup_size" <?php echo $popup_size == 'medium' ? 'checked="checked"' : ''; ?> autocomplete="off" value="medium" type="radio"> <?php echo $text_medium; ?>
                              </label>
                              <label class="btn btn-default <?php echo $popup_size == 'large' ? 'active' : ''; ?>">
                                <input name="popup_size" <?php echo $popup_size == 'large' ? 'checked="checked"' : ''; ?> autocomplete="off" value="large" type="radio"> <?php echo $text_large; ?>
                              </label>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-fields">
              <div class="row rowrl">
                <div id="maincontent">
                  <button type="button" class="openbtntable" onclick="openNav()">&#9776; <?php echo $button_valinfo; ?></button>
                  <div class="col-sm-4 col-xs-12 col-md-3">
                    <ul class="nav nav-pills nav-stacked" id="pageformfields">
                    <?php $field_row = 0; ?>
                      <?php foreach($fields as $field) { ?>
                      <li class="pageformfields-li"><a href="#tab-field<?php echo $field_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-field<?php echo $field_row; ?>\']').parent().remove(); $('#tab-field<?php echo $field_row; ?>').remove(); $('#pageformfields a:first').tab('show');"></i> <?php echo (!empty($field['description'][$config_language_id]['field_name']) ? $field['description'][$config_language_id]['field_name'] : $tab_field .'-' . ($field_row + (int)1)); ?> <i class="fa fa-arrows pull-right" aria-hidden="true"></i></a></li>
                      <?php $field_row++; ?>
                      <?php } ?>
                    </ul>
                    <ul class="nav nav-pills nav-stacked adfieldbutton">
                      <li><button type="button" class="btn btn-default btn-block" onclick="addField();"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?php echo $button_add_field; ?></button></li>
                    </ul>
                  </div>
                  <div class="col-sm-8 col-xs-12 col-md-9" >
                    <div class="tab-content" id="tab-content">
                    <?php $field_row = 0; ?>
                    <?php $page_form_option_value_row = 0; ?>
                      <?php foreach($fields as $field) { ?>
                      <div class="tab-pane" id="tab-field<?php echo $field_row; ?>">
                        <fieldset>
                          <legend><i class="fa fa-cogs"></i> <?php echo $text_type_setting ?></legend>
                          <div class="form-group">
                            <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>
                            <div class="col-sm-12">
                              <select name="page_form_field[<?php echo $field_row; ?>][type]" class="form-control field-type" rel="<?php echo $field_row; ?>">
                                <optgroup label="<?php echo $text_choose; ?>">
                                <?php if ($field['type'] == 'select') { ?>
                                <option value="select" selected="selected"><?php echo $text_select; ?></option>
                                <?php } else { ?>
                                <option value="select"><?php echo $text_select; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'multi_select') { ?>
                                <option value="multi_select" selected="selected"><?php echo $text_multi_select; ?></option>
                                <?php } else { ?>
                                <option value="multi_select"><?php echo $text_multi_select; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'radio') { ?>
                                <option value="radio" selected="selected"><?php echo $text_radio; ?></option>
                                <?php } else { ?>
                                <option value="radio"><?php echo $text_radio; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'radio_toggle') { ?>
                                <option value="radio_toggle" selected="selected"><?php echo $text_radio_toggle; ?></option>
                                <?php } else { ?>
                                <option value="radio_toggle"><?php echo $text_radio_toggle; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'checkbox') { ?>
                                <option value="checkbox" selected="selected"><?php echo $text_checkbox; ?></option>
                                <?php } else { ?>
                                <option value="checkbox"><?php echo $text_checkbox; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'checkbox_switch') { ?>
                                <option value="checkbox_switch" selected="selected"><?php echo $text_checkbox_switch; ?></option>
                                <?php } else { ?>
                                <option value="checkbox_switch"><?php echo $text_checkbox_switch; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'checkbox_toggle') { ?>
                                <option value="checkbox_toggle" selected="selected"><?php echo $text_checkbox_toggle; ?></option>
                                <?php } else { ?>
                                <option value="checkbox_toggle"><?php echo $text_checkbox_toggle; ?></option>
                                <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_input; ?>">
                                <?php if ($field['type'] == 'text') { ?>
                                <option value="text" selected="selected"><?php echo $text_text; ?></option>
                                <?php } else { ?>
                                <option value="text"><?php echo $text_text; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'multiple_text') { ?>
                                <option value="multiple_text" selected="selected"><?php echo $text_multiple_text; ?></option>
                                <?php } else { ?>
                                <option value="multiple_text"><?php echo $text_multiple_text; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'textarea') { ?>
                                <option value="textarea" selected="selected"><?php echo $text_textarea; ?></option>
                                <?php } else { ?>
                                <option value="textarea"><?php echo $text_textarea; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'number') { ?>
                                <option value="number" selected="selected"><?php echo $text_number; ?></option>
                                <?php } else { ?>
                                <option value="number"><?php echo $text_number; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'telephone') { ?>
                                <option value="telephone" selected="selected"><?php echo $text_telephone; ?></option>
                                <?php } else { ?>
                                <option value="telephone"><?php echo $text_telephone; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'firstname') { ?>
                                <option value="firstname" selected="selected"><?php echo $text_firstname; ?></option>
                                <?php } else { ?>
                                <option value="firstname"><?php echo $text_firstname; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'lastname') { ?>
                                <option value="lastname" selected="selected"><?php echo $text_lastname; ?></option>
                                <?php } else { ?>
                                <option value="lastname"><?php echo $text_lastname; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'email') { ?>
                                <option value="email" selected="selected"><?php echo $text_email; ?></option>
                                <?php } else { ?>
                                <option value="email"><?php echo $text_email; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'email_exists') { ?>
                                <option value="email_exists" selected="selected"><?php echo $text_email_exists; ?></option>
                                <?php } else { ?>
                                <option value="email_exists"><?php echo $text_email_exists; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'password') { ?>
                                <option value="password" selected="selected"><?php echo $text_password; ?></option>
                                <?php } else { ?>
                                <option value="password"><?php echo $text_password; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'confirm_password') { ?>
                                <option value="confirm_password" selected="selected"><?php echo $text_confirm_password; ?></option>
                                <?php } else { ?>
                                <option value="confirm_password"><?php echo $text_confirm_password; ?></option>
                                <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_file; ?>">
                                <?php if ($field['type'] == 'file') { ?>
                                <option value="file" selected="selected"><?php echo $text_file; ?></option>
                                <?php } else { ?>
                                <option value="file"><?php echo $text_file; ?></option>
                                <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_date; ?>">
                                <?php if ($field['type'] == 'date') { ?>
                                <option value="date" selected="selected"><?php echo $text_date; ?></option>
                                <?php } else { ?>
                                <option value="date"><?php echo $text_date; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'time') { ?>
                                <option value="time" selected="selected"><?php echo $text_time; ?></option>
                                <?php } else { ?>
                                <option value="time"><?php echo $text_time; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'datetime') { ?>
                                <option value="datetime" selected="selected"><?php echo $text_datetime; ?></option>
                                <?php } else { ?>
                                <option value="datetime"><?php echo $text_datetime; ?></option>
                                <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_localisation; ?>">
                                <?php if ($field['type'] == 'country') { ?>
                                <option value="country" selected="selected"><?php echo $text_country; ?></option>
                                <?php } else { ?>
                                <option value="country"><?php echo $text_country; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'zone') { ?>
                                <option value="zone" selected="selected"><?php echo $text_zone; ?></option>
                                <?php } else { ?>
                                <option value="zone"><?php echo $text_zone; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'postcode') { ?>
                                <option value="postcode" selected="selected"><?php echo $text_postcode; ?></option>
                                <?php } else { ?>
                                <option value="postcode"><?php echo $text_postcode; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'address') { ?>
                                <option value="address" selected="selected"><?php echo $text_address; ?></option>
                                <?php } else { ?>
                                <option value="address"><?php echo $text_address; ?></option>
                                <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_header_type; ?>">
                                <?php if ($field['type'] == 'header') { ?>
                                <option value="header" selected="selected"><?php echo $text_header; ?></option>
                                <?php } else { ?>
                                <option value="header"><?php echo $text_header; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'paragraph') { ?>
                                <option value="paragraph" selected="selected"><?php echo $text_paragraph; ?></option>
                                <?php } else { ?>
                                <option value="paragraph"><?php echo $text_paragraph; ?></option>
                                <?php } ?>
                                <?php if ($field['type'] == 'hrline') { ?>
                                <option value="hrline" selected="selected"><?php echo $text_hrline; ?></option>
                                <?php } else { ?>
                                <option value="hrline"><?php echo $text_hrline; ?></option>
                                <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_google_map; ?>">
                                  <?php if ($field['type'] == 'google_map') { ?>
                                  <option value="google_map" selected="selected"><?php echo $text_google_map; ?></option>
                                  <?php } else { ?>
                                  <option value="google_map"><?php echo $text_google_map; ?></option>
                                  <?php } ?>
                                </optgroup>
                                <optgroup label="<?php echo $text_display_message; ?>">
                                  <?php if ($field['type'] == 'display_message') { ?>
                                  <option value="display_message" selected="selected"><?php echo $text_display_message; ?></option>
                                  <?php } else { ?>
                                  <option value="display_message"><?php echo $text_display_message; ?></option>
                                  <?php } ?>
                                </optgroup>
                              </select>
                            </div>
                          </div>

                          <div class="form-group field-number-input<?php echo $field_row; ?>">
                            <label class="col-sm-12 control-label"><?php echo $entry_number_input; ?></label>
                            <div class="col-sm-4">
                              <input type="number" name="page_form_field[<?php echo $field_row; ?>][number_input]" value="<?php echo $field['number_input']; ?>" class="form-control">
                            </div>
                          </div>

                          <div class="form-group field-file-limit<?php echo $field_row; ?>" id="field-file<?php echo $field_row;  ?>">
                            <label class="col-sm-12 control-label"><?php echo $entry_file_limit; ?></label>
                            <div class="col-sm-4">
                              <input type="number" name="page_form_field[<?php echo $field_row; ?>][file_limit]" value="<?php echo $field['file_limit']; ?>" class="form-control">
                            </div>
                          </div>

                        </fieldset>
                        <fieldset>
                          <legend><i class="fa fa-language"></i> <?php echo $text_lang_setting ?></legend>
                          <input type="hidden" name="page_form_field[<?php echo $field_row; ?>][page_form_option_id]" value="<?php echo (isset($field['page_form_option_id']) ? $field['page_form_option_id'] : ''); ?>" />
                          <div class="field-group">
                            <ul class="nav nav-tabs" id="field-language<?php echo $field_row; ?>">
                              <?php foreach ($languages as $language) { ?>
                              <li><a href="#field-language<?php echo $field_row; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab">
                              <?php if(VERSION >= '2.2.0.0') { ?>
                              <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                              <?php } else { ?>
                              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                              <?php } ?> <?php echo $language['name']; ?></a></li>
                              <?php } ?>
                            </ul>
                            <div class="tab-content">
                              <?php foreach ($languages as $language) { ?>
                              <div class="tab-pane" id="field-language<?php echo $field_row; ?>-<?php echo $language['language_id']; ?>">
                                <div class="form-group required">
                                  <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_name; ?>"><?php echo $entry_field_name; ?></span></label>
                                  <div class="col-sm-12">
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][field_name]" value="<?php echo isset($field['description'][$language['language_id']]['field_name']) ? $field['description'][$language['language_id']]['field_name'] : ''; ?>" placeholder="<?php echo $entry_field_name; ?>" class="form-control" />
                                    <?php if (isset($error_field_name[$field_row][$language['language_id']])) { ?>
                                    <div class="text-danger"><?php echo $error_field_name[$field_row][$language['language_id']]; ?></div>
                                    <?php } ?>
                                  </div>
                                </div>
                                <div class="form-group field-myhelp<?php echo $field_row; ?>">
                                  <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_help; ?>"><?php echo $entry_field_help; ?></span></label>
                                  <div class="col-sm-12">
                                    <textarea name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][field_help]" class="form-control"><?php echo isset($field['description'][$language['language_id']]['field_help']) ? $field['description'][$language['language_id']]['field_help'] : ''; ?></textarea>
                                  </div>
                                </div>
                                <div class="form-group field-placeholder<?php echo $field_row; ?>">
                                  <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_placeholder; ?>"><?php echo $entry_field_placeholder; ?></span></label>
                                  <div class="col-sm-12">
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][field_placeholder]" value="<?php echo isset($field['description'][$language['language_id']]['field_placeholder']) ? $field['description'][$language['language_id']]['field_placeholder'] : ''; ?>" placeholder="<?php echo $entry_field_placeholder; ?>" class="form-control" />
                                  </div>
                                </div>
                                <div class="form-group field-dvalue<?php echo $field_row; ?>">
                                  <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_dvalue; ?>"><?php echo $entry_field_dvalue; ?></span></label>
                                  <div class="col-sm-12">
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][field_dvalue]" value="<?php echo isset($field['description'][$language['language_id']]['field_dvalue']) ? $field['description'][$language['language_id']]['field_dvalue'] : ''; ?>" placeholder="<?php echo $entry_field_dvalue; ?>" class="form-control" />
                                  </div>
                                </div>
                                <div class="form-group field-groupbtn<?php echo $field_row; ?>">
                                  <label class="col-sm-12 control-label"><?php echo $entry_input_group_button_text; ?></label>
                                  <div class="col-sm-12">
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][input_group_button_text]" value="<?php echo isset($field['description'][$language['language_id']]['input_group_button_text']) ? $field['description'][$language['language_id']]['input_group_button_text'] : ''; ?>" placeholder="<?php echo $entry_input_group_button_text; ?>" class="form-control" />
                                  </div>
                                </div>

                                <div class="form-group field-display-message<?php echo $field_row; ?>">
                                  <label class="col-sm-12 control-label"><?php echo $entry_field_display_message; ?></label>
                                  <div class="col-sm-12">
                                    <textarea name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][field_display_message]" class="form-control summernote" data-toggle="summernote" data-lang="" id="input-display-message<?php echo $field_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($field['description'][$language['language_id']]['field_display_message']) ? $field['description'][$language['language_id']]['field_display_message'] : ''; ?></textarea>
                                  </div>
                                </div>

                                <div class="form-group field-error<?php echo $field_row; ?>">
                                  <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_error; ?>"><?php echo $entry_field_error; ?></span></label>
                                  <div class="col-sm-12">
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][description][<?php echo $language['language_id']; ?>][field_error]" value="<?php echo isset($field['description'][$language['language_id']]['field_error']) ? $field['description'][$language['language_id']]['field_error'] : ''; ?>" placeholder="<?php echo $entry_field_error; ?>" class="form-control" />
                                  </div>
                                </div>

                              </div>
                              <?php } ?>
                            </div>
                          </div>
                        </fieldset>
                        <fieldset class="field-image<?php echo $field_row; ?>">
                          <legend><i class="fa fa-image"></i> <?php echo $text_image_setting ?></legend>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group field-thumb-type<?php echo $field_row; ?>">
                                <label class="col-sm-12 control-label"><?php echo $entry_thumb_type; ?></label>
                                <div class="col-sm-12">
                                  <div class="btn-group" data-toggle="buttons">
                                    <label class="thumb_type btn btn-default <?php echo $field['thumb_type'] == 'image' ? 'active' : ''; ?>" rel="<?php echo $field_row; ?>">
                                      <input name="page_form_field[<?php echo $field_row; ?>][thumb_type]" <?php echo $field['thumb_type'] == 'image' ? 'checked="checked"' : ''; ?> autocomplete="off" value="image" type="radio"><?php echo $text_image; ?>
                                    </label>
                                    <label class="thumb_type btn btn-default <?php echo $field['thumb_type'] == 'icon' ? 'active' : ''; ?>" rel="<?php echo $field_row; ?>">
                                      <input name="page_form_field[<?php echo $field_row; ?>][thumb_type]" <?php echo $field['thumb_type'] == 'icon' ? 'checked="checked"' : ''; ?> autocomplete="off" value="icon" type="radio"><?php echo $text_icon; ?>
                                    </label>
                                    <label class="thumb_type btn btn-default <?php echo $field['thumb_type'] == 'none' ? 'active' : ''; ?>" rel="<?php echo $field_row; ?>">
                                      <input name="page_form_field[<?php echo $field_row; ?>][thumb_type]" <?php echo $field['thumb_type'] == 'icon' ? 'checked="checked"' : ''; ?> autocomplete="off" value="none" type="radio"><?php echo $text_none; ?>
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6 image-align-group">
                              <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo $entry_image_align; ?></label>
                                <div class="col-sm-12">
                                  <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-default <?php echo $field['image_align'] == 'left' ? 'active' : ''; ?>">
                                      <input name="page_form_field[<?php echo $field_row; ?>][image_align]" <?php echo $field['image_align'] == 'left' ? 'checked="checked"' : ''; ?> autocomplete="off" value="left" type="radio"><?php echo $text_left; ?>
                                    </label>
                                    <label class="btn btn-default <?php echo $field['image_align'] == 'center' ? 'active' : ''; ?>">
                                      <input name="page_form_field[<?php echo $field_row; ?>][image_align]" <?php echo $field['image_align'] == 'center' ? 'checked="checked"' : ''; ?> autocomplete="off" value="center" type="radio"><?php echo $text_center; ?>
                                    </label>
                                    <label class="btn btn-default <?php echo $field['image_align'] == 'right' ? 'active' : ''; ?>">
                                      <input name="page_form_field[<?php echo $field_row; ?>][image_align]" <?php echo $field['image_align'] == 'right' ? 'checked="checked"' : ''; ?> autocomplete="off" value="right" type="radio"><?php echo $text_right; ?>
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row image-group">
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo $entry_image; ?></label>
                                <div class="col-sm-12"><a href="" id="thumb-image<?php echo $field_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo isset($field['thumb']) ? $field['thumb'] : ''; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                  <input type="hidden" name="page_form_field[<?php echo $field_row; ?>][image]" value="<?php echo $field['image']; ?>" id="input-image<?php echo $field_row; ?>" />
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo $entry_image_width; ?></label>
                                <div class="col-sm-12">
                                  <input type="text" name="page_form_field[<?php echo $field_row; ?>][image_width]" value="<?php echo $field['image_width']; ?>" class="form-control" />
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo $entry_image_height; ?></label>
                                <div class="col-sm-12">
                                  <input type="text" name="page_form_field[<?php echo $field_row; ?>][image_height]" value="<?php echo $field['image_height']; ?>" class="form-control" />
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row icon-group">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo $entry_icon_class; ?></label>
                                <div class="col-sm-12">
                                  <input type="text" name="page_form_field[<?php echo $field_row; ?>][icon_class]" value="<?php echo $field['icon_class']; ?>" class="form-control" />
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="col-sm-12 control-label"><?php echo $entry_icon_size; ?></label>
                                <div class="col-sm-12">
                                  <div class="input-group">
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][icon_size]" value="<?php echo $field['icon_size']; ?>" class="form-control" />
                                    <span class="input-group-btn"><button type="button" class="btn">PX</button></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                        <fieldset class="field-autofill<?php echo $field_row; ?>">
                          <legend><i class="fa fa-list-alt"></i> <?php echo $text_dynamic_setting ?></legend>
                          <div class="form-group">
                            <label class="col-sm-12 control-label"><?php echo $entry_auto_fill_value; ?></label>
                            <div class="col-sm-12">
                              <div class="btn-group" data-toggle="buttons">
                              <?php foreach($auto_fill_values as $auto_fill_value) { ?>
                                <?php if($auto_fill_value['value'] == $field['auto_fill_value']) { ?>
                                <label class="btn btn-default active">
                                  <input name="page_form_field[<?php echo $field_row; ?>][auto_fill_value]" checked="checked" autocomplete="off" value="<?php echo $auto_fill_value['value']; ?>" type="radio"><?php echo $auto_fill_value['text']; ?>
                                </label>
                                <?php } else { ?>
                                <label class="btn btn-default">
                                  <input name="page_form_field[<?php echo $field_row; ?>][auto_fill_value]" autocomplete="off" value="<?php echo $auto_fill_value['value']; ?>" type="radio"><?php echo $auto_fill_value['text']; ?>
                                </label>
                                <?php } ?>
                              <?php } ?>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                        <fieldset>
                          <legend><i class="fa fa-cogs"></i> <?php echo $text_required_setting ?></legend>

                          <div class="form-group">
                            <label class="col-sm-12 control-label"><?php echo $entry_field_status; ?></label>
                            <div class="col-sm-12">
                              <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php echo !empty($field['status']) ? 'active' : ''; ?>">
                                  <input name="page_form_field[<?php echo $field_row; ?>][status]" <?php echo !empty($field['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>
                                </label>
                                <label class="btn btn-default <?php echo empty($field['status']) ? 'active' : ''; ?>">
                                  <input name="page_form_field[<?php echo $field_row; ?>][status]" <?php echo empty($field['status']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>
                                </label>
                              </div>
                            </div>
                          </div>

                          <div class="form-group field-required<?php echo $field_row; ?>">
                            <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_required; ?>"><?php echo $entry_required; ?></span></label>
                            <div class="col-sm-12">
                              <div class="btn-group" data-toggle="buttons">
                                <label data-val="1" rel="<?php echo $field_row; ?>" class="field-required btn btn-default <?php echo !empty($field['required']) ? 'active' : ''; ?>">
                                  <input name="page_form_field[<?php echo $field_row; ?>][required]" <?php echo !empty($field['required']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                                </label>
                                <label data-val="0" rel="<?php echo $field_row; ?>" class="field-required btn btn-default <?php echo empty($field['required']) ? 'active' : ''; ?>">
                                  <input name="page_form_field[<?php echo $field_row; ?>][required]" <?php echo empty($field['required']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group field-width<?php echo $field_row; ?>">
                            <label class="col-sm-12 control-label"><?php echo $entry_width; ?></label>
                            <div class="col-sm-12">
                              <div class="btn-group" data-toggle="buttons">
                              <?php foreach($set_widths as $set_width) { ?>
                                <?php if($set_width['value'] == $field['width']) { ?>
                                <label class="btn btn-default active">
                                  <input name="page_form_field[<?php echo $field_row; ?>][width]" checked="checked" autocomplete="off" value="<?php echo $set_width['value']; ?>" type="radio"><?php echo $set_width['text']; ?>
                                </label>
                                <?php } else { ?>
                                  <label class="btn btn-default">
                                  <input name="page_form_field[<?php echo $field_row; ?>][width]" autocomplete="off" value="<?php echo $set_width['value']; ?>" type="radio"><?php echo $set_width['text']; ?>
                                </label>
                                <?php } ?>
                              <?php } ?>
                              </div>
                            </div>
                          </div>
                          <div class="form-group hide">
                            <label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_sort_order; ?>"><?php echo $entry_sort_order; ?></span></label>
                            <div class="col-sm-12">
                              <input type="text" name="page_form_field[<?php echo $field_row; ?>][sort_order]"  value="<?php echo $field['sort_order']; ?>" class="form-control field-sortorder" />
                            </div>
                          </div>
                          <div class="form-group field-label-display<?php echo $field_row; ?>">
                            <label class="col-sm-12 control-label"><?php echo $entry_label_display; ?></label>
                            <div class="col-sm-12">
                              <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default <?php echo !empty($field['label_display']) ? 'active' : ''; ?>">
                                  <input name="page_form_field[<?php echo $field_row; ?>][label_display]" <?php echo !empty($field['label_display']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                                </label>
                                <label class="btn btn-default <?php echo empty($field['label_display']) ? 'active' : ''; ?>">
                                  <input name="page_form_field[<?php echo $field_row; ?>][label_display]" <?php echo empty($field['label_display']) ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group field-label-align<?php echo $field_row; ?>">
                              <label class="col-sm-12 control-label"><?php echo $entry_label_align; ?></label>
                              <div class="col-sm-12">
                                <div class="btn-group" data-toggle="buttons">
                                  <label class="btn btn-default <?php echo $field['label_align'] == 'left' ? 'active' : ''; ?>">
                                    <input name="page_form_field[<?php echo $field_row; ?>][label_align]" <?php echo $field['label_align'] == 'left' ? 'checked="checked"' : ''; ?> autocomplete="off" value="left" type="radio"><?php echo $text_left; ?>
                                  </label>
                                  <label class="btn btn-default <?php echo $field['label_align'] == 'center' ? 'active' : ''; ?>">
                                    <input name="page_form_field[<?php echo $field_row; ?>][label_align]" <?php echo $field['label_align'] == 'center' ? 'checked="checked"' : ''; ?> autocomplete="off" value="center" type="radio"><?php echo $text_center; ?>
                                  </label>
                                  <label class="btn btn-default <?php echo $field['label_align'] == 'right' ? 'active' : ''; ?>">
                                    <input name="page_form_field[<?php echo $field_row; ?>][label_align]" <?php echo $field['label_align'] == 'right' ? 'checked="checked"' : ''; ?> autocomplete="off" value="right" type="radio"><?php echo $text_right; ?>
                                  </label>
                                </div>
                              </div>
                            </div>

                            <div class="form-group field-class<?php echo $field_row; ?>">
                              <label class="col-sm-12 control-label"><?php echo $entry_class; ?></label>
                              <div class="col-sm-12">
                                <input type="text" name="page_form_field[<?php echo $field_row; ?>][class]"  value="<?php echo $field['class']; ?>" class="form-control">
                              </div>
                            </div>
                        </fieldset>
                        <fieldset id="field-values<?php echo $field_row;  ?>">
                          <legend><i class="fa fa-list"></i> <?php echo $text_value_setting; ?></legend>
                          <table id="pageformoption-value<?php echo $field_row; ?>" class="table table-striped table-bordered table-hover">
                            <thead>
                              <tr>
                                <td class="text-left required" style="width: 40%;"><?php echo $entry_option_value; ?></td>
                                <td class="text-left" style="width: 10%;"><?php echo $entry_default_value; ?></td>
                                <td class="text-left" style="width: 10%;"><?php echo $entry_image; ?></td>
                                <td class="text-left" style="width: 30%;"><?php echo $entry_color; ?></td>
                                <td class="text-left" style="width: 10%;"><?php echo $entry_sort_order; ?></td>
                                <td class="text-right" style="width: 1px;"><?php echo $entry_action; ?></td>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if(!empty($field['option_value'])) { ?>
                              <?php foreach ($field['option_value'] as $page_form_option_value) { ?>
                              <tr id="pageformoption-value-row<?php echo $field_row; ?>-<?php echo $page_form_option_value_row; ?>">
                                <td class="text-left">
                                  <input type="hidden" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][page_form_option_value_id]" value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" />
                                  <?php foreach ($languages as $language) { ?>
                                  <div class="input-group"><span class="input-group-addon">
                                  <?php if(VERSION >= '2.2.0.0') { ?>
                                    <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                                    <?php } else{ ?>
                                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                                    <?php } ?></span>
                                    <input type="text" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][page_form_option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($page_form_option_value['page_form_option_value_description'][$language['language_id']]['name']) ? $page_form_option_value['page_form_option_value_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_option_value; ?>" class="form-control" />
                                    <?php if (isset($error_value_name[$field_row][$page_form_option_value_row][$language['language_id']])) { ?>
                                    <div class="text-danger"><?php echo $error_value_name[$field_row][$page_form_option_value_row][$language['language_id']]; ?></div>
                                    <?php } ?>
                                  </div>
                                  <?php } ?></td>
                                <td class="text-center">
                                  <?php if(!empty($page_form_option_value['default_value'])) { ?>
                                  <input type="checkbox" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][default_value]" value="1" checked="checked" />
                                  <?php } else { ?>
                                  <input type="checkbox" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][default_value]" value="1" />
                                  <?php } ?>
                                </td>
                                <td class="text-center">
                                  <a href="" id="thumb-valueimage<?php echo $field_row; ?>-<?php echo $page_form_option_value_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo isset($page_form_option_value['thumb']) ? $page_form_option_value['thumb'] : ''; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                  <input type="hidden" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][image]" value="<?php echo $page_form_option_value['image']; ?>" id="input-valueimage<?php echo $field_row; ?>-<?php echo $page_form_option_value_row; ?>" />
                                </td>
                                <td class="text-right"><input type="text" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][color]" value="<?php echo $page_form_option_value['color']; ?>" class="form-control color-picker" /><div class="preview"></div></td>

                                <td class="text-right"><input type="text" name="page_form_field[<?php echo $field_row; ?>][option_value][<?php echo $page_form_option_value_row; ?>][sort_order]" value="<?php echo $page_form_option_value['sort_order']; ?>" class="form-control" /></td>
                                <td class="text-right"><button type="button" onclick="$('#pageformoption-value-row<?php echo $field_row; ?>-<?php echo $page_form_option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>
                              </tr>
                              <?php $page_form_option_value_row++; ?>
                              <?php } ?>
                              <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><button type="button" onclick="addPageFormOptionValue('<?php echo $field_row; ?>');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn  btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>
                              </tr>
                            </tfoot>
                          </table>
                        </fieldset>
                      </div>
                      <?php $field_row++; ?>
                      <?php } ?>
                    </div>
                  </div>
                  <div id="exmple-table" class="exmple-table">
                    <a href="javascript:void(0)" class="closetablebtn" onclick="closeNav()">&times;</a>
                    <table class="table table-bordered table-responsive">
                      <thead><tr><td><?php echo $valid_field_type; ?></td><td><?php echo $valid_field_info; ?></td></tr></thead>
                      <thead><tr><td class="text-center" colspan="2"><?php echo $valid_select_type; ?></td></tr></thead>
                      <tbody>
                        <tr><td><?php echo $text_select; ?></td><td><?php echo $text_select_value; ?></td></tr>
                        <tr><td><?php echo $text_radio; ?></td><td><?php echo $text_radio_value; ?></td></tr>
                        <tr><td><?php echo $text_checkbox; ?></td><td><?php echo $text_checkbox_value; ?></td></tr>
                      </tbody>
                      <thead><tr><td class="text-center" colspan="2"><?php echo $valid_input_type; ?></td></tr></thead>
                      <tbody>
                        <tr><td><?php echo $text_text; ?></td><td><?php echo $text_text_value; ?></td></tr>
                        <tr><td><?php echo $text_textarea; ?></td><td><?php echo $text_textarea_value; ?></td></tr>
                        <tr><td><?php echo $text_number; ?></td><td><?php echo $text_number_value; ?></td></tr>
                        <tr><td><?php echo $text_telephone; ?></td><td><?php echo $text_telephone_value; ?></td></tr>
                        <tr><td><?php echo $text_firstname; ?></td><td><?php echo $text_firstname_value; ?></td></tr>
                        <tr><td><?php echo $text_lastname; ?></td><td><?php echo $text_lastname_value; ?></td></tr>
                        <tr><td><?php echo $text_email; ?></td><td><?php echo $text_email_value; ?></td></tr>
                        <tr><td><?php echo $text_email_exists; ?></td><td><?php echo $text_email_exists_value; ?></td></tr>
                        <tr><td><?php echo $text_password; ?></td><td><?php echo $text_password_value; ?></td></tr>
                        <tr><td><?php echo $text_confirm_password; ?></td><td><?php echo $text_confirm_value; ?></td></tr>
                      </tbody>
                      <thead><tr><td class="text-center" colspan="2"><?php echo $valid_file_type; ?></td></tr></thead>
                      <tbody>
                        <tr><td><?php echo $text_file; ?></td><td><?php echo $text_file_value; ?></td></tr>
                      </tbody>
                      <thead><tr><td class="text-center" colspan="2"><?php echo $valid_date_type; ?></td></tr></thead>
                      <tbody>
                        <tr><td><?php echo $text_date; ?></td><td><?php echo $text_date_value; ?></td></tr>
                        <tr><td><?php echo $text_time; ?></td><td><?php echo $text_time_value; ?></td></tr>
                        <tr><td><?php echo $text_datetime; ?></td><td><?php echo $text_datetime_value; ?></td></tr>
                      </tbody>
                      <thead><tr><td class="text-center" colspan="2"><?php echo $valid_localisation_type; ?></td></tr></thead>
                      <tbody>
                        <tr><td><?php echo $text_country; ?></td><td><?php echo $text_country_value; ?></td></tr>
                        <tr><td><?php echo $text_zone; ?></td><td><?php echo $text_zone_value; ?></td></tr>
                        <tr><td><?php echo $text_postcode; ?></td><td><?php echo $text_postcode_value; ?></td></tr>
                        <tr><td><?php echo $text_address; ?></td><td><?php echo $text_address_value; ?></td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-email">
              <ul class="nav nav-tabs" id="email">
                <li class="active"><a href="#tab-customer" data-toggle="tab"><i class="fa fa-users" aria-hidden="true"></i> <?php echo $tab_customer_email; ?></a></li>
                <li><a href="#tab-admin" data-toggle="tab"><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $tab_admin_email; ?></a></li>
              </ul>
              <div class="tab-content col-sm-9">
                <div class="tab-pane active" id="tab-customer">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_customer_email_status; ?>"><?php echo $entry_customer_email_status; ?></span></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default customer_email_status <?php echo $customer_email_status ? 'active' : ''; ?>">
                          <input name="customer_email_status" <?php echo $customer_email_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-default customer_email_status <?php echo !$customer_email_status ? 'active' : ''; ?>">
                          <input name="customer_email_status" <?php echo !$customer_email_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="customeremaillanguage-group">
                    <ul class="nav nav-tabs" id="customer-email-language">
                      <?php foreach ($languages as $language) { ?>
                      <li><a href="#customer-email-language<?php echo $language['language_id']; ?>" data-toggle="tab"><?php if(VERSION >= '2.2.0.0') { ?>
                      <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                      <?php } else{ ?>
                      <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                      <?php } ?> <?php echo $language['name']; ?></a></li>
                      <?php } ?>
                    </ul>
                    <div class="tab-content">
                      <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane" id="customer-email-language<?php echo $language['language_id']; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-customer-subject<?php echo $language['language_id']; ?>"><?php echo $entry_customer_subject; ?></label>
                          <div class="col-sm-10">
                            <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][customer_subject]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['customer_subject'] : ''; ?>" placeholder="<?php echo $entry_customer_subject; ?>" id="input-customer-subject<?php echo $language['language_id']; ?>" class="form-control" />
                            <?php if (isset($error_customer_subject[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_customer_subject[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-customer-message<?php echo $language['language_id']; ?>"><?php echo $entry_customer_message; ?></label>
                          <div class="col-sm-10">
                            <textarea name="page_form_description[<?php echo $language['language_id']; ?>][customer_message]" placeholder="<?php echo $entry_customer_message; ?>" id="input-customer-message<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang=""><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['customer_message'] : ''; ?></textarea>
                            <?php if (isset($error_customer_message[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_customer_message[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_field_attachment; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $customer_field_attachment ? 'active' : ''; ?>">
                          <input name="customer_field_attachment" <?php echo $customer_field_attachment ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-default <?php echo !$customer_field_attachment ? 'active' : ''; ?>">
                          <input name="customer_field_attachment" <?php echo !$customer_field_attachment ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="tab-admin">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_admin_email_status; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default admin_email_status <?php echo $admin_email_status ? 'active' : ''; ?>">
                          <input name="admin_email_status" <?php echo $admin_email_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-default admin_email_status <?php echo !$admin_email_status ? 'active' : ''; ?>">
                          <input name="admin_email_status" <?php echo !$admin_email_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="adminemaillanguage-group">
                    <div class="form-group required">
                      <label class="col-sm-2 control-label"><?php echo $entry_admin_email; ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="admin_email" value="<?php echo $admin_email; ?>" class="form-control">
                        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $alert_admin_email; ?></div>
                        <?php if ($error_admin_email) { ?>
                        <div class="text-danger"><?php echo $error_admin_email; ?></div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="input-mail-alert-email"><span data-toggle="tooltip" title="<?php echo $help_mail_alert_email; ?>"><?php echo $entry_mail_alert_email; ?></span></label>
                      <div class="col-sm-10">
                        <div class="input-group" style="width: 100%">
                          <span class="input-group-btn" style="width: 99px;">
                            <div class="btn-group" data-toggle="buttons" style="margin-right: 0;">
                              <label class="btn btn-default mail_alert_email_status <?php echo $mail_alert_email_status ? 'active' : ''; ?>" style="line-height: 50px;">
                                <input name="mail_alert_email_status" <?php echo $mail_alert_email_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                              </label>
                              <label class="btn btn-default mail_alert_email_status <?php echo !$mail_alert_email_status ? 'active' : ''; ?>" style="line-height: 50px;">
                                <input name="mail_alert_email_status" <?php echo !$mail_alert_email_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                              </label>
                            </div>
                          </span>
                          <textarea name="mail_alert_email" rows="2" placeholder="<?php echo $entry_mail_alert_email; ?>" id="input-alert-email" class="form-control"><?php echo $mail_alert_email; ?></textarea>
                        </div>
                      </div>
                    </div>
                    <ul class="nav nav-tabs" id="admin-email-language">
                      <?php foreach ($languages as $language) { ?>
                      <li><a href="#admin-email-language<?php echo $language['language_id']; ?>" data-toggle="tab"><?php if(VERSION >= '2.2.0.0') { ?>
                      <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                      <?php } else{ ?>
                      <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                      <?php } ?> <?php echo $language['name']; ?></a></li>
                      <?php } ?>
                    </ul>
                    <div class="tab-content">
                      <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane" id="admin-email-language<?php echo $language['language_id']; ?>">
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-admin-subject<?php echo $language['language_id']; ?>"><?php echo $entry_admin_subject; ?></label>
                          <div class="col-sm-10">
                            <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][admin_subject]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['admin_subject'] : ''; ?>" placeholder="<?php echo $entry_admin_subject; ?>" id="input-admin-subject<?php echo $language['language_id']; ?>" class="form-control" />
                            <?php if (isset($error_admin_subject[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_admin_subject[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-admin-message<?php echo $language['language_id']; ?>"><?php echo $entry_admin_message; ?></label>
                          <div class="col-sm-10">
                            <textarea name="page_form_description[<?php echo $language['language_id']; ?>][admin_message]" placeholder="<?php echo $entry_admin_message; ?>" id="input-admin-message<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang=""><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['admin_message'] : ''; ?></textarea>
                            <?php if (isset($error_admin_message[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_admin_message[$language['language_id']]; ?></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_field_attachment; ?></label>
                    <div class="col-sm-10">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $admin_field_attachment ? 'active' : ''; ?>">
                          <input name="admin_field_attachment" <?php echo $admin_field_attachment ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                        </label>
                        <label class="btn btn-default <?php echo !$admin_field_attachment ? 'active' : ''; ?>">
                          <input name="admin_field_attachment" <?php echo !$admin_field_attachment ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-3">
                <br/>
                <table class="table table-bordered">
                  <thead>
                    <tr><td><?php echo $const_names; ?></td><td><?php echo $const_short_codes; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?php echo $const_logo; ?></td><td>{LOGO}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_store_name; ?></td><td>{STORE_NAME}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_store_link; ?></td><td>{STORE_LINK}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_product_id; ?></td><td>{PRODUCT_ID}</td>
                    </tr>
                    <tr>
                      <td><?php echo $const_product_name; ?></td><td>{PRODUCT_NAME}</td>
                    </tr>
                    <tr>

                      <td><?php echo $const_product_model; ?></td><td>{PRODUCT_MODEL}</td>

                    </tr>
                    <tr>
                      <td><?php echo $const_product_link; ?></td><td>{PRODUCT_LINK}</td>
                    </tr>
                    <tr>

                      <td><?php echo $const_product_image; ?></td><td>{PRODUCT_IMAGE}</td>

                    </tr>
                    <tr>
                      <td><?php echo $const_name; ?></td><td>{INFORMATION}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-success-page">
              <ul class="nav nav-tabs" id="success-language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#success-language<?php echo $language['language_id']; ?>" data-toggle="tab"><?php if(VERSION >= '2.2.0.0') { ?>
                    <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                    <?php } else{ ?>
                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                    <?php } ?> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="success-language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-success-title<?php echo $language['language_id']; ?>"><?php echo $entry_success_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="page_form_description[<?php echo $language['language_id']; ?>][success_title]" value="<?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['success_title'] : ''; ?>" placeholder="<?php echo $entry_success_title; ?>" id="input-success-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_success_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_success_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-success-description<?php echo $language['language_id']; ?>"><?php echo $entry_success_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="page_form_description[<?php echo $language['language_id']; ?>][success_description]" placeholder="<?php echo $entry_success_description; ?>" id="input-success-description<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang=""><?php echo isset($page_form_description[$language['language_id']]) ? $page_form_description[$language['language_id']]['success_description'] : ''; ?></textarea>
                      <?php if (isset($error_success_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_success_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-seo">
              <?php if(VERSION <= '2.3.0.2') { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } else { ?>
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_keyword; ?></div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_keyword; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($stores as $store) { ?>
                  <tr>
                    <td class="text-left"><?php echo $store['name']; ?></td>
                    <td class="text-left">
                      <?php foreach($languages as $language) { ?>
                      <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="page_form_seo_url[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo isset($page_form_seo_url[$store['store_id']][$language['language_id']]) ? $page_form_seo_url[$store['store_id']][$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_keyword; ?>" class="form-control" />
                      </div>
                      <?php if(isset($error_keyword[$store['store_id']][$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_keyword[$store['store_id']][$language['language_id']]; ?></div>
                      <?php } ?>
                      <?php } ?>
                    </td>
                  </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
              <?php } ?>
            </div>
            <div class="tab-pane" id="tab-css">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $tab_css; ?></label>
                <div class="col-sm-10">
                  <textarea style="height: 250px;" name="css" class="form-control"><?php echo $css; ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function openNav() {
    document.getElementById("exmple-table").style.width = "300px";
    document.getElementById("maincontent").style.marginRight = "300px";
  }

  /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
  function closeNav() {
    document.getElementById("exmple-table").style.width = "0";
    document.getElementById("maincontent").style.marginRight = "0";
  }
</script>
<script type="text/javascript">
$('.producttype').click(function() {
  var producttype = $(this).find('input').val();

  if(producttype == 'no') {
    $('.product-group').addClass('hide');
    $('.pbutton_title').addClass('hide');
  } else if(producttype == 'all') {
    $('.product-group').addClass('hide');
    $('.pbutton_title').removeClass('hide');
  } else if(producttype == 'choose') {
    $('.product-group').removeClass('hide');
    $('.pbutton_title').removeClass('hide');
  }
});

$('input[name=\'product\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&<?php echo $module_token; ?>=<?php echo $ci_token; ?>&filter_name='+  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
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
    $('input[name=\'product\']').val('');

    $('#formbuilder-product'+ item['value']).remove();

    $('#formbuilder-product').append('<div id="formbuilder-product'+ item['value'] +'"><i class="fa fa-minus-circle"></i> '+ item['label'] +'<input type="hidden" name="page_form_product[]" value="'+ item['value'] +'" /></div>');
  }
});

$('#formbuilder-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
</script>
  <script type="text/javascript"><!--
  $('#language a:first').tab('show');
  $('#customer-email-language a:first').tab('show');
  $('#admin-email-language a:first').tab('show');
  $('#success-language a:first').tab('show');

  $('#email a:first').tab('show');

  $('#pageformfields li:first-child a').tab('show');
  <?php foreach($fields as $key => $field) { ?>
  $('#field-language<?php echo $key; ?> li:first-child a').tab('show');
  <?php } ?>
  //--></script>
  <script type="text/javascript"><!--
  $('.admin_email_status').click(function() {
    if($(this).find('input').val() == 1) {
      $('.adminemaillanguage-group').slideDown(300);
    }else{
      $('.adminemaillanguage-group').slideUp(300);
    }
  });
  $('input[name=\'admin_email_status\']:checked').trigger('click');

  $('.customer_email_status').click(function() {
    if($(this).find('input').val() == 1) {
      $('.customeremaillanguage-group').slideDown(300);
    }else{
      $('.customeremaillanguage-group').slideUp(300);
    }
  });
  $('input[name=\'customer_email_status\']:checked').trigger('click');
  //--></script>

<script type="text/javascript"><!--
$('#pageformfields a:first').tab('show');

var field_row = <?php echo $field_row; ?>;

function addField() {
  html = '<div class="tab-pane" id="tab-field'+ field_row +'">';

    html += '<fieldset>';
      html += '<legend><i class="fa fa-cogs"></i> <?php echo $text_type_setting ?></legend>';
      html += '<div class="form-group">';
        html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>';
        html += '<div class="col-sm-12">';
          html += '<select name="page_form_field['+ field_row +'][type]" class="form-control field-type" rel="'+ field_row +'">';
            html += '<optgroup label="<?php echo $text_choose; ?>">';
            html += '<option value="select"><?php echo $text_select; ?></option>';
            html += '<option value="multi_select"><?php echo $text_multi_select; ?></option>';
            html += '<option value="radio"><?php echo $text_radio; ?></option>';
            html += '<option value="radio_toggle"><?php echo $text_radio_toggle; ?></option>';
            html += '<option value="checkbox"><?php echo $text_checkbox; ?></option>';
            html += '<option value="checkbox_switch"><?php echo $text_checkbox_switch; ?></option>';
            html += '<option value="checkbox_toggle"><?php echo $text_checkbox_toggle; ?></option>';

            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_input; ?>">';
            html += '<option value="text" selected="selected"><?php echo $text_text; ?></option>';
            html += '<option value="multiple_text"><?php echo $text_multiple_text; ?></option>';
            html += '<option value="textarea"><?php echo $text_textarea; ?></option>';
            html += '<option value="number"><?php echo $text_number; ?></option>';
            html += '<option value="telephone"><?php echo $text_telephone; ?></option>';
            html += '<option value="firstname"><?php echo $text_firstname; ?></option>';
            html += '<option value="lastname"><?php echo $text_lastname; ?></option>';
            html += '<option value="email"><?php echo $text_email; ?></option>';
            html += '<option value="email_exists"><?php echo $text_email_exists; ?></option>';
            html += '<option value="password"><?php echo $text_password; ?></option>';
            html += '<option value="confirm_password"><?php echo $text_confirm_password; ?></option>';
            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_file; ?>">';
            html += '<option value="file"><?php echo $text_file; ?></option>';
            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_date; ?>">';
            html += '<option value="date"><?php echo $text_date; ?></option>';
            html += '<option value="time"><?php echo $text_time; ?></option>';
            html += '<option value="datetime"><?php echo $text_datetime; ?></option>';
            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_localisation; ?>">';
            html += '<option value="country"><?php echo $text_country; ?></option>';
            html += '<option value="zone"><?php echo $text_zone; ?></option>';
            html += '<option value="postcode"><?php echo $text_postcode; ?></option>';
            html += '<option value="address"><?php echo $text_address; ?></option>';
            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_header_type; ?>">';
            html += '<option value="header"><?php echo $text_header; ?></option>';
            html += '<option value="paragraph"><?php echo $text_paragraph; ?></option>';
            html += '<option value="hrline"><?php echo $text_hrline; ?></option>';
            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_google_map; ?>">';
            html += '<option value="google_map"><?php echo $text_google_map; ?></option>';
            html += '</optgroup>';
            html += '<optgroup label="<?php echo $text_display_message; ?>">';
            html += '<option value="display_message"><?php echo $text_display_message; ?></option>';
            html += '</optgroup>';
          html += '</select>';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group field-number-input'+ field_row +'">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_number_input; ?></label>';
        html += '<div class="col-sm-4">';
          html += '<input type="number" name="page_form_field['+ field_row +'][number_input]" value="2" class="form-control">';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group" id="field-file'+ field_row +'">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_file_limit; ?></label>';
        html += '<div class="col-sm-4">';
          html += '<input type="number" name="page_form_field['+ field_row +'][file_limit]"  value="1" class="form-control" />';
        html += '</div>';
      html += '</div>';

    html += '</fieldset>';

    html += '<fieldset>';
      html += '<legend><i class="fa fa-language"></i> <?php echo $text_lang_setting ?></legend>';
      html += '<input type="hidden" name="page_form_field['+ field_row +'][page_form_option_id]" value="" />';
      html += '<div class="field-group">';
        html += '<ul class="nav nav-tabs" id="field-language'+ field_row +'">';
          <?php foreach ($languages as $language) { ?>
          html += '<li><a href="#field-language'+ field_row +'-<?php echo $language['language_id']; ?>" data-toggle="tab">';
          <?php if(VERSION >= '2.2.0.0') { ?>
          html += '<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />';
          <?php } else{ ?>
          html += '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
          <?php } ?>
         html += ' <?php echo $language['name']; ?></a></li>';
          <?php } ?>
        html += '</ul>';
        html += '<div class="tab-content">';
          <?php foreach ($languages as $language) { ?>
          html += '<div class="tab-pane" id="field-language'+ field_row +'-<?php echo $language['language_id']; ?>">';
            html += '<div class="form-group required">';
              html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_name; ?>"><?php echo $entry_field_name; ?></span></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][field_name]" value="" placeholder="<?php echo $entry_field_name; ?>" class="form-control" />';
              html += '</div>';
            html += '</div>';
            html += '<div class="form-group field-myhelp'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_help; ?>"><?php echo $entry_field_help; ?></span></label>';
              html += '<div class="col-sm-12">';
                html += '<textarea name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][field_help]" class="form-control"></textarea>';
              html += '</div>';
            html += '</div>';
            html += '<div class="form-group field-placeholder'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_placeholder; ?>"><?php echo $entry_field_placeholder; ?></span></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][field_placeholder]" value="" placeholder="<?php echo $entry_field_placeholder; ?>" class="form-control" />';
              html += '</div>';
            html += '</div>';
            html += '<div class="form-group field-dvalue'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_dvalue; ?>"><?php echo $entry_field_dvalue; ?></span></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][field_dvalue]" value="" placeholder="<?php echo $entry_field_dvalue; ?>" class="form-control" />';
              html += '</div>';
            html += '</div>';
            html += '<div class="form-group field-groupbtn'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_input_group_button_text; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][input_group_button_text]" value="" placeholder="<?php echo $entry_input_group_button_text; ?>" class="form-control" />';
              html += '</div>';
            html += '</div>';
            html += '<div class="form-group field-display-message'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_field_display_message; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<textarea name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][field_display_message]" class="form-control summernote" data-toggle="summernote" data-lang="" id="input-display-message'+ field_row +'-<?php echo $language['language_id']; ?>"></textarea>';
              html += '</div>';
            html += '</div>';

            html += '<div class="form-group field-error'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_field_error; ?>"><?php echo $entry_field_error; ?></span></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][description][<?php echo $language['language_id']; ?>][field_error]" value="" placeholder="<?php echo $entry_field_error; ?>" class="form-control" />';
              html += '</div>';
            html += '</div>';

          html += '</div>';
          <?php } ?>
        html += '</div>';
      html += '</fieldset>';
      html += '<fieldset class="field-image'+ field_row +'">';
        html += '<legend><i class="fa fa-image"></i> <?php echo $text_image_setting ?></legend>';

        html += '<div class="row">';
          html += '<div class="col-sm-6">';
            html += '<div class="form-group field-thumb-type'+ field_row +'">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_thumb_type; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<div class="btn-group" data-toggle="buttons">';
                  html += '<label class="thumb_type btn btn-default" rel="'+ field_row +'">';
                    html += '<input name="page_form_field['+ field_row +'][thumb_type]" autocomplete="off" value="image" type="radio"><?php echo $text_image; ?>';
                  html += '</label>';
                  html += '<label class="thumb_type btn btn-default" rel="'+ field_row +'">';
                    html += '<input name="page_form_field['+ field_row +'][thumb_type]" autocomplete="off" value="icon" type="radio"><?php echo $text_icon; ?>';
                  html += '</label>';
                  html += '<label class="thumb_type btn btn-default active" rel="'+ field_row +'">';
                    html += '<input name="page_form_field['+ field_row +'][thumb_type]" autocomplete="off" value="none" type="radio" checked="checked"><?php echo $text_none; ?>';
                  html += '</label>';
                html += '</div>';
              html += '</div>';
            html += '</div>';
          html += '</div>';
          html += '<div class="col-sm-6 image-align-group" style="display: none;">';
            html += '<div class="form-group">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_image_align; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<div class="btn-group" data-toggle="buttons">';
                  html += '<label class="btn btn-default active">';
                    html += '<input name="page_form_field['+ field_row +'][image_align]" checked="checked" autocomplete="off" value="left" type="radio"><?php echo $text_left; ?>';
                  html += '</label>';
                  html += '<label class="btn btn-default">';
                    html += '<input name="page_form_field['+ field_row +'][image_align]" autocomplete="off" value="center" type="radio"><?php echo $text_center; ?>';
                  html += '</label>';
                  html += '<label class="btn btn-default">';
                    html += '<input name="page_form_field['+ field_row +'][image_align]" autocomplete="off" value="right" type="radio"><?php echo $text_right; ?>';
                  html += '</label>';
                html += '</div>';
              html += '</div>';
            html += '</div>';
          html += '</div>';
        html += '</div>';

        html += '<div class="row image-group" style="display: none;">';
          html += '<div class="col-sm-4">';
            html += '<div class="form-group">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_image; ?></label>';
              html += '<div class="col-sm-12"><a href="" id="thumb-image'+ field_row +'" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>';
                html += '<input type="hidden" name="page_form_field['+ field_row +'][image]" value="" id="input-image'+ field_row +'" />';
              html += '</div>';
            html += '</div>';
          html += '</div>';
          html += '<div class="col-sm-4">';
            html += '<div class="form-group">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_image_width; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][image_width]" value="70" class="form-control" />';
              html += '</div>';
            html += '</div>';
          html += '</div>';
          html += '<div class="col-sm-4">';
            html += '<div class="form-group">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_image_height; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][image_height]" value="70" class="form-control" />';
              html += '</div>';
            html += '</div>';
          html += '</div>';
        html += '</div>';

        html += '<div class="row icon-group" style="display: none;">';
          html += '<div class="col-sm-6">';
            html += '<div class="form-group">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_icon_class; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<input type="text" name="page_form_field['+ field_row +'][icon_class]" value="" class="form-control" />';
              html += '</div>';
            html += '</div>';
          html += '</div>';
          html += '<div class="col-sm-6">';
            html += '<div class="form-group">';
              html += '<label class="col-sm-12 control-label"><?php echo $entry_icon_size; ?></label>';
              html += '<div class="col-sm-12">';
                html += '<div class="input-group">';
                  html += '<input type="text" name="page_form_field['+ field_row +'][icon_size]" value="70" class="form-control" />';
                  html += '<span class="input-group-btn"><button type="button" class="btn">PX</button></span>';
                html += '</div>';
              html += '</div>';
            html += '</div>';
          html += '</div>';
        html += '</div>';

      html += '</fieldset>';
      html += '<fieldset class="field-autofill'+ field_row +'">';
        html += '<legend><i class="fa fa-user"></i> <?php echo $text_dynamic_setting ?></legend>';

        html += '<div class="form-group">';
          html += '<label class="col-sm-12 control-label"><?php echo $entry_auto_fill_value; ?></label>';
          html += '<div class="col-sm-12">';
            html += '<div class="btn-group" data-toggle="buttons">';
              <?php foreach($auto_fill_values as $auto_fill_value) { ?>
              <?php if($auto_fill_value['value'] == '') { ?>
              html += '<label class="btn btn-default active">';
              html += '<input name="page_form_field['+ field_row +'][auto_fill_value]" checked="checked" autocomplete="off" value="<?php echo $auto_fill_value['value']; ?>" type="radio"><?php echo $auto_fill_value['text']; ?>';
              html += '</label>';
              <?php } else { ?>
              html += '<label class="btn btn-default">';
              html += '<input name="page_form_field['+ field_row +'][auto_fill_value]" autocomplete="off" value="<?php echo $auto_fill_value['value']; ?>" type="radio"><?php echo $auto_fill_value['text']; ?>';
              html += '</label>';
              <?php } ?>
              <?php } ?>
            html += '</div>';
          html += '</div>';
        html += '</div>';

    html += '</fieldset>';
    html += '<fieldset>';
      html += '<legend><i class="fa fa-cogs"></i> <?php echo $text_required_setting ?></legend>';

      html += '<div class="form-group">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_field_status; ?></label>';
        html += '<div class="col-sm-12">';
          html += '<div class="btn-group" data-toggle="buttons">';
            html += '<label class="btn btn-default active">';
            html += '<input name="page_form_field['+ field_row +'][status]" checked="checked" autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>';
            html += '</label>';
            html += '<label class="btn btn-default">';
            html += '<input name="page_form_field['+ field_row +'][status]" autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>';
            html += '</label>';
          html += '</div>';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group field-required'+ field_row +'">';
        html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_required; ?>"><?php echo $entry_required; ?></span></label>';
        html += '<div class="col-sm-12">';
          html += '<div class="btn-group" data-toggle="buttons">';
            html += '<label data-val="1" rel="'+ field_row +'" class="field-required btn btn-default active">';
            html += '<input name="page_form_field['+ field_row +'][required]" checked="checked" autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>';
            html += '</label>';
            html += '<label data-val="0" rel="'+ field_row +'" class="field-required btn btn-default">';
            html += '<input name="page_form_field['+ field_row +'][required]" autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>';
            html += '</label>';
          html += '</div>';
        html += '</div>';
      html += '</div>';
      html += '<div class="form-group field-width'+ field_row +'">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_width; ?></label>';
        html += '<div class="col-sm-12">';
          html += '<div class="btn-group" data-toggle="buttons">';
            <?php foreach($set_widths as $set_width) { ?>
            <?php if($set_width['value'] == 12) { ?>
            html += '<label class="btn btn-default active">';
            html += '<input name="page_form_field['+ field_row +'][width]" checked="checked" autocomplete="off" value="<?php echo $set_width['value']; ?>" type="radio"><?php echo $set_width['text']; ?>';
            html += '</label>';
            <?php } else { ?>
            html += '<label class="btn btn-default">';
            html += '<input name="page_form_field['+ field_row +'][width]" autocomplete="off" value="<?php echo $set_width['value']; ?>" type="radio"><?php echo $set_width['text']; ?>';
            html += '</label>';
            <?php } ?>
            <?php } ?>
          html += '</div>';
        html += '</div>';
      html += '</div>';
      html += '<div class="form-group hide">';
        html += '<label class="col-sm-12 control-label"><span data-toggle="tooltip" title="<?php echo $help_sort_order; ?>"><?php echo $entry_sort_order; ?></span></label>';
        html += '<div class="col-sm-12">';
          html += '<input type="text" name="page_form_field['+ field_row +'][sort_order]"  value="'+ (field_row + parseInt(1)) +'" class="form-control field-sortorder" />';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group field-label-display<?php echo $field_row; ?>">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_label_display; ?></label>';
        html += '<div class="col-sm-12">';
          html += '<div class="btn-group" data-toggle="buttons">';
            html += '<label class="btn btn-default active">';
            html += '<input name="page_form_field['+ field_row +'][label_display]" checked="checked" autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>';
            html += '</label>';
            html += '<label class="btn btn-default">';
            html += '<input name="page_form_field['+ field_row +'][label_display]" autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>';
            html += '</label>';
          html += '</div>';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group field-label-align'+ field_row +'">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_label_align; ?></label>';
        html += '<div class="col-sm-12">';
          html += '<div class="btn-group" data-toggle="buttons">';
            html += '<label class="btn btn-default active">';
              html += '<input name="page_form_field['+ field_row +'][label_align]" checked="checked" autocomplete="off" value="left" type="radio"><?php echo $text_left; ?>';
            html += '</label>';
            html += '<label class="btn btn-default">';
              html += '<input name="page_form_field['+ field_row +'][label_align]" autocomplete="off" value="center" type="radio"><?php echo $text_center; ?>';
            html += '</label>';
            html += '<label class="btn btn-default">';
              html += '<input name="page_form_field['+ field_row +'][label_align]" autocomplete="off" value="right" type="radio"><?php echo $text_right; ?>';
            html += '</label>';
          html += '</div>';
        html += '</div>';
      html += '</div>';

      html += '<div class="form-group field-class'+ field_row +'">';
        html += '<label class="col-sm-12 control-label"><?php echo $entry_class; ?></label>';
        html += '<div class="col-sm-12">';
          html += '<input type="text" name="page_form_field['+ field_row +'][class]"  value="" class="form-control" />';
        html += '</div>';
      html += '</div>';

    html += '</fieldset>';
    html += '<fieldset id="field-values'+ field_row +'">';
      html += '<legend><i class="fa fa-list"></i> <?php echo $text_value_setting; ?></legend>';
        html += '<table id="pageformoption-value'+ field_row +'" class="table table-striped table-bordered table-hover">';
          html += '<thead>';
            html += '<tr>';
              html += '<td class="text-left required" style="width: 40%;"><?php echo $entry_option_value; ?></td>';
              html += '<td class="text-left" style="width: 10%;"><?php echo $entry_default_value; ?></td>';
              html += '<td class="text-left" style="width: 10%;"><?php echo $entry_image; ?></td>';
              html += '<td class="text-left" style="width: 30%;"><?php echo $entry_color; ?></td>';
              html += '<td class="text-left" style="width: 10%;"><?php echo $entry_sort_order; ?></td>';
              html += '<td class="text-right" style="width: 1px;"><?php echo $entry_action; ?></td>';
            html += '</tr>';
          html += '</thead>';
          html += '<tbody>';
          html += '</tbody>';
          html += '<tfoot>';
            html += '<tr>';
              html += '<td colspan="5"></td>';
              html += '<td class="text-right"><button type="button" onclick="addPageFormOptionValue('+ field_row +');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>';
            html += '</tr>';
          html += '</tfoot>';
        html += '</table>';
    html += '</fieldset>';
  html += '</div>';

  $('#tab-fields #tab-content').append(html);

  $('#pageformfields').append('<li class="pageformfields-li"><a href="#tab-field'+ field_row +'" data-toggle="tab"><i class="fa fa-minus-circle" onclick=" $(\'#pageformfields a:first\').tab(\'show\');$(\'a[href=\\\'#tab-field'+ field_row +'\\\']\').parent().remove(); $(\'#tab-field'+ field_row +'\').remove();"></i> <?php echo $tab_field; ?>-'+ (field_row + parseInt(1))  +' <i class="fa fa-arrows pull-right" aria-hidden="true"></i></a></li>');

  $('#pageformfields a[href=\'#tab-field'+ field_row +'\']').tab('show');

  $('#field-language'+ field_row +' a:first').tab('show');

  $('[data-toggle=\'tooltip\']').tooltip({
    container: 'body',
    html: true
  });

  $('select[name=\'page_form_field['+ field_row +'][type]\'].field-type').trigger('change');

  <?php foreach ($languages as $language) { ?>
  $('#input-display-message' + field_row + '-<?php echo $language['language_id']; ?>').summernote({ height: 300 });
  <?php } ?>

  field_row++;
}
//--></script>
<script type="text/javascript"><!--
$('select[name=\'type\']').on('change', function() {
  if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'radio_togle') {
    $('#pageoption-value').show();
  } else {
    $('#pageoption-value').hide();
  }
});

$('select[name=\'type\']').trigger('change');

var page_form_option_value_row = '<?php echo (isset($page_form_option_value_row) ? $page_form_option_value_row : 0); ?>';

function addPageFormOptionValue(field_row) {
  html  = '<tr id="pageformoption-value-row'+ field_row +'-'+ page_form_option_value_row +'">';
    html += '  <td class="text-left"><input type="hidden" name="page_form_field['+ field_row +'][option_value]['+ page_form_option_value_row +'][page_form_option_value_id]" value="" />';
  <?php foreach ($languages as $language) { ?>
  html += '    <div class="input-group">';
  html += '      <span class="input-group-addon">';
  <?php if(VERSION >= '2.2.0.0') { ?>
  html += '<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />';
  <?php } else{ ?>
  html += '<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
  <?php } ?>
  html += '</span><input type="text" name="page_form_field['+ field_row +'][option_value]['+ page_form_option_value_row +'][page_form_option_value_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_option_value; ?>" class="form-control" />';
    html += '    </div>';
  <?php } ?>
  html += '  </td>';
  html += '  <td class="text-center"><input type="checkbox" name="page_form_field['+ field_row +'][option_value]['+ page_form_option_value_row +'][default_value]" value="1" /></td>';
  html += '  <td class="text-center"><a href="" id="thumb-valueimage'+ field_row +'-'+ page_form_option_value_row +'" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="page_form_field['+ field_row +'][option_value]['+ page_form_option_value_row +'][image]" value="" id="input-valueimage'+ field_row +'-'+ page_form_option_value_row +'" /></td>';

  html += '  <td class="text-right"><input type="text" name="page_form_field['+ field_row +'][option_value]['+ page_form_option_value_row +'][color]" value="" class="form-control color-picker" /><div class="preview"></div></td>';

  html += '  <td class="text-right"><input type="text" name="page_form_field['+ field_row +'][option_value]['+ page_form_option_value_row +'][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '  <td class="text-right"><button type="button" onclick="$(\'#pageformoption-value-row'+ field_row +'-'+ page_form_option_value_row +'\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#pageformoption-value'+ field_row +' tbody').append(html);

  setColorPicker('#pageformoption-value-row'+ field_row +'-'+ page_form_option_value_row +' .color-picker');

  page_form_option_value_row++;
}
//--></script>
<script type="text/javascript"><!--
$(document).delegate('.field-type', 'change', function() {
  var rel = $(this).attr('rel');

  if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'radio_toggle' || this.value == 'checkbox_switch' || this.value == 'checkbox_toggle' || this.value == 'multi_select') {
    $('#field-values'+ rel).show();
  } else {
    $('#field-values'+ rel).hide();
    $('#field-values'+ rel +' tbody').html('');
  }

  if (this.value == 'firstname' || this.value == 'lastname' || this.value == 'text' || this.value == 'textarea' || this.value == 'number' || this.value == 'telephone' || this.value == 'email' || this.value == 'email_exists' || this.value == 'date' || this.value == 'time' || this.value == 'datetime' || this.value == 'postcode' || this.value == 'address' || this.value == 'multiple_text') {
    $('.field-placeholder'+ rel).show();
    $('.field-dvalue'+ rel).show();

  } else {
    $('.field-placeholder'+ rel).hide();
    $('.field-dvalue'+ rel).hide();
  }

  if (this.value == 'file') {
    $('#field-file'+ rel).show();
  } else {
    $('#field-file'+ rel).hide();
  }

  if (this.value == 'header' || this.value == 'paragraph' || this.value == 'hrline') {
    $('.field-myhelp'+ rel).hide();
    $('.field-required'+ rel).addClass('hide');
    $('.field-width'+ rel).hide();
    $('.field-error'+ rel).hide();
    $('.field-image'+ rel).hide();

    $('.field-myhelp'+ rel +' label span').text('<?php echo $entry_field_help; ?>').attr('data-original-title', '<?php echo $help_field_help; ?>');
  } else if (this.value == 'google_map') {
    $('.field-myhelp'+ rel).show();
    $('.field-required'+ rel).addClass('hide');
    $('.field-width'+ rel).show();
    $('.field-error'+ rel).hide();
    $('.field-image'+ rel).show();

    $('.field-myhelp'+ rel +' label span').text('<?php echo $entry_map; ?>').attr('data-original-title', '<?php echo $help_field_map; ?>'); $('.field-myhelp'+ rel +' textarea').attr('rows', '5');
  } else {
    $('.field-myhelp'+ rel).show();
    $('.field-required'+ rel).removeClass('hide');
    $('.field-width'+ rel).show();
    $('.field-error'+ rel).show();
    $('.field-image'+ rel).show();

    $('.field-myhelp'+ rel +' label span').text('<?php echo $entry_field_help; ?>').attr('data-original-title', '<?php echo $help_field_help; ?>');
  }

  $('.field-display-message'+ rel).hide();
  $('.field-autofill'+ rel).hide();
  $('.field-groupbtn'+ rel).hide();


  if (this.value == 'display_message') {
    $('.field-display-message'+ rel).show();
    $('.field-error'+ rel).hide();
  }

  if (this.value == 'firstname' || this.value == 'lastname' || this.value == 'text' || this.value == 'textarea' || this.value == 'telephone' || this.value == 'email' || this.value == 'email_exists' || this.value == 'postcode' || this.value == 'address' || this.value == 'country' || this.value == 'zone') {
    $('.field-autofill'+ rel).show();
  }

  if (this.value == 'firstname' || this.value == 'lastname' || this.value == 'text' || this.value == 'telephone' || this.value == 'email' || this.value == 'email_exists' || this.value == 'postcode' || this.value == 'address') {
    $('.field-groupbtn'+ rel).show();
  }

  $('.field-number-input'+ rel).hide();
  if (this.value == 'multiple_text') {
    $('.field-number-input'+ rel).show();
  }

  $('.field-placeholder'+ rel +' .multiple_text_placeholder').remove();
  if (this.value == 'multiple_text') {
    $('.field-placeholder'+ rel +' input').after('<div class="alert alert-info multiple_text_placeholder"><i class="fa fa-info-circle"></i> <?php echo $help_multiple_text_placeholder; ?></div>');
  }


  $('.field-label-display'+ rel).show();
  $('.field-label-align'+ rel).show();

  if(this.value == 'header' || this.value == 'paragraph') {
    $('.field-label-display'+ rel).hide();
  }

  if(this.value == 'hrline') {
    $('.field-label-display'+ rel).hide();
    $('.field-label-align'+ rel).hide();
  }
});
$('.field-type').trigger('change');


$(document).delegate('.thumb_type', 'click', function() {
  var rel = $(this).attr('rel');
  if ($(this).find('input').val() == 'image') {
    $('.field-image'+ rel + ' .image-group').show();
    $('.field-image'+ rel + ' .icon-group').hide();

    $('.field-image'+ rel + ' .image-align-group').show();
  } else if ($(this).find('input').val() == 'icon') {
    $('.field-image'+ rel + ' .image-group').hide();
    $('.field-image'+ rel + ' .icon-group').show();

    $('.field-image'+ rel + ' .image-align-group').show();
  } else {
    $('.field-image'+ rel + ' .image-group').hide();
    $('.field-image'+ rel + ' .icon-group').hide();

    $('.field-image'+ rel + ' .image-align-group').hide();
  }
});
$('.thumb_type.active').trigger('click');

<?php if(empty($fields)) { ?>
  addField();
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
  $("#pageformfields").sortable({
    cursor: "move",
    stop: function() {
      $('#pageformfields .pageformfields-li').each(function() {
        $($(this).find('a').attr('href')).find('.field-sortorder').val(($(this).index() + 1));
      });
    }
  });
});
//--></script>
<?php if(VERSION <= '2.2.0.0') { ?>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-bottom-description<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-customer-message<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-admin-message<?php echo $language['language_id']; ?>').summernote({ height: 300 });
$('#input-success-description<?php echo $language['language_id']; ?>').summernote({ height: 300 });
<?php } ?>
//--></script>
<?php } else if(VERSION <= '2.3.0.2') { ?>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php } else if(VERSION >= '3.0.0.0') { ?>
<link href="view/javascript/codemirror/lib/codemirror.css" rel="stylesheet" />
<link href="view/javascript/codemirror/theme/monokai.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/codemirror/lib/codemirror.js"></script>
<script type="text/javascript" src="view/javascript/codemirror/lib/xml.js"></script>
<script type="text/javascript" src="view/javascript/codemirror/lib/formatting.js"></script>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/summernote-image-attributes.js"></script>
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php } ?>

<script type="text/javascript">
/* Color Picker */
function setColorPicker(color_var) {
  var element = null;
  $(color_var).ColorPicker({
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
      element.curr.parent().find('.preview').css('background', '#' + hex);
      element.curr.val('#'+hex);
    }
  }).bind('keyup', function(){
    $(this).ColorPickerSetColor(this.value);
  }).click(function(){
    element = this;
    element.curr = $(this);
  });

  $.each($(color_var),function(key,value) {
    $(this).parent().find('.preview').css({'background': $(this).val()});
  });
}

setColorPicker('.color-picker');
</script>
<?php echo $footer; ?>