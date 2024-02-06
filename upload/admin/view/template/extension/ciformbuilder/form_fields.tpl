<?php if ($page_form_options) { ?>
  <?php foreach ($page_form_options as $page_form_option) { ?>
    <?php if ($page_form_option['type'] == 'select') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <select name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
          <?php
          if($page_form_option['field_dvalue'] == $page_form_option_value['page_form_option_value_id']) {
            $sel = 'selected="selected"';
          } else {
            $sel = '';
          }
          ?>
          <option value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" <?php echo $sel; ?>><?php echo $page_form_option_value['name']; ?></option>
          <?php } ?>
        </select>
      </div>
      <?php } ?>
      <?php if ($page_form_option['type'] == 'radio') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div id="input-field<?php echo $page_form_option['page_form_option_id']; ?>">
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
          <div class="radio-inline">
            <label>
              <?php
              if($page_form_option['field_dvalue'] == $page_form_option_value['page_form_option_value_id']) {
                $sel = 'checked="checked"';
              } else {
                $sel = '';
              }
              ?>
              <input type="radio" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" <?php echo $sel; ?> />
              <?php echo $page_form_option_value['name']; ?>
            </label>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
      <?php if ($page_form_option['type'] == 'checkbox') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div id="input-field<?php echo $page_form_option['page_form_option_id']; ?>">
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
          <div class="checkbox">
            <label>
              <?php
              if(!empty($page_form_option['field_dvalue']) && in_array($page_form_option_value['page_form_option_value_id'], (array)$page_form_option['field_dvalue'])) {
                $sel = 'checked="checked"';
              } else {
                $sel = '';
              }
              ?>
              <input type="checkbox" name="field[<?php echo $page_form_option['page_form_option_id']; ?>][]" value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" <?php echo $sel; ?> />
              <?php echo $page_form_option_value['name']; ?>
            </label>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'checkbox_switch') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div id="input-field<?php echo $page_form_option['page_form_option_id']; ?>">
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
          <div class="checkbox">
            <label class="ci-switch">
              <?php
              if(in_array($page_form_option_value['page_form_option_value_id'], (array)$page_form_option['field_dvalue'])) {
                $sel = 'checked="checked"';
                $sel_active = 'active';
              } else {
                $sel = '';
                $sel_active = '';
              }
              ?>
              <input type="checkbox" name="field[<?php echo $page_form_option['page_form_option_id']; ?>][]" value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" <?php echo $sel; ?> />
              <span class="ci-slider round"></span>
            </label>
            <label>
            <?php echo $page_form_option_value['name']; ?>
            </label>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'multi_select') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <select multiple name="field[<?php echo $page_form_option['page_form_option_id']; ?>][]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control multiselect">
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
          <?php
          if(in_array($page_form_option_value['page_form_option_value_id'], (array)$page_form_option['field_dvalue'])) {
            $sel = 'selected="selected"';
          } else {
            $sel = '';
          }
          ?>
          <option value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" <?php echo $sel; ?>><?php echo $page_form_option_value['name']; ?>
          </option>
          <?php } ?>
        </select>
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'radio_toggle') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="btn-group" data-toggle="buttons">
          <?php $sel_count = 0; ?>
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
            <?php
            if($sel_count == 0 && $page_form_option['field_dvalue'] == $page_form_option_value['page_form_option_value_id']) {
              $sel = 'checked="checked"';
              $sel_active = 'active';
              $sel_count++;
            } else {
              $sel = '';
              $sel_active = '';
            }
            ?>
            <label class="btn btn-default <?php echo $sel_active; ?>">
              <input name="field[<?php echo $page_form_option['page_form_option_id']; ?>]"  autocomplete="off" value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" type="radio" <?php echo $sel; ?>><?php echo $page_form_option_value['name']; ?>
            </label>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'checkbox_toggle') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="btn-group" data-toggle="buttons">
          <?php $sel_count = 0; ?>
          <?php foreach ($page_form_option['page_form_option_value'] as $page_form_option_value) { ?>
            <?php
            if($sel_count == 0 && $page_form_option['field_dvalue'] == $page_form_option_value['page_form_option_value_id']) {
              $sel = 'checked="checked"';
              $sel_active = 'active';
              $sel_count++;
            } else {
              $sel = '';
              $sel_active = '';
            }
            ?>
            <label class="btn btn-default <?php echo $sel_active; ?>">
              <input name="field[<?php echo $page_form_option['page_form_option_id']; ?>][]" autocomplete="off" value="<?php echo $page_form_option_value['page_form_option_value_id']; ?>" type="checkbox" <?php echo $sel; ?>><?php echo $page_form_option_value['name']; ?>
            </label>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if (in_array($page_form_option['type'], array('text', 'number', 'telephone', 'firstname', 'lastname', 'email', 'email_exists', 'postcode', 'address', 'multiple_text'))) { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <input type="text" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" value="<?php echo $page_form_option['field_dvalue']; ?>" class="form-control" placeholder="<?php echo $page_form_option['field_placeholder']; ?>">
      </div>
    <?php } ?>
    <?php if ($page_form_option['type'] == 'textarea') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <textarea name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" rows="5" placeholder="<?php echo $page_form_option['field_placeholder']; ?>" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control"><?php echo $page_form_option['field_dvalue']; ?></textarea>
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'color_picker') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <input type="text" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" value="<?php echo $page_form_option['field_dvalue']; ?>" class="form-control color-picker" placeholder="<?php echo $page_form_option['field_placeholder']; ?>">
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'google_map') { ?>
        <?php if(!empty($page_form_option['field_help'])) { ?>
        <div class="form-group <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
          <iframe src="<?php echo $page_form_option['field_help']; ?>" width="100%" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <?php } ?>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'file') { ?>

      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?> upload-wise">
        <label class="control-label"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div class="dropzone dropzone-file-area" id="dropzone-upload<?php echo $page_form_option['page_form_option_id']; ?>" data-fieldid="<?php echo $page_form_option['page_form_option_id']; ?>" data-formid="<?php echo $page_form_id; ?>" data-limit="<?php echo !empty($page_form_option['file_limit']) ? $page_form_option['file_limit'] : 1; ?>">
          <div id="uploaded-media<?php echo $page_form_option['page_form_option_id']; ?>" class="hidden"></div>
          <div class="dz-default dz-message">
            <h4 class="sbold"><i class="fa fa-cloud-upload"></i></h4>
            <label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
          </div>
        </div>
        <div id="input-field<?php echo $page_form_option['page_form_option_id']; ?>">
        </div>
        <?php /* <input type="hidden" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" value="" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" /> */ ?>

        <div class="alert alert-info file-vname">
          <?php foreach($page_form_option['extra_field_dvalue'] as $extra_field_dvalue) { ?>
          <button type="button" id="file-<?php echo $extra_field_dvalue['value']; ?>" class="btn btn-primary" style="cursor: inherit;">
            <input type="hidden" name="field[<?php echo $page_form_option['page_form_option_id']; ?>][]" value="<?php echo $extra_field_dvalue['value']; ?>" class="form-control">
            <?php echo $extra_field_dvalue['filename']; ?>
            <a class="btn btn-danger btn-xs" onclick="$('#file-<?php echo $extra_field_dvalue['value']; ?>').remove();"><i class="fa fa-times"></i></a>
          </button>
          <?php } ?>
        </div>
      </div>
      <?php } ?>

      <?php if ($page_form_option['type'] == 'date') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div class="input-group date">
          <input type="text" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" value="<?php echo $page_form_option['field_dvalue']; ?>" data-date-format="YYYY-MM-DD" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control" placeholder="<?php echo $page_form_option['field_placeholder']; ?>" />
          <span class="input-group-btn">
          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
          </span>
        </div>
      </div>
      <?php } ?>
      <?php if ($page_form_option['type'] == 'datetime') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div class="input-group datetime">
          <input type="text" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" value="<?php echo $page_form_option['field_dvalue']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control" placeholder="<?php echo $page_form_option['field_placeholder']; ?>" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span>
        </div>
      </div>
      <?php } ?>
      <?php if ($page_form_option['type'] == 'time') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <div class="input-group time">
          <input type="text" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" value="<?php echo $page_form_option['field_dvalue']; ?>" data-date-format="HH:mm" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control" placeholder="<?php echo $page_form_option['field_placeholder']; ?>" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span>
        </div>
      </div>
    <?php } ?>
    <?php if ($page_form_option['type'] == 'country') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <select name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control country_id">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($countries as $country) { ?>
          <?php
          if($page_form_option['field_dvalue'] == $country['country_id']) {
            $sel = 'selected="selected"';
          } else {
            $sel = '';
          }
          ?>
          <option value="<?php echo $country['country_id']; ?>" <?php echo $sel; ?>><?php echo $country['name']; ?>
          </option>
          <?php } ?>
        </select>
      </div>
    <?php } ?>
    <?php if ($page_form_option['type'] == 'zone') { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <?php if($country_exists && false) { ?>
        <select name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control zone_id"></select>
        <?php } else { ?>
        <select name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" class="form-control zone_id">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach($page_form_option['zones'] as $zone) { ?>
          <?php
          if($page_form_option['field_dvalue'] == $zone['zone_id']) {
            $sel = 'selected="selected"';
          } else {
            $sel = '';
          }
          ?>
          <option value="<?php echo $zone['zone_id']; ?>" <?php echo $sel; ?>><?php echo $zone['name']; ?>
          </option>
          <?php } ?>
        </select>
        <?php } ?>
      </div>
    <?php } ?>
    <?php if (in_array($page_form_option['type'], array('password', 'confirm_password'))) { ?>
      <div class="form-group<?php echo ($page_form_option['required'] ? ' required' : ''); ?> <?php echo !empty($page_form_option['width']) ? 'col-xs-12 col-sm-'. $page_form_option['width'] : ''; ?> <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>">
        <label class="control-label" for="input-field<?php echo $page_form_option['page_form_option_id']; ?>"><?php echo $page_form_option['field_name']; ?>
          <?php if(!empty($page_form_option['field_help'])) { ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo $page_form_option['field_help']; ?>"></i>
          <?php } ?>
        </label>
        <input type="password" name="field[<?php echo $page_form_option['page_form_option_id']; ?>]" id="input-field<?php echo $page_form_option['page_form_option_id']; ?>" value="<?php echo $page_form_option['field_dvalue']; ?>" class="form-control">
      </div>
    <?php } ?>

    <?php if ($page_form_option['type'] == 'header') { ?>
      <div class="col-sm-12">
        <h3 class="ci-header <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>"><?php echo $page_form_option['field_name']; ?></h3>
      </div>
    <?php } ?>

    <?php if ($page_form_option['type'] == 'paragraph') { ?>
    <div class="col-sm-12">
      <p class="ci-paragraph <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>"><?php echo $page_form_option['field_name']; ?></p>
    </div>
    <?php } ?>

    <?php if ($page_form_option['type'] == 'hrline') { ?>
      <div class="col-sm-12">
        <hr class="ci-hrline <?php echo !empty($page_form_option['class']) ? $page_form_option['class'] : ''; ?>" />
      </div>
    <?php } ?>

  <?php } ?>
<?php } ?>