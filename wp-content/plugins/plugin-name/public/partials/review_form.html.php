<?php
//Cargar librerias jqueryMobile para los sliders
$dir = plugin_dir_url( dirname(__FILE__) );
$dir = str_replace('partials', '', $dir);
wp_enqueue_script( 'jquery-ui-mobile', $dir. 'js/jquery.mobile-1.4.5.min.js');
wp_enqueue_style( 'jquery-ui-mobile', $dir. 'css/jquery.mobile-1.4.5.min.css' );
wp_enqueue_style( 'jquery-ui-mobile-icons', $dir. 'css/jquery.mobile.icons-1.4.5.min.css' );
?>
<script type="text/javascript">
var srm_scale = ['#FFE699','#FFD878','#FFCA5A','#FFBF42','#FBB123','#F8A600','#F39C00',
    '#EA8F00','#E58500','#DE7C00','#D77200','#CF6900','#CB6200','#C35900',
    '#BB5100','#B54C00','#B04500','#A63E00','#A13700','#9B3200','#952D00',
    '#8E2900','#882300','#821E00','#7B1A00','#771900','#701400','#6A0E00',
    '#660D00','#5E0B00','#5A0A02','#600903','#520907','#4C0505','#470606',
    '#440607','#3F0708','#3B0607','#3A070B','#36080A'];
var srm_scale = ['#F3F993',
    '#F5F75C','#F6F513','#EAE615','#E0D01B','#D5BC26','#CDAA37','#C1963C','#BE8C3A',
    '#BE823A','#C17A37','#BF7138','#BC6733','#B26033','#A85839','#985336','#8D4C32',
    '#7C452D','#6B3A1E','#5D341A','#4E2A0C','#4A2727','#361F1B','#261716','#231716',
    '#19100F','#16100F','#120D0C','#100B0A','#050B0A'];
</script>
<div style="margin-top:2.5em"></div>
<a href="<?php echo get_permalink( get_page_by_title('Beer List') );?>" target="_parent">Regresar a la lista</a>

<div class="col-md-12 nwm-beer-review-container">

<div class="container col-md-3 nwm-beer-info-photo">
    <img src="<?php echo $image;?>" />
</div>

