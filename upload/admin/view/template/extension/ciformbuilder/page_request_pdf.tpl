<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
	<meta charset="UTF-8" />
	<title><?php echo $heading_title; ?></title>
	<base href="<?php echo $base; ?>" />
	<link type="text/css" href="view/stylesheet/formbuilder/formpdf.css" rel="stylesheet" media="screen" />
</head>
<body>
	<div id="containers">
		<div id="content">
			<?php $count = 1; ?>
			<?php $total_forms = count($forms_submissions); ?>
			<?php foreach($forms_submissions as $forms_submission) { ?>
			<div class="container-fluid">

				<?php if($forms_submission['thumb_logo']) { ?>
				<div class="logo text-center">
					<img src="<?php echo $forms_submission['thumb_logo']; ?>" title="<?php echo $forms_submission['page_form_title']; ?>" alt="<?php echo $forms_submission['page_form_title']; ?>">
				</div>
				<?php } ?>

				<div class="row">
		      <div class="col-md-12">
		        <div class="panel panel-default">
		          <div class="panel-heading">
		            <h2><?php echo $forms_submission['page_form_title']; ?></h2>
		          </div>
		          <table class="table">
		            <tbody>
			            <tr>
			              <td><?php if ($forms_submission['customer']) { ?>
			                <?php echo $forms_submission['firstname']; ?> <?php echo $forms_submission['lastname']; ?>
			                <?php } else { ?>
			                <?php echo $forms_submission['firstname']; ?> <?php echo $forms_submission['lastname']; ?>
			                <?php } ?>
			              </td>
			              <td><?php echo $forms_submission['date_added']; ?></td>
			            </tr>
		            </tbody>
		          </table>
		        </div>
		      </div>
		    </div>
		    <div class="row">
		    	<div class="col-md-12">
		    		<div class="panel panel-default">
				      <div class="panel-body">
				        <table class="table table-bordered table-responsive">
				          <thead>
				            <tr class="panel-heading">
				              <td style="width: 40%;" class="text-left"><h2><?php echo $text_field_name; ?></h2></td>
				              <td style="width: 60%;" class="text-left"><h2><?php echo $text_field_value; ?></h2></td>
				            </tr>
				          </thead>
				          <tbody>
				            <?php foreach($forms_submission['page_request_options'] as $page_request_option) { ?>
				            <tr>
				              <td class="text-left"><label><?php echo $page_request_option['name']; ?></label></td>
				              <td class="text-left">
				                <?php if ($page_request_option['type'] != 'file') { ?>
				                	<?php echo $page_request_option['value']; ?>
				                <?php } else { ?>
				                	<?php foreach($page_request_option['value'] as $value_file) { ?>
				                		<a><?php echo $value_file['filename']; ?></a>
				                	<?php } ?>
				                <?php } ?>
				              </td>
				            </tr>
				            <?php } ?>
				          </tbody>
				        </table>
				      </div>
				    </div>
				  </div>
				</div>
			</div>

			<?php if($count != $total_forms) { ?>
			<div class="page_break"></div>
			<?php } ?>

			<?php $count++; ?>
			<?php } ?>
		</div>
	</div>
<style type="text/css">
.page_break { page-break-before: always; }
</style>

</body>
</html>

