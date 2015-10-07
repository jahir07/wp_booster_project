<!-- CUSTOM Javascript -->
<?php echo td_panel_generator::box_start('Custom Javascript'); ?>
	<div class="td-box-row">
		<div class="td-box-description td-box-full">
			<span class="td-box-title">YOUR CUSTOM JAVASCRIPT</span>
			<p>Add custom javascript easly, using this editor. Please do not include the &lt;script&gt; &lt;/script&gt;.</p>
		</div>
	</div>


	<div class="td-box-row-margin-bottom">
		<?php
		echo td_panel_generator::js_editor(array(
			'ds' => 'td_option',
			'option_id' => 'tds_custom_javascript',
		));
		?>
	</div>


<?php echo td_panel_generator::box_end();?>