<div class="container col-md-3 nwm-beer-info-data">
    <h1><?php echo $cerveza_data->post_title;?></h1>
    <a href="<?php echo get_permalink(get_page_by_title($productor,'object','brewery'));?>"><h2><?php echo $productor;?></h2></a>
    <div class="nwm-beer-info-data-desc">
      <p><?php echo $cerveza_data->post_content;?></p>
      <?php if($puntos_venta_num > 0) {?>
      <h6 style="font-size:75%">De venta en:</h6>
      <p>
        <span>
        <?php for($i = 0; $i < $puntos_venta_num; $i++) {?>
          <a style="display:inline;font-size:75%;margin:0;" target="_blank" href="http://<?php echo $puntos_venta[$i]->link;?>"><?php echo $puntos_venta[$i]->nombre;?></a>
        <?php
        echo ($i+1 < $puntos_venta_num-1)?',':'';
        if($i == 3) {
            break;
          }
        }?>
        </span>
        <?php if($puntos_venta_num > 3) {?>
          <a style="display:inline;font-size:75%;margin:0;" href="#" onclick="jQuery(this).css('display','none');jQuery('#extra_pv').css('display','inline')">...</a>
          <span id="extra_pv" style="display:none">
            <?php for($i = $i; $i < $puntos_venta_num; $i++) { ?>
              <a style="display:inline;font-size:75%;margin:0;" target="_blank" href="http://<?php echo $puntos_venta[$i]->link;?>"><?php echo $puntos_venta[$i]->nombre;?></a>
            <?php }?>
          </span>
        <?php }?>
      </p>
      <?php }?>
      <?php if($puntos_distribucion_num > 0) {?>
        <h6 style="font-size:75%">Distribuido por:</h6>
        <p>
          <span>
          <?php for($i = 0; $i < $puntos_distribucion_num; $i++) {?>
            <a style="display:inline;font-size:75%;margin:0;" target="_blank" href="http://<?php echo $puntos_distribucion[$i]->link;?>"><?php echo $puntos_distribucion[$i]->nombre;?></a>
          <?php
            echo ($i+1 < $puntos_distribucion_num)?',':'';
            if($i == 3) {
                break;
            }
          }?>
            </span>
            <?php if($puntos_distribucion_num > 3) {?>
              <a style="display:inline;font-size:75%;margin:0;" href="#" onclick="jQuery(this).css('display','none');jQuery('#extra_pd').css('display','inline')">...</a>
              <span id="extra_pd" style="display:none">
                <?php for($i = $i; $i < $puntos_distribucion_num; $i++) { ?>
                  <a style="display:inline;font-size:75%;margin:0;" target="_blank" href="http://<?php echo $puntos_distribucion[$i]->link;?>"><?php echo $puntos_distribucion[$i]->nombre;?></a>
                <?php }?>
              </span>
            <?php }?>
        </p>
      <?php }?>
    </div>

    <div class="nwm-beer-info-data-detail">
      <p><span class="tag">Tipo:</span> <?php echo (empty($type_of_beer)) ? '-' : $type_of_beer[0]->name;?></p>
      <p><span class="tag">Nivel de alcohol:</span> <?php echo $porcentaje_alcohol;?> Alcohol</p>
      <p><span class="tag">Origen:</span> <?php echo $origen;?>, <?php echo $origen_estado;?></p>
      <p><span class="tag">Temperatura de servicio:</span> <?php echo $temperatura;?> Celcius</p>
      <p><span class="tag">Vaso:</span> <?php echo $vaso;?></p>
      <div class="rate-container"><span class="tag">Calificación Gran Maestre:</span>
        <div <?php echo ($maestre_rating>=1)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($maestre_rating>=2)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($maestre_rating>=3)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($maestre_rating>=4)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($maestre_rating>=5)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
      </div>
      <div class="rate-container"><span class="tag">Calificación Comunidad LOC:</span>
        <div <?php echo ($community_rating>=1)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($community_rating>=2)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($community_rating>=3)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($community_rating>=4)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
        <div <?php echo ($community_rating>=5)?'class="rate chk"':'class="rate unchk"';?>>&nbsp;</div>
      </div>

    </div>
    <div class="nwm-beer-review-outer-circle">
      <div class="nwm-beer-review-inner-circle"></div>
    </div>
</div>

<div class="col-md-6 form-review">
  <div class="container nwm-beer-review">
  <form id="review-form" action="<?php echo site_url().'/index.php/review-sample?beer_id='.$cerveza_id;?>" method="post">
  <input type="hidden" name="review_id" value="<?php echo $my_review_id;?>" />
    <div class="row">
        <h2 class="nwm-beer-review-title">CATAR ESTA CERVEZA</h2>
    </div>
    <div class="row">
        <div class="nwm-beer_review-subtitle">Color:</div>
        <div class="">
            <input type="range" id="color" name="color" value="<?php echo $color;?>" min="0" max="39" data-theme="a" onchange="jQuery('#color_sample').css('background-color', srm_scale[this.value]);" />
        </div>
    </div>
    <div class="row">
        <div id="color_sample" class="col-md-10" style="text-align: right" style="height:50px;width:50px">&nbsp;</div>
    </div>
    <div class="row"><div class="nwm-beer_review-subtitle">Espuma:</div></div>
    <div class="row">
        <div class="">
          <input type="range" id="espuma" name="espuma" value="<?php echo $espuma;?>" min="0" max="10" data-theme="a" />
        </div>
    </div>
    <div class="row nwm-beer_review-tags">
      <span class="nwm-beer-review-text-left">Espesa</span>
      <span class="nwm-beer-review-text-center">Ligera</span>
      <span class="nwm-beer-review-text-right">Sin espuma</span>
    </div>

    <div class="row"><div class="nwm-beer_review-subtitle">Alcohol Percibido:</div></div>
    <div class="row">
        <div class="">
          <input type="range" id="alcohol" name="alcohol" value="<?php echo $alcohol;?>" min="0" max="10" data-theme="a" />
        </div>
    </div>
    <div class="row nwm-beer_review-tags">
      <span class="nwm-beer-review-text-left">Ausente</span>
      <span class="nwm-beer-review-text-center">Equilibrada</span>
      <span class="nwm-beer-review-text-right">Muy alcohólica</span>
    </div>

    <div class="row"><div class="nwm-beer_review-subtitle">Cuerpo:</div></div>
    <div class="row">
        <div class="">
          <input type="range" id="cuerpo" name="cuerpo" value="<?php echo $cuerpo;?>" min="0" max="10" data-theme="a" />
        </div>
    </div>
    <div class="row nwm-beer_review-tags">
      <span class="nwm-beer-review-text-left">Ligero</span>
      <span class="nwm-beer-review-text-center">Robusto</span>
      <span class="nwm-beer-review-text-right">Pesado</span>
    </div>

    <div class="row"><div class="nwm-beer_review-subtitle">Balance:</div></div>
    <div class="row">
        <div class="">
          <input type="range" id="final" name="final" value="<?php echo $final;?>" min="0" max="10" data-theme="a" />
        </div>
    </div>
    <div class="row nwm-beer_review-tags">
      <span class="nwm-beer-review-text-left">Seco</span>
      <span class="nwm-beer-review-text-center">Equilibrado</span>
      <span class="nwm-beer-review-text-right">Dulce</span>
    </div>

    <div class="row"><div class="nwm-beer_review-subtitle">Amargor:</div></div>
    <div class="row">
        <div class="">
            <input type="range" id="amargor" name="amargor" value="<?php echo $amargor;?>" min="0" max="10" data-theme="a" />
        </div>
    </div>
    <div class="row nwm-beer_review-tags">
      <span class="nwm-beer-review-text-left">Amargo</span>
      <span class="nwm-beer-review-text-center">Equilibrado</span>
      <span class="nwm-beer-review-text-right">Muy amargo</span>
    </div>

