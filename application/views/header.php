<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Velobörse <?php echo config_item('ort'); ?></title>
    <meta name="description" content="Velobörse: Velos kaufen und verkaufen. Online Erfassung">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">';

    <link rel="stylesheet" href="<?php echo base_url();?>css/main.css">

<?php
if (!empty($querformat)) {
	// The cheap way. Will have to add a pdf link too.
	echo '
    <style type="text/css" media="print">@page {size: landscape;}</style>';
}
?>

</head>
<body<?php echo $bodyClass; ?>>
	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->


<?php
if (!isset($hideNavi) || false == $hideNavi) {
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
	if (is_role('admin,Helfer')) {
		echo '
					<li class="dropdown">
          				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							Ressorts <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">';
		foreach ($ressortNavi as $href => $name) {
			echo '
            				<li>' . anchor($href, $name) . '</li>';
		}
		echo '
						</ul>
			        </li>';
		// Admins
		if (is_role('admin')) {
			echo '
					<li>' . anchor('login/dispatch/admin', 'Administration') . '</li>';
		}

	}
    if (is_role('Verkäufer privat')) {
        echo '
				<li>' . anchor('verkaeufer/index', 'Meine Sachen') . '</li>
				<li>' . anchor('verkaeufer/userForm', 'Adressänderung') . '</li>';
    }
	if ($loggedIn) {
	    echo '
					<li>' . anchor('login/logout', 'Logout') . '</li>';
	} else {
		echo '
					<li>' . anchor('login/form', 'Login') . '</li>
		            <li>' . anchor('login/registrationForm', 'Registrierung') . '</li>';
	}
	echo '
				</ul>';
	if (true === $showSearchForm) {
	echo '
				' . form_open($formAction, array('class'=>'navbar-form navbar-right','role'=>"search")) . '
					<div class="form-group">
						' . form_input(array('name'=>'id','class'=>'form-control focusPlease','placeholder'=>"Quittungs-Nr.")) . '
					</div>
					<button type="submit" class="btn">' . $formSubmitText . '</button>
				' . form_close();
	}
	echo		'</div><!-- /.navbar-collapse -->
		</nav>';
} // End if not $hideNavi
?>

	<div class="container">


<?php
if (1 == $this->session->userdata('logged_in')) {
	echo '
		<div id="confirmation_modal" class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		          <h4 class="modal-title" id="gridSystemModalLabel">Warte auf Antwort</h4>
		        </div>
		        <div class="modal-body">
		          <div class="container-fluid">
		            <div class="row">
		              <div class="col-md-12">
						Das Formular wurde abgeschickt. <br>
						Wir müssen auf Antwort warten, weil sonst das System aus dem Takt gerät!
					  </div>
		            </div>
		          </div>
		        </div>
		      </div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->
		';
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
