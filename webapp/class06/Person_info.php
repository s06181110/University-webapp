<?php


class Person_info extends Person {
    private $age;
    private $blood;

    public function __construct($name, $weight, $height, $age, $blood)
    {
        parent::__construct($name, $weight, $height);
        $this->age = $age;
        $this->blood = $blood;
    }

    public function bmi_age() {
        parent::bmi_cal();
        if (($this->age < 30 && $this->bmi >= 24 )|| ($this->age >= 30 && $this->bmi >= 27)) {
            echo 'メタボだよ';
        } else {
            echo 'メタボじゃないよ';
        }
    }

    public function bmi_cal()
    {
        // オーバーライドだよ（この授業何がしたいかわかんねぇ）
        parent::bmi_cal();
    }

}