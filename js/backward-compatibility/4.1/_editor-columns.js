/**
 * This is the 4.1 method of adding new columns. This version is buggy in 4.2, when you select
 * a wpview, then create a column, it creates a jumbled up row. The process here is an outright
 * replace the selected content
 *
 * This was changed in 4.2 to a render new content -> clear selected content -> insert new content
 *
 * The addButton below overwrites the 4.2 column button
 */
if ( pbsandwich_column.wp_version.match( /^4.1/ ) ) {
	editor._pbsCreateNewColumn = function( columnConfig ) {
		preUpdateSortable( editor );
		editor.insertContent( _pbsandwich_columns_formTable( columnConfig, editor.selection.getContent() ) );
		updateSortable( editor );
		fixTableParagraphs( editor );
	}
}