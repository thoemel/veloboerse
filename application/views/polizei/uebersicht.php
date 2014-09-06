<?php
include APPPATH . 'views/header.php';

echo '
	' . heading('Polizei', 1) . '
<div class="row">
	<p>
		Hier ein paar nette Links für die Zusammenarbeit mit der Polizei:
	</p>
	<ul>
		<li>' . anchor('polizei/gestohlene', 'Gestohlene Velos') . '</li>
	</ul>
</div>';


include APPPATH . 'views/footer.php';
