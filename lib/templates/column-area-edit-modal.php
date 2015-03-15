<script type="text/html" id="tmpl-pbs-column-area-edit-modal">
		
	<div id="pbsandwich_column_area_edit" class="sandwich_modal">
		
		<div class="style_area" style="display: inline-block; margin-right: 20px">
			<h4>Styles</h4>

			<div class="border_area">
				<style>
				.border_area:before {
					content: "Border";
				}
				</style>
				
				<input type="number" id="border_top" name="border_top" value="{{ data.fields.border_top }}" min="0" max="999" step="1" placeholder="0"/>
				<input type="number" id="border_right" name="border_right" value="{{ data.fields.border_right }}" min="0" max="999" step="1" placeholder="0"/>
				<input type="number" id="border_bottom" name="border_bottom" value="{{ data.fields.border_bottom }}" min="0" max="999" step="1" placeholder="0"/>
				<input type="number" id="border_left" name="border_left" value="{{ data.fields.border_left }}" min="0" max="999" step="1" placeholder="0"/>
				
				<style>
				.padding_area:before {
					content: "Padding";
				}
				</style>
				<div class="padding_area">
					<input type="number" id="padding_top" name="padding_top" value="{{ data.fields.padding_top }}" min="0" max="999" step="1" placeholder="0"/>
					<input type="number" id="padding_right" name="padding_right" value="{{ data.fields.padding_right }}" min="0" max="999" step="1" placeholder="0"/>
					<input type="number" id="padding_bottom" name="padding_bottom" value="{{ data.fields.padding_bottom }}" min="0" max="999" step="1" placeholder="0"/>
					<input type="number" id="padding_left" name="padding_left" value="{{ data.fields.padding_left }}" min="0" max="999" step="1" placeholder="0"/>
				</div>
			</div>
		</div>
		<div style="display: inline-block; width: 350px">
			<h4>Border</h4>
			<label for="border_style">
				Style 
				<select id="border_style" name="border_style" value="{{ data.fields.border_style }}">
					<option value="none"  <# if ( data.fields.border_style === 'none' ) { #> selected="selected" <# } #>>None</option>
					<option value="dotted"  <# if ( data.fields.border_style === 'dotted' ) { #> selected="selected" <# } #>>Dotted</option>
					<option value="dashed"  <# if ( data.fields.border_style === 'dashed' ) { #> selected="selected" <# } #>>Dashed</option>
					<option value="solid" <# if ( data.fields.border_style === 'solid' ) { #> selected="selected" <# } #>>Solid</option>
					<option value="double" <# if ( data.fields.border_style === 'double' ) { #> selected="selected" <# } #>>Double</option>
					<option value="groove" <# if ( data.fields.border_style === 'groove' ) { #> selected="selected" <# } #>>Groove</option>
					<option value="ridge" <# if ( data.fields.border_style === 'ridge' ) { #> selected="selected" <# } #>>Ridge</option>
					<option value="inset" <# if ( data.fields.border_style === 'inset' ) { #> selected="selected" <# } #>>Inset</option>
					<option value="outset" <# if ( data.fields.border_style === 'outset' ) { #> selected="selected" <# } #>>Outset</option>
				</select>
			</label>
			<label for="border_color">Color <input type="text" id="border_color" name="border_color" value="{{ data.fields.border_color }}"/></label>
			<h4>Background</h4>
			<label for="background_color">Color <input type="text" id="background_color" name="background_color" value="{{ data.fields.background_color }}"/></label>
			<label for="background_image">
				Image 
				<img class="image_preview" id="background_image_preview" src='{{ data.fields.background_image_preview }}'/>
				<input type="hidden" id="background_image" name="background_image" value="{{ data.fields.background_image }}"/>
				<input type="hidden" id="background_image_url" name="background_image_url" value="{{ data.fields.background_image_url }}"/>
			</label>
			<label for="background_size">
				Size
				<select id="background_size" name="background_size" value="{{ data.fields.background_size }}">
					<option value="inherit"  <# if ( data.fields.background_size === 'inherit' ) { #> selected="selected" <# } #>>Inherit</option>
					<option value="cover"  <# if ( data.fields.background_size === 'cover' ) { #> selected="selected" <# } #>>Cover</option>
					<option value="contain"  <# if ( data.fields.background_size === 'contain' ) { #> selected="selected" <# } #>>Contain</option>
				</select>
			</label>
			<label for="background_repeat">
				Repeat
				<select id="background_repeat" name="background_repeat" value="{{ data.fields.background_repeat }}">
					<option value="inherit"  <# if ( data.fields.background_repeat === 'inherit' ) { #> selected="selected" <# } #>>Inherit</option>
					<option value="repeat"  <# if ( data.fields.background_repeat === 'repeat' ) { #> selected="selected" <# } #>>Repeat</option>
					<option value="repeat-x"  <# if ( data.fields.background_repeat === 'repeat-x' ) { #> selected="selected" <# } #>>Repeat-x</option>
					<option value="repeat-y"  <# if ( data.fields.background_repeat === 'repeat-y' ) { #> selected="selected" <# } #>>Repeat-y</option>
					<option value="no-repeat"  <# if ( data.fields.background_repeat === 'no-repeat' ) { #> selected="selected" <# } #>>No-repeat</option>
					<option value="round"  <# if ( data.fields.background_repeat === 'round' ) { #> selected="selected" <# } #>>Round</option>
					<option value="space"  <# if ( data.fields.background_repeat === 'space' ) { #> selected="selected" <# } #>>Space</option>
				</select>
			</label>
		</div>

	</div>
	
</script>