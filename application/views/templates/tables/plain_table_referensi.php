<?php
$form = [
    'type'    => 'select',
    'class'   => 'form-control',
];
$select[0] = '-- Pilih Bank Soal --';
foreach ($bank_soal as $key => $value) {
    $select[$value->id] = $value->nama;
}
?>
<div class="card-header">
    <h5 for="">Referensi Soal</h5>
    <p></p>
</div>
<div class="card-body table-responsive">
    <!--  -->
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <input type="hidden" name="r" value="<?= $referensi ?>">
                <thead>
                    <th>Bank Soal</th>
                    <th>PG</th>
                    <th>Isian</th>
                    <th>Esai</th>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < $referensi; $i++) :
                        ?>
                        <tr>
                            <td>
                                <?php
                                    if (isset($data)) {
                                        $select_bank_soal = $data[$i]->id;
                                        echo '<input type="hidden" name="ref_id_' . $i . '" value="' . $data[$i]->ref_id . '">';
                                    }
                                    $form['name'] = $form['id'] = 'bank_soal_id_' . $i;
                                    $form['options'] = $select;
                                    if (isset($select_bank_soal))
                                        $form['selected'] = $select_bank_soal;
                                    echo form_dropdown($form);

                                    $form = [
                                        'type'    => 'select',
                                        'class'   => 'form-control',
                                    ];
                                    ?>
                            </td>
                            <td>
                                <?php
                                    $form['name'] = $form['id'] = 'pg_' . $i;
                                    echo form_dropdown($form);
                                    ?>
                            </td>
                            <td>
                                <?php
                                    $form['name'] = $form['id'] = 'isian_' . $i;
                                    echo form_dropdown($form);
                                    ?>
                            </td>
                            <td>
                                <?php
                                    $form['name'] = $form['id'] = 'esai_' . $i;
                                    echo form_dropdown($form);
                                    ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php for ($j = 0; $j < $i; $j++) : ?>
    <script>
        $(document).ready(function() {
            $('#bank_soal_id_' + <?= $j ?>).change(function() {
                var bank_soal_id = $(this).val();
                console.log(bank_soal_id);
                $.ajax({
                    type: 'POST', //method
                    url: '<?= base_url('guru/bank_soal/count_type') ?>', //action
                    data: {
                        id: bank_soal_id
                    }, //data yang dikrim ke action $_POST['id']
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        var html_pg = '<option value=""> - </option>';
                        var html_isian = '<option value=""> - </option>';
                        var html_esai = '<option value=""> - </option>';

                        var i;
                        for (i = 1; i <= data.pg; i++) {
                            html_pg += '<option value="' + i + '"' + '>' + i + '</option>'
                        }
                        for (i = 1; i <= data.isian; i++) {
                            html_isian += '<option value="' + i + '"' + '>' + i + '</option>'
                        }
                        for (i = 1; i <= data.esai; i++) {
                            html_esai += '<option value="' + i + '"' + '>' + i + '</option>'
                        }
                        console.log(data);
                        $('#pg_<?= $j ?>').html(html_pg);
                        $('#isian_<?= $j ?>').html(html_isian);
                        $('#esai_<?= $j ?>').html(html_esai);
                    }
                });
            })
        });
    </script>
<?php endfor; ?>