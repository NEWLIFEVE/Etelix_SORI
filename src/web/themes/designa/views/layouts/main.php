<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<!-- Remove this line if you use the .htaccess -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width">
	<meta name="description" content="Designa Studio, a HTML5 / CSS3 template.">
	<meta name="author" content="Sylvain Lafitte, Web Designer, sylvainlafitte.com">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
         <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/favicon.ico" type="image/x-icon" />
<!--	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">-->
	<link rel="shortcut icon" type="image/png" href="favicon.png">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/divs.css">
 	<script src="<?php echo Yii::app()->baseUrl; ?>/js/class.js"></script>
 	<script src="<?php echo Yii::app()->baseUrl; ?>/js/views.js"></script>

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
<!-- Prompt IE 7 users to install Chrome Frame -->
<!--[if lt IE 8]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
<div class="container">
	<header id="navtop">
		<a href="/" class="logo fleft">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="SORI 1.0.1">
		</a>
		V 1.0.1
		<nav class="fright">
			<?php $this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>'Home', 'url'=>array('/site/index')),
					/*array('label'=>'Carrier', 'url'=>array('/carrier/admin'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Ventas', 'url'=>array('/balance/ventas'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Compras', 'url'=>array('/balance/compras'), 'visible'=>!Yii::app()->user->isGuest),
					array('label'=>'Reportes', 'url'=>array('/balance/reportes'), 'visible'=>!Yii::app()->user->isGuest),*/
					array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				)); 
			?>
		</nav>
	</header>
	<div class="home-page main">
		<section class="grid-wrap" >
			<header class="grid col-full">
				<hr>
				<div class="info" style='text-align:left;'>
					<?php
					$flashMessages = Yii::app()->user->getFlashes();
					if($flashMessages)
					{
						echo '<ul class="flashes" >';
						foreach ($flashMessages as $key=> $message)
						{
							echo '<div class="flash-' .$key. '">' . $message ."</div>\n";
						}
						echo '</ul>';
					}
					?>
				</div>
				<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
					)); 
				?><!-- breadcrumbs -->
				<!--<a href="#" class="arrow fright">see more infos</a>-->
			</header>
			<div class="grid col-full mq2-col-full">
				<?php echo $content; ?>
			</div>
		</section>
	</div><!--main-->
	<div class="divide-top">
		<footer class="grid-wrap">
			<ul class="grid col-one-third social">
				<li><a href="#"> </a></li>
				<li><a href="#"> </a></li>
				<li><a href="#"> </a></li>
				<li><a href="#"> </a></li>
				<li><a href="#"> </a></li>
			</ul>
			<div style="padding-left: 557px;" class="up grid col-one-third ">
				<a href="#navtop" title="Go back up">&uarr;</a>
			</div>
			<nav  class="grid col-one-third ">
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						/*array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)*/
						),
					)); 
				?>
			</nav>
		</footer>
	</div>
	</div>
<!-- Javascript - jQuery -->
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.7.2.min.js"><\/script>')</script>

	<!--[if (gte IE 6)&(lte IE 8)]>
	<script src="js/selectivizr.js"></script>
	<![endif]-->

	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flexslider-min.js"></script>
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/scripts.js"></script>

<!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID. -->
	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
	<div class="transparente2 oculta">
		<div class="interna2">
			<h1>CARGANDO ARCHIVOS</h1>
			<p>Este proceso puede tardar unos minutos</p>
			<p>Por favor espere</p>
			<img src="/images/image_464753.gif">
		</div>
	</div>
</body>
</html>
<?php
Yii::app()->clientScript->registerScript(
        'myHideEffect',
        '$(".info").animate({opacity: 1.0}, 5000).slideUp("slow");',
        CClientScript::POS_READY
        );

?>
