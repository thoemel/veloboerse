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


<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
<style>
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
</style>
<link rel="stylesheet"
	href="<?php echo base_url();?>css/bootstrap-responsive.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/main.css">

<script src="<?php echo base_url();?>js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
</head>
<body<?php echo $bodyClass; ?>>
	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->


	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
				</a>
<?php 
echo			anchor('', img('img/logo_nur_ringe.png').'&nbsp;Velobörse', array('class'=>'brand'));
echo '			<div class="nav-collapse collapse">';
echo '				<ul class="nav">';
//echo '					<li class="active">' . anchor('', 'Start') . '</li>';
if ($this->session->userdata('logged_in')) {
	echo '				<li>' . anchor('login/showChoices', 'Ressort') . '</li>';
	echo '				<li>' . anchor('login/logout', 'Logout') . '</li>';
} else {
	echo '				<li>' . anchor('login/form', 'Login') . '</li>';
}
echo '				</ul>';
echo '
					' . form_open($formAction, array('class'=>'navbar-form pull-right')) . '
					' . form_input(array('name'=>'id','class'=>'focusPlease')) . '
					' . '<button 
							type="submit" 
							class="btn"
							data-html="Formular abgeschickt - warte auf Antwort"
							data-placement="bottom">' . $formSubmitText . '</button>' . '
					' . form_close() . '
				</div>
				<!--/.nav-collapse -->';
?>
			</div>
		</div>
	</div>

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