<!--AROMAS-->
    <div class="row"><div class="nwm-beer_review-subtitle">Aromas:</div></div>
    <div class="row" data-role="controlgroup" data-type="horizontal" style="width: 100%">
      <?php
          $aromas = get_terms('aroma_of_beer', array('hide_empty' => false));
          if ( $aromas ) {
              foreach ($aromas as $a) {
                $flag = false;
                $tags = ' style="display:none" ';
                $check = '';
                foreach($review_aromas as $ra) {
                  if($ra->term_taxonomy_id == $a->term_taxonomy_id) {
                    $flag = true;
                    $tags = ' style="display:inline" ';
                    $check = ' checked="checked" ';
                    break;
                  }
                }
      ?>
      <div id="show-<?php echo $a->term_taxonomy_id;?>" <?php echo $tags;?>>
        <input name="tax_input[aroma_of_beer][]" value="<?php echo $a->term_taxonomy_id;?>" type="checkbox" id="<?php echo $a->term_taxonomy_id;?>-show" class="custom nwm-beer-review-show" <?php echo $check;?>>
        <label for="<?php echo $a->term_taxonomy_id;?>-show"><?php echo $a->name;?></label>
      </div>
      <?php }}?>
    </div>
    <div class="row nwm-beer-review-aromas-button"><center>
        <input class="" type="button" value="..." onclick="(jQuery('#aromas_more').css('display')=='none')?jQuery('#aromas_more').css('display','block'):jQuery('#aromas_more').css('display','none');"/>
    </center></div>
    <div id="aromas_more" class="row" data-role="controlgroup" data-type="horizontal" style="width: 100%;display: none">
      <?php
          if ( $aromas ) {
              foreach ($aromas as $a) {
                $flag = false;
                $tags = ' style="display:inline" ';
                $check = '';
                foreach($review_aromas as $ra) {
                  if($ra->term_taxonomy_id == $a->term_taxonomy_id) {
                    $flag = true;
                    $tags = ' style="display:none" ';
                    $check = ' checked="checked" ';
                    break;
                  }
                }
      ?>
      <div id="more-<?php echo $a->term_taxonomy_id;?>" <?php echo $tags;?>>
        <input value="<?php echo $a->term_taxonomy_id;?>" type="checkbox" id="<?php echo $a->term_taxonomy_id;?>-more" class="custom nwm-beer-review-more" <?php echo $check;?>>
        <label for="<?php echo $a->term_taxonomy_id;?>-more"><?php echo $a->name;?></label>
      </div>
      <?php }}?>
    </div>
