<ul style="margin:0; padding:10px 50px;list-style: none; display: inline-block;text-align: center;">
	<?php foreach($infos as $info) { ?>
	<li style="float:left;width:100%;">
		<span style="margin-bottom: 5px;float:left;font-size:14px; text-align: left;display: inline-block;"><?php echo $info['name']; ?></span>
		<span style="float:right;text-align: right;display: inline-block;">
			<?php if($info['type'] != 'file') { ?>
				<?php echo $info['value']; ?>
			<?php } else { ?>
				<a href="<?php echo $info['href']; ?>"><?php echo $info['value']; ?></a>
			<?php } ?>
		</span>
	</li>
	<?php } ?>
</ul>