<?php
$roww = array();
$ipa = array();
$ips = array();
$bahasa = array();
foreach ($rows as $key => $row) {
    if ($row->bidang_studi_id == 1)
        $ipa[]['ipa'] = $row->nama;
    if ($row->bidang_studi_id == 2)
        $ipa[]['ips'] = $row->nama;
    if ($row->bidang_studi_id == 3)
        $ipa[]['bahasa'] = $row->nama;
}
var_dump($ipa);
// array_push($roww);
var_dump($rows);
die;
?>

<div class="table-responsive ">
    <table class="table table-striped table-bordered table-hover  ">
        <thead class="thin-border-bottom">
            <tr>
                <th style="width:50px">No</th>
                <?php foreach ($header as $key => $value) : ?>
                    <th><?php echo $value ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = (isset($number) && ($number != NULL))  ? $number : 1;
            foreach ($header as $key => $value) : ?>
                <?php
                    foreach ($rows as $ind => $row) :
                        ?>
                    <tr>
                        <td> <?php echo $no++ ?> </td>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                    <?php endforeach; ?>
                    </tr>
                <?php
                endforeach;
                ?>
        </tbody>
    </table>
</div>