<!--END OF AROMAS-->
<div class="row"><div class="nwm-beer_review-subtitle">Sabores:</div></div>
<div class="row" data-role="controlgroup" data-type="horizontal" style="width: 100%">
  <?php
      $sabores = get_terms('flavor_of_beer', array('hide_empty' => false));
      if ( $sabores ) {
          foreach ($sabores as $s) {
            $flag = false;
            $tags = ' style="display:none" ';
            $check = '';
            foreach($review_sabores as $rs) {
              if($rs->term_taxonomy_id == $s->term_taxonomy_id) {
                $flag = true;
                $tags = ' style="display:inline" ';
                $check = ' checked="checked" ';
                break;
              }
            }
  ?>
  <div id="show-<?php echo $s->term_taxonomy_id;?>" <?php echo $tags;?>>
    <input name="tax_input[flavor_of_beer][]" value="<?php echo $s->term_taxonomy_id;?>" type="checkbox" id="<?php echo $s->term_taxonomy_id;?>-show" class="custom nwm-beer-review-show" <?php echo $check;?>>
    <label for="<?php echo $s->term_taxonomy_id;?>-show"><?php echo $s->name;?></label>
  </div>
  <?php }}?>
</div>
<div class="row nwm-beer-review-sabores-button"><center>
    <input class="" type="button" value="..." onclick="(jQuery('#sabores_more').css('display')=='none')?jQuery('#sabores_more').css('display','block'):jQuery('#sabores_more').css('display','none');"/>
</center></div>
<div id="sabores_more" class="row" data-role="controlgroup" data-type="horizontal" style="width: 100%;display: none">
  <?php
      if ( $sabores ) {
          foreach ($sabores as $s) {
            $flag = false;
            $tags = ' style="display:inline" ';
            $check = '';
            foreach($review_sabores as $rs) {
              if($rs->term_taxonomy_id == $s->term_taxonomy_id) {
                $flag = true;
                $tags = ' style="display:none" ';
                $check = ' checked="checked" ';
                break;
              }
            }
  ?>
  <div id="more-<?php echo $s->term_taxonomy_id;?>" <?php echo $tags;?>>
    <input value="<?php echo $s->term_taxonomy_id;?>" type="checkbox" id="<?php echo $s->term_taxonomy_id;?>-more" class="custom nwm-beer-review-more" <?php echo $check;?>>
    <label for="<?php echo $s->term_taxonomy_id;?>-more"><?php echo $s->name;?></label>
  </div>
  <?php }}?>
</div>
  <style media="screen">
    label.ui-btn.ui-corner-all.ui-btn-inherit.ui-btn-active.ui-radio-on,
    label.ui-btn.ui-corner-all.ui-btn-inherit.ui-radio-off {
      height: 20px;
      width: 20px;
      display: inline-block;
      border-radius: 20px;
      margin-right: 0.5em;
    }
    label.ui-btn.ui-corner-all.ui-btn-inherit.ui-btn-active.ui-radio-on {
      background-color: #0E162A !important;
    }
    label.ui-btn.ui-corner-all.ui-btn-inherit.ui-radio-off {
      background-color: #999999 !important;
    }
  </style>
  <div class="row"><div class="nwm-beer_review-subtitle">Calificación:</div></div>
  <div class="row rating-radio" data-role="controlgroup" data-type="horizontal" style="text-align: center">

    <input class="1" name="1" type="radio" id="1" <?php echo ($rating >= 1)?'checked="checked"':'';?> />
    <label id="label-1" for="1">&nbsp;</label>

    <input class="2" name="2" type="radio" id="2" <?php echo ($rating >= 2)?'checked="checked"':'';?> />
    <label id="label-2" for="2">&nbsp;</label>

    <input class="3" name="3" type="radio" id="3" <?php echo ($rating >= 3)?'checked="checked"':'';?> />
    <label id="label-3" for="3">&nbsp;</label>

    <input class="4" name="4" type="radio" id="4" <?php echo ($rating >= 4)?'checked="checked"':'';?> />
    <label id="label-4" for="4">&nbsp;</label>

    <input class="5" name="5" type="radio" id="5" <?php echo ($rating >= 5)?'checked="checked"':'';?> />
    <label id="label-5" for="5">&nbsp;</label>

    <input type="hidden" id="rating" name="rating" class="valor" value="<?php echo $rating;?>"/>
  </div>
  <div class="row nwm-beer-review-cata-button" style="text-align: center">
    <input type="submit" data-toggle="modal" data-target="#msg-cata" value="CATA LISTA"/>
  </div>
