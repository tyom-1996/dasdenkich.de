<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";

/*$message = '';
if($_GET['msg']=="success") {
	$message = '<div class="success">'."\n"
				.$lang['success'][$l]
				.'</div>';
} elseif($_GET['msg']=="error") {

	$message = '<div class="error">'."\n"
				.$lang['warn'][$l]
				.'</div>';
}*/

$message = '';
if(!empty($_GET['msg'])) {

	$message = '<div class="'.$errors[$l][$_GET['msg']][0].'">'
				.$errors[$l][$_GET['msg']][1]
				.'</div>';
}


$thisPage = "home";
?><!DOCTYPE html>
<html lang="de">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
</head>

<style>
    .content-wrapper > p > span {
        display:block;
        line-height: 25px;
    }
    .content-wrapper {
        color: black;
        font-size: 18px;
    }
</style>

<body class='eeeeee' id="<?php echo $thisPage; ?>">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>


	<div id="hero">
		<div class="hero-content">

			<h1><?php echo $lang['headline1'][$l]; ?></h1>
			<img src="/images/phones-<?php echo $l; ?>.png" class="phones">
		</div>

			<video autoplay preload muted loop>
			 <source src="/video/background2.mp4" type="video/mp4">
			</video>

	</div>
	<div id="main" style="padding: 30px 0;" class="home-section">
	    
		<div class='content-wrapper'>
			<h4>Impressum</h4>
			
			<span style="text-decoration: underline;">Angaben gem. § 5 TMG:</span>
			</br>
			<p>
			    <span>InCom Technical Solutions Inc.</span>
                <span>509 Glendale Ave East, Suite 204</span>
                <span>Niagara-on-the-Lake, Ontario</span>
                <span>L0S 1J0, Canada</span>
			</p>
			<p>
			    <span>Anspechpartner</span>
                <span>Thomas Roethling</span>
                <span>Max-Roscher-Steasse 18</span>
                <span>09599 Freiberg</span>
			</p>
            <span style="text-decoration: underline;">Kontaktaufnahme:</span>
            </br>
            <p>
			    <span>Telefon: +1 905-933-0667</span>
                <span>E-Mail: thomas@ incom.ca</span>
			</p>
			
			
            <h4>Umsatzsteuer-ID</h4>
            
            <span style="text-decoration: underline;">Umsatzsteuer-Identifikationsnummer gem. § 27 a Umsatzsteuergesetz:</span>
			</br>
			</br>
			<span>Bei dieser Webseite handelt es sich nicht um eine kommerziellen Seite</span>
			
			
			<h4>Haftungsausschluss - Disclaimer:</h4>
			
			<span style="text-decoration: underline;">Haftung für Inhalte</span>
			
			<br>
			<p style="max-width: 777px;line-height: 32px;">
			    Alle Inhalte unseres Internetauftritts wurden mit größter Sorgfalt und nach bestem Gewissen erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt.
                Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntniserlangung einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von den o.g. Rechtsverletzungen werden wir diese Inhalte unverzüglich entfernen.
			</p>
			<br>
			<span style="text-decoration: underline;">	Haftungsbeschränkung für externe Links</span>
            <br>
            
            <p style="max-width: 777px;line-height: 32px;" >
                Unsere Webseite enthält Links auf externe Webseiten Dritter. Auf die Inhalte dieser direkt oder indirekt verlinkten Webseiten haben wir keinen Einfluss. Daher können wir für die „www.daskennich.de“ auch keine Gewähr auf Richtigkeit der Inhalte übernehmen. Für die Inhalte der externen Links sind die jeweilige Anbieter oder Betreiber (Urheber) der Seiten verantwortlich.
                Die externen Links wurden zum Zeitpunkt der Linksetzung auf eventuelle Rechtsverstöße überprüft und waren im Zeitpunkt der Linksetzung frei von rechtswidrigen Inhalten. Eine ständige inhaltliche Überprüfung der externen Links ist ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht möglich. Bei direkten oder indirekten Verlinkungen auf die Webseiten Dritter, die außerhalb unseres Verantwortungsbereichs liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall nur bestehen, wenn wir von den Inhalten Kenntnis erlangen und es uns technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern.
                Diese Haftungsausschlusserklärung gilt auch innerhalb des eigenen Internetauftrittes „www.daskennich.de“ gesetzten Links und Verweise von Fragestellern, Blogeinträgern, Gästen des Diskussionsforums. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargestellten Informationen entstehen, haftet allein der Diensteanbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.
                Werden uns Rechtsverletzungen bekannt, werden die externen Links durch uns unverzüglich entfernt.

            </p>
            
            <br>
			<span style="text-decoration: underline;">Urheberrecht</span>
            <br>
            
            <p style="max-width: 777px;line-height: 32px;" >
               Die auf unserer Webseite veröffentlichen Inhalte und Werke unterliegen dem deutschen Urheberrecht
               (<a href="http://www.gesetze-im-internet.de/bundesrecht/urhg/gesamt.pdf">http://www.gesetze-im-internet.de/bundesrecht/urhg/gesamt.pdf</a>) . 
               Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung des geistigen Eigentums in ideeller und materieller Sicht des
               Urhebers außerhalb der Grenzen des Urheberrechtes bedürfen der vorherigen schriftlichen Zustimmung des jeweiligen Urhebers i.S.d. Urhebergesetzes
               (<a href="http://www.gesetze-im-internet.de/bundesrecht/urhg/gesamt.pdf">http://www.gesetze-im-internet.de/bundesrecht/urhg/gesamt.pdf</a> ). 
               Downloads und Kopien dieser Seite sind nur für den privaten und nicht kommerziellen Gebrauch erlaubt.
               Sind die Inhalte auf unserer Webseite nicht von uns erstellt wurden, sind die Urheberrechte Dritter zu beachten.
               Die Inhalte Dritter werden als solche kenntlich gemacht. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden,
               bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte unverzüglich entfernen.

            </p>
            
			
		</div>
		
	</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

</body>
</html>
