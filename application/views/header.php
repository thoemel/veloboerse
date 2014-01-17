<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html>
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Velobörse Bern</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php 
$min = ('dev' == substr($_SERVER['SERVER_NAME'], 0, 3)) ? '' : '.min';
echo '<link rel="stylesheet" href="' . base_url() . 'css/bootstrap' . $min . '.css">';
?>

<link rel="stylesheet" href="<?php echo base_url();?>css/main.css">

<?php 
if (!empty($querformat)) {
	// The cheap way. Will have to add a pdf link too.
	echo '
<style type="text/css" media="print">@page {size: landscape;}</style>';
}
?>

<script src="<?php echo base_url();?>js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
</head>
<body<?php echo $bodyClass; ?>>
	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->


<?php 
echo	'
	<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#vb-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			' . anchor('', img('img/logo_nur_ringe.png').'&nbsp;Velobörse', array('class'=>'navbar-brand')) . '
		</div><!-- End of navbar-header -->
	
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="vb-navbar-collapse-1">
			<ul class="nav navbar-nav">';
if ($this->session->userdata('logged_in')) {
	echo '				<li>' . anchor('login/showChoices', 'Ressort') . '</li>';
	echo '				<li>' . anchor('login/logout', 'Logout') . '</li>';
} else {
	echo '				<li>' . anchor('login/form', 'Login') . '</li>';
}
echo '
			</ul>
			' . form_open($formAction, array('class'=>'navbar-form navbar-right','role'=>"search")) . '
				<div class="form-group">
					' . form_input(array('name'=>'id','class'=>'form-control focusPlease','placeholder'=>"Quittungs-Nr.")) . '
				</div>
				<button type="submit" class="btn">' . $formSubmitText . '</button>
			' . form_close() . '
		</div><!-- /.navbar-collapse -->
	</nav>';
?>

	<div class="container">
	
	
<?php 
if (1 == $this->session->userdata('logged_in')) {
	echo '
		<div id="confirmation">
			<h2>Formular abgeschickt - warte auf Antwort</h2>
		</div>';
}

if (false != $this->session->flashdata('success')) {
	echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
}
if (!empty($success)) {
	echo '<div class="alert alert-success">' . $success . '</div>';
}
if (false != $this->session->flashdata('error')) {
	echo '<div class="alert alert-error">' . $this->session->flashdata('error') . '</div>';
}
if (!empty($error)) {
	echo '<div class="alert alert-error">' . $error . '</div>';
}
