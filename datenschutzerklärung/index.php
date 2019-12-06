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
		    
			<h4>Datenschutzerklärung</h4>
			
			<br>
			
			<h4 style="margin: 0!important;">Allgemeiner Hinweis und Pflichtinformationen</h4>
			<h4 style="margin-top: 9px;">Benennung der verantwortlichen Stelle</h4>
			
			<span>Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:</span>
			<br>
			
			
            
           
           
            
            <p>
			    <span>InCom Technical Solutions Inc..</span>
                <span>Thomas Roethling</span>
                <span> 509 Glendale Ave East, Suite 204</span>
                <span> Niagara-on-the-Lake, Ontario</span>
                <span> L0S 1J0, Canada</span>
			</p>

			<p>
			    Die verantwortliche Stelle entscheidet allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z.B. Namen, Kontaktdaten o. Ä.).
			</p>
			
			
			<h4 style="margin: 18px 0;">Widerruf Ihrer Einwilligung zur Datenverarbeitung</h4>
			<p>
			    Nur mit Ihrer ausdrücklichen Einwilligung sind einige Vorgänge der Datenverarbeitung möglich. Ein Widerruf Ihrer bereits erteilten Einwilligung ist jederzeit möglich. Für den Widerruf genügt eine formlose Mitteilung per E-Mail. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.
			</p>
			
			
			<h4 style="margin: 18px 0;">Recht auf Beschwerde bei der zuständigen Aufsichtsbehörde</h4>
			<p>
			    Als Betroffener steht Ihnen im Falle eines datenschutzrechtlichen Verstoßes ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu. Zuständige Aufsichtsbehörde bezüglich datenschutzrechtlicher Fragen ist der Landesdatenschutzbeauftragte des Bundeslandes, in dem sich der Sitz unseres Unternehmens befindet. Der folgende Link stellt eine Liste der Datenschutzbeauftragten sowie deren Kontaktdaten bereit:
			    <a href="https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html">https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html</a>.
			</p>
			
			
			<h4 style="margin: 18px 0;">Recht auf Datenübertragbarkeit</h4>
            <p>
                Ihnen steht das Recht zu, Daten, die wir auf Grundlage Ihrer Einwilligung oder in Erfüllung eines Vertrags automatisiert verarbeiten, an sich oder an Dritte aushändigen zu lassen. Die Bereitstellung erfolgt in einem maschinenlesbaren Format. Sofern Sie die direkte Übertragung der Daten an einen anderen Verantwortlichen verlangen, erfolgt dies nur, soweit es technisch machbar ist.
            </p>
			
			
			<h4 style="margin: 18px 0;">Recht auf Auskunft, Berichtigung, Sperrung, Löschung</h4>
            <p>
                Sie haben jederzeit im Rahmen der geltenden gesetzlichen Bestimmungen das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, Herkunft der Daten, deren Empfänger und den Zweck der Datenverarbeitung und ggf. ein Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Diesbezüglich und auch zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit über die im Impressum aufgeführten Kontaktmöglichkeiten an uns wenden.
            </p>
			
			<h4 style="margin: 18px 0;">	SSL- bzw. TLS-Verschlüsselung</h4>
			<p>
                Aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte, die Sie an uns als Seitenbetreiber senden, nutzt unsere Website eine SSL-bzw. TLS-Verschlüsselung. Damit sind Daten, die Sie über diese Website übermitteln, für Dritte nicht mitlesbar. Sie erkennen eine verschlüsselte Verbindung an der „https://“ Adresszeile Ihres Browsers und am Schloss-Symbol in der Browserzeile.			    
			</p>
			
			
			<h4 style="margin: 18px 0;">Server-Log-Dateien</h4>
			<p>
			    In Server-Log-Dateien erhebt und speichert der Provider der Website automatisch Informationen, die Ihr Browser automatisch an uns übermittelt. Dies sind:
			</p>
			
			<ul>
			    <li>Besuchte Seite auf unserer Domain</li>
			    <li>Datum und Uhrzeit der Serveranfrage</li>
			    <li>Browsertyp und Browserversion</li>
			    <li>Verwendetes Betriebssystem</li>
			    <li>Referrer URL</li>
			    <li>Hostname des zugreifenden Rechners</li> 
			    <li>IP-Adresse</li>
			</ul>
		    
            <p>
                Es findet keine Zusammenführung dieser Daten mit anderen Datenquellen statt. Grundlage der Datenverarbeitung bildet Art. 6 Abs. 1 lit. b DSGVO, der die Verarbeitung von Daten zur Erfüllung eines Vertrags oder vorvertraglicher Maßnahmen gestattet.
            </p>
            
           
            <h4 style="margin: 18px 0;"> Kontaktformular</h4>
            
            <p>
                Per Kontaktformular übermittelte Daten werden einschließlich Ihrer Kontaktdaten gespeichert, um Ihre Anfrage bearbeiten zu können oder um für Anschlussfragen bereitzustehen. Eine Weitergabe dieser Daten findet ohne Ihre Einwilligung nicht statt.
            </p>
            
            <p>
                Die Verarbeitung der in das Kontaktformular eingegebenen Daten erfolgt ausschließlich auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Ein Widerruf Ihrer bereits erteilten Einwilligung ist jederzeit möglich. Für den Widerruf genügt eine formlose Mitteilung per E-Mail. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitungsvorgänge bleibt vom Widerruf unberührt.
            </p>
            
            <p>
                Über das Kontaktformular übermittelte Daten verbleiben bei uns, bis Sie uns zur Löschung auffordern, Ihre Einwilligung zur Speicherung widerrufen oder keine Notwendigkeit der Datenspeicherung mehr besteht. Zwingende gesetzliche Bestimmungen - insbesondere Aufbewahrungsfristen - bleiben unberührt.
            </p>
            
            
            <h4 style="margin: 18px 0;"> YouTube</h4>
            <p>
                Für Integration und Darstellung von Videoinhalten nutzt unsere Website Plugins von YouTube. Anbieter des Videoportals ist die YouTube, LLC, 901 Cherry Ave., San Bruno, CA 94066, USA.
            </p>
            
            <p>
                Bei Aufruf einer Seite mit integriertem YouTube-Plugin wird eine Verbindung zu den Servern von YouTube hergestellt. YouTube erfährt hierdurch, welche unserer Seiten Sie aufgerufen haben.           
            </p>
            
            <p>
			    YouTube kann Ihr Surfverhalten direkt Ihrem persönlichen Profil zuzuordnen, sollten Sie in Ihrem YouTube Konto eingeloggt sein. Durch vorheriges Ausloggen haben Sie die Möglichkeit, dies zu unterbinden.
			</p>
			
			<p>
			    Die Nutzung von YouTube erfolgt im Interesse einer ansprechenden Darstellung unserer Online-Angebote. Dies stellt ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO dar.
            </p>
            
            <p>
                Einzelheiten zum Umgang mit Nutzerdaten finden Sie in der Datenschutzerklärung von YouTube unter:
                <a>https://www.google.de/intl/de/policies/privacy.</a>
            </p>
                

			<h4 style="margin: 18px 0;"> Google Analytics</h4>
		    <p>
		        Unsere Website verwendet Funktionen des Webanalysedienstes Google Analytics. Anbieter des Webanalysedienstes ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA.
		    </p>
		    
		    <p>
		        Google Analytics verwendet "Cookies." Das sind kleine Textdateien, die Ihr Webbrowser auf Ihrem Endgerät speichert und eine Analyse der Website-Benutzung ermöglichen. Mittels Cookie erzeugte Informationen über Ihre Benutzung unserer Website werden an einen Server von Google übermittelt und dort gespeichert. Server-Standort ist im Regelfall die USA.
		    </p>
		    
		    <p>
		        Das Setzen von Google-Analytics-Cookies erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Als Betreiber dieser Website haben wir  ein berechtigtes Interesse an der Analyse des Nutzerverhaltens, um unser Webangebot und ggf. auch Werbung zu optimieren.
		    </p>
    
            <p>
                IP-Anonymisierung
            </p>
            
            <p>
                Wir setzen Google Analytics in Verbindung mit der Funktion IP-Anonymisierung ein. Sie gewährleistet, dass Google Ihre IP-Adresse innerhalb von Mitgliedstaaten der Europäischen 
            </p>
            
            <p>
                Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum vor der Übermittlung in die USA kürzt. Es kann Ausnahmefälle geben, in denen Google die volle IP-Adresse an einen Server in den USA überträgt und dort kürzt. In unserem Auftrag wird Google diese Informationen benutzen, um Ihre Nutzung der Website auszuwerten, um Reports über Websiteaktivitäten zu erstellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen gegenüber uns zu erbringen. Es findet keine Zusammenführung der von Google Analytics übermittelten IP-Adresse mit anderen Daten von Google statt.

            </p>
            
            <p>
                Browser Plugin
            </p>    
            
            <p>
                Das Setzen von Cookies durch Ihren Webbrowser ist verhinderbar. Einige Funktionen unserer Website könnten dadurch jedoch eingeschränkt werden. Ebenso können Sie die Erfassung von Daten bezüglich Ihrer Website-Nutzung einschließlich Ihrer IP-Adresse mitsamt anschließender Verarbeitung durch Google unterbinden. Dies ist möglich, indem Sie das über folgenden Link erreichbare Browser-Plugin herunterladen und installieren: 
                <a>https://tools.google.com/dlpage/gaoptout?hl=de.</a>

            </p>
            
            <p>
                Widerspruch gegen die Datenerfassung
            </p>
            
            <p>
                Sie können die Erfassung Ihrer Daten durch Google Analytics verhindern, indem Sie auf folgenden Link klicken. Es wird ein Opt-Out-Cookie gesetzt, der die Erfassung Ihrer Daten bei zukünftigen Besuchen unserer Website verhindert: Google Analytics deaktivieren.
            </p>
            
            <p>
                Einzelheiten zum Umgang mit Nutzerdaten bei Google Analytics finden Sie in der Datenschutzerklärung von Google: https://support.google.com/analytics/answer/6004245?hl=de.
                <a>https://support.google.com/analytics/answer/6004245?hl=de.</a>
            </p>
            
            <p>
                Auftragsverarbeitung
            </p>
            
            <p>
               Zur vollständigen Erfüllung der gesetzlichen Datenschutzvorgaben haben wir mit Google einen Vertrag über die Auftragsverarbeitung abgeschlossen.
            </p>
            
            <p>
              Demografische Merkmale bei Google Analytics
            </p>
            
            <p>
              Unsere Website verwendet die Funktion “demografische Merkmale” von Google Analytics. Mit ihr lassen sich Berichte erstellen, die Aussagen zu Alter, Geschlecht und Interessen der Seitenbesucher enthalten. Diese Daten stammen aus interessenbezogener Werbung von Google sowie aus Besucherdaten von Drittanbietern. Eine Zuordnung der Daten zu einer bestimmten Person ist nicht möglich. Sie können diese Funktion jederzeit deaktivieren. Dies ist über die Anzeigeneinstellungen in Ihrem Google-Konto möglich oder indem Sie die Erfassung Ihrer Daten durch Google Analytics, wie im Punkt “Widerspruch gegen die Datenerfassung” erläutert, generell untersagen.  
            </p>
            
            <h4 style="margin: 18px 0;"> PayPal</h4>
            
            <p>
               Unsere Website ermöglicht die Bezahlung via PayPal. Anbieter des Bezahldienstes ist die PayPal (Europe) S.à.r.l. et Cie, S.C.A., 22-24 Boulevard Royal, L-2449 Luxembourg.
            </p>
            
            <p>
               Wenn Sie mit PayPal bezahlen, erfolgt eine Übermittlung der von Ihnen eingegebenen Zahlungsdaten an PayPal. 
            </p>
            
            <p>
               Die Übermittlung Ihrer Daten an PayPal erfolgt auf Grundlage von Art. 6 Abs. 1 lit. a DSGVO (Einwilligung) und Art. 6 Abs. 1 lit. b DSGVO (Verarbeitung zur Erfüllung eines Vertrags). Ein Widerruf Ihrer bereits erteilten Einwilligung ist jederzeit möglich. In der Vergangenheit liegende Datenverarbeitungsvorgänge bleiben bei einem Widerruf wirksam. 
            </p>
        
			<h4 style="margin: 18px 0;"> Google AdWords und Google Conversion-Tracking</h4>
			
			<p>
			    Unsere Website verwendet Google AdWords. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, United States.
			</p>
			
			<p>
			  AdWords ist ein Online-Werbeprogramm. Im Rahmen des Online-Werbeprogramms arbeiten wir mit Conversion-Tracking. Nach einem Klick auf eine von Google geschaltete Anzeige wird ein Cookie für das Conversion-Tracking gesetzt. Cookies sind kleine Textdateien, die Ihr Webbrowser auf Ihrem Endgerät speichert. Google AdWords Cookies verlieren nach 30 Tagen ihre Gültigkeit und dienen nicht der persönlichen Identifizierung der Nutzer. Am Cookie können Google und wir erkennen, dass Sie auf eine Anzeige geklickt haben und zu unserer Website weitergeleitet wurden.
 
			</p>
		    	
			<p>
			   Jeder Google AdWords-Kunde erhält ein anderes Cookie. Die Cookies sind nicht über Websites von AdWords-Kunden nachverfolgbar. Mit Conversion-Cookies werden Conversion-Statistiken für AdWords-Kunden, die Conversion-Tracking einsetzen, erstellt. Adwords-Kunden erfahren wie viele Nutzer auf ihre Anzeige geklickt haben und auf Seiten mit Conversion-Tracking-Tag weitergeleitet wurden. AdWords-Kunden erhalten jedoch keine Informationen, die eine persönliche Identifikation der Nutzer ermöglichen. Wenn Sie nicht am Tracking teilnehmen möchten, können Sie einer Nutzung widersprechen. Hier ist das Conversion-Cookie in den Nutzereinstellungen des Browsers zu deaktivieren. So findet auch keine Aufnahme in die Conversion-Tracking Statistiken statt.
			</p>
			   
			<p>
			    Die Speicherung von “Conversion-Cookies” erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Wir als Websitebetreiber haben ein berechtigtes Interesse an der Analyse des Nutzerverhaltens, um unser Webangebot und unsere Werbung zu optimieren.
			</p>
			    
			<p>
			    Einzelheiten zu Google AdWords und Google Conversion-Tracking finden Sie in den Datenschutzbestimmungen von Google: 
			    <a hreff="https://www.google.de/policies/privacy/">https://www.google.de/policies/privacy/.</a>    
			</p>
			
			<p>
			    Mit einem modernen Webbrowser können Sie das Setzen von Cookies überwachen, einschränken oder unterbinden. Die Deaktivierung von Cookies kann eine eingeschränkte Funktionalität unserer Website zur Folge haben.
			</p>
			
			<h4 style="margin: 18px 0;"> Google Web Fonts</h4>
			
			<p>
			    Unsere Website verwendet Web Fonts von Google. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA
			</p>
			
			<p>
			    Durch den Einsatz dieser Web Fonts wird es möglich Ihnen die von uns gewünschte Darstellung unserer Website zu präsentieren, unabhängig davon welche Schriften Ihnen lokal zur Verfügung stehen. Dies erfolgt über den Abruf der Google Web Fonts von einem Server von Google in den USA und der damit verbundenen Weitergabe Ihre Daten an Google. Dabei handelt es sich um Ihre IP-Adresse und welche Seite Sie bei uns besucht haben. Der Einsatz von Google Web Fonts erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Als Betreiber dieser Website haben wir ein berechtigtes Interesse an der optimalen Darstellung und Übertragung unseres Webauftritts.
 
			</p>
			
			<p>
			    Das Unternehmen Google ist für das us-europäische Datenschutzübereinkommen "Privacy Shield" zertifiziert. Dieses Datenschutzübereinkommen soll die Einhaltung des in der EU geltenden Datenschutzniveaus gewährleisten.
			</p>
			
			<p>
			    Einzelheiten über Google Web Fonts finden Sie unter: 
			    <a href="https://www.google.com/fonts#AboutPlace">https://www.google.com/fonts#AboutPlace</a>:about und weitere Informationen in den 
			    Datenschutzbestimmungen von Google:
			    <a hreff="https://policies.google.com/privacy/partners?hl=de">https://policies.google.com/privacy/partners?hl=de</a>

			</p>
			
		</div>
		
	</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

</body>
</html>
