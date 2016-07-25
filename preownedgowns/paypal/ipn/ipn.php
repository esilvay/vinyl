<?php
require("connect.php");            // this is optional but useful for setting up database access constants etc

// The majority of the following code is a direct copy of the example code specified on the Paypal site.

// Paypal POSTs HTML FORM variables to this page
// we must post all the variables back to paypal exactly unchanged and add an extra parameter cmd with value _notify-validate

// initialise a variable with the requried cmd parameter
$req = 'cmd=_notify-validate';

// go through each of the POSTed vars and add them to the variable
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

// In a live application send it back to www.paypal.com
// but during development you will want to uswe the paypal sandbox

// comment out one of the following lines

//$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

// or use port 443 for an SSL connection
//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);


if (!$fp) {
// HTTP ERROR Failed to connect
// You can optionally send an email to let you know of the problem
// or add other error handling. 
 
 //email
 $mail_From = "From: noreply@PreOwnedGowns.com";
 $mail_To = $email;
 $mail_Subject = "HTTP ERROR";
 $mail_Body = $errstr;//error string from fsockopen

 mail($mail_To, $mail_Subject, $mail_Body, $mail_From); 
//
// If you want to log to a file as well then uncomment the following lines
// You can use these later on in the script as well
// 
// $fh = fopen("logipn.txt", 'a');//open file and create if does not exist
// fwrite($fh, "\r\n/////////////////////////////////////////\r\n HTTP ERROR \r\n");//Just for spacing in log file
//
// fwrite($fh, $errstr);//write data
// fclose($fh);//close file

}
else
{
  fputs ($fp, $header . $req);
  while (!feof($fp)) {
    $res = fgets ($fp, 1024);
    if (strcmp ($res, "VERIFIED") == 0) {


// assign posted variables to local variables
// the actual variables POSTed will vary depending on your application.
// there are a huge number of possible variables that can be used. See the paypal documentation.

// the ones shown here are what is needed for a simple purchase
// a "custom" variable is available for you to pass whatever you want in it. 
// if you have many complex variables to pass it is possible to use session variables to pass them.

      $custom = $_POST['custom'];
	  $buyername = $_POST['first_name'];
      $payment_status = $_POST['payment_status'];
      $payment_amount = $_POST['mc_gross'];         //full amount of payment. payment_gross in US
      $payment_currency = $_POST['mc_currency'];
      $txn_id = $_POST['txn_id'];                   //unique transaction id
      $receiver_email = $_POST['receiver_email'];
      $payer_email = $_POST['payer_email'];
	  $payment_date = $_POST['payment_date'];
	  $item_number = $_POST['item_number'];
		
// use the above params to look up what the price of "item_name" should be.

      $amount_they_should_have_paid = '2.00';
	  $amount_they_should_have_paid_a = '1.00';// you need to create this code to find out what the price for the item they bought really is so that you can check it against what they have paid. This is an anti hacker check.

// the next part is also very important from a security point of view
// you must check at the least the following...

      if (($payment_status == 'Completed') &&   //payment_status = Completed
         ($item_number == '001') &&
		 ($receiver_email == "info@preownedgowns.com") &&   // receiver_email is same as your account email
         ($payment_amount == $amount_they_should_have_paid ) &&  //check they payed what they should have
         ($payment_currency == "USD")) {//&&   and its the correct currency 
         //(!txn_id_used_before($txn_id)))   //txn_id isn't same as previous to stop duplicate payments. You will need to write a function to do this check.
			//$fp = fopen('log.txt', 'w+');
			//fseek($fp,0,SEEK_END);
			//fwrite($fp, $payer_email, $txn_id, $payment_amount, $payment_status, $payment_date);
			//fclose($fp);
			
// everything is ok
// you will probably want to do some processing here such as logging the purchase in a database etc

					$qstring="INSERT INTO gowns (UserID, TransID, GownType, Designer, GownCondition, Silhouette, SleeveStyle, StyleNumber, NeckLine, WaistLine, DressLength, PurchaseDate, Paid, AskingPrice, Labelsize, StreetSize, BustSize, WaistSize, HipSize, HeightwShoes, GownDescr, AltDescr, VeilDescr, PresMethod, TypeofSeller, OriginalReceipt, OriginalOwner, State, City, Zip, ShippingCosts, SaleTerms, WillShip, AcceptPayment, ContactEmail, HearAboutpog, TermsAgree, ListingDate, Image1, Image2, Image3, Image4, Image5) SELECT UserID, '$txn_id', GownType, Designer, GownCondition, Silhouette, SleeveStyle, StyleNumber, NeckLine, WaistLine, DressLength, PurchaseDate, Paid, AskingPrice, Labelsize, StreetSize, BustSize, WaistSize, HipSize, HeightwShoes, GownDescr, AltDescr, VeilDescr, PresMethod, TypeofSeller, OriginalReceipt, OriginalOwner, State, City, Zip, ShippingCosts, SaleTerms, WillShip, AcceptPayment, ContactEmail, HearAboutpog, TermsAgree, ListingDate, Image1, Image2, Image3, Image4, Image5 FROM tempg WHERE ID=$custom";

					mysql_query($qstring);
					
					//$ourFileName = "log.txt";
					//$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
					//fwrite($ourFileHandle, $qstring);
					//fclose($ourFileHandle);
				
				$dstring="DELETE FROM tempg WHERE ID=$custom";
				mysql_query($dstring);

// you can also during development or debugging send yourself an email to say it worked.
// email is a good choice because you can't display messages on the screen as this processing is happening totally independently of
// the main web page processing.

//        uncomment this section during development to receive an email to indicate whats happened
//            $mail_To = "payit@designertuts.com";
//            $mail_Subject = "completed status received from paypal";
//            $mail_Body = "completed: $item_number  $txn_id";
//            mail($mail_To, $mail_Subject, $mail_Body);


      }
      elseif (($payment_status == 'Completed') &&   //payment_status = Completed
         ($item_number == '002') &&
		 ($receiver_email == "info@preownedgowns.com") &&   // receiver_email is same as your account email
         ($payment_amount == $amount_they_should_have_paid_a ) &&  //check they payed what they should have
         ($payment_currency == "USD")) {
		  
		  
					$qstring1="INSERT INTO accessories (UserID, TransID, AccessoryType, JewelryType, JewelryCondition, WrapJacketShaw, WrapLegnth, WrapCondition, ShoeColor, ShoeSize, ShoeCondition, VeilColor, VeilLength, VeilCondition, SashColor, SashCondition, SlipColor, SlipSize, SlipCondition, TiaraCondition, PurseColor, PurseCondition, AskingPrice, AccessDescr, TypeofSeller, OriginalReceipt, OriginalOwner, State, City, Zip, ShippingCost, SaleTerms, WillShip, AcceptPayment, ContactEmail, HearAboutpog, TermsAgree, ListingDate, Image1, Image2, Image3) SELECT UserID, '$txn_id', AccessoryType, JewelryType, JewelryCondition, WrapJacketShaw, WrapLegnth, WrapCondition, ShoeColor, ShoeSize, ShoeCondition, VeilColor, VeilLength, VeilCondition, SashColor, SashCondition, SlipColor, SlipSize, SlipCondition, TiaraCondition, PurseColor, PurseCondition, AskingPrice, AccessDescr, TypeofSeller, OriginalReceipt, OriginalOwner, State, City, Zip, ShippingCost, SaleTerms, WillShip, AcceptPayment, ContactEmail, HearAboutpog, TermsAgree, ListingDate, Image1, Image2, Image3 FROM tempa WHERE ID=$custom";

					mysql_query($qstring1);
				
					$dstring1="DELETE FROM tempa WHERE ID=$custom";
					mysql_query($dstring1);

	  }
	  else{
//
// paypal replied with something other than completed or one of the security checks failed.
// you might want to do some extra processing here
//
//in this application we only accept a status of "Completed" and treat all others as failure. You may want to handle the other possibilities differently
//payment_status can be one of the following
//Canceled_Reversal: A reversal has been canceled. For example, you won a dispute with the customer, and the funds for
//                           Completed the transaction that was reversed have been returned to you.
//Completed:            The payment has been completed, and the funds have been added successfully to your account balance.
//Denied:                 You denied the payment. This happens only if the payment was previously pending because of possible
//                            reasons described for the PendingReason element.
//Expired:                 This authorization has expired and cannot be captured.
//Failed:                   The payment has failed. This happens only if the payment was made from your customer’s bank account.
//Pending:                The payment is pending. See pending_reason for more information.
//Refunded:              You refunded the payment.
//Reversed:              A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from
//                          your account balance and returned to the buyer. The reason for the
//                           reversal is specified in the ReasonCode element.
//Processed:            A payment has been accepted.
//Voided:                 This authorization has been voided.
//

//
// we will send an email to say that something went wrong
          $mail_To = "info@preownedgowns.com";
          $mail_Subject = "PayPal IPN status not completed or security check fail";
//
//you can put whatever debug info you want in the email
//
          $mail_Body = "Something wrong. \n\nThe transaction ID number is: $txn_id \n\n Payment status = $payment_status \n\n Payment amount = $payment_amount";
          mail($mail_To, $mail_Subject, $mail_Body);

      }
    }
    else if (strcmp ($res, "INVALID") == 0) {
//
// Paypal didnt like what we sent. If you start getting these after system was working ok in the past, check if Paypal has altered its IPN format
//
      $mail_To = "info@preownedgowns.com";
      $mail_Subject = "PayPal - Invalid IPN ";
	  //$mail_Body = "We have had an INVALID response. \n\nThe transaction ID number is: $txn_id \n\n username = $username";
      $mail_Body = "We have REMOVED this data from our records.";
      mail($mail_To, $mail_Subject, $mail_Body);
    }
  } //end of while
fclose ($fp);
}
mysql_close($conn);
?>

