<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?php echo $column_status; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if($histories) { ?>
      <?php foreach($histories as $key => $history) { ?>
      <tr>
        <td class="text-left"><?php echo $history['date_added']; ?>
          <?php if($key == 0) { ?>
            <label class="label label-success"><?php echo $text_latest; ?></label>
          <?php } ?>
        </td>
         <td class="text-left"><span class="label" style="background-color: <?php echo $history['bgcolor']; ?>;color: <?php echo $history.textcolor ? history.textcolor : '#666'; ?>"><?php echo $history['status']; ?></span></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>