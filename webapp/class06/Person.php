<?php


class Person
{
    private $name;
    private $weight;
    private $height;
    protected $bmi;

    public function __construct($name, $weight, $height) {
        $this->name = $name;
        $this->weight = $weight;
        $this->height = $height;
    }

    public function bmi_cal() {
        $this->bmi = $this->weight/($this->height**2);
        echo $this->name.'のBMIは「'.$this->bmi.'」です。';
    }
}