<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=database_table_" . $tabelle . "_" . date('Ymd') . ".xls");

echo $content;