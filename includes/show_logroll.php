<section id="log_roll">
	<div class="container">
		<div class="row">
			<h4>Log</h4>
			<ul>
				<?php 
					if (!empty($log_roll)) {
						show_log_roll($log_roll);
					}
				?>
	    </ul>
		</div>
	</div>
</section>