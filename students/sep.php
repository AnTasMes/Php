<?php
    function seperateSubjects($subjects){

        $full_subjects = array();

        foreach($subjects as $subject){

                $raw_grade = "";
        
                for ($i=count($subject)-2; $i < count($subject); $i++) { 
                    // Check if the character is not a space (so you dont have to remove it later)
                    if($subject[$i] !== " ") 
                        $raw_grade .= $subject[$i];
                }
        
                $subject_name = substr($subject, 0, count($subject) - 2);
        
                array_push($full_subjects, 
                    array(
                        $name => $subject_name,
                        $grade => $grade
                    ));
            }
        return $full_subjects;
    }