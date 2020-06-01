<?php

//ajax required
if(!isset($_SESSION)) { session_start(); } 
header('Content-Type: application/json');
date_default_timezone_set('Europe/London');
/*************************************** */
require __DIR__ . '/vendor/autoload.php';

//bank modulus
use Cs278\BankModulus\BankModulus;
use Cs278\BankModulus\Exception\AccountNumberInvalidException;
use Cs278\BankModulus\Exception\SortCodeInvalidException;

//monolog
// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;


function modulusCheck() {
    // $logger = new Logger('Bank-Modulus');
    // $logger->pushHandler(new StreamHandler(__DIR__ .'/bank.log', Logger::INFO));
    // $logger->pushProcessor (function ($entry) {
    //     $entry['extra']['session_id'] = session_id();
    //     return $entry;
    // });
    $sortcode = $_POST['SortCode'];
    $accountnumber = $_POST['AccNum'];

    $modulus = new BankModulus();
    //var_dump($accountnumber);
    try {
        $modulus->normalize($sortcode, $accountnumber);
        $modulus = $modulus->check($sortcode, $accountnumber);
        if($modulus) {
            $message = "Valid" . "<br><br>";
            //$logger->info('Valid: ' . $sortcode . " " . $accountnumber);
        } else {
            $message = "INVALID" . "<br><br>";
            // $logger->info('Invalid Bank Details: ' . $sortcode . " " . $accountnumber);
        };
    } catch (SortCodeInvalidException $e) {
        $message = "Sort code exception";
        // $logger->info($message.": ".$sortcode);
    } catch (AccountNumberInvalidException $e) {
        $message = "Account number exception";
        // $logger->info($message.": ".$accountnumber);
    } catch (Exception $e ) {
        $message = "Other exception<p>" . $e->getMessage();
        // $logger->info($message.": ".$e->getMessage());
    }

    return $message;
}

$thisvar = modulusCheck();

$response_array = array(
    "result" => $thisvar
);

//return the result
if(isset($response_array)) {
    echo json_encode($response_array);
}












?>