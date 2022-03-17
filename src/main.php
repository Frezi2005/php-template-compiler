<?php

    require_once "compiler.php";

    //Examples location: examples/...
    //Example: variables.ptc
    $c = new Compiler("variables");
    $c->compile(["name" => "Kamil", "surname" => "Waniczek", "age" => 17]);

?>