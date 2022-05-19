<?php

    require_once "compiler.php";

    //Examples location: examples/...
    //Example: variables.ptc
    $c = new Compiler("loop");
    $c->compile(["guests" => [["guest_name" => "Kamil", "guest_surname" => "Waniczek", "guest_age" => 17], ["guest_name" => "Test", "guest_surname" => "Test", "guest_age" => 60]], "test" => "tekst"]);
?>