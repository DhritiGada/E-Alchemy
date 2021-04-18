<?php

class Header{
    private $title;
    private $customCSS;

    public function __construct($title,$customCSS) {
        $this->title = $title;
        $this->customCSS = $customCSS;
    }

    public function output(){
        $customCSSLink = "";

        if($this->customCSS !== ""){
            $customCSSLink = "<link rel='stylesheet' href='assets/css/".$this->customCSS."' />";
        }
        
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
            <title>".$this->title."</title>
        
            <!-- Website icon -->
            <link rel='icon' href='assets/images/seismic.png' />
        
            <!-- Semantic ui -->
            <link rel='stylesheet' href='assets/css/semantic.min.css' />
        
           
            <!-- Navbar -->
            <link rel='stylesheet' href='assets/css/nav.css'>

            <!-- Footer -->
            <link rel='stylesheet' href='assets/css/footer.css'>"

            .$customCSSLink."
        
        </head>";
    }
}

