<?PHP
######################################################
#                                                    #
#                Forms To Go 4.5.4                   #
#             http://www.bebosoft.com/               #
#                                                    #
######################################################

$return = '/index.php';

if(!empty($_POST['subject'])) {
  header("location: ".$return);
  exit();
}


define('kOptional', true);
define('kMandatory', false);

define('kStringRangeFrom', 1);
define('kStringRangeTo', 2);
define('kStringRangeBetween', 3);

define('kYes', 'yes');
define('kNo', 'no');




error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('track_errors', true);

function DoStripSlashes($fieldValue)  {
// temporary fix for PHP6 compatibility - magic quotes deprecated in PHP6
 if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() ) {
  if (is_array($fieldValue) ) {
   return array_map('DoStripSlashes', $fieldValue);
  } else {
   return trim(stripslashes($fieldValue));
  }
 } else {
  return $fieldValue;
 }
}

function FilterCChars($theString) {
 return preg_replace('/[\x00-\x1F]/', '', $theString);
}

function CheckString($value, $low, $high, $mode, $limitAlpha, $limitNumbers, $limitEmptySpaces, $limitExtraChars, $optional) {

 $regEx = '';

 if ($limitAlpha == kYes) {
  $regExp = 'A-Za-z';
 }

 if ($limitNumbers == kYes) {
  $regExp .= '0-9';
 }

 if ($limitEmptySpaces == kYes) {
  $regExp .= ' ';
 }

 if (strlen($limitExtraChars) > 0) {

  $search = array('\\', '[', ']', '-', '$', '.', '*', '(', ')', '?', '+', '^', '{', '}', '|', '/');
  $replace = array('\\\\', '\[', '\]', '\-', '\$', '\.', '\*', '\(', '\)', '\?', '\+', '\^', '\{', '\}', '\|', '\/');

  $regExp .= str_replace($search, $replace, $limitExtraChars);

 }

 if ( (strlen($regExp) > 0) && (strlen($value) > 0) ){
  if (preg_match('/[^' . $regExp . ']/', $value)) {
   return false;
  }
 }

 if ( (strlen($value) == 0) && ($optional === kOptional) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && ($mode == kStringRangeFrom) ) {
  return true;
 } elseif ( (strlen($value) <= $high) && ($mode == kStringRangeTo) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && (strlen($value) <= $high) && ($mode == kStringRangeBetween) ) {
  return true;
 } else {
  return false;
 }

}


function CheckEmail($email, $optional) {
 if ( (strlen($email) == 0) && ($optional === kOptional) ) {
  return true;
  } elseif ( preg_match("/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i", $email) == 1 ) {
  return true;
 } else {
  return false;
 }
}




if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
 $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
 $clientIP = $_SERVER['REMOTE_ADDR'];
}

$FTGname = DoStripSlashes( $_POST['name'] );
$FTGemail = DoStripSlashes( $_POST['email'] );
$FTGmessage = DoStripSlashes( $_POST['message'] );
$FTGsite = DoStripSlashes( $_POST['site'] );



$validationFailed = false;

# Fields Validations


if (!CheckString($FTGname, 1, 0, kStringRangeFrom, kNo, kNo, kNo, '', kMandatory)) { $validationFailed = true; }

if (!CheckEmail($FTGemail, kMandatory)) { $validationFailed = true; }



# Redirect user to the error page

if ($validationFailed === true) {

 header("Location: ".$return."?msg=error#mitmachen");

}

if ( $validationFailed === false ) {

 # Email to Form Owner

 $emailSubject = FilterCChars("[".$FTGsite."] Contact Form");

 $emailBody = "name: $FTGname\n"
  . "email: $FTGemail\n"
  . "\n"
  . "message:\n"
  . "$FTGmessage\n"
  . "";
  //$emailTo = 'Incom <vrbangie@gmail.com>';
  $emailTo = 'Incom <info@daskennich.de>';

  $emailFrom = FilterCChars("$FTGemail");

  $emailHeader = "From: $emailFrom\n"
  . "Bcc: vrbangie@gmail.com\n"
   . "MIME-Version: 1.0\n"
   . "Content-type: text/plain; charset=\"UTF-8\"\n"
   . "Content-transfer-encoding: 8bit\n";

  mail($emailTo, $emailSubject, $emailBody, $emailHeader);




# Redirect user to success page

header("Location: ".$return."?msg=success#mitmachen");

}

?>
