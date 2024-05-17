<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<?php $this->load->view('partials/head'); ?>
	<body class="bg-cover">
		<div class="body-wrapper" style="">
			<header class="ltn__header-area ltn__header-5 ltn__header-transparent">
				<div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-black ltn__logo-right-menu-option plr--9---">
					<div class="container">
						<div class="row">
							<div class="col">
								<div class="site-logo-wrap">
									<div class="site-logo">
										<?= anchor('', img($this->images_url.(!empty($this->machine['image']) ? $this->machine['image'] : ''))) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<div class="ltn__utilize-overlay"></div>
			<?= $contents; ?>
		<?php $this->load->view('partials/scripts'); ?>
	</body>
</html>