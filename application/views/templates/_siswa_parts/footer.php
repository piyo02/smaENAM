<?php
// var_dump($siswa);
// var_dump($time_test);
// die;
// timer
if (isset($siswa) && isset($time_test)) {
    $mulai = $siswa->timestamp;
    $waktu_berlalu = time() - $mulai;
    $waktu = (int) $time_test;

    $temp_waktu = $waktu * 60 - $waktu_berlalu;
    $temp_menit = (int) ($temp_waktu / 60);
    $temp_detik = $temp_waktu % 60;

    if ($temp_menit < 60) {
        $jam   = 0;
        $menit = $temp_menit;
        $detik = $temp_detik;
    } else {
        $jam   = (int) ($temp_menit / 60);
        $menit = $temp_menit % 60;
        $detik = $temp_detik;
    }
}
?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/') ?>dist/js/adminlte.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url('assets/') ?>plugins/chart.js/Chart.min.js"></script>
<script>
    var areaChartData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Digital Goods',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            pointRadius: false,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: [28, 48, 40, 19, 86, 27, 90]
        }]
    }

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = jQuery.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets
    barChartData.datasets = temp0

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    })
</script>
<script>
    $(document).ready(function() {
        var detik = <?= $detik; ?>;
        var menit = <?= $menit; ?>;
        var jam = <?= $jam; ?>;

        function hitung() {
            setTimeout(hitung, 1000);

            $('#timer').html(
                '<h6 align="center">' + jam + ' jam : ' + menit + ' menit : ' + detik + ' detik</h6>'
            );

            detik--;

            if (detik < 0) {
                detik = 59;
                menit--;

                if (menit < 0) {
                    menit = 59;
                    jam--;

                    if (jam < 0) {
                        clearInterval();
                        var formSoal = document.getElementById('formSoal');
                        formSoal.submit();
                    }
                }
            }
        }

        function work() {
            setTimeout(work, 1000);

            $.ajax({
                type: 'GET',
                url: '<?= base_url('student/academy/student_work') ?>',
                success: function(data) {
                    if (data == 0) {
                        var formSoal = document.getElementById('formSoal');
                        formSoal.submit();
                    }
                }
            })
        }
        work();
        hitung();
    });
</script>
</body>

</html>