/**
 * Column change toolbar button
 */
editor.on('toolbar-column-columns', function(e) {
    var colModal = editor.windowManager.open( {
        title: pbsandwich_column.change_column,
		buttons: [{
            text: pbsandwich_column.cancel,
            onclick: 'close'
        }],
        body: [{
			type: 'container',
			html: '<div id="pbsandwich_column_change_modal"><h4>' + pbsandwich_column.preset + '</h4><p class="desc">' + pbsandwich_column.preset_desc + '</p>' +
				'<p class="mce-btn"><button data-columns="1/2+1/2">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/3+1/3+1/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/4+1/4+1/4+1/4">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '4' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/3+2/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/3 + 2/3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="2/3+1/3">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '2/3 + 1/3' ) + '</button></p> ' + 
				'<p class="mce-btn"><button data-columns="1/4+2/4+1/4">' + _pbsandwich_columns_sprintf( pbsandwich_column.columns, '1/4 + 2/4 + 1/4' ) + '</button></p> ' + 
				'<hr>' +
				'<h4>' + pbsandwich_column.custom + '</h4><input type="text" class="mce-textbox custom_column" value="1/2+1/2"><p class="mce-btn"><button>' + pbsandwich_column.use_custom + '</button></p><p class="desc">' + pbsandwich_column.modal_description + '<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/2+1/2</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/3+1/3+1/3</code> <code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/4+2/4+1/4</code></p></div>'
		}],
        onsubmit: function( e ) {
			preUpdateSortable( editor );
            editor.insertContent( _pbsandwich_columns_formTable( e.data.columns, editor.selection.getContent() ) );
			updateSortable( editor );
        }
    });
});


/**
 * Change columns modal action handler. When a change column button is clicked
 */
jQuery('body').on('click', '#pbsandwich_column_change_modal button', function() {
	var $ = jQuery;
	
	// Get the column composition
	var columns = $(this).attr('data-columns');
	if ( typeof columns === 'undefined' ) {
		columns = $(this).parents('#pbsandwich_column_change_modal').find('input.custom_column').val();
	}
	
	// The column container will have the attribute data-wp-columnselect
	var selectedColumn = $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]')[0];
	var columnIndexPrev = $(tinyMCE.activeEditor.getBody()).find('[data-wp-columnselect="1"]').index();
	tinyMCE.activeEditor.selection.select( $(selectedColumn).parents('.pbsandwich_column:eq(0)')[0] );

	// Change the column
	preUpdateSortable( tinyMCE.activeEditor );
    tinyMCE.activeEditor.insertContent( _pbsandwich_columns_formTable( columns, tinyMCE.activeEditor.selection.getContent() ) );
	updateSortable( tinyMCE.activeEditor );
	
	// Find out what column was previously selected
	var columnContainer = tinyMCE.activeEditor.selection.getSelectedBlocks();
	var columnToSelect = false;
	if ( typeof columnContainer !== 'undefined' ) {
		if ( $(columnContainer).parents('.pbsandwich_column:eq(0)').find('> tbody > tr > td').length - 1 >= columnIndexPrev ) {
			columnToSelect = $(columnContainer).parents('.pbsandwich_column:eq(0)').find('> tbody > tr > td:eq(' + columnIndexPrev + ')');
		} else {
			columnToSelect = $(columnContainer).parents('.pbsandwich_column > tbody > tr > td:eq(-1)');
		}
		
	}
	// Fix paragraphs
	fixTableParagraphs( tinyMCE.activeEditor );
	// Put the cursor in the new table
	if ( columnToSelect !== false ) {
		tinyMCE.activeEditor.selection.setCursorLocation(columnToSelect[0]);
	}
	
	// Close our modal window
	tinyMCE.activeEditor.windowManager.getWindows()[0].close();
});


/**
 * Column remove entire row toolbar button
 */
editor.on('toolbar-column-remove-row', function(e) {
	var $ = jQuery;
	preUpdateSortable( editor );
	$(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)').remove();
	updateSortable( editor );
	
	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Column clone entire row toolbar button
 */
editor.on('toolbar-column-clone-row', function(e) {
	var $ = jQuery;

	preUpdateSortable( editor );
	var newElement = $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)').clone();
	newElement.insertAfter( $(editor.getBody()).find('[data-wp-columnselect]').parents('.pbsandwich_column:eq(0)') );
	updateSortable( editor );
	
	// Cleanup to make views with iframes display again
	if ( ( newElement.find('.wpview-wrap iframe').length > 0 ) ) {
		editor.execCommand( 'mceCleanup' );
	}
	
	_pbsandwich_removeColumnToolbar( editor );
});


/**
 * Column remove area/column toolbar button
 */
editor.on('toolbar-column-remove-area', function(e) {
	var $ = jQuery;
	
	preUpdateSortable( editor );
	$(editor.getBody()).find('[data-wp-columnselect]').remove();
	updateSortable( editor );
});


/**
 * Column clone area/column toolbar button
 */
editor.on('toolbar-column-clone-area', function(e) {
	preUpdateSortable( editor );
	var newElement = $(editor.getBody()).find('[data-wp-columnselect]').clone();
	newElement.insertAfter( $(editor.getBody()).find('[data-wp-columnselect]') );
	updateSortable( editor );
	
	// Cleanup to make views with iframes display again
	if ( ( newElement.find('.wpview-wrap iframe').length > 0 ) ) {
		editor.execCommand( 'mceCleanup' );
	}
});