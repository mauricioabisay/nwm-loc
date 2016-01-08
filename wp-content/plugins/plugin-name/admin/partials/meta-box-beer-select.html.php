<?php
if(isset($_GET['post'])) {
    $id = $_GET['post'];
    $color = get_post_meta($id, 'color', true);
    $cerveza_id = get_post_meta($id, 'cerveza_id', true);
    $cerveza_name = get_post_meta($id, 'cerveza_name', true);
    $espuma = get_post_meta( $id, 'espuma', true );
    $alcohol = get_post_meta( $id, 'alcohol', true );
    $cuerpo = get_post_meta( $id, 'cuerpo', true );
    $final = get_post_meta( $id, 'final', true );
    $amargor = get_post_meta( $id, 'amargor', true );
} else {
    $color = 0;
    $cerveza_id = (isset($_GET['beer'])) ? $_GET['beer'] : '';
    $cerveza_name = (isset($_GET['beer_title'])) ? $_GET['beer_title'] : '';
    $espuma = '250';
    $alcohol = '250';
    $cuerpo = '250';
    $final = '250';
    $amargor = '250';
}
?>
<input type="hidden" id="color" name="color" value="<?php echo $color;?>" />
<input type="hidden" id="espuma" name="espuma" value="<?php echo $espuma;?>" />
<input type="hidden" id="alcohol" name="alcohol" value="<?php echo $alcohol;?>" />
<input type="hidden" id="cuerpo" name="cuerpo" value="<?php echo $cuerpo;?>" />
<input type="hidden" id="final" name="final" value="<?php echo $final;?>" />
<input type="hidden" id="amargor" name="amargor" value="<?php echo $amargor;?>" />

<table>
    <tr>
        <td style="width: 150px">Beer:</td>
        <td style="width:100%">
        	<input type="hidden" name="beer_id" value="<?php echo $cerveza_id;?>" />
            <input type="text" id="beer" name="beer_name" value="<?php echo $cerveza_name;?>" />
        </td>
    </tr>
    <tr>
        <td style="width: 150px">Color:</td>
        <td><div id="color_sample">&nbsp;</div></td>
    </tr>
    <tr>
        <td><input type="button" value="-" id="down_color" /></td>
        <td style="width:100%">
            <div id="color_slider"></div>
        </td>
        <td><input type="button" value="+" id="up_color" /></td>
    </tr>
    <tr>
        <td style="width: 150px">Espuma:</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="button" value="-" id="down_espuma" /></td>
        <td style="width:100%">
            <div id="espuma_slider"></div>
        </td>
        <td><input type="button" value="+" id="up_espuma" /></td>
    </tr>
    <tr>
        <td style="width: 150px">Alcohol Percibido:</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="button" value="-" id="down_alcohol" /></td>
        <td style="width:100%">
            <div id="alcohol_slider"></div>
        </td>
        <td><input type="button" value="+" id="up_alcohol" /></td>
    </tr>
    <tr>
        <td style="width: 150px">Cuerpo:</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="button" value="-" id="down_cuerpo" /></td>
        <td style="width:100%">
            <div id="cuerpo_slider"></div>
        </td>
        <td><input type="button" value="+" id="up_cuerpo" /></td>
    </tr>
    <tr>
        <td style="width: 150px">Final:</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="button" value="Dulce" id="down_final" /></td>
        <td style="width:100%">
            <div id="final_slider"></div>
        </td>
        <td><input type="button" value="Seco" id="up_final" /></td>
    </tr>
    <tr>
        <td style="width: 150px">Amargor:</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="button" value="-" id="down_amargor" /></td>
        <td style="width:100%">
            <div id="amargor_slider"></div>
        </td>
        <td><input type="button" value="+" id="up_amargor" /></td>
    </tr>
