<?php

$lang = array();

$lang['domain']['en'] = 'frogswing.com';
$lang['domain']['de'] = 'daskennich.de';


$lang['title']['de'] = 'das kenn ich .de';
$lang['title']['en'] = 'Frog Swing.com';


if (strpos(curPageURL(), "dasdenkich") !== false) {
	$lang['domain']['de'] = 'dasdenkich.de';
}


$lang['tagline']['de'] = 'Ihre Meinung zählt';
$lang['tagline']['en'] = 'opinions matter';


$lang['menu2']['de'] = 'anmelden';
$lang['menu2']['en'] = 'login';

$lang['menu3']['de'] = 'mitmachen';
$lang['menu3']['en'] = 'join';




$errors['de'] = array(

	"success" => array("success", "<h2>Danke</h2><p>Ihre Nachricht ist auf dem Weg.</p>"),
	"warn" => array("warn", "<h2>Error</h2><p>Ein Fehler ist aufgetreten, bitte versuchen Sie es noch einmal.</p>"),
	// login errors
	"notactive" => array("warn", "<p>Ihr Konto wurde noch nicht freigeschaltet.</p>"),
	"nouser" => array("warn", "<p>Username oder Password sind nicht korrekt. Versuchen sie es noch einmal.</p>"),
	"nouser2" => array("warn", "<p>Geben sie bitte Ihre email Adresse ein</p>"),
	"notauth" => array("warn", "<p>You must be logged in to view the requested page.</p>"),
	"loggedout" => array("success", "<p>Sie haben sich abgemeldet.</p>"),
	"emptyemail" => array("warn", "<p>Geben sie bitte Ihr e-mail an</p>"),
	"req_create_error" => array("warn", "<p>... hier gab es ein kleines Problem!</p>"),
	"req_create_success" => array("success", "<p><strong>Step one complete</strong> Now enter your location to narrow your request to shops close by.</p>"),

	// update phone number name
	"addname" => array("success", "<p><strong>Name wurde erfolgreich hinzugefügt</p>"),
	"addnameerror" => array("warn", "<p><strong>Der Name wurde nicht hinzugefügt, versuchen sie es bitte noch einmal.</p>"),

	// send message
	"addmessage" => array("success", "<p><strong>Ihre Nachricht ist auf den Weg...!</p>"),
	"addmessageerror" => array("warn", "<p><strong>Ihre Nachricht wurde noch nicht abgeschickt, versuchen sie es noch einmal.</p>"),

);

$errors['en'] = array(

	"success" => array("success", "<h2>Thank You</h2><p>Your message has been sent.</p>"),
	"warn" => array("warn", "<h2>Error</h2><p>There was a problem with you submission. Please try again.</p>"),


	// login errors
	"notactive" => array("warn", "<p>Your account has not been activated yet. We will notify you when your account has been activated.</p>"),
	"nouser" => array("warn", "<p>That username &amp; password is not correct. Please try again.</p>"),
	"nouser2" => array("warn", "<p>Please enter your email and password to login.</p>"),
	"notauth" => array("warn", "<p>You must be logged in to view the requested page.</p>"),
	"loggedout" => array("success", "<p>You have logged out.</p>"),
	"emptyemail" => array("warn", "<p>Please enter your email address</p>"),
	"req_create_error" => array("warn", "<p>There was a problem</p>"),
	"req_create_success" => array("success", "<p><strong>Step one complete</strong> Now enter your location to narrow your request to shops close by.</p>"),

	// update phone number name
	"addname" => array("success", "<p><strong>Name added successfully</p>"),
	"addnameerror" => array("warn", "<p><strong>There was a problem adding the name. Please try again</p>"),

	// send message
	"addmessage" => array("success", "<p><strong>Message has been scheduled to be sent.</p>"),
	"addmessageerror" => array("warn", "<p><strong>Message not scheduled. Please try again.</p>"),


);


// HOMEPAGE

$lang['headline1']['de'] = 'Bekommen sie mehr Bewertungen von Ihren Kunden';
$lang['headline1']['en'] = 'Get more reviews from your customers';

$lang['home-main']['de'] = '<h2>Warum sind Online-Bewertungen so wichtig?</h2>

<p>Um die eigene Sichtbarkeit zu steigern und mehr Kunden zu gewinnen sind Google-Bewertungen und Rezensionen anderer Plattformen wichtig. </p>
<p>Jede 5. Suchanfrage in Deutschland hat mittlerweile einen lokalen Bezug</p>
<p>Die Anzahl der Sterne und die Anzahl Ihrer Bewertungen ist einfach ein Blickfang.</p>
<p>88% der Internetnutzer haben bereits Bewertungen von lokalen Unternehmen gelesen, 35% sogar regelmäßig (Quelle: searchengineland.com)</p>
<p>Ganze 85% der Internetnutzer vertrauen sogar Online-Bewertungen, lassen sich positiv und negativ davon beeinflussen (Quelle: <a href="https://www.brightlocal.com/learn/local-consumer-review-survey/" target="_blank">brightlocal.com</a>)


<h3>So! Sie haben schon ein paar Bewertungen?</h3>
<p>Prima! Sie haben schon drei \'5-Sterne\' Bewertungen bekommen. Ihre Konkurrenz kommt aber nur auf ein Durchschnitt von 4.7.</p>
<p>Ist doch gut, oder?</p>
<p>Ist\'s nicht, denn Ihre Konkurrenz hat bereits 80 Bewertungen bekommen!</p>
';

