<div class="content-wrapper">
	<section class="content">
		<div class="container-fluid">
			<div class="block-header">
				<h6>
					<?php echo $block_header ?>
				</h6>
			</div>
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="card-header">
							<h5>
								<?php echo strtoupper($header) ?>
								<small><?php echo $sub_header ?></small>
							</h5>
							<ul class="header-dropdown m-r--5">
								<a href="<?php echo site_url($parent_page) ?>"><button type="button" class="btn btn-warning" name="button">Kembali</button></a>
							</ul>
						</div>
						<div class="card-body">
							<h2 class="card-inside-title"></h2>
							<div class="row clearfix">
								<div class="col-lg-12">
									<?php if (isset($alert)) echo $alert; ?>
								</div>
								<?php $this->load->view('templates/_admin_parts/form'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
</div>