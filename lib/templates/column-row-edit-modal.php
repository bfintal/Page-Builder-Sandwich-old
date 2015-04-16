<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<script type="text/html" id="tmpl-pbs-column-row-edit-modal">
	
	<?php
	// Placeholder tabs
	?>
	<div class="pbsandwich_modal_tabs" style="display: none">
		<div class="pbsandwich_modal_tab active" data-for="pbsandwich_column_row_edit">{{ data.styles }}</div>
	</div>
	
	
	<div id="pbsandwich_column_row_edit" class="sandwich_modal">
		
		<div class="style_area" style="display: inline-block; margin-right: 20px">
			<h4>{{ data.styles }}</h4>

			<div class="margin_area">
				<style>
				.margin_area:before {
					content: "{{ data.margin }}";
				}
				</style>
				
				<input type="number" id="margin_top" name="margin_top" value="{{ data.fields.margin_top }}" min="0" max="999" step="1" placeholder="0"/>
				<input type="number" id="margin_right" name="margin_right" value="{{ data.fields.margin_right }}" min="0" max="999" step="1" placeholder="0"/>
				<input type="number" id="margin_bottom" name="margin_bottom" value="{{ data.fields.margin_bottom }}" min="0" max="999" step="1" placeholder="0"/>
				<input type="number" id="margin_left" name="margin_left" value="{{ data.fields.margin_left }}" min="0" max="999" step="1" placeholder="0"/>


				<div class="border_area">
					<style>
					.border_area:before {
						content: "{{ data.border }}";
					}
					</style>
				
					<input type="number" id="border_top" name="border_top" value="{{ data.fields.border_top }}" min="0" max="999" step="1" placeholder="0"/>
					<input type="number" id="border_right" name="border_right" value="{{ data.fields.border_right }}" min="0" max="999" step="1" placeholder="0"/>
					<input type="number" id="border_bottom" name="border_bottom" value="{{ data.fields.border_bottom }}" min="0" max="999" step="1" placeholder="0"/>
					<input type="number" id="border_left" name="border_left" value="{{ data.fields.border_left }}" min="0" max="999" step="1" placeholder="0"/>
				
					<style>
					.padding_area:before {
						content: "{{ data.padding }}";
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
			<label for="full_width">
				{{ data.full_width }}
				<select id="full_width" name="full_width" value="{{ data.fields.full_width }}">
					<option value=""  <# if ( data.fields.full_width === '' ) { #> selected="selected" <# } #>>{{ data.full_width_normal }}</option>
					<option value="1" <# if ( data.fields.full_width === '1' ) { #> selected="selected" <# } #>>{{ data.full_width_1 }}</option>
					<option value="2" <# if ( data.fields.full_width === '2' ) { #> selected="selected" <# } #>>{{ data.full_width_2 }}</option>
					<option value="3" <# if ( data.fields.full_width === '3' ) { #> selected="selected" <# } #>>{{ data.full_width_3 }}</option>
					<option value="4" <# if ( data.fields.full_width === '4' ) { #> selected="selected" <# } #>>{{ data.full_width_4 }}</option>
					<option value="5" <# if ( data.fields.full_width === '5' ) { #> selected="selected" <# } #>>{{ data.full_width_5 }}</option>
					<option value="6" <# if ( data.fields.full_width === '6' ) { #> selected="selected" <# } #>>{{ data.full_width_6 }}</option>
					<option value="7" <# if ( data.fields.full_width === '7' ) { #> selected="selected" <# } #>>{{ data.full_width_7 }}</option>
					<option value="8" <# if ( data.fields.full_width === '8' ) { #> selected="selected" <# } #>>{{ data.full_width_8 }}</option>
					<option value="9" <# if ( data.fields.full_width === '9' ) { #> selected="selected" <# } #>>{{ data.full_width_9 }}</option>
					<option value="99" <# if ( data.fields.full_width === '99' ) { #> selected="selected" <# } #>>{{ data.full_width_99 }}</option>
				</select>
			</label>
			<p class="description">{{ data.full_width_desc }}</p>
		</div>
		<div style="display: inline-block; width: 350px">
			<h4>{{ data.background }}</h4>
			<label for="background_color">{{ data.color }} <input type="text" id="background_color" name="background_color" value="{{ data.fields.background_color }}"/></label>
			<label for="background_image" class="image_type">
				{{ data.image }} 
				<img class="image_preview" id="background_image_preview" src='{{ data.fields.background_image_preview }}'/>
				<span class="remove_image dashicons dashicons-no"></span>
				<input type="hidden" id="background_image" name="background_image" value="{{ data.fields.background_image }}"/>
				<input type="hidden" id="background_image_url" name="background_image_url" value="{{ data.fields.background_image_url }}"/>
			</label>
			<label for="background_size">
				{{ data.size }}
				<select id="background_size" name="background_size" value="{{ data.fields.background_size }}">
					<option value="inherit"	 <# if ( data.fields.background_size === 'inherit' ) { #> selected="selected" <# } #>>{{ data.inherit }}</option>
					<option value="cover"  <# if ( data.fields.background_size === 'cover' ) { #> selected="selected" <# } #>>{{ data.cover }}</option>
					<option value="contain"	 <# if ( data.fields.background_size === 'contain' ) { #> selected="selected" <# } #>>{{ data.contain }}</option>
				</select>
			</label>
			<label for="background_repeat">
				{{ data.repeat }}
				<select id="background_repeat" name="background_repeat" value="{{ data.fields.background_repeat }}">
					<option value="inherit"	 <# if ( data.fields.background_repeat === 'inherit' ) { #> selected="selected" <# } #>>{{ data.inherit }}</option>
					<option value="repeat"	<# if ( data.fields.background_repeat === 'repeat' ) { #> selected="selected" <# } #>>{{ data.repeat }}</option>
					<option value="repeat-x"  <# if ( data.fields.background_repeat === 'repeat-x' ) { #> selected="selected" <# } #>>{{ data.repeatx }}</option>
					<option value="repeat-y"  <# if ( data.fields.background_repeat === 'repeat-y' ) { #> selected="selected" <# } #>>{{ data.repeaty }}</option>
					<option value="no-repeat"  <# if ( data.fields.background_repeat === 'no-repeat' ) { #> selected="selected" <# } #>>{{ data.norepeat }}</option>
					<option value="round"  <# if ( data.fields.background_repeat === 'round' ) { #> selected="selected" <# } #>>{{ data.round }}</option>
					<option value="space"  <# if ( data.fields.background_repeat === 'space' ) { #> selected="selected" <# } #>>{{ data.space }}</option>
				</select>
			</label>
			<label for="background_position">
				{{ data.position }}
				<input type="text" id="background_position" name="background_position" value="{{ data.fields.background_position }}"/>
			</label>
			<h4>{{ data.border }}</h4>
			<label for="border_style">
				{{ data.style }}
				<select id="border_style" name="border_style" value="{{ data.fields.border_style }}">
					<option value="none"  <# if ( data.fields.border_style === 'none' ) { #> selected="selected" <# } #>>{{ data.none }}</option>
					<option value="dotted"	<# if ( data.fields.border_style === 'dotted' ) { #> selected="selected" <# } #>>{{ data.dotted }}</option>
					<option value="dashed"	<# if ( data.fields.border_style === 'dashed' ) { #> selected="selected" <# } #>>{{ data.dashed }}</option>
					<option value="solid" <# if ( data.fields.border_style === 'solid' ) { #> selected="selected" <# } #>>{{ data.solid }}</option>
					<option value="double" <# if ( data.fields.border_style === 'double' ) { #> selected="selected" <# } #>>{{ data.double }}</option>
					<option value="groove" <# if ( data.fields.border_style === 'groove' ) { #> selected="selected" <# } #>>{{ data.groove }}</option>
					<option value="ridge" <# if ( data.fields.border_style === 'ridge' ) { #> selected="selected" <# } #>>{{ data.ridge }}</option>
					<option value="inset" <# if ( data.fields.border_style === 'inset' ) { #> selected="selected" <# } #>>{{ data.inset }}</option>
					<option value="outset" <# if ( data.fields.border_style === 'outset' ) { #> selected="selected" <# } #>>{{ data.outset }}</option>
				</select>
			</label>
			<label for="border_color">{{ data.color }} <input type="text" id="border_color" name="border_color" value="{{ data.fields.border_color }}"/></label>
			<label for="border_radius">
				{{ data.radius }}
				<input type="number" id="border_radius" name="border_radius" value="{{ data.fields.border_radius }}" class="small-text" min="0" max="999" step="1"/>px
			</label>
		</div>

	</div>
	
</script>