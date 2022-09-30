<?php 
    include('functions/groups.php');
    include('functions/file.php');


    class Test {
        public $students;
        public $numberGroup;
        public $uniqueID;

        public function __construct ($students, $numberGroup, $uniqueID)
        {
            $this->students = $students;
            $this->numberGroup = $numberGroup;
            $this->uniqueID = $uniqueID;
        }
    };
    
  
    ?>