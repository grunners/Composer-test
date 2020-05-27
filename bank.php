<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

//bank modulus
use Cs278\BankModulus\BankModulus;
use Cs278\BankModulus\Exception\AccountNumberInvalidException;
use Cs278\BankModulus\Exception\SortCodeInvalidException;

//monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('Bank-Modulus');
$logger->pushHandler(new StreamHandler(__DIR__ .'/bank.log', Logger::INFO));
$logger->pushProcessor (function ($entry) {
    $entry['extra']['session_id'] = session_id();
    return $entry;
});

?>

<form method="post">
	<h3>Account Details</h3>
    <input type="text" name="txtSortCode" placeholder="Sort code" <?php if(isset($_POST['txtSortCode'])) { echo 'value="' . $_POST['txtSortCode'] . '"'; } else { echo 'value="774814"'; } ?>><br>
    <input type="text" name="txtAccNumber" placeholder="Account number" <?php if(isset($_POST['txtAccNumber'])) { echo 'value="' . $_POST['txtAccNumber'] . '"'; } else { echo 'value="24782346"'; } ?>><br>
	<input name="submitAccountCheck" type="submit" value="Check" />

	<?php 
		if (array_key_exists('submitAccountCheck',$_POST)) {
            $sortcode = $_POST['txtSortCode'];
            $accountnumber = $_POST['txtAccNumber'];
            $modulus = new BankModulus();
            //var_dump($accountnumber);
            try {
                $modulus->normalize($sortcode, $accountnumber);
                $modulus = $modulus->check($sortcode, $accountnumber);
                if($modulus) {
                    echo "Valid" . "<br><br>";
                } else {
                    echo "INVALID" . "<br><br>";
                    $logger->info('Invalid Bank Details: ' . $sortcode . " " . $accountnumber);
                };
            } catch (SortCodeInvalidException $e) {
                echo "Sort code exception";
                $logger->info('Sort code exception: ' . $sortcode);
            } catch (AccountNumberInvalidException $e) {
                echo "Account number exception";
                $logger->info('Account number exception: ' . $accountnumber);
            } catch (Exception $e ) {
                echo "Other exception<p>" . $e->getMessage();
                $logger->info('General exception: ' . $e->getMessage());
            }
            
            //var_dump($sortcode, $accountnumber);

            
            
            

		}
	?>

</form>	</form>

