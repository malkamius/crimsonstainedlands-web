<?php
    class BasePage {
        public string $Content = "";
        public string $Title = "";
        private bool $Captured;
        private bool $CaptureStarted = false;

        function Start() : void {
            $this->Captured = false;
            $this->CaptureStarted = true;
            ob_start();
        }
    
        private function EndCapture() : string {
            $this->Content = ob_get_clean();
            $this->Captured = true;
            $this->CaptureStarted = false;
            return $this->Content;
        }

        function End() {
            if($this->CaptureStarted)
                $this->EndCapture();

            $title = $this->Title;
            $content = $this->Content;

            include("layout/layout.php");

            $this->Captured = false;
        }
    }

    $Page = new BasePage();
?>