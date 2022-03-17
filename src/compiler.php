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

        function compile($vars) {
            $fc = file_get_contents($_ENV["EXAMPLES_DIR"].$this->template.".ptc");
            $fc = $this->transpile($fc);
            $re = '/(?<=%)\w.*(?=%)/miU';
            preg_match_all($re, $fc, $matches, PREG_SET_ORDER, 0);
            for($i = 0; $i < count($matches); $i++) {
                $fc = str_replace("%".$matches[$i][0]."%", $vars[$matches[$i][0]], $fc);
            }
            $this->render($fc);
        }
        
        function transpile($src) {
            $html = "";
            $re = '/<(<|\/).*((>|\/)>|)/mi';
            preg_match_all($re, $src, $matches, PREG_SET_ORDER, 0);
            for($i = 0; $i < count($matches); $i++) {
                $html .= str_replace(["<<", ">>", "/>"], ["<", ">", ">"], $matches[$i][0]);
            }
            return $html;
        }
        
        function render($src) {
            echo $src;
        }
    }
?>