
</div>
<!-- /container -->

<footer>
	<div class="container hidden-print">
<?php
echo anchor('', img('img/logo-veloboerse-de_32.png'), array('title'=>'Start'));
echo '&nbsp;' . anchor('start/disclaimer', 'Disclaimer');
echo '&nbsp;|&nbsp;' . anchor('start/datenschutzerklaerung', 'Datenschutzerklärung');
?>
	</div>
</footer>


<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="<?php echo base_url();?>js/jquery-1.12.4.min.js"><\/script>')</script>
<script src="<?php echo base_url();?>js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

<?php
echo '
<script src="' . base_url() . 'js/main.js?' . (filemtime('js/main.js')) . '"></script>';
?>

</body>
</html>
