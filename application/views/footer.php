
</div>
<!-- /container -->

<footer>
	<div class="container hidden-print">
<?php echo anchor('', img('img/logo_nur_ringe.png'), array('title'=>'Start')); ?>
	</div>
</footer>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>

<?php 
$min = ('dev' == substr($_SERVER['SERVER_NAME'], 0, 3)) ? '' : '.min';
echo '
<script src="' . base_url() . 'js/vendor/bootstrap' . $min . '.js"></script>

<script src="' . base_url() . 'js/main.js"></script>';
?>

</body>
</html>
