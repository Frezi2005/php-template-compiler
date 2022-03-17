<?php
    require 'vendor/autoload.php';

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    class Compiler {

        private $template;

        function __construct($_template) {
            $this->template = $_template;
        }

        function compile() {
            $this->render(file_get_contents($_ENV["EXAMPLES_DIR"].$this->template.".ptc"));
        }
        
        function render($src) {
            print_r($src);
        }
    }
?>