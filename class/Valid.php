<?php

class Valid {

    function csrf_create()
    {
        // Echoes the HTML input field with the token
        $salt = openssl_random_pseudo_bytes(16);
        $string = implode('|', [time(), require('include/getip.php'), $salt]);
        $token = base64_encode($string);
        $_SESSION['csrf_code'] = $token;
        echo '<input type="hidden" name="csrf_code" value="'.$token.'">';
    }

    function csrf_check()
    {
        // Test for valid csrf
        if (isset($_POST['csrf_code']) && isset($_SESSION['csrf_code'])) {
            if ($_POST['csrf_code'] === $_SESSION['csrf_code']) {
                $token = $_SESSION['csrf_code'];
                $string = base64_decode($token);
                $ex = explode('|', $string);
                if ((time() - $ex[0]) <= 600)
                    if ((require('include/getip.php')) === $ex[1]) {
                        unset($_SESSION['csrf_code']);
                        return;
                    }
            }
        }
        if (isset($_SESSION['csrf_code']))
            unset($_SESSION['csrf_code']);
        exit('Invalid CSRF code');
    }

    static function captcha()
    {
        // TEMP4:  Generating CAPTCHA image.txt
        // https://gist.github.com/raywint/6200891

        session_start();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");   
        header("Cache-Control: no-store, no-cache, must-revalidate");   
        header("Cache-Control: post-check=0, pre-check=0", false);  
        header("Pragma: no-cache"); 

        $characters = '23456789abcdefghijkmnpqrstuvwxyz';
        $text = '';
        for($i=0; $i<5; $i++) // getting the required random 5 characters
            $text[$i] = $characters[rand(0, strlen($characters)-1)];
        $_SESSION['captcha'] = $text; // assigning the text into session

        $image      = imagecreate(90,30);
        $background = imagecolorallocate($image,208,208,208);
        $color      = imagecolorallocate($image,64,64,64); // text color
        $line_color = imagecolorallocate($image, 128, 128, 128);

        $font = realpath('consolab.ttf'); // setting the font path
        imagettftext($image, 19, 0, 9, 22, $color, $font, $text);

        for($i=0; $i<8; $i++){ // FOR LOOP FOR CREATING RANDOM LINES
            $x1 = rand( 0, 29); $y1 = rand(0, 29); // RANDOM STARTING, ENDING POSITION 
            $x2 = rand(60, 89); $y2 = rand(0, 29);
            imageline($image, $x1, $y1, $x2, $y2, $line_color); // RANDOM LINE
        }

        header('Content-Type: image/png'); // setting the content type as png
        imagepng($image);
        imagedestroy($image);

    }

    function captcha_input()
    {
        echo '<input name="captcha" style="font-family:Consolas; 
            font-weight:bold; font-size:18pt" size="5" required>';
    }

    function captcha_check()
    {
        $result = $_POST['captcha'] == $_SESSION['captcha'];
        unset($_SESSION['captcha']);
        return $result;
    }

}
