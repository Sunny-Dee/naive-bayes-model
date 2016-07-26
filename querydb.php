<?php

    $tweettext = $_POST['posttext'];

    $servername = "localhost";;
    $username = "myadminperson";
    $password = "securepassword";
    $dbname = "mydb";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (mysqli_connect_error()){
        die("There was an error connecting to your database");
        echo "could not connect";
    }

    // Format the data into a word array
    $token = prepTweet($tweettext);
    $size = sizeof($token);
    $appProbability = 0;
    $otherProbability = 0;



    // Calculate App probability
    for ($x = 0; $x <= $size - 1; $x++){
        $sql = "SELECT Word, Probability FROM APP WHERE Word = '$token[$x]'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_array($result);
        $appProbability += $rows["Probability"];
    }
    
    
    // Calculate Other probability
    for ($x = 0; $x <= $size - 1; $x++){
        $sql = "SELECT Word, Probability FROM OTHER WHERE Word = '$token[$x]'";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_array($result);
        $otherProbability += $rows["Probability"];
    }

    if ($appProbability < $otherProbability){
        echo "App";
    } else {
        echo "Other";
    }


    function prepTweet($tweettext){
        $tweettext = strtolower($tweettext);
        $tweettext = str_replace('. ', ' ', $tweettext);
        $tweettext = str_replace(': ', ' ', $tweettext);
        $tweettext = str_replace('?', ' ', $tweettext);
        $tweettext = str_replace('!', ' ', $tweettext);
        $tweettext = str_replace(';', ' ', $tweettext);
        $tweettext = str_replace(',', ' ', $tweettext);
              
        if (strcmp(substr($tweettext, -1),'.') == 0){
            $length = strlen($tweettext) - 1 ;          
            $tweettext = substr($tweettext, 0, $length);
        }
        
        $tweettext = preg_replace('!\s+!', ' ', $tweettext);
        
        $words = explode(" ", $tweettext);
            
        return $words ;   
    }

    
    mysqli_close($conn);

?>