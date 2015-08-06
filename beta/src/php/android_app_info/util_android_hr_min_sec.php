<?php
     
    /**
     * Convert number of seconds into hours, minutes and seconds
     * and return an array containing those values
     *
     * @param integer $seconds Number of seconds to parse
     * @return array
     */
    function secondsToTime($seconds)
    {
        // extract hours
        $hours = floor($seconds / (60 * 60));
        if($hours < 10) 
        {
          $hours = "0".$hours;
        }
     
        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);
        if($minutes < 10) 
        {
          $minutes = "0".$minutes;
        }
     
        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);
        if($seconds < 10)
        {
          $seconds = "0".$seconds;
        }
     
        // return the final array
        /*
        $obj = array(
            "h" => (int) $hours,
            "m" => (int) $minutes,
            "s" => (int) $seconds,
        );
        */
        $obj = array(
            "h" => $hours,
            "m" => $minutes,
            "s" => $seconds,
        );
        
        //$time[] = (int) $hours;
        //$time[] = (int) $minutes;
        //$time[] = (int) $seconds;
                
        //echo "<br>hr:".(int)$hours." ,min:".(int)$minutes." ,sec:".(int)$seconds;
        return $obj;
    }
        
?>
