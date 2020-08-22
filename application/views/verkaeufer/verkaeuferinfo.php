	<div class="form-group">
		<label for="verkaeuferInfo" class="col-lg-2 control-label">
           Verkäufy
        </label>
		<div class="col-sm-6 col-md-6 col-lg-6">
		  <span class="badge">
              <span class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#verkaeufyModal"></span>
          </span>
          <!-- Modal -->
          <div class="modal fade" id="verkaeufyModal" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Verkäufy-Info</h4>
                </div>
                <div class="modal-body">
                  <p>
                  	<dl>
<?php
foreach ($verkaeuferInfo as $key => $value) {
    $val = empty($value) ? '-' : $value;
    echo '
                <dt>' . $key . '</dt>
                <dd>' . $val . '</dd>
    ';
}
?>
					</dl>
                  </p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                </div>
              </div>

            </div>
          </div>
        </div>
        </div>
<?php
