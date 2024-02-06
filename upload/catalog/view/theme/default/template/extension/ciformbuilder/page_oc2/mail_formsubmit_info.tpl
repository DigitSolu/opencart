<?php foreach($infos as $info) { ?>
	<p>
		<span>
			<b><?php echo $info['name']; ?>: </b>
			<?php if($info['type'] == 'file') { ?>
				<?php foreach($info['value'] as $file_value) { ?>
					<a href="<?php echo $file_value['href']; ?>"><?php echo $file_value['filename']; ?></a>
					<br>
				<?php } ?>
			<?php } else { ?>
				<?php echo $info['value']; ?>
			<?php } ?>
		</span>
	</p>
<?php } ?>