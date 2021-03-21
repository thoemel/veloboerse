<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH . 'views/header.php';


echo validation_errors();

echo $newsletter_html;



include APPPATH . 'views/footer.php';