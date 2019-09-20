<?php defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('templates/_siswa_parts/head');
$this->load->view('templates/_siswa_parts/header');
$this->load->view('templates/_siswa_parts/top_menu');
?>
<?php echo $the_view_content; ?>
<?php $this->load->view('templates/_siswa_parts/footer'); ?>
