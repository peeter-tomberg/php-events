<?php


class MyModel {
    use Events;
}



$model = new MyModel();

$model->bind("sup", function($name, $dawg) {

	var_dump($name, $dawg);



}, 2);
$model->trigger("sup", "peeter", $model);