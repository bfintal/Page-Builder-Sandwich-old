<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<script type="text/html" id="tmpl-pbs-column-change-modal">
	
	<div id="pbsandwich_column_change_modal">
		
		<h4>{{ data.preset }}</h4>
		<p class="desc">{{ data.preset_desc }}</p>
		
		<p class="mce-btn"><button data-columns="1/1">{{ data.column_1 }}</button></p>
		<p class="mce-btn"><button data-columns="1/2+1/2">{{ data.column_2 }}</button></p>
		<p class="mce-btn"><button data-columns="1/3+1/3+1/3">{{ data.column_3 }}</button></p>
		<p class="mce-btn"><button data-columns="1/4+1/4+1/4+1/4">{{ data.column_4 }}</button></p>
		<p class="mce-btn"><button data-columns="1/3+2/3">{{ data.column_1323 }}</button></p>
		<p class="mce-btn"><button data-columns="2/3+1/3">{{ data.column_2313 }}</button></p>
		<p class="mce-btn"><button data-columns="1/4+1/2+1/4">{{ data.column_141214 }}</button></p>
		
		<hr>
		
		<h4>{{ data.custom }}</h4>
		
		<input type="text" class="mce-textbox custom_column" value="1/2+1/2">
		<p class="mce-btn"><button>{{ data.use_custom }}</button></p>
		<p class="desc">
			{{{ data.modal_description }}}
			<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/2+1/2</code>
			<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/3+1/3+1/3</code>
			<code style="font-family: monospace; background: #eee; padding: 0 .4em; line-height: 1.6em; display: inline-block; border: 1px solid #ddd; border-radius: 4px;">1/4+2/4+1/4</code>
		</p>
	
	</div>

</script>