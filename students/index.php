<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
    function seperateSubjects($subjects){
        // Pravi niz predmeta:
        // naziv / ocena
        $full_subjects = array();
    
        foreach($subjects as $subject){
    
                $grade = "";
                $subject_name = rtrim($subject); // Uklanjamo svaki space sa desne strane jer sa leve je vec obradjeno

                // Uzimanje ocene iz predmeta (mogao je i substr ali u trenutku pisanja nijesam znao kako se to radi)
                for ($i=strlen($subject_name)-2; $i < strlen($subject_name); $i++) { 
                    // Proverava da li je karakter space ili ne
                    if($subject[$i] !== " ") 
                        $grade .= $subject[$i];
                }
                
                // Ime predmeta se cita samo do mesta za ocenu
                $subject_name = substr($subject_name, 0, strlen($subject_name) - 2);
                
                // Cuvamo predmet u niz predmeta
                array_push($full_subjects, 
                    array(
                        'name' => $subject_name,
                        'grade' => (int)$grade
                    ));
            }
        return $full_subjects;
    }

    function getStudents($file){

        $students = array();

        while(!feof($file)){
            $student_stats = fgets($file, 999);
            $student_stats = explode(" >>> ", $student_stats);
            
            $student_name = $student_stats[0];
            $raw_subjects = $student_stats[1];
            $raw_subjects = explode(", ", $raw_subjects);

            $subjects_array = seperateSubjects($raw_subjects);

            array_push($students,
                array(
                    'name' => $student_name,
                    'subjects' => $subjects_array
            ));
        }

        return $students;
    }

    function printSubjects($student, $with_grade = 0){
        // Ispisuje predmete na ekranu
        // Bez ocene 0
        // Sa ocenom 1
        
        foreach($student['subjects'] as $subject){
            if($with_grade == 0){
                echo $subject['name']. " \ ";
            }
            if($with_grade == 1){
                echo $subject['name'][0]. ": ". $subject['grade']. " \ ";
            }
        }
    }

    function getAverageGrade($student){
        // Racuna prosecnu ocenu za studenta

        $len = 0;
        $sum = 0;

        foreach($student['subjects'] as $subject){
            $sum += $subject['grade'];
            $len ++; 
        }

        return $sum / $len;
    }

    function getOuput($student){
        // Pravi definisani output opisan u tempalte.txt

        //Marko >>> Mathematics 8, Programming basics 9, Computer networks 9, Statistics 7 >>> Average: 8.25

        $outStr = $student['name']. " >>> "; 
        foreach($student['subjects'] as $subject){
            $outStr .= $subject['name']. " ". $subject['grade']. ", ";
        }
        $outStr = substr($outStr, 0, strlen($outStr) - 2). " >>> ";
        $outStr .= "Average: ". getAverageGrade($student);

        return $outStr. "\n";
    }

    $file = fopen('stud.txt', 'a+');

    if(!$file){
        echo "<script>alert('There has been an error')</script>";
        exit;
    }
    $students = getStudents($file);
    fclose($file);



    ?>

<body>
    <table>
        <tr>
            <th>Student name</th>
            <th>Subjects</th>
            <th>Subjects with grades</th>
            <th>Average grade</th>
        </tr>
        <?php
            $full_output_string_thingy = "";
            
            foreach($students as $student){
                echo "<tr>";
                echo "<td>".$student['name']."</td><td>";
                printSubjects($student);
                echo "</td><td>";
                printSubjects($student, 1);
                echo "</td><td>";
                echo getAverageGrade($student);
                echo "</td>";
                echo "</tr>";
                
                $full_output_string_thingy .= getOuput($student);
            }
            ?>
    </table>
    <form action='index.php' , method='post'>
        <input type="submit" name="outToFile" value="Print to file">
    </form>
</body>
<?php

    // Kada kliknemo na dugme, on posalje sve u fajl
    if(isset($_POST['outToFile'])){
        $out = fopen('out.txt', 'w+');
        fputs($out, $full_output_string_thingy);
    }
    
    ?>

</html>