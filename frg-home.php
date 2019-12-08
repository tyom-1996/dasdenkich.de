<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";


$message = '';

if(!empty($_GET['msg'])) {
	$message = '<div class="'.isset($errors) ? $errors[$l][$_GET['msg']][0] : ''.'">'
				.$errors[$l][$_GET['msg']][1]
				.'</div>';
}

$thisPage = "home";

?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link rel="stylesheet" href='/css/frg-home.css'>
</head>


<body  id="<?php echo $thisPage; ?>">

	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>

	<section id="main" class="home-section">
	    
	    <video autoplay="" preload="" muted="" loop="">
			 <source src="/video/background2.mp4" type="video/mp4">
			</video>
		<div style="position: relative;z-index: 2;margin-top: 137px;color: white;">

		    <h2 style="color: white;" class="section-title">Text Messaging is how People Want to Communicate With You.</h2>
           
            <p>
                <img src="/images/homepage/unnamed.png" >
               <span> Texting has become one of the most popular forms of communication in today's hectic world.</span>
                Are you a car-dealer, insurance broker, real-estate agent, jeweler, or work in the home service industry, then having a reliable way to communicate between your employees and customers is essential. 
            </p>

		</div>
	</section>
	
	<section id="main2" class="home-section">
		<div>
			<h2 class="section-title">Do you believe caring about a good communication with your customers is important?</h2>
           
            <p>
               Customers are trying to find and communicate with you using their phones... and want to message you.
            </p>
            <br>
            <p style="font-weight: 600;color: black;" >
                64% of consumers believe that businesses should use SMS messages to interact with customers more often than they currently do   (source: Forbes)
                <a style="display: block;" href="https://www.forbes.com/sites/stephanieburns/2019/09/06/9-clever-ways-to-use-text-messaging-in-your-business/#3ee2beb92951">https://www.forbes.com/sites/stephanieburns/2019/09/06/9-clever-ways-to-use-text-messaging-in-your-business/#3ee2beb92951</a>
            </p>
            <br>
            <p style="line-height: 32px;font-size: 19px;">
                 <img style="float: right;width: 212px;margin-left: 10px;margin-right: 20px;position: relative;top: -3px;" src="/images/homepage/widget.png">
                And when you text people, research shows that SMS open rates are as high as 98%, compared to just 20% of all emails. And, on average, it takes 90 seconds for someone to respond to a text and 90 minutes to respond to an email.
            </p>
            
            <p style="color: black;font-weight: 600;">Make SMS texting part of your workflow today and better your business!</p>

		</div>
	</section>
	
	<section id="main3" class="home-section">
		<div>
		    <h2 class="section-title">Get Online Reviews by Texting your Customers</h2>
			<div style="display: flex;justify-content: space-between;overflow: unset!important;">
                <p style="flex: 1;line-height: 36px;font-size: 21px;font-weight: 600;color: black;">
			        <img src="/images/homepage/google.png" style="width: 259px;">
			        Send a final word to your customer and include a default message asking the client to leave a review for your business. Your customer will have a choice to leave a review on all popular platforms, such as: Google, Tripdvisor, Yelp, YellowPages, DealerRater, WeddingWire, Facebook or wherever business might be listed! We will set that up for you!
			    </p>
                <div>  
                    <img src="/images/homepage/phones-google.png" style="float: unset;width: 288px;position: relative;top: -21px;">  
                </div>
            </div>
		</div>
	</section>
	
	<section id="main4" class="home-section">
		<div>
		    <h2 class="section-title">	Employee Assignment</h2>
		    <p style="line-height: 30px;font-size: 18px;/* font-weight: 600; */">
		        <img src="/images/homepage/user-table.png" style="float: left;width: 500px;height: 159px;margin: 0 35px 5px 0;">
		        Your new portal will allow you to set up different employees of your business so you can assign specific messages/ contacts to one of your staff-members; ensuring the right person in your office is taking care and answering the customer.
		    </p>
	
		</div>
	</section>
	
	
	<section id="main5" class="home-section">
    <div>
        <h2 class="section-title" >	Pricing</h2>
        <ul style="background: #ffffff;padding: 59px;box-sizing: border-box;margin: auto;border: 1px dashed black;border-radius: 0 79px 0 79px;">
            <li>all including...
                <ul>
                    <li>your own new dedicated number for texting</li>
                    <li>5 sub-user accounts</li>
                    <li>setup for your reviews link (to find you on Google, Tripadvisor etc.) ….. </li>
                </ul>
            </li>

            <li>one-time setup charge US$ 50.00 CDN $75.00 </li>
            <li>monthly plans… (incoming messages are free of charge)

                <ul>
                    <li>upto 50 outgoing messages /day US$ 50.00 /month CDN $75.00 /month</li>
                    <li>upto 100 outgoing messages /day US$ 90.00 /month CDN $150.00 /month </li>
                    <li>upto 150 outgoing messages /day US$ 130.00 /month CDN $230.00 /month</li>
                    <li>upto 200 outgoing messages /day US$ 170.00 /month CDN $290.00 /month</li>
                    <li>(minimum 3 months prepaid)</li>
                    <li>(monthly plans can be changed anytime)</li>
                </ul>
            </li>

        </ul>

    </div>
</section>
	
	
	<section id="main6" class="home-section">
		<div>
		    <h2 class="section-title">	You Are Already Using a POS System?</h2>
		    <p >
		        Your existing POS system could be seamlessly integrated with your new texting portal, making it even easier to communicate with your customers. We would be happy to talk to your software provider!
		    </p>
		</div>
	</section>
	
	<div id="mitmachen" class="home-section">
		<div>
			<h2>
                <?php echo $lang['home-mitmachen'][$l]; ?>
            </h2>
			<form method="post" action="/includes/send-contact.php">
				<input type="hidden" name="site" value="<?php echo $lang['domain'][$l]; ?>">
			    <?php echo $message; ?>
				<input type="text" name="subject" value="" class="gone">
				<div>
					<label>name</label>
					<span><input type="text" name="name" id="name" placeholder="Name"></span>
				</div>
				<div>
					<label>email</label>
					<span><input type="email" name="email" id="email" placeholder="Email"></span>
				</div>
				<div>
					<label>message</label>
					<span><textarea name="message" id="message" placeholder="<?php echo $lang['message'][$l]; ?>"></textarea></span>
				</div>
				<div class="submit">
					<button><?php echo $lang['send'][$l]; ?></button>
				</div>
			</form>
		</div>
	</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
<iframe id="message-iframe" style="border: none;height: 461px;position: fixed;bottom: 0;right: 0;width: 293px;" src="https://dasdenkich.de/msg-widget/msg-widget.php?id=298148752&type=admin"></iframe>
</body>
</html>
