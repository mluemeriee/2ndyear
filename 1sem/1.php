<?php

class Student{
    //properties - declared variables
    public $fullname;
    public $course;
    public $level;
    public $idnumber;

    //methods - declared functions
    public function __construct($fullname, $course, $level, $idnumber){
        $this->fullname = $fullname;
        $this->course = $course;
        $this->level = $level;
        $this->idnumber = $idnumber;
    }

    public function introduction(){
        return "I am $this->fullname a $this->level $this->course student";
    }
    public function registration(){
        return "Congratulations $this->fullname, you have been registered.";
    }
    public function status(){
        return "I am $this->fullname a $this->level $this->course student and my id number is $this->idnumber";
    }    
}

$student1= new Student("Lee", "BSIT", "2nd year", "242");
echo $student1->introduction() . "<br>";
echo $student1->registration() ."<br>";
echo $student1->status(); ""


?>
