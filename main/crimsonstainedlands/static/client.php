<?php 
    include_once 'controllers/PageController.php';
    $Page->Title = "Online Client";
    $Page->Start();
?>
	<link rel="stylesheet" href="css/xterm.css" />
    <script src="/@xterm/xterm/lib/xterm.js"></script>
    <script src="/@xterm/addon-fit/lib/addon-fit.js"></script>
	
	<style>
        .terminal {
            margin: 0px !important;
        }
    </style>
    <div class="console">
        <!-- <div id="output"></div> -->
        <div class="terminal" id="terminal"></div>
        <input type="text" id="input" placeholder="Enter command..." value="/connect">
        <script lang="javascript">
            document.getElementById("input").select();
        </script>
        <script type="module" src="js/app.js"></script>
    </div>
    <script>
		// var term = new Terminal({rendererType: "canvas",
        //     convertEol: true});
        // const fitAddon = new FitAddon.FitAddon();
        // term.loadAddon(fitAddon);
		// term.open(document.getElementById('terminal'));
        // // Make the terminal's dimension fit its container
        // fitAddon.fit();

        // // Optionally, you can make it responsive
        // window.addEventListener('resize', function() {
        //     fitAddon.fit();
        // });
		// term.writeln('Hello from \x1B[1;3;31mxterm.js\x1B[0m $ ')
	</script>
<?php 
	$Page->End();
?>