<?php

require_once("../inc/config.php");
	//$_SERVER is the php variable that holds the method of the form. When the user submits the form, it is sent back to this page and if the method is POST, we'll send the email with the information.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = trim($_POST["name"]);
	$email = trim($_POST["email"]);
	$message = trim($_POST["message"]);

	if ($name == "" OR $email == "" OR $message == "") {
		$error_message = "You must specify a value for a name, email address, and message.";
	}

	if (!isset($error_message)) {
		foreach( $_POST as $value) {
			if (stripos($value, 'Content-Type:') !== FALSE) {
				$error_message = "There was problem with the information you entered";
			}
		}
	}
	


	if (!isset($error_message) && $_POST["address"] != "") {
		$error_message = "Your submission has an error.";
	}

	require_once(ROOT_PATH . "inc/phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();

	if (!isset($error_message) && !$mail->ValidateAddress($email)) {
		$error_message = "You must specify a valid  email address.";
	}


	if (!isset($error_message)) {
		$email_body = "";
		$email_body = $email_body . "Name: " . $name . "<br>";
		$email_body = $email_body . "Email: " . $email . "<br>";
		$email_body = $email_body . "Message: " . $message;

		//Set who the message is to be sent from
		$mail->setFrom($email, $name);
		//Set who the message is to be sent to
		$mail->addAddress('davidstriga@gmail.com', 'David');
		//Set the subject line
		$mail->Subject = 'Email from Store';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($email_body);
		//send the message, check for errors
		if ($mail->send()) {
			header("Location: " . BASE_URL . "contact/?status=thanks");
			exit;
		} else {
			$error_message = "There was a problem sending the email: " . $mail->ErrorInfo;
		}
		
	}
}


?>





<?php 
$pageTitle = "Contact Mike";
$section = "contact";
include(ROOT_PATH . 'inc/header.php'); ?>

	<div class="section page">

		<div class="wrapper">

			<h1>Contact</h1>
			<!--after the user submits the form, we'll check to see if the $_GET variable has the value of thanks. If it does, we'll show the thank you message.-->
			<?php if (isset($_GET["status"]) AND $_GET["status"]  == "thanks") { ?> 
			<p>Thanks for the email! I&rsquo;ll be in touch shortly.</p>
			<?php } else { ?>
				
				<!--The form will display if the method is not post (we are putting everything in one php file instead of multiple-->
				<form method="post" action="<?php echo BASE_URL; ?>contact.php">

				<?php
					if (!isset($error_message)) {
						echo '<p>I&rsquo;d love to hear from you! Complete the form to send me an email.</p>';
				} else {
					echo '<p class="message">' . $error_message . '</p>';
				}

				?>

					<table>
						<tr>
							<th>
								<label for="name">Name</label>
							</th> 
							<td>
								<input type="text" name="name" id="name" value="<?php if (isset($name)) { echo htmlspecialchars($name); } ?>">
							</td>
						</tr>
						<tr>
							<th>
								<label for="email">Email</label>
							</th> 
							<td>
								<input type="text" name="email" id="email" value="<?php if (isset($email)) { echo htmlspecialchars($email); } ?>">
							</td>
						</tr>
						<tr>
							<th>
								<label for="message">Message</label>
							</th> 
							<td>
								<textarea name="message" id="message" ><?php if (isset($message)) { echo htmlspecialchars($message); } ?></textarea>
							</td>
						</tr>
						<tr style="display: none;">
							<th>
								<label for="address">Address</label>
							</th> 
							<td>
								<input type="text" name="address" id="address">
								<p>Humans: please, leave this field blank.</p>
							</td>
						</tr>
					</table>
					<input type="submit" value="Send">


				</form>

			<?php } ?>
		</div>

	</div>

<?php include(ROOT_PATH . 'inc/footer.php'); ?>