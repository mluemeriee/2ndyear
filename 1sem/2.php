<?php
class student {

    public $fullname;
    public $course;
    public $level;
    public $status;

    public function __construct($fullname, $course, $level, $status) {
        $this->fullname = $fullname;
        $this->course = $course;
        $this->level = $level;
        $this->status = $status;
    }

    public function introduction() {
        return "I am $this->fullname a $this->level $this->course student"; 
    }

    public function enrollment() {
        return "You have been enrolled as a $this->course";
    }
}

class studentCouncil extends student{
    public $position;
    public $organization;

    public function __construct($fullname, $course, $level, $position, $organization) {
        parent ::__construct($fullname, $course, $level);
        $this->position = $position;
        $this->organization = $organization;
    }

    public function intro2() {
        return "A current $this->course $this->level $this->organization $this->position";
    }

    public function enrollment(){
        return "You have been enrolled as $this->course student and as $this->organization $this->position";
    }
}

class studentScholar extends student{
    public $scholarshipProgram;
    public $scholarshipType;

        public function __construct($fullname, $course, $level, $scholarshipProgram, $scholarshipType) {
        parent ::__construct($fullname, $course, $level);
        $this->scholarshipProgram = $scholarshipProgram;
        $this->scholarshipType = $scholarshipType;
    }

    public function intro2() {
        return "A current $this->course $this->level";
    }
}
?>
