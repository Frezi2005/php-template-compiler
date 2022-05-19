<?php
    require '../vendor/autoload.php';

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    class Compiler {

        private $template;

        function __construct($_template) {
            $this->template = $_template;
        }

        function compile($vars) {
            $fc = file_get_contents("../{$_ENV["EXAMPLES_DIR"]}{$this->template}.ptc");
            $transpiled = $this->transpile($fc);

            $original = $transpiled[0];
            $loops = $transpiled[1];
            $loopStartRegex = '/#{1}[^\/#]*[^\/#]#{1}/i';
            $html = "";
            for($i = 0; $i < count($loops); $i++) {
                preg_match_all($loopStartRegex, $loops[$i], $matches, PREG_SET_ORDER, 0);
                for($j = 0; $j < count($vars[str_replace("#", "", $matches[0][0])]); $j++) {
                    $test = $loops[$i];
                    foreach($vars[str_replace("#", "", $matches[0][0])][$j] as $key => $value) {
                        $test = str_replace("%{$key}%", $value, $test); 
                    }
                    $html .= $test;
                }

                $original = str_replace($loops[$i], preg_replace('/#.*#/miU', "", $html), $original);
                $html = "";
            }
            
            preg_match_all('/%.*%/miU', $original, $matches, PREG_SET_ORDER, 0);
            for($i = 0; $i < count($matches); $i++) {
                $original = str_replace($matches[$i][0], $vars[str_replace("%", "", $matches[$i][0])], $original);
            }

            $this->render($original);
        }
        
        function transpile($src) {
            $loops = [];
            $loopIndex = -1;
            $lines = explode("\n", $src);
            $loop = false;
            $loopHtml = "";
            $html = "";
            foreach($lines as $l) {
                $l = str_replace(["<<", ">>", "/>"], ["<", ">", ">"], $l);
                $loopStartRegex = '/#{1}[^\/#]*[^\/#]#{1}/i';
                $loopEndRegex = '/#\/.*\/#/i';
                if(preg_match($loopStartRegex, $l)) {
                    $loop = true;
                    $loopIndex++;
                    if(preg_match($loopEndRegex, $l)) {
                        $loop = false;
                        $loops[$loopIndex] = $loopHtml;
                        $loopHtml = "";
                        $loopHtml .= $l;
                    }
                } else if(preg_match($loopEndRegex, $l)) {
                    $loop = false;
                    $loopHtml .= $l;
                    $loops[$loopIndex] = $loopHtml;
                    $loopHtml = "";
                }

                if($loop) {
                    $loopHtml .= $l;
                }

                $html .= $l;
            }

            return [$html, $loops];
        }
        
        function render($src) {
            echo $src;
        }

        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }

        function debug($str) {
            if (is_array($str)) {
                echo "<pre>";
                print_r($str);
                echo "</pre>";
            } else {
                echo htmlspecialchars($str);
            }
        }
    }
?>