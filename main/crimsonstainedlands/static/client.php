<?php 
    include_once 'controllers/PageController.php';
    $Page->Title = "Online Client";
    $Page->Start();
?>
	<link rel="stylesheet" href="css/xterm.css" />
  <link rel="stylesheet" href="css/client.css" />
  <div class="main-content">
      
      <!-- Terminal Area -->
      <div class="terminal" id="terminal"></div>
      <div style="width: 100%; padding: 5px;">
          <input type="text" id="input" placeholder="Enter command..." value="/connect">
      </div>
  </div>
  
  <input type="file" id="settings-file-input" accept=".json" style="display: none;">
  <script type="module">
      import { App } from '/js/app.js';  // Make sure to include .js extension
      // Check for localStorage support
      if (typeof Storage === 'undefined') {
          alert('Your browser does not support local storage. Settings will not be saved between sessions.');
      }

      // Check for WebSocket support
      if (typeof WebSocket === 'undefined') {
          alert('Your browser does not support WebSockets. The MUD client will not be able to connect to servers.');
      }

      document.addEventListener('DOMContentLoaded', () => {
          const app = new App("terminal", 4003);
          window.mudApp = app;
          
          const terminalElement = document.getElementById('terminal');
          const inputElement = document.getElementById('input');
          
          // Focus the input by default when page loads
          inputElement.select();
          
          // Check for focus changes on the document
          document.addEventListener('click', (event) => {
              if (app.isModalOpen() || app.isInteractingWithDropdown()) {
                  return;
              }
              // Check if the click was outside the terminal and input
              if (!terminalElement.contains(event.target) && event.target !== inputElement) {
                  inputElement.select();
              }
          });

          // Additional handler for focus loss via other means (tab navigation, etc.)
          document.addEventListener('focusin', (event) => {
              if (app.isModalOpen() || app.isInteractingWithDropdown()) {
                  return;
              }
              // If focus is on the document body or any element other than input/terminal
              if (event.target !== inputElement && !terminalElement.contains(event.target)) {
                  // Delay the focus slightly to avoid conflicts with other event handlers
                  setTimeout(() => {
                      inputElement.select();
                  }, 10);
              }
          });
      }); 
      

  </script>
<?php 
	$Page->End(NoFooter: true);
?>