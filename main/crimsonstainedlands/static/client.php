<?php 
    include_once 'controllers/PageController.php';
    $Page->Title = "Online Client";
    $Page->Start();
?>
	<link rel="stylesheet" href="css/xterm.css" />

	<style>
		body {
			height: 100%;
		}
		.terminal {
			margin: 0px !important;
			height: 100%;
			min-height: 300px;
		}

		.main-content {
			height: 80vh !important;
			width: 80vw !important;
		}

		#input {
			width: 100%;
		}
	</style>
	<!-- <div id="output"></div> -->
	<div class="terminal" id="terminal"></div>
	<div>
	<input type="text" id="input" placeholder="Enter command..." value="/connect">
	</div>
	<script type="module">
		import { App } from '/js/app.js';  // Make sure to include .js extension
		document.addEventListener('DOMContentLoaded', () => {
			const app = new App();
		});
		document.getElementById("input").select();
	</script>

<?php 
	$Page->End(NoFooter: true);
?>