$lang['home-main']['en'] = '<h2>Why are online reviews so important?</h2>

<p>Google reviews and reviews of other platforms are important to increase visibility and attract more customers.</p>
<p>Every fifth search query now has a local reference</p>
<p>The number of stars and the number of reviews is simply an eye-catcher.</p>
<p>88% of Internet users have already read reviews from local companies, 35% even regularly (source: searchengineland.com)</p>
<p>As many as 85% of Internet users even trust online reviews, they can be positively and negatively influenced (source: <a href="https://www.brightlocal.com/learn/local-consumer-review-survey/" target="_blank">brightlocal.com</a>)</p>

<h3>So! You\'ve already got a few reviews?</h3>
<p>Great! You have already received three \'5-star\' ratings. Your competition comes only to an average of 4.7</p>
<p>That\'s good, right?</p>
<p>It\'s not, because your competition has already received 80 reviews!</p>';



$lang['home-main2']['de'] = '
<h2>Wie können Ihre Kunden denn im Moment Bewertungen hinterlassen?</h2>
<p>Ihr Kunde muss zu allererst einmal Ihr Geschäft auf den unterschiedlichen Portalen finden, im Falle Google\'s wahrscheinlich etwas einfacher. Einmal darauf gelandet, kann man auch eine Bewertung abgeben. Bei den anderen Portalen kann es schwieriger werden!</p>
<p>Wo liegt das Problem?</p>
<p>Niemand hat Zeit, wenige nehmen sie sich. Es sei denn, wir machen es ihnen einfach!</p>
';

$lang['home-main2']['en'] = '
<h2>How can your customers leave feedback at the moment?</h2>
<p>First and foremost, your customer has to find your business on all the different portals, in the case of Google\'s probably a little easier. Once landed on it, they need to find your business name and finally can give a rating. With the other portals it can be more difficult!
<p>Where is the problem?</p>
<p>No one has the time, few take it. Unless we make it easy!</p>
';


$lang['home-main3']['de'] = '
<h2>So geht\'s einfacher...!</h2>

<p>Einfacher wäre es für Ihren Kunden wenn sie nur einen Link zu Ihrer Bewertungsseite übergeben könnten (z.B. www.daskennich.de/[namen], und egal auf wievielen unterschiedlichen Portalen sie erreichbar sind, alle sind mit einem "Click" erreichbar. Nun sind alle Bewertungsportal-Logos sichtbar auf nur einer Seite!</p>
<p>Ihre Kundschaft kann nun ganz einfach über\'s Handy Bewertungen abgeben, ohne viel Zeit zu investieren.</p>


<h3>Link übergeben? Wie denn?</h3>

<p>Um Ihren Bewertungslink zu übergeben gibt es viele Möglichkeiten...</p>

<ul>
	<li>ein Hinweisschild an der Kasse oder am Ausgang</li>
	<li>Tisch-Hinweisschilder für Kunden, während sie auf Ihre Bestellung warten</li>
	<li>Integration von Kassen-Systemen, wir reden gern mit Ihrem Software-Anbieter!</li>
	<li>direktes Versenden von Text-Nachrichten durch unser Web-Portal</li>
	<li>nutzten sie QR-codes (keine Sorge, das ist einfach - wir helfen ihnen)</li>

</ul>';

$lang['home-main3']['en'] = '
<h2>There is an easier way.... this is how it works ...!</h2>
<p>It would be much simpler for your customers if you could only provide them with a page-link (eg www.frogswing.com/[name], and no matter how many different portals you are reachable, all are accessible with one "click" on one logo - visible on just one page!</p>
<p>Your customers can now easily submit their reviews via mobile-phones  without investing much time.</p>
<h3>Pass a link? How?</h3>
<p>There are many ways to pass your rating link...</p>
<ul>
	<li>a sign on the counter or at the exit-door</li>
	<li>Table signage for customers while they wait for their order</li>
	<li>Integration of cash register systems... we like talking to your software provider!</li>
	<li>sending of text messages through our web portal</li>
	<li>use of QR codes (do not worry, it\'s easy - we help you)</li>
</ul>

';


$lang['home-main4']['de'] = '
<h2>Wir helfen ihnen gern!</h2>
<p>Haben sie erst einmal Ihr Konto auf unserer Seite eröffnet, können wir Ihre Bewertungslinks in den entsprechenden Felder konfigurieren.</p>
<p>Wir helfen ihnen gern und machen es für sie, wir benötigen dann nur Ihren Geschäftsnamen und Anschrift, dann tun wir das gern alles für sie!</p>
';

$lang['home-main4']['en'] = '
<h2>We would like to help you!</h2>
<p>Once you\'ve opened your account on our site, we can configure your rating links in the appropriate fields.</p>
<p>We like to help and do it for you, we only need your business name and address, we gladly do that all for you!</p>
';



$lang['home-mitmachen']['de'] = 'Mitmachen? Fragen? Wir hören zu!';
$lang['home-mitmachen']['en'] = 'Want to join? Questions? We are listening!';

$lang['message']['de'] = 'Ihre Nachricht zu uns...';
$lang['message']['en'] = 'your message...';

$lang['send']['de'] = 'Absenden...!';
$lang['send']['en'] = 'Send...!';


// LOGIN

$lang['login-headline']['de'] = 'Login';
$lang['login-headline']['en'] = 'Login';


$lang['login-submit']['de'] = 'Login';
$lang['login-submit']['en'] = 'Login';