</table>
<script type="text/javascript" >
    jQuery(document).ready(function($) {
        $(function() {

            $( "#beer" ).autocomplete({
              source: "<?php echo site_url('wp-admin').'/admin-ajax.php?action=search_beer';?>",
              minLength: 0,
            });

            //Color Slider
            var srm_scale = ['#FFE699','#FFD878','#FFCA5A','#FFBF42','#FBB123','#F8A600','#F39C00',
                '#EA8F00','#E58500','#DE7C00','#D77200','#CF6900','#CB6200','#C35900',
                '#BB5100','#B54C00','#B04500','#A63E00','#A13700','#9B3200','#952D00',
                '#8E2900','#882300','#821E00','#7B1A00','#771900','#701400','#6A0E00',
                '#660D00','#5E0B00','#5A0A02','#600903','#520907','#4C0505','#470606',
                '#440607','#3F0708','#3B0607','#3A070B','#36080A'];

            $("#color_slider").slider({
                min: 0,
                max: srm_scale.length - 1,
                slide: function(event, ui) {
                    $("#color_sample").css('background-color', srm_scale[ui.value]);
                    $("#color").val(ui.value);
                }
            });

            $('#down_color').click(function() {
                $('#color_slider').slider('value', $('#color_slider').slider('value') - $('#color_slider').slider( "option", "step" ) );
                $("#color_sample").css('background-color', srm_scale[$('#color_slider').slider('value')]);
                $("#color").val($('#color_slider').slider('value'));
            });
            $('#up_color').click(function() {
                $('#color_slider').slider('value', $('#color_slider').slider('value') + $('#color_slider').slider( "option", "step" ) );
                $("#color_sample").css('background-color', srm_scale[$('#color_slider').slider('value')]);
                $("#color").val($('#color_slider').slider('value'));
            });

            //Espuma Slider
            $( "#espuma_slider" ).slider({
                value: $('#espuma').val(),
                min: 0,
                max: 10,
                step: 1,
                slide: function( event, ui ) {
                    $( "#espuma" ).val(ui.value);
                }
            });
            $('#down_espuma').click(function() {
                $('#espuma_slider').slider('value', $('#espuma_slider').slider('value') - $('#espuma_slider').slider( "option", "step" ) );
                $( "#espuma" ).val($('#espuma_slider').slider('value'));
            });
            $('#up_espuma').click(function() {
                $('#espuma_slider').slider('value', $('#espuma_slider').slider('value') + $('#espuma_slider').slider( "option", "step" ) );
                $( "#espuma" ).val($('#espuma_slider').slider('value'));
            });
            //Alcohol Slider
            $( "#alcohol_slider" ).slider({
                value: $('#alcohol').val(),
                min: 0,
                max: 10,
                step: 1,
                slide: function( event, ui ) {
                    $( "#alcohol" ).val(ui.value);
                }
            });
            $('#down_alcohol').click(function() {
                $('#alcohol_slider').slider('value', $('#alcohol_slider').slider('value') - $('#alcohol_slider').slider( "option", "step" ) );
                $( "#alcohol" ).val($('#alcohol_slider').slider('value'));
            });
            $('#up_alcohol').click(function() {
                $('#alcohol_slider').slider('value', $('#alcohol_slider').slider('value') + $('#alcohol_slider').slider( "option", "step" ) );
                $( "#alcohol" ).val($('#alcohol_slider').slider('value'));
            });

            $( "#cuerpo_slider" ).slider({
                value: $('#cuerpo').val(),
                min: 0,
                max: 10,
                step: 1,
                slide: function( event, ui ) {
                    $( "#cuerpo" ).val(ui.value);
                }
            });
            $('#down_cuerpo').click(function() {
                $('#cuerpo_slider').slider('value', $('#cuerpo_slider').slider('value') - $('#cuerpo_slider').slider( "option", "step" ) );
                $( "#cuerpo" ).val($('#cuerpo_slider').slider('value'));
            });
            $('#up_cuerpo').click(function() {
                $('#cuerpo_slider').slider('value', $('#cuerpo_slider').slider('value') + $('#cuerpo_slider').slider( "option", "step" ) );
                $( "#cuerpo" ).val($('#cuerpo_slider').slider('value'));
            });

            $( "#final_slider" ).slider({
                value: $('#final').val(),
                min: 0,
                max: 10,
                step: 1,
                slide: function( event, ui ) {
                    $( "#final" ).val(ui.value);
                }
            });
            $('#down_final').click(function() {
                $('#final_slider').slider('value', $('#final_slider').slider('value') - $('#final_slider').slider( "option", "step" ) );
                $( "#final" ).val($('#final_slider').slider('value'));
            });
            $('#up_final').click(function() {
                $('#final_slider').slider('value', $('#final_slider').slider('value') + $('#final_slider').slider( "option", "step" ) );
                $( "#final" ).val($('#final_slider').slider('value'));
            });

            $( "#amargor_slider" ).slider({
                value: $('#amargor').val(),
                min: 0,
                max: 10,
                step: 1,
                slide: function( event, ui ) {
                    $( "#amargor" ).val(ui.value);
                }
            });
            $('#down_amargor').click(function() {
                $('#amargor_slider').slider('value', $('#amargor_slider').slider('value') - $('#amargor_slider').slider( "option", "step" ) );
                $( "#amargor" ).val($('#amargor_slider').slider('value'));
            });
            $('#up_amargor').click(function() {
                $('#amargor_slider').slider('value', $('#amargor_slider').slider('value') + $('#amargor_slider').slider( "option", "step" ) );
                $( "#amargor" ).val($('#amargor_slider').slider('value'));
            });
        });
    });
</script>
