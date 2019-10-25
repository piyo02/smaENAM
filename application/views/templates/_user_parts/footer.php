<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('assets/') ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?= base_url('assets/') ?>plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?= base_url('assets/') ?>plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?= base_url('assets/') ?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/jqvmap/maps/jquery.vmap.world.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= base_url('assets/') ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url('assets/') ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url('assets/') ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('assets/') ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/') ?>dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url('assets/') ?>dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('assets/') ?>dist/js/demo.js"></script>
<!-- Select2 -->
<script src="<?= base_url('assets/') ?>plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="<?= base_url('assets/') ?>plugins/inputmask/jquery.inputmask.bundle.js"></script>
<!-- bootstrap color picker -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('#mapel_id').change(function() {
            var mapel_id = $(this).val();
            console.log(mapel_id);
            $.ajax({
                type: 'POST', //method
                url: '<?= base_url('guru/akademik/get_subbab') ?>', //action
                data: {
                    map_id: mapel_id
                }, //data yang dikrim ke action $_POST['prov_id']
                dataType: 'json',
                async: false,
                success: function(data) {
                    var html = '<option value=""> -- Pilih Materi Bab -- </option>';

                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '"' + '>' + data[i].nama + '</option>'
                    }
                    console.log(html);
                    $('#subbab_id').html(html);
                }
            });
        })
    });
    $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
    })
    $('[data-mask]').inputmask()
</script>
<script>
    CKEDITOR.replace('editor');
</script>
</body>

</html>