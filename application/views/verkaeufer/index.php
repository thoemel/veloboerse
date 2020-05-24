<?php
include APPPATH . 'views/header.php';

echo '
<h1>Verkäufer*innen</h1>

<div>
	<ul>
		<li>' . anchor('verkaeufer/veloformular', 'Velo erfassen') . '
		<li>Adresse ändern
	</ul>
</div>

<div>
';

if ($meineVelos->num_rows() > 0) {
    echo '<h2>Meine Velos</h2>';

    foreach ($meineVelos->result() as $row) {
        if (1 == $row->storniert) {
            continue;
        }
        echo '<div class="row">';
        if (!empty($row->img)) {
            $imgAttrs = ['src'=>'uploads/'.$row->img, 'style'=>'width:100%;max-width:150px', 'class'=>'img-responsive'];
            $myImg = img($imgAttrs);
            unset($imgAttrs['style']);
            echo '<a data-toggle="modal" data-target="#Velobild'.$row->id.'">'.$myImg.'</a>

            <div id="Velobild'.$row->id.'" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            '.img($imgAttrs).'
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                        </div>
                    </div>
                </div>
            </div>';
        }

        echo '<dl>';
        foreach (['preis','typ','marke','farbe','rahmennummer'] as $key) {
            $value = $row->$key;
            if (empty($value)) {
                $value = '-';
            }
            echo '<dt>' . $key . '</dt><dd>' . $value . '</dd>';
        }

        echo '<dt>Status</dt><dd>';
        if ('no' == $row->angenommen) {
            echo 'Noch nicht in der Halle';
        } elseif ('yes' == $row->angenommen && 'no' == $row->verkauft && 'no' == $row->abgeholt) {
            echo 'In der Halle';
        } elseif ('yes' == $row->verkauft) {
            echo 'Verkauft';
        } elseif ('yes' == $row->abgeholt) {
            echo 'Unverkauft zurückgenommen';
        } else {
            echo 'Keine Ahnung - frag im Backoffice nach.';
        }
        echo '</dd>
            </dl>';
        if ('no' == $row->angenommen) {
            echo anchor('verkaeufer/veloformular/' . $row->id, 'Angebot ändern');
            echo '<br>';
            echo anchor('verkaeufer/stornieren/' . $row->id, 'Angebot zurückziehen');
        }
        echo '
        </div>';
    } // End foreach meineVelos
}


echo '</div>';


include APPPATH . 'views/footer.php';