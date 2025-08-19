<?php
//
// ***** CONFIGURATON ************************************
//
// edit the information in this section according to your
// installation.
//
// Lines starting with "//" are remarks, lines starting with "#" are examples.
if (!defined('CONFTOOL')) die('Hacking attempt!');

// --- paths -----------

// [paths/prefix] points to the installation path for the
// conftool. This should be an absolute path.
$ctconf['paths/prefix'] = '../';
// For Windows, e.g.:
#$ctconf['paths/prefix'] = 'D:/www/conftool/conftool/';

// [paths/etc] points to the directory where configuration
// files and language files are found. Relative path is appended to [paths/prefix].
// Usually you can keep the default setting!
$ctconf['paths/etc'] = 'etc/';

// [paths/uploads] is a directory where the tool will
// store files uploaded by users when they submit a paper
// to the conference.
$ctconf['paths/uploads'] = 'uploads/';

// [paths/lib] points to the directory, where class
// definitions and other libraries can be found. A relative
// path is appended to the [paths/prefix] directory.
$ctconf['paths/lib'] = 'lib/';

// [paths/lib] points to the directory, where class
// definitions and other libraries can be found. A relative
// path is appended to the [paths/prefix] directory.
$ctconf['paths/classes'] = 'classes/';

// [paths/pages] points to the directory, where page
// definitions can be found that are included by the
// dispatcher page. A relative path is appended to the
// [paths/preifx] directory.
$ctconf['paths/pages'] = 'pages/';


// --- database -----------

// [db/dbms] sets which DBMS should be used. Mappings to
// different databases are provided as library files that
// implement generic db-functions. At the moment, only mysql
// is supported.
$ctconf['db/dbms'] = 'mysql';

// [db/dbhost] holds the server's name where the database server
// is running.
//$ctconf['db/host'] = 'home.sit.auckland.ac.nz';
$ctconf['db/host'] = 'localhost';

// [db/dbport] is the port number where the database server
// can be reached.
$ctconf['db/port'] = 3306;

// [db/database] is the name of the database on the DBMS where
// the conftool stores its data.
$ctconf['db/database'] = 'cs_ivs';

// [db/username] is the username used when connecting to the
// database server.
$ctconf['db/username'] = 'cs_ivs';

// [db/password] is the password used when connecting to the server.
$ctconf['db/password'] = 'sdjh86637';

// Allow persistent connections or not?
// Disable if you may have problems with the maximum number of mysql connections on your server
// Persistent connections are usually  (a little bit) faster.
$ctconf['db/persistent'] = false;  # true

// Please note: You find a DATABASE BACKUP SCRIPT in "install/backup_database.sh"

// --- language, charset etc. -----------

// [web/baseurl] is the absolute URL where the index-page can be found.
// ATTENTION: Leave empty if you want to disable a redirect to this URL and
// let conftool use the current URL als base URL.
#$ctconf['web/baseurl'] = 'https://www.conftool.net/demo/standard/'; // If you want to redirect to this URL (https).
$ctconf['web/baseurl'] = 'http://www.ivs.auckland.ac.nz/ivcnz2011_temp/htdocs/';

// A short string to identify the session name of this conftool instance.
// Just leave empty if you have only one conference on your server, otherwise
// Use a unique short alphanumeric string in upper case.
#$ctconf['web/sessionname'] = 'DEMO2009';
$ctconf['web/sessionname'] = '';  // leave empty if you handle only one conference.

// [language] sets the language to be used for the tool.
// A corresponding language definition file must be available in [paths/etc].
// If [language] is set to 'german', there has to be a file called 'german-utf8.lang'.
// If you need another language, please contact us.
$ctconf['language'] = 'english'; // The english language file has only ASCII characters, so it's also UTF-8 compatible.
#$ctconf['language'] = 'german-utf8';

// Character set of language file and database connection.
//Please note: I now recommend  using UTF-8 for most languages as it is
//commonly supported by most browsers and systems now.
#$ctconf['charset']='iso-8859-1'; // OUTDATED! If required, you MUST use the corresponding 8bit language file and also iso-8859-1 as database encoding!
$ctconf['charset']='UTF-8';

// [web/defaultcountry] is the default country for the registration process. Leave empty for no default country.
$ctconf['web/defaultcountry'] = 'New Zealand';

// Show the State / Territory / Province selection (US/CA/AU) on registration page?
// Disable if you don't expect participants from these countries...
$ctconf['web/liststates'] = true;



// --- Formatting of dates, times, numbers and currencies ---------------

// Output of formatted dates:
$ctconf['dateformat']='d/M/Y'; 		# English Format 24/Dec/2011
#$ctconf['dateformat']='M/d/Y'; 	# American Format Dec/24/2011
#$ctconf['dateformat']='d.m.Y';		# German Format 24.12.2011
#$ctconf['dateformat']='d/m/Y'; 	# International format: 24/04/2011

// Output of formatted date & time
$ctconf['datetimeformat']='jS M Y, h:i:sa'; # English format: 24th Apr 2011 08:59:59pm
#$ctconf['datetimeformat']='M/d/Y, h:i:sa'; # American format: Apr/24/2011 08:59:59pm
#$ctconf['datetimeformat']='d.M Y H:i:s'; 	# German format: 24. Apr 2011 20:59:59
#$ctconf['datetimeformat']='d/m/Y H:i:s'; 	# International format: 24/04/2011 20:59:59


