<script type="text/html" id="tmpl-pbs-column-toolbar">
	
	<?php do_action( 'pbs_column_toolbar_pre' ) ?>
	
	<style>
	.toolbar-label.column:before {
		content: "{{ data.column }}";
	}
	</style>
	<div class="toolbar-label column" data-mce-bogus="1"></div>
	<div class="dashicons dashicons-edit" data-column-action="edit-area" data-mce-bogus="1" title="{{ data.edit_area }}"></div>
	<div class="dashicons dashicons-images-alt" data-column-action="clone-area" data-mce-bogus="1" title="{{ data.clone_area }}"></div>
	<div class="dashicons dashicons-no-alt" data-column-action="remove-area" data-mce-bogus="1" title="{{ data.delete_area }}"></div>
	
	<div class="sep" data-mce-bogus="1"></div>
	
	<style>
	.toolbar-label.row:before { 
		content: "{{ data.row }}";
	}
	</style>
	<div class="toolbar-label row" data-mce-bogus="1"></div>
	<div class="dashicons dashicons-edit" data-column-action="edit-row" data-mce-bogus="1" title="{{ data.edit_row }}"></div>
	<div class="dashicons dashicons-tagcloud" data-column-action="columns" data-mce-bogus="1" title="{{ data.change_columns }}"></div>
	<div class="dashicons dashicons-images-alt" data-column-action="clone-row" data-mce-bogus="1" title="{{ data.clone_row }}"></div>
	<div class="dashicons dashicons-no-alt" data-column-action="remove-row" data-mce-bogus="1" title="{{ data.delete_row }}"></div>

	<?php do_action( 'pbs_column_toolbar_post' ) ?>
	
</script>