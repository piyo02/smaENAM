<?php
$data = (isset($data) && $data != NULL) ? $data : '';
$data_param = ($data != '') ? (isset($data->$param) ? $data->$param : '')   : '';
?>
<button type="button" class="btn btn-<?= $button_color; ?>" data-toggle="modal" style="margin-left: 5px;" data-target="#<?= $modal_id . $data_param; ?>">
    <?= $name; ?>
</button>
<!-- Modal Delete-->
<div class="modal fade" id="<?php echo $modal_id . $data_param ?>" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?= $url ?>" id="formSoal" method="get">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyelesaikan ulangan ini?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Batal</button>
                    <button type="submit" class="btn  btn-success">Ok</button>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!--  -->