// decimal number formatting. International: (1.234,50)
$ctconf['currency/decimals'] = '2';	   //  Default nmber of digits after the "dot" for currencies (e.g. for cents)
$ctconf['currency/decimal_point'] = ',';  // Decimal separator. "." for USA&GB, "," for most other countries.
$ctconf['currency/thousands_separator'] = '.';  // Separator symbol for thousands. Can also be empty.
// English / American Format:
#$ctconf['currency/decimals'] = '2';	   //  Default nmber of digits after the "dot" for currencies (e.g. for cents)
#$ctconf['currency/decimal_point'] = '.';  // Decimal separator. "." for USA&GB, "," for most other countries.
#$ctconf['currency/thousands_separator'] = ',';  // Separator symbol for thousands. Can also be empty.


// "currency/style": Style for currency output: 0="EUR 123.50", 1="123.50 EUR"
$ctconf['currency/style']='0'; // US format: "$ 123.50"
#$ctconf['currency/style']='1'; // German format: "123.50 EUR"



// --- e-mail settings -------------

// Main e-mail contact address. PLEASE REPLACE!
$ctconf['mail/contact'] = 'info@ivs.auckland.ac.nz';

// Send a BCC of every email to the following address
// This is very useful to have a log of all mails sent. Leave empty to disable.
$ctconf['mail/bcc'] = ''; // example: 'mail-backups@yourconference.org';

// Do also check the existance of the domain name of the e-mail address during user registration?
// DISABLE this if you have problem entering emails or registering new users (as it does not work an all systems).
// Recommended is true!
$ctconf['mail/checkdns'] = true;

// Use the PHPMailer library instead of build-in php mail support?
// I recommend to use PHPMailer instead of the build-in mail function
// of php as the former is far more advanced and allows for special
// character encoding, authentification, html emails and much more.
// But, you might have to use the build-in php mail function, if the
// firewall settings of your server are very restrictive (e.g. the German
// company 1und1 has such limitations).
$ctconf['mail/phpmailer'] = true;  // Default is true

// The following mail settings are for PHPMailer only!
// Your SMTP host and port
$ctconf['mail/smtphost'] = 'localhost';
#$ctconf['mail/smtpport'] = 587;

// Use 'tls', 'ssl', 'sslv2', or 'sslv3' if required.
// Please note: PHP has to be compiled with SSL support enabled (not available for windows)
#$ctconf['mail/smtpsecure'] = 'ssl';

// Use a different hostname for message ID creation and received path.
// Usually NOT required.
#$ctconf['mail/hostname'] = 'maildomain.edu'; // Enter your domain if required.

// SMTP Authentification required? (PHPMailer only)
$ctconf['mail/SMTPAuth'] = false;
$ctconf['mail/username'] = 'your_email_username';
$ctconf['mail/password'] = 'your_email_password';

// Add alternative host, used if the normal host temporarily fails (PHPMailer only). Usually NOT required!
#$ctconf['mail/smtphost2'] = 'localhost';
#$ctconf['mail/smtpsecure2'] = false; // 'ssl'
#$ctconf['mail/hostname2'] = 'conftool.com';
#$ctconf['mail/SMTPAuth2'] = false;
#$ctconf['mail/username2'] = '';
#$ctconf['mail/password2'] = '';

// IMPORTANT: Please TEST if you mail settings work!, e.g. by using the
// file info.php in the htdocs directory and/or the "Lost Password" function!



// --- Paper submission -----------

// Disable if you do NOT want to use paper submissions and reviewing functions at all.
$ctconf['submission/enabled'] = true; // true or false

// Limit the maximum file size for uploads.
// You may also have to modify php.ini or .htacces or settings.php!!
// (See installation instructions and FAQs and you have to TEST BIG UPLOADS!)
$ctconf['paperupload/maxsize'] = 10000000;

// Again: Please TEST if up and downloads work. Also test with bigger files (e.g. a 9MB file)!
// Please consider also the output of info.php in the htodocs-directory.


// --- Reviewing -----------

// Anonymous / double-blind reviews?
$ctconf['review/anonymous'] = true; // TRUE for double-blind reviews (DEFAULT!)
									// FALSE if reviewers may see the authors.


// --- participant registration ------------

// Enable the  participant registration module for this conference?
// Only disable, if you don't need it at all.
$ctconf['participation/enabled'] = true;

// Request payment information during registration.
// If set to FALSE all participants can register for free and no payment information will be shown.
$ctconf['payment/enabled'] = true; // TRUE or FALSE

// Enable VAT for this conference?
// Usually non-commercial events do not need to charge VAT, as your financial advisor. :-)
$ctconf['participation/vat'] = false;

// --- debugging etc. -------------------------

$ctconf['demomode']=false;			// lock critical backoffice functions
$ctconf['language/debug']=false;    // reload language file for every page (SLOW!)
$ctconf['display/debug']=false;		// show all queries.
$ctconf['offline']=false; 			// use to set temporarily offline
$ctconf['offline/message']="<H3>The System is currently undergoing maintenance.</h3><h4>Please come back in about 5 minutes</h4>";  // Custom message to show to user.

//
//==========================================================================
// User-configuration ends here.
//==========================================================================
//

// ATTENTION: Avoid any spaces and empty line after the next line!!!
?>
