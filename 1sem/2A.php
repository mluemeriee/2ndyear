<?php
    include '2.php';

    $student1 = new student("Shan", "BSIT", "Second Year");
    echo $student1->introduction() . "<br>";

    $student2 = new studentCouncil("Cas, "BLIS", "4th Year", "Mayor", "Classroom");
    echo $student2->intro2() . "<br>";

    $student3 = new studentScholar("Mea", "BLIS", "4th Year", "mema", "mema");
    echo $student3->introduction();
?>