</form>
</div><!--END nwm-beer-review-->
</div><!--END form-review-->

</div><!--END nwm-beer-review-container-->
<div class="row"></div>
<?php include_once 'list_suggest_beer.html.php';?>
<script type="text/javascript">
    jQuery( function() {
      jQuery("#color_sample").css('background-color', srm_scale[jQuery('#color').val()]);

      jQuery('.nwm-beer-review-show').click(function () {
        jQuery('#'+this.value+'-show').attr('checked', false).checkboxradio( "refresh" );
        jQuery('#'+this.value+'-more').attr('checked', false).checkboxradio( "refresh" );

        jQuery('#show-'+this.value).css('display','none');
        jQuery('#more-'+this.value).css('display','inline');

      });

      jQuery('.nwm-beer-review-more').click(function () {
        jQuery('#'+this.value+'-show').attr('checked', true).checkboxradio( "refresh" );;
        jQuery('#'+this.value+'-more').attr('checked', false).checkboxradio( "refresh" );;

        jQuery('#show-'+this.value).css('display','inline');
        jQuery('#more-'+this.value).css('display','none');
      });

      document.getElementById("1").onclick = (function() {
        jQuery('#1').prop('checked', true).checkboxradio('refresh');
        jQuery('#2').prop('checked', false).checkboxradio('refresh');
        jQuery('#3').prop('checked', false).checkboxradio('refresh');
        jQuery('#4').prop('checked', false).checkboxradio('refresh');
        jQuery('#5').prop('checked', false).checkboxradio('refresh');
        jQuery('#rating').val(1);
      });
      document.getElementById("2").onclick = (function() {
        jQuery('#1').prop('checked', true).checkboxradio('refresh');
        jQuery('#2').prop('checked', true).checkboxradio('refresh');
        jQuery('#3').prop('checked', false).checkboxradio('refresh');
        jQuery('#4').prop('checked', false).checkboxradio('refresh');
        jQuery('#5').prop('checked', false).checkboxradio('refresh');
        jQuery('#rating').val(2);
      });
      document.getElementById("3").onclick = (function() {
        jQuery('#1').prop('checked', true).checkboxradio('refresh');
        jQuery('#2').prop('checked', true).checkboxradio('refresh');
        jQuery('#3').prop('checked', true).checkboxradio('refresh');
        jQuery('#4').prop('checked', false).checkboxradio('refresh');
        jQuery('#5').prop('checked', false).checkboxradio('refresh');
        jQuery('#rating').val(3);
      });
      document.getElementById("4").onclick = (function() {
        jQuery('#1').prop('checked', true).checkboxradio('refresh');
        jQuery('#2').prop('checked', true).checkboxradio('refresh');
        jQuery('#3').prop('checked', true).checkboxradio('refresh');
        jQuery('#4').prop('checked', true).checkboxradio('refresh');
        jQuery('#5').prop('checked', false).checkboxradio('refresh');
        jQuery('#rating').val(4);
      });
      document.getElementById("5").onclick = (function() {
        jQuery('#1').prop('checked', true).checkboxradio('refresh');
        jQuery('#2').prop('checked', true).checkboxradio('refresh');
        jQuery('#3').prop('checked', true).checkboxradio('refresh');
        jQuery('#4').prop('checked', true).checkboxradio('refresh');
        jQuery('#5').prop('checked', true).checkboxradio('refresh');
        jQuery('#rating').val(5);
      });
  });
</script>
<script>
jQuery( "#review-form" ).submit(function( event ) {
  event.preventDefault();
  var $form = jQuery( this );
  var dataForm = $form.serialize();
  var url = $form.attr( "action" );

  var posting = jQuery.post( url, dataForm );

  posting.done(function( data ) {
    jQuery('#msg-cata').modal('hide');
  });
});
</script>
<div id="msg-cata" class="modal fade" style="top:30%;z-index:3000 !important">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body nwm-login-body">
        <div class="nwm-login-marco">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="row nwm-beerlist-header nwm-beerlist-instrucciones">
          <center>
            <h2>La Corte ha hablado.</h2>
          </center>
          <p>Gracias por tu veredicto.</p>
        </div>
      </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style media="screen">
	.modal-backdrop {display: none}
</style>
