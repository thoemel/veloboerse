<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");

foreach ($content as $line) {
	echo utf8_decode(implode(';', $line)) . "\n";
}