<div class="row nwm-beerlist-header nwm-beerlist-header-small hidden-sm hidden-md hidden-lg">
  <center>
    <img class="nwm-beerlist-header-img" src="<?php echo get_site_url().'/wp-content/uploads/2015/10/CorteCervecera.svg';?>"/>
  </center>
  <center>
    <h1>Bienvenido a la Corte Cervecera</h1>
  </center>
</div>

<div class="row nwm-beerlist-header hidden-xs">
  <center>
    <h1>Bienvenido a la <img class="nwm-beerlist-header-img" src="<?php echo get_site_url().'/wp-content/uploads/2015/10/CorteCervecera.svg';?>"/> Corte Cervecera</h1>
  </center>
</div>

<div class="nwm-beerlist-title">
  <center>
<?php if ($section == 'abc') { ?>
  <h1>Alfabético</h1>
<?php }?>
<?php if ($section == 'news') { ?>
  <h1>Recientes</h1>
<?php }?>
<?php if ($section == 'suggest') { ?>
  <h1>Sugeridas</h1>
<?php }?>
<?php if ($section == 'hielera') { ?>
  <h1>Mi Gabinete -
<?php if(isset($_GET['status'])) {
  switch($_GET['status']) {
    case 'Favorite': {echo 'Favoritas';break;}
    case 'ForLater': {echo 'Por Probar';break;}
    case 'Rated': {echo 'Por Catar';break;}
    case 'Reviewed': {echo 'Catas';break;}
  }
}?>
  </h1>
<?php }?>
  </center>
</div>

<div id="myModal" class="modal fade" style="top:30%;z-index:3000 !important">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body nwm-login-body">
        <div class="nwm-login-marco">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="row nwm-beerlist-header">
          <center>
            <p class="nwm-login-texto-inscribe">INSCRIBE TU NOMBRE EN LAS PÁGINAS DE LA CORTE</p>
            <p class="nwm-login-texto-cata">PARA CATAR ESTA CERVEZA</p>
          </center>
        </div>
        <hr class="nwm-login-barra"/>
        <form class="nwm-login-form" name="loginform" id="nwm-loginform" action="http://laordencervecera.com/wp-login.php?redirect_to=<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
  			<div class="row nwm-login-username">
  				<label for="user_login">Username</label>
  				<input type="text" name="log" id="user_login" class="input" value="" size="20">
  			</div>
  			<div class="row nwm-login-password">
  				<label for="user_pass">Password</label>
  				<input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
  			</div>
  			<!--<div class="row nwm-login-remember">
          <label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember Me</label>
        </div>-->
  			<div class="row nwm-login-submit">
  				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="LOG IN">
  				<input type="hidden" name="redirect_to" value="http://laordencervecera.com/index.php/beer-list/">
  			</div>
        <div class="row nwm-login-social">
          <a href="http://laordencervecera.com/wp-login.php?apsl_login_id=facebook_login&state=cmVkaXJlY3RfdG89aHR0cCUzQSUyRiUyRmxhb3JkZW5jZXJ2ZWNlcmEuY29tJTJGcHJ1ZWJhJTJGd3AtYWRtaW4lMkY=">
            <input type="button" name="fb-login" id="fb-login" class="nwm-login-fb-button" value="LOG IN CON FACEBOOK"/>
          </a>
          <!--
          <a href="https://api.twitter.com/oauth2/1023685272-FwSc7tvNGnA6x9Zte6NgeVegSCo5zizUoBYv157">
            <input type="button" name="tw-login" id="tw-login" class="nwm-login-tw-button" value="LOG IN CON TWITTER"/>
          </a>
          -->
  			</div>
		  </form>
      </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="instrucciones" class="modal fade" style="top:30%;z-index:3000 !important">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body nwm-login-body">
        <div class="nwm-login-marco">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="row nwm-beerlist-header nwm-beerlist-instrucciones">
          <center>
            <h2>Bienvenido a La Corte Cervecera</h2>
          </center>
          <hr class="nwm-login-barra"/>
          <ol>
            <li>Inscribe tu nombre en las páginas de La Corte Cervecera.</li>
            <li>Califica, cata y da tus impresiones sobre las cervezas que has probado.</li>
            <li>Guarda en el gabinete tus cervezas favoritas y crea tu lista cervezas por calificar.</li>
            <li>Comparte con la comunidad cervecera y tus redes sociales.</li>
          </ol>
        </div>
      </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="msg" class="modal fade" style="top:30%;z-index:3000 !important">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body nwm-login-body">
        <div class="nwm-login-marco">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="row nwm-beerlist-header nwm-beerlist-instrucciones">
          <center>
            <h2><span id="raiting-texto">0</span> calificación has dado a esta etiqueta.</h2>
          </center>
          <center style="color:black">
            Para recordar sus sabores, texturas y aromas, te invitamos a realizar toda la degustación.
          </center>
          <center>
            <button class="nwm-my-button" id="cata-quick-access" type="button"><a href="">Catar esta cerveza</a></button>
            <button class="nwm-my-button" type="button" data-dismiss="modal">No gracias, en otra ocasión</button>
          </center>
        </div>
      </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<a href="" class="nwm-beerlist-faq" data-toggle="modal" data-target="#instrucciones"><center>?</center></a>

<style media="screen">
	.modal-backdrop {display: none}
</style>
