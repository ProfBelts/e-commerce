<?php 
function is_valid_number($number)
{
    $number = preg_replace("/\D/", "", $number);

    if (strlen($number) == 11 && substr($number, 0, 2) == "09") {
        echo "Validation passed<br>"; // Debugging statement
        return true;
    } else {
        echo "Validation failed<br>"; // Debugging statement
        return false;
    }
}
?>