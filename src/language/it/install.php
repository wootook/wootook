<?php
/**
*
* install [Italian]
*
* @package language
* @version $Id: install.php 10152 2009-09-16 13:02:13Z acydburn $
* @copyright (c) 2005 phpBB Group
* @copyright (c) 2010 phpBB.it - translated on 2010-03-01
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ADMIN_CONFIG'				=> 'Configurazione amministratore',
	'ADMIN_PASSWORD'			=> 'Password amministratore',
	'ADMIN_PASSWORD_CONFIRM'	=> 'Conferma password amministratore',
	'ADMIN_PASSWORD_EXPLAIN'	=> 'La password deve contenere un minimo di 6 ed un massimo di 30 caratteri.',
	'ADMIN_TEST'				=> 'Controlla le impostazioni dell’amministratore',
	'ADMIN_USERNAME'			=> 'Nome utente amministratore',
	'ADMIN_USERNAME_EXPLAIN'	=> 'Il nome utente deve contenere un minimo di 3 ed un massimo di 20 caratteri.',
	'APP_MAGICK'				=> 'Supporto Imagemagick [ Allegati ]',
	'AUTHOR_NOTES'				=> 'Note autore<br />» %s',
	'AVAILABLE'					=> 'Disponibile',
	'AVAILABLE_CONVERTORS'		=> 'Convertitori disponibili',

	'BEGIN_CONVERT'					=> 'Inizia conversione',
	'BLANK_PREFIX_FOUND'			=> 'Una scansione delle tabelle evidenzia la presenza di un’installazione valida priva di prefisso tabelle.',
	'BOARD_NOT_INSTALLED'			=> 'Non è stata trovata alcuna installazione',
	'BOARD_NOT_INSTALLED_EXPLAIN'	=> 'Il phpBB Unified Convertor Framework richiede un’installazione predefinita di phpBB3 per funzionare, <a href="%s">inizia installando phpBB3</a>.',

	'CATEGORY'					=> 'Categoria',
	'CACHE_STORE'				=> 'Tipo di cache',
	'CACHE_STORE_EXPLAIN'		=> 'La locazione fisica dove i dati sono inseriti in cache, il filesystem è preferito.',
	'CAT_CONVERT'				=> 'Converti',
	'CAT_INSTALL'				=> 'Installa',
	'CAT_OVERVIEW'				=> 'Panoramica',
	'CAT_UPDATE'				=> 'Aggiorna',
	'CHANGE'					=> 'Modifica',
	'CHECK_TABLE_PREFIX'		=> 'Controlla il prefisso delle tabelle e riprova.',
	'CLEAN_VERIFY'				=> 'Pulizia e verifica della struttura finale',
	'CLEANING_USERNAMES'		=> 'Pulizia nomi utente',
	'COLLIDING_CLEAN_USERNAME'	=> '<strong>%s</strong> è il nome utente ripulito per:',
	'COLLIDING_USERNAMES_FOUND'	=> 'Nella vecchia board sono stati rilevati nomi utente in conflitto tra loro. Per poter portare a termine la conversione, cancella o rinomina questi utenti in modo che nella tua vecchia board ci sia un solo utente per ogni nome utente ripulito.',
	'COLLIDING_USER'			=> '» id utente: <strong>%d</strong> nome utente: <strong>%s</strong> (%d argomenti)',
	'CONFIG_CONVERT'			=> 'Il sistema sta convertendo la configurazione',
	'CONFIG_FILE_UNABLE_WRITE'	=> 'Non è stato possibile scrivere il file di configurazione. Nell’area sottostante vengono illustrati metodi alternativi per la creazione del file.',
	'CONFIG_FILE_WRITTEN'		=> 'Il file di configurazione è stato scritto. Puoi procedere ora alla prossima parte dell’installazione.',
	'CONFIG_PHPBB_EMPTY'		=> 'La variabile di configurazione phpBB3 per “%s” è vuota.',
	'CONFIG_RETRY'				=> 'Riprova',
	'CONTACT_EMAIL_CONFIRM'		=> 'Conferma contatto e-mail',
	'CONTINUE_CONVERT'			=> 'Continua la conversione',
	'CONTINUE_CONVERT_BODY'		=> 'Un precedente tentativo di conversione è stato individuato. Puoi scegliere se iniziare una nuova conversione o continuare con la vecchia.',
	'CONTINUE_LAST'				=> 'Continua con le ultime impostazioni',
	'CONTINUE_OLD_CONVERSION'	=> 'Continua la conversione precedentemente iniziata',
	'CONVERT'					=> 'Converti',
	'CONVERT_COMPLETE'			=> 'Conversione completata',
	'CONVERT_COMPLETE_EXPLAIN'	=> 'Hai convertito correttamente la tua board a phpBB 3.0. Puoi effettuare il login e <a href="../">accedere alla tua board</a>. Assicurati che le regolazioni siano state trasferite correttamente prima di cancellare la cartella d’installazione. Ricorda che l’aiuto sull’uso di phpBB è disponibile online con la <a href="http://www.phpbb.com/support/documentation/3.0/">Documentazione</a> ed i <a href="http://www.phpbb.com/community/viewforum.php?f=46">forum di supporto</a>.',
	'CONVERT_INTRO'				=> 'Benvenuto nel phpBB Unified Convertor Framework',
	'CONVERT_INTRO_BODY'		=> 'Da qui hai la possibilità di importare dati da altri forum (installati). La lista sottostante mostra tutti i moduli di conversione attualmente disponibili. Se la lista non contiene un convertitore per il software dal quale vuoi prelevare i dati, controlla sul nostro sito dove ulteriori moduli di conversione potrebbero essere disponibili.',
	'CONVERT_NEW_CONVERSION'	=> 'Nuova conversione',
	'CONVERT_NOT_EXIST'			=> 'Il convertitore selezionato non esiste.',
	'CONVERT_OPTIONS'			=> 'Opzioni',
	'CONVERT_SETTINGS_VERIFIED'	=> 'Le informazioni che hai inserito sono state verificate. Per iniziare il processo di conversione, clicca il pulsante sotto.',
	'CONV_ERR_FATAL'			=> 'Errore critico conversione',

	'CONV_ERROR_ATTACH_FTP_DIR'			=> 'L’invio FTP degli allegati sulla vecchia board è abilitato. Disabilita l’opzione ed assicurati di specificare una cartella upload valida, infine copia tutti i file allegati a questa cartella (che deve essere accessibile da web). Fatto questo fai ripartire il convertitore.',
	'CONV_ERROR_CONFIG_EMPTY'			=> 'Non ci sono informazioni di configurazione disponibili per la conversione.',
	'CONV_ERROR_FORUM_ACCESS'			=> 'Impossibile ottenere le informazioni d’accesso al forum.',
	'CONV_ERROR_GET_CATEGORIES'			=> 'Impossibile ottenere le categorie.',
	'CONV_ERROR_GET_CONFIG'				=> 'Impossibile recuperare la configurazione della board.',
	'CONV_ERROR_COULD_NOT_READ'			=> 'Impossibile accedere/leggere “%s”.',
	'CONV_ERROR_GROUP_ACCESS'			=> 'Impossibile ottenere informazioni di autenticazione di gruppo.',
	'CONV_ERROR_INCONSISTENT_GROUPS'	=> 'Rilevata inconsistenza della tabella gruppi in add_bots() - devi aggiungere tutti i gruppi speciali se lo fai manualmente.',
	'CONV_ERROR_INSERT_BOT'				=> 'Impossibile inserire bot nella tabella utenti.',
	'CONV_ERROR_INSERT_BOTGROUP'		=> 'Impossibile inserire bot nella tabella bot.',
	'CONV_ERROR_INSERT_USER_GROUP'		=> 'Impossibile inserire utente nella tabella user_group.',
	'CONV_ERROR_MESSAGE_PARSER'			=> 'Errore analizzatore del messaggio',
	'CONV_ERROR_NO_AVATAR_PATH'			=> 'Nota per lo sviluppatore: devi specificare il $convertitore[\'avatar_path\'] da utilizzare %s.',
	'CONV_ERROR_NO_FORUM_PATH'			=> 'Il percorso relativo alla board non è stato specificato.',
	'CONV_ERROR_NO_GALLERY_PATH'		=> 'Nota per lo sviluppatore: devi specificare il $convertitore[\'avatar_gallery_path\'] da utilizzare %s.',
	'CONV_ERROR_NO_GROUP'				=> 'Gruppo “%1$s” impossibile trovarlo in %2$s.',
	'CONV_ERROR_NO_RANKS_PATH'			=> 'Nota per lo sviluppatore: devi specificare il $convertitore[\'ranks_path\'] da utilizzare %s.',
	'CONV_ERROR_NO_SMILIES_PATH'		=> 'Nota per lo sviluppatore: devi specificare il $convertitore[\'smilies_path\'] da utilizzare %s.',
	'CONV_ERROR_NO_UPLOAD_DIR'			=> 'Nota per lo sviluppatore: devi specificare il $convertitore[\'upload_path\'] da utilizzare %s.',
	'CONV_ERROR_PERM_SETTING'			=> 'Impossibile inserire/aggiornare impostazione permessi.',
	'CONV_ERROR_PM_COUNT'				=> 'Impossibile selezionare cartella conteggio pm.',
	'CONV_ERROR_REPLACE_CATEGORY'		=> 'Impossibile inserire nuova sezione in sostituzione della vecchia categoria.',
	'CONV_ERROR_REPLACE_FORUM'			=> 'Impossibile inserire nuova sezione in sostituzione della vecchia.',
	'CONV_ERROR_USER_ACCESS'			=> 'Impossibile ottenere informazioni autenticazione utente.',
	'CONV_ERROR_WRONG_GROUP'			=> 'Gruppo errato “%1$s” definito in %2$s.',
	'CONV_OPTIONS_BODY'					=> 'Percorso <strong>relativo</strong> alla tua ex board dalla <strong>cartella principale del tuo phpBB</strong>.',
	'CONV_SAVED_MESSAGES'				=> 'Messaggi salvati',

	'COULD_NOT_COPY'			=> 'Non puoi copiare il file <strong>%1$s</strong> in <strong>%2$s</strong><br /><br />Controlla che la cartella di destinazione esista e che sia scrivibile.',
	'COULD_NOT_FIND_PATH'		=> 'Non trovo il percorso precedente della tua board. Controlla le tue impostazioni e prova di nuovo.<br />» %s era il percorso sorgente specificato.',

	'DBMS'						=> 'Tipo database',
	'DB_CONFIG'					=> 'Configurazione database',
	'DB_CONNECTION'				=> 'Connessione database',
	'DB_ERR_INSERT'				=> 'Errore durante il processo della query <code>INSERT</code>.',
	'DB_ERR_LAST'				=> 'Errore durante il processo <var>query_last</var>.',
	'DB_ERR_QUERY_FIRST'		=> 'Errore durante l’esecuzione <var>query_first</var>.',
	'DB_ERR_QUERY_FIRST_TABLE'	=> 'Errore durante l’esecuzione <var>query_first</var>, %s (“%s”).',
	'DB_ERR_SELECT'				=> 'Errore durante l’esecuzione della query <code>SELECT</code>.',
	'DB_HOST'					=> 'Hostname server del database o DSN',
	'DB_HOST_EXPLAIN'			=> 'DSN sta per i Dati Sorgente Nome ed è importante solo per installazioni ODBC.',
	'DB_NAME'					=> 'Nome database',
	'DB_PASSWORD'				=> 'Password database',
	'DB_PORT'					=> 'Porta server del database',
	'DB_PORT_EXPLAIN'			=> 'Lascia questo spazio vuoto a meno tu non sappia che il server opera su una porta non-standard.',
	'DB_UPDATE_NOT_SUPPORTED'   => 'Siamo spiacenti, ma questo script non supporta aggiornamenti da versioni di phpBB antecedenti la “%1$s”. La versione che hai installata ora è la “%2$s”. Per favore, provvedi ad aggiornare ad una versione precedente a questa, prima di utilizzare questo script. Se necessiti di assistenza fai riferimento al Support Forum di phpBB.com.',
	'DB_USERNAME'				=> 'Nome utente database',
	'DB_TEST'					=> 'Test di connessione',
	'DEFAULT_LANG'				=> 'Lingua predefinita della board',
	'DEFAULT_PREFIX_IS'			=> 'Il convertitore non è riuscito a trovare le tabelle con il prefisso specificato. Assicurati di aver inserito i dettagli corretti per la board da cui stai convertendo. Il prefisso predefinito per la tabella %1$s è <strong>%2$s</strong>.',
	'DEV_NO_TEST_FILE'			=> 'Nessun valore è stato specificato per la variabile test_file nel convertitore. Se sei un utente di questo convertitore non dovresti vedere questo errore. Sei pregato quindi di segnalarlo all’autore del convertitore. Se sei l’autore del convertitore devi specificare il nome di un file esistente nella board sorgente per permettere la verifica del percorso ad essa.',
	'DIRECTORIES_AND_FILES'		=> 'Setup cartelle e file',
	'DISABLE_KEYS'				=> 'Disabilita chiavi',
	'DLL_FIREBIRD'				=> 'Firebird',
	'DLL_FTP'					=> 'Remote FTP supportato [ Installazione ]',
	'DLL_GD'					=> 'GD graphics supportato [ Conferma visuale ]',
	'DLL_MBSTRING'				=> 'Caratteri Multi-byte supportati',
	'DLL_MSSQL'					=> 'MSSQL Server 2000+',
	'DLL_MSSQL_ODBC'			=> 'MSSQL Server 2000+ via ODBC',
	'DLL_MYSQL'					=> 'MySQL',
	'DLL_MYSQLI'				=> 'MySQL con estensione MySQLi',
	'DLL_ORACLE'				=> 'Oracle',
	'DLL_POSTGRES'				=> 'PostgreSQL 7.x/8.x',
	'DLL_SQLITE'				=> 'SQLite',
	'DLL_XML'					=> 'XML support [ Jabber ]',
	'DLL_ZLIB'					=> 'zlib compressioni supportate [ gz, .tar.gz, .zip ]',
	'DL_CONFIG'					=> 'Scarica config',
	'DL_CONFIG_EXPLAIN'			=> 'Devi scaricare il file completo config.php sul tuo PC. Dovrai poi caricare il file manualmente, sostituendo qualsiasi config.php esistente nella cartella principale di phpBB 3.0. Ricordati di caricare il file in formato ASCII ( consulta la  documentazione per l’applicazione FTP se sei incerto su come fare ). Quando avrai caricato il file config.php clicca su “Fatto” per passare allo stadio successivo.',
	'DL_DOWNLOAD'				=> 'Scarica',
	'DONE'						=> 'Fatto',

	'ENABLE_KEYS'				=> 'Riabilita chiavi. Questo può impiegare alcuni istanti.',

	'FILES_OPTIONAL'			=> 'File e cartelle facoltative',
	'FILES_OPTIONAL_EXPLAIN'	=> '<strong>Facoltativi</strong> - Questi file, cartelle o impostazioni permessi non sono richiesti. Il sistema di installazione tenterà di utilizzare diverse tecniche per crearli, se non esistono o non possono essere scritti. Tuttavia, la loro presenza accelererà l’installazione.',
	'FILES_REQUIRED'			=> 'File e Cartelle',
	'FILES_REQUIRED_EXPLAIN'	=> '<strong>Richiesti</strong> - Per funzionare correttamente, phpBB deve poter accedere ad alcune cartelle e deve poter scrivere alcuni file. Se leggi “Non trovato”, devi creare il file o la cartella relativa. Se leggi “Non scrivibile”, devi modificare i permessi relativi al file o alla cartella per permettere a phpBB di scrivere.',
	'FILLING_TABLE'				=> 'La tabella <strong>%s</strong> viene popolata',
	'FILLING_TABLES'			=> 'Tabelle in popolamento',
	
	'FIREBIRD_DBMS_UPDATE_REQUIRED'=> 'phpBB non supporta più versioni antecedenti la 2.1 di Firebird/Interbase. Per favore, provvedi ad aggiornare Firebird almeno alla versione 2.1.0 prima di procedere con l’aggiornamento.',
	
	'FINAL_STEP'				=> 'Esegui il passaggio finale',
	'FORUM_ADDRESS'				=> 'Indirizzo della board',
	'FORUM_ADDRESS_EXPLAIN'		=> 'Questo è l’URL della precedente board, per esempio <samp>http://www.example.com/phpBB2/</samp>. Se qui è stato immesso un indirizzo ogni parola di questo sarà sostituita con il nuovo indirizzo della board all’interno dei messaggi, dei messaggi privati e delle firme.',
	'FORUM_PATH'				=> 'Percorso della board',
	'FORUM_PATH_EXPLAIN'		=> 'Questo è il percorso <strong>relativo</strong> su disco della tua precedente board dalla <strong>radice del tuo phpBB</strong>.',
	'FOUND'						=> 'Trovato',
	'FTP_CONFIG'				=> 'Trasferisci config tramite FTP',
	'FTP_CONFIG_EXPLAIN'		=> 'phpBB ha rilevato la presenza del modulo FTP su questo server. Puoi tentare di installare il tuo config.php per mezzo dell’FTP, se vuoi. Dovrai fornire le informazioni sotto elencate. Ricordati che il tuo nome utente e la tua password sono quelli del tuo server!! (domanda chiarimenti al tuo provider se non sei sicuro di quali siano).',
	'FTP_PATH'					=> 'Percorso FTP',
	'FTP_PATH_EXPLAIN'			=> 'Questo è il percorso dalla cartella alla radice di phpBB, es. <samp>htdocs/phpBB3/</samp>.',
	'FTP_UPLOAD'				=> 'Scarica',

	'GPL'						=> 'General Public License',

	'INITIAL_CONFIG'			=> 'Configurazione di base',
	'INITIAL_CONFIG_EXPLAIN'	=> 'Ora che la procedura di installazione ha constatato che il tuo server può eseguire phpBB, devi fornire alcune informazioni specifiche. Se non sai come collegarti al tuo database devi contattare il tuo fornitore di hosting: qualora non potessi puoi provare nei forum di supporto phpBB. Controlla attentamente tutti i dati che inserisci prima di continuare.',
	'INSTALL_CONGRATS'			=> 'Congratulazioni!',
	'INSTALL_CONGRATS_EXPLAIN'	=> '
		<p>Hai appena installato correttamente phpBB %1$s. Si prega di procedere scegliendo una delle seguenti opzioni:</p>
		<h2>Converti una board esistente a phpBB3</h2>
		<p>Il phpBB Unified Convertor Framework supporta la conversione da phpBB 2.0.x e da altre board a phpBB3. Se possiedi già una board che desideri convertire allora <a href="%2$s">procedi alla conversione</a>.</p>
		<h2>Inizia ad usare il tuo phpBB3!</h2>
		<p>Cliccando il pulsante sottostante accederai ad un modulo del tuo Panello di Controllo Amministratore (PCA) che ti permette di inviare informazioni di carattere statistico a phpBB. Ti saremmo grati se vorrai aiutarci inviandoci queste semplici informazioni. Dopo di questo, ti consigliamo di dedicare un po’ di tempo per esaminare le opzioni disponibili. Ricorda che è disponibile un aiuto online tramite la <a href="http://www.phpbb.com/support/documentation/3.0/">Documentazione</a> e anche presso i <a href="http://www.phpbb.com/community/viewforum.php?f=46">Forum di Supporto</a>, leggi <a href="%3$s">README</a> per ulteriori informazioni.</p><p><strong>Adesso cancella, sposta o rinomina la cartella "install" prima di usare la board. Se la cartella è ancora presente potrai accedere solo al Pannello di Controllo Amministratore (PCA).</strong></p>',
	'INSTALL_INTRO'				=> 'Benvenuto in phpBB3',

	'INSTALL_INTRO_BODY'		=> 'Con questa opzione è possibile installare phpBB3 sul vostro server.</p><p>Per poter installare phpBB avrai bisogno dei dati di accesso al tuo database, che ti sono stati forniti dal gestore del servizio di hosting. Se non sei in possesso di questi dati NON procedere con l’installazione, scrivi al servizio di posta elettronica del tuo fornitore di hosting chiedendo le chiavi di accesso al database. Sono necessari:</p>

	<ul>
		<li>Tipo di database - il database in uso.</li>
		<li>Database server hostname o DSN - l’indirizzo del server su cui risiede il database.</li>
		<li>Porta server - la porta del server del database server (nella maggior parte dei casi non è richiesta).</li>
		<li>Nome Database - il nome del database sul server.</li>
		<li>Nome Utente e Password - dati di accesso al tuo database.</li>
	</ul>

	<p><strong>N.B.:</strong> se stai installando con SQLite, devi inserire il percorso completo al tuo file del database nel campo DSN e lasciare i campi nome utente e password vuoti. Per motivi di sicurezza assicurati che il file del database non sia memorizzato in una posizione accessibile da web.</p>

	<p>phpBB3 supporta i seguenti database:</p>
	<ul>
		<li>MySQL 3.23 o superiore (MySQLi supportato)</li>
		<li>PostgreSQL 7.3+</li>
		<li>SQLite 2.8.2+</li>
		<li>Firebird 2.1+</li>
		<li>MS SQL Server 2000 o superiore (direttamente o via ODBC)</li>
		<li>Oracle</li>
	</ul>
	
	<p>Saranno visualizzati solo quei database supportati sul tuo server.',
	'INSTALL_INTRO_NEXT'		=> 'Per iniziare l’installazione clicca il pulsante sottostante.',
	'INSTALL_LOGIN'				=> 'Login',
	'INSTALL_NEXT'				=> 'Passaggio successivo',
	'INSTALL_NEXT_FAIL'			=> 'Alcuni test sono falliti dovresti correggere i vari problemi prima di procedere al passaggio successivo. In caso contrario potresti incorrere in un’installazione incompleta.',
	'INSTALL_NEXT_PASS'			=> 'Tutti i test di base sono riusciti e puoi procedere al passaggio successivo dell’installazione. Se hai modificato qualche permesso, modulo, ecc., e desideri eseguire nuovamente dei test, puoi farlo ora.',
	'INSTALL_PANEL'				=> 'Pannello d’installazione',
	'INSTALL_SEND_CONFIG'		=> 'Purtroppo phpBB non ha potuto scrivere le informazioni di configurazione direttamente su config.php. Può dipendere dal fatto che il file non esista o che non sia scrivibile. Sotto trovi una lista di opzioni che ti permetterà di completare l’installazione del config.php.',
	'INSTALL_START'				=> 'Inizia l’installazione',
	'INSTALL_TEST'				=> 'Esegui nuovamente il test',
	'INST_ERR'					=> 'Errore d’installazione',
	'INST_ERR_DB_CONNECT'		=> 'Impossibile collegarsi al database, controlla il messaggio d’errore qui sotto.',
	'INST_ERR_DB_FORUM_PATH'	=> 'Il file del database che hai specificato si trova nella cartella della tua board. Devi mettere questo file in una zona non accessibile da web.',
	'INST_ERR_DB_NO_ERROR'		=> 'Nessun messaggio d’errore restituito.',
	'INST_ERR_DB_NO_MYSQLI'		=> 'La versione di MySQL installata su questa macchina è incompatibile con l’opzione “MySQL con estensione MySQLi” selezionata. In alternativa prova l’opzione “MySQL”.',
	'INST_ERR_DB_NO_SQLITE'		=> 'La versione dell’estensione SQLite che hai installato è troppo vecchia e deve essere aggiornata almeno alla 2.8.2.',
	'INST_ERR_DB_NO_ORACLE'		=> 'La versione di Oracle installata su questa macchina necessita che imposti il parametro <var>NLS_CHARACTERSET</var> su <var>UTF8</var>. Modifica il parametro oppure aggiorna la tua installazione alla 9.2+.',
	'INST_ERR_DB_NO_FIREBIRD'	=> 'La versione di Firebird installata su questa macchina è più vecchia della 2.1 e devi aggiornare a una versione più recente.',
	'INST_ERR_DB_NO_FIREBIRD_PS'=> 'Il database che hai selezionato per Firebird ha una grandezza pagina inferiore a 8192 e deve essere almeno 8192.',
	'INST_ERR_DB_NO_POSTGRES'	=> 'Il database che hai selezionato non è stato creato con codifica <var>UNICODE</var> o <var>UTF8</var>. Prova ad installare un database con codifica <var>UNICODE</var> o <var>UTF8</var>.',
	'INST_ERR_DB_NO_NAME'		=> 'Nessun nome database specificato.',
	'INST_ERR_EMAIL_INVALID'	=> 'L’indirizzo e-mail che hai inserito non è valido.',
	'INST_ERR_EMAIL_MISMATCH'	=> 'Gli indirizzi e-mail che hai inserito non coincidono.',
	'INST_ERR_FATAL'			=> 'Errore critico d’installazione',
	'INST_ERR_FATAL_DB'			=> 'Si è verificato un errore critico ed irrecuperabile del database. Questo può essere causato dal fatto che l’utente specificato non hai i permessi adatti di <code>CREATE TABLES</code> o <code>INSERT</code>, etc. Maggiori informazioni le trovi successivamente. Prima contatta il tuo hosting provider ed eventualmente i forum di supporto di phpBB per ulteriore assistenza.',
	'INST_ERR_FTP_PATH'			=> 'Impossibile spostarsi alla cartella indicata, verifica il percorso.',
	'INST_ERR_FTP_LOGIN'		=> 'Impossibile collegarsi al server FTP, verifica nome utente e password.',
	'INST_ERR_MISSING_DATA'		=> 'Devi completare tutti i campi di questo blocco.',
	'INST_ERR_NO_DB'			=> 'Impossibile caricare il modulo PHP per il tipo di database selezionato.',
	'INST_ERR_PASSWORD_MISMATCH'	=> 'Le password che hai inserito non coincidono.',
	'INST_ERR_PASSWORD_TOO_LONG'	=> 'La password che hai inserito è troppo lunga. La lunghezza massima è di 30 caratteri.',
	'INST_ERR_PASSWORD_TOO_SHORT'	=> 'La password che hai inserito è troppo corta. La lunghezza minima è di 6 caratteri.',
	'INST_ERR_PREFIX'			=> 'Esistono già tabelle con il prefisso specificato, scegli un’alternativa.',
	'INST_ERR_PREFIX_INVALID'	=> 'Il prefisso tabella specificato non è valido per il tuo database. Scegline un altro, eliminando caratteri come il trattino.',
	'INST_ERR_PREFIX_TOO_LONG'	=> 'Il prefisso tabella specificato è troppo lungo. La lunghezza massima è di %d caratteri.',
	'INST_ERR_USER_TOO_LONG'	=> 'Il nome utente che hai inserito è troppo lungo. La lunghezza massima è di 20 caratteri.',
	'INST_ERR_USER_TOO_SHORT'	=> 'Il nome utente che hai inserito è troppo corto. La lunghezza minima è di 3 caratteri.',
	'INVALID_PRIMARY_KEY'		=> 'Chiave primaria non valida: %s',

	'LONG_SCRIPT_EXECUTION'		=> 'Il processo dura alcuni minuti… Non interrompere lo script.',
	
	// mbstring
	'MBSTRING_CHECK'						=> 'Controllo estensione <samp>mbstring</samp>',
	'MBSTRING_CHECK_EXPLAIN'				=> '<strong>Obbligatorio</strong> - <samp>mbstring</samp> è un’estensione PHP che fornisce funzioni stringa multibyte. Certe caratteristiche di mbstring non sono compatibili con phpBB e devono essere disabilitate.',
	'MBSTRING_FUNC_OVERLOAD'				=> 'Funzione in sovraccarico',
	'MBSTRING_FUNC_OVERLOAD_EXPLAIN'		=> '<var>mbstring.func_overload</var> deve essere impostato su 0 oppure su 4.',
	'MBSTRING_ENCODING_TRANSLATION'			=> 'Codifica carattere trasparente',
	'MBSTRING_ENCODING_TRANSLATION_EXPLAIN'	=> '<var>mbstring.encoding_translation</var> deve essere impostato su 0.',
	'MBSTRING_HTTP_INPUT'					=> 'Conversione carattere HTTP di input',
	'MBSTRING_HTTP_INPUT_EXPLAIN'			=> '<var>mbstring.http_input</var> deve essere impostato su <samp>pass</samp>.',
	'MBSTRING_HTTP_OUTPUT'					=> 'Conversione carattere HTTP di output',
	'MBSTRING_HTTP_OUTPUT_EXPLAIN'			=> '<var>mbstring.http_output</var> deve essere impostato su <samp>pass</samp>.',

	'MAKE_FOLDER_WRITABLE'		=> 'Assicurati che la cartella esista e sia scrivibile dal webserver e poi riprova:<br />»<strong>%s</strong>.',
	'MAKE_FOLDERS_WRITABLE'		=> 'Assicurati che le cartelle esistano e siano scrivibili dal webserver e poi riprova:<br />»<strong>%s</strong>.',
	'MYSQL_SCHEMA_UPDATE_REQUIRED'   => 'Lo schema del tuo database MySQL database è superato. phpBB ha idividuato uno schema per MySQL 3.x/4.x, mentre il server gira su MySQL %2$s.<br /><strong>Prima di procedere con l’aggiornamento devi effettuare quello dello schema.</strong><br /><br />Segui i riferimenti su <a href="http://www.phpbb.com/kb/article/doesnt-have-a-default-value-errors/">Knowledge Base article about upgrading the MySQL schema</a>. Se incontri problemi, cerca soluzioni o invia il tuo commento sul <a href="http://www.phpbb.com/community/viewforum.php?f=46">nostro forum di supporto</a>.',

	'NAMING_CONFLICT'			=> 'Conflitto di nomi: %s e %s sono entrambi degli alias<br /><br />%s',
	'NEXT_STEP'					=> 'Procedi al passaggio successivo',
	'NOT_FOUND'					=> 'Non trovato',
    'NOT_UNDERSTAND'			=> 'Non capisco %s #%d, tabella %s (“%s”)',
	'NO_CONVERTORS'				=> 'Nessun convertitore disponibile.',
	'NO_CONVERT_SPECIFIED'		=> 'Nessun convertitore specificato.',
	'NO_LOCATION'				=> 'Impossibile determinare l’ubicazione. Se sai che Imagemagick è installato devi specificarne l’ubicazione più tardi nel tuo Pannello di Controllo Amministratore',
	'NO_TABLES_FOUND'			=> 'Nessuna tabella individuata.',

	'OVERVIEW_BODY'					=> 'Benvenuto su phpBB3!<br /><br />phpBB™ è la soluzione open source per forum più utilizzata al mondo. phpBB3 è la più recente edizione di una storia iniziata nel 2000. Come i suoi predecessori il software di phpBB3 è ricco di nuove caratteristiche, di facile utilizzo e completamente supportato dal Team di phpBB. Sono molti i miglioramenti apportati dalla versione phpBB2, che vanno ad integrare caratteristiche molto richieste e non presenti nella precedente versione. Speriamo che sia anche più di quello che ti aspettavi.<br /><br />Questa procedura guidata è stata studiata per aiutarti durante l’installazione di phpBB3, l’aggiornamento all’ultima versione da precedenti release di phpBB3, ed anche convertire a phpBB3 da altri sistemi di forum di discussione (incluso phpBB2). Per altre informazioni ti suggeriamo di leggere <a href="../docs/INSTALL.html">la guida all’installazione</a>.<br /><br />Per leggere la licenza d’uso di phpBB3 o scoprire come ottenere aiuto e supporto, selezione l’opzione giusta dal menù a lato. Per continuare seleziona in alto la sezione appropriata.',

	'PCRE_UTF_SUPPORT'				=> 'Supporto PCRE UTF-8',
	'PCRE_UTF_SUPPORT_EXPLAIN'		=> 'phpBB <strong>non</strong> funzionerà se il tuo PHP non è compilato con supporto UTF-8 nell’estensione PCRE.',
	'PHP_GETIMAGESIZE_SUPPORT'			=> 'La funzione PHP getimagesize() è disponibile',
	'PHP_GETIMAGESIZE_SUPPORT_EXPLAIN'	=> '<strong>Richiesto</strong> - Per fare in modo che phpBB funzioni correttamente, la funzione getimagesize deve essere abilitata.',
	'PHP_OPTIONAL_MODULE'			=> 'Moduli facoltativi',
	'PHP_OPTIONAL_MODULE_EXPLAIN'	=> '<strong>Facoltativi</strong> - Questi moduli o applicazioni sono facoltativi. Tuttavia, se sono disponibili permetteranno caratteristiche supplementari.',
	'PHP_SUPPORTED_DB'				=> 'Database supportati',
	'PHP_SUPPORTED_DB_EXPLAIN'		=> '<strong>Richiesto</strong> - Devi supportare almeno un database compatibile all’interno di PHP. Se nessun modulo database viene visualizzato come disponibile dovresti contattare il tuo fornitore o esaminare la documentazione di installazione PHP relativa per avere dei suggerimenti.',
	'PHP_REGISTER_GLOBALS'			=> 'Impostazione PHP <var>register_globals</var> è disabilitata',
	'PHP_REGISTER_GLOBALS_EXPLAIN'	=> 'phpBB funzionerà anche se questa impostazione è abilitata, ma se è possibile, si raccomanda di disabilitare register_globals sul tuo PHP per motivi di sicurezza.',
	'PHP_SAFE_MODE'					=> 'Modo sicuro',
	'PHP_SETTINGS'					=> 'Versione e impostazioni PHP',
	'PHP_SETTINGS_EXPLAIN'			=> '<strong>Richiesto</strong> - Dovete eseguire per lo meno la versione di 4.3.3 di PHP per poter installare phpBB. Se visualizzate <var>modo sicuro</var> il vostro PHP sta eseguendo in quel modo. Questo imporrà limitazioni sulla gestione remota e simili.',
	'PHP_URL_FOPEN_SUPPORT'			=> 'Impostazione PHP <var>allow_url_fopen</var> è abilitata',
	'PHP_URL_FOPEN_SUPPORT_EXPLAIN'	=> '<strong>Facoltativo</strong> - Questa impostazione è facoltativa, comunque alcune funzioni di phpBB, tipo quella di poter caricare gli avatar da altri siti, non funzioneranno correttamente senza di essa. ',
	'PHP_VERSION_REQD'				=> 'Versione PHP >= 4.3.3',
	'POST_ID'						=> 'ID argomento',
	'PREFIX_FOUND'					=> 'Una scansione delle tabelle ha visualizzato un’installazione valida utilizzando  <strong>%s</strong> come prefisso tabella.',
	'PREPROCESS_STEP'				=> 'Esecuzione delle funzioni di pre-elaborazione/query',
	'PRE_CONVERT_COMPLETE'			=> 'Tutti i passaggi di pre-conversione sono stati completati correttamente. Ora puoi iniziare il processo di conversione effettivo. Nota che è possibile che tu debba adattare manualmente numerose cose. Dopo la conversione controlla sopratutto i permessi assegnati, ricostruisci se necessario l’indice di ricerca e assicurati che i file siano stati copiati correttamente, ad esempio gli avatar e le emoticon.',
	'PROCESS_LAST'					=> 'Elaborazione delle ultime istruzioni',

	'REFRESH_PAGE'				=> 'Aggiorna pagina per continuare conversione',
	'REFRESH_PAGE_EXPLAIN'		=> 'Se impostato su SI, il convertitore aggiornerà la pagina per continuare la conversione dopo aver terminato un passaggio. Se questa è la tua prima conversione per scopi di verifica e per determinare qualsiasi errore in anticipo, ti suggeriamo di impostare NO.',

	'REQUIREMENTS_TITLE'		=> 'Compatibilità installazione',
	'REQUIREMENTS_EXPLAIN'		=> 'Prima di procedere all’installazione completa phpBB eseguirà alcuni test sulla configurazione del tuo server e dei tuoi file, per assicurarsi che tu possa installare ed eseguire phpBB. Assicurati di leggere accuratamente i risultati e non procedere fin quando tutti i test richiesti non saranno superati. Se desideri utilizzare una qualunque delle caratteristiche a seconda dei test opzionali, devi essere sicuro che anche tutti i test siano stati superati.',
	'RETRY_WRITE'				=> 'Ritenta scrittura di config',
	'RETRY_WRITE_EXPLAIN'		=> 'Se vuoi puoi cambiare i permessi in config.php per permettere a phpBB di scriverlo. Se desideri fare questo puoi cliccare "Ritenta" qui sotto. Ricorda di restituire i permessi in config.php dopo che phpBB ha finito l’installazione.',

	'SCRIPT_PATH'				=> 'Percorso dello script',
	'SCRIPT_PATH_EXPLAIN'		=> 'Il percorso dove è situato phpbb relativo al nome di dominio, es. <samp>/phpBB3</samp>.',
	'SELECT_LANG'				=> 'Seleziona lingua',
	'SERVER_CONFIG'				=> 'Configurazione del server',
	'SEARCH_INDEX_UNCONVERTED'	=> 'Indice di ricerca non convertito',
	'SEARCH_INDEX_UNCONVERTED_EXPLAIN'	=> 'Il tuo vecchio indice di ricerca non è stato convertito. La ricerca darà sempre un risultato vuoto. Per creare un nuovo indice di ricerca vai nel Pannello di Controllo Amministratore e scegli "Manutenzione", poi scegli Cerca Indice dal submenu.',
	'SOFTWARE'					=> 'Board software',
	'SPECIFY_OPTIONS'			=> 'Specifica opzioni di conversione',
	'STAGE_ADMINISTRATOR'		=> 'Dettagli Amministratore',
	'STAGE_ADVANCED'			=> 'Impostazioni avanzate',
	'STAGE_ADVANCED_EXPLAIN'	=> 'Le impostazioni in questa pagina necessitano di essere usate solo se si è a conoscenza di aver bisogno di qualcosa di diverso dalle impostazioni predefinite. Se non sei sicuro procedi alla pagina successiva, dato che queste impostazioni possono essere modificate in seguito dal Pannello di Controllo Amministratore.',
	'STAGE_CONFIG_FILE'			=> 'File di configurazione',
	'STAGE_CREATE_TABLE'		=> 'Crea tabelle database',
	'STAGE_CREATE_TABLE_EXPLAIN'	=> 'Le tabelle presenti nel database utilizzate da phpBB 3 sono state create e popolate con qualche dato iniziale. Procedi alla schermata successiva per completare l’installazione di phpBB.',
	'STAGE_DATABASE'			=> 'Impostazioni database',
	'STAGE_FINAL'				=> 'Stadio finale',
	'STAGE_INTRO'				=> 'Introduzione',
	'STAGE_IN_PROGRESS'			=> 'Conversione in corso',
	'STAGE_REQUIREMENTS'		=> 'Requisiti',
	'STAGE_SETTINGS'			=> 'Impostazioni',
	'STARTING_CONVERT'			=> 'Inizio processo di conversione',
	'STEP_PERCENT_COMPLETED'	=> 'Passo <strong>%d</strong> di <strong>%d</strong>',
	'SUB_INTRO'					=> 'Introduzione',
	'SUB_LICENSE'				=> 'Licenza',
	'SUB_SUPPORT'				=> 'Supporto',
	'SUCCESSFUL_CONNECT'		=> 'Connesso con successo',

	'SUPPORT_BODY'				=> 'Supporto gratuito viene fornito per la versione più recente di phpBB3 su phpBB.com (in inglese) e su phpBB.it (in italiano). Questo include problemi relativi a:</p><ul><li>installazione</li><li>configurazione</li><li>questioni tecniche</li><li>possibili bug nel software</li><li>aggiornamento da versioni release candidate (3.0.RCx) all\'ultima versione stabile (3.0.x)</li><li>conversione da phpBB 2.0.x a phpBB3</li><li>conversione da altri software per forum a phpBB3 (vedere in particolare il <a href="http://www.phpbb.com/community/viewforum.php?f=65">Convertors Forum</a> su phpBB.com)</li></ul><p>Incoraggiamo gli utenti che ancora usino versioni beta di phpBB3 a sostituire interamente le loro installazioni con una copia ex-novo dell\'ultima versione del software (l\'aggiornamento da versioni beta a stabili non è supportato né possibile).</p><h2>MOD / Stili</h2><p>Per questioni relative alle MOD (modifiche al codice), consultate gli appropriati forum su <a href="http://www.phpbb.it/forum/viewforum.php?f=17">phpBB.it</a> o <a href="http://www.phpbb.com/community/viewforum.php?f=81">phpBB.com</a>.<br />Analogamente per gli stili, template e set di immagini, consultate i relativi forum su <a href="http://www.phpbb.it/forum/viewforum.php?f=18">phpBB.it</a> o <a href="http://www.phpbb.com/community/viewforum.php?f=80">phpBB.com</a>.<br /><br />Se le tue domande riguardano una specifica MOD o uno specifico stile, scrivi direttamente nel topic dedicato a quella MOD o a quello stile su phpBB.com.</p><h2>Come ottenere supporto su phpBB.it</h2><p><a href="http://www.phpbb.it/?page_id=4">Guide e FAQ su phpBB3</a><br /><a href="http://www.phpbb.it/forum/index.php">Forum con informazioni e supporto</a>.</p><h2>Come ottenere supporto su phpBB.com (in inglese)</h2><p><a href="http://www.phpbb.com/community/viewtopic.php?f=14&amp;t=571070">Risorse e riferimenti</a><br /><a href="http://www.phpbb.com/support/">Sezione di supporto</a><br /><a href="http://www.phpbb.com/support/documentation/3.0/quickstart/">Quick Start Guide</a><br /><br />Per assicurarti di essere aggiornato sulle ultime novità e release, puoi sottoscrivere la <a href="http://www.phpbb.com/support/">mailing list di phpBB.com</a>.<br /><br />',
	'SYNC_FORUMS'				=> 'Inizio sincronizzazione delle sezioni',
	'SYNC_POST_COUNT'			=> 'Sincronizzazione totale messaggi',
	'SYNC_POST_COUNT_ID'		=> 'Sincronizzazione totale messaggi dalla <var>voce</var> %1$s alla %2$s.',
	'SYNC_TOPICS'				=> 'Inizio sincronizzazione degli argomenti',
	'SYNC_TOPIC_ID'				=> 'Sincronizzazione argomenti da <var>id argomento</var> %1$s a %2$s.',

	'TABLES_MISSING'			=> 'Impossibile trovare queste tabelle<br />» <strong>%s</strong>.',
	'TABLE_PREFIX'				=> 'Prefisso delle tabelle nel database',
	'TABLE_PREFIX_SAME'			=> 'Il prefisso della tabella deve essere quello utilizzato dal software da cui state facendo la conversione.<br />» Il prefisso specificato per la tabella era %s.',
	'TESTS_PASSED'				=> 'Test superati',
	'TESTS_FAILED'				=> 'Test falliti',

	'UNABLE_WRITE_LOCK'			=> 'Impossibile scrivere file di blocco.',
	'UNAVAILABLE'				=> 'Non disponibile',
	'UNWRITABLE'				=> 'Non scrivibile',
	'UPDATE_TOPICS_POSTED'		=> 'Sto elaborando informazioni sugli argomenti inviati',
	'UPDATE_TOPICS_POSTED_ERR'	=> 'Si è verificato un errore durante l’elaborazione delle informazioni sugli argomenti inviati. Puoi ritentare questo passaggio nel PCA dopo che il processo di conversione sarà completato.',
	'VERIFY_OPTIONS'         => 'Sto verificando le opzioni di conversione',
	'VERSION'					=> 'Versione',

	'WELCOME_INSTALL'			=> 'Benvenuto nel pannello di installazione di phpBB3',
	'WRITABLE'					=> 'Scrivibile',
));

// Updater
$lang = array_merge($lang, array(
	'ALL_FILES_UP_TO_DATE'		=> 'Tutti i file sono aggiornati all’ultima versione di phpBB. Adesso devi <a href="../ucp.php?mode=login&amp;redirect=adm/index.php%3Fi=send_statistics%26mode=send_statistics">effettuare il login</a> e controllare se tutto funziona correttamente. Non dimenticare di cancellare, rinominare oppure spostare la cartella "install"! Ti saremmo grati se volessi inviarci informazioni aggiornate su server e configurazioni della board dal modulo <a href="../ucp.php?mode=login&amp;redirect=adm/index.php%3Fi=send_statistics%26mode=send_statistics">Invia dati statistici</a> nel tuo Pannello di Controllo Amministratore (PCA).',
	'ARCHIVE_FILE'				=> 'File sorgente all’interno dell’archivio',

	'BACK'				=> 'Indietro',
	'BINARY_FILE'		=> 'File binario',
	'BOT'				=> 'Spider/Robot',

	'CHANGE_CLEAN_NAMES'			=> 'Il metodo utilizzato per controllare se un nome utente non sia usato da più utenti è cambiato. Con questo nuovo metodo di comparazione potrebbe risultare che utenti abbiano un nome utente uguale. Devi eliminare o rinominare questi utenti per fare in modo che ogni nome utente sia usato da un solo utente, prima che tu possa continuare.',
	'CHECK_FILES'					=> 'Controlla i file',
	'CHECK_FILES_AGAIN'				=> 'Controlla nuovamente i file',
	'CHECK_FILES_EXPLAIN'			=> 'Con il passo successivo tutti i file verranno controllati e confrontati con i file di aggiornamento  - questo processo potrebbe impiegare alcuni istanti se questo è il primo controllo file.',
	'CHECK_FILES_UP_TO_DATE'		=> 'Secondo il database la tua versione è aggiornata. Puoi continuare con il controllo dei file per assicurarti che siano veramente aggiornati con l’ultima versione phpBB.',
	'CHECK_UPDATE_DATABASE'			=> 'Continua il processo di aggiornamento',
	'COLLECTED_INFORMATION'			=> 'Informazioni sui file',
	'COLLECTED_INFORMATION_EXPLAIN'	=> 'La lista sottostante mostra informazioni sui file che devono essere aggiornati. Leggi le informazioni visualizzate per ogni blocco per capirne il significato e sapere cosa fare per eseguire un aggiornamento efficace.',
	'COLLECTING_FILE_DIFFS'         => 'Sto raccogliendo le differenze tra i file',
	'COMPLETE_LOGIN_TO_BOARD'		=> 'Adesso devi <a href="../ucp.php?mode=login">effettuare il login alla tua board</a> e controllare se tutto funziona correttamente. Non dimenticare di cancellare, rinominare oppure spostare la cartella "install"!',
	'CONTINUE_UPDATE_NOW'			=> 'Procedi con il processo di aggiornamento',		// Shown within the database update script at the end if called from the updater
	'CONTINUE_UPDATE'				=> 'Procedi con l’aggiornamento',					// Shown after file upload to indicate the update process is not yet finished
	'CURRENT_FILE'					=> 'Inizio del conflitto - Codice del file originale prima dell’aggiornamento',
	'CURRENT_VERSION'				=> 'Versione attuale',

	'DATABASE_TYPE'						=> 'Tipo di database',
	'DATABASE_UPDATE_INFO_OLD'			=> 'Il file di aggiornamento del database contenuto all’interno della directory di installazione è obsoleto. Assicurati di aver caricato la versione corretta del file.',
	'DELETE_USER_REMOVE'				=> 'Cancella utente e rimuovi i messaggi',
	'DELETE_USER_RETAIN'				=> 'Cancella utente ma non i messaggi',
	'DESTINATION'						=> 'Destinazione del file',
	'DIFF_INLINE'						=> 'In linea',
	'DIFF_RAW'							=> 'Diff unificato base',
	'DIFF_SEP_EXPLAIN'					=> 'Blocco di codice usato nel file aggiornato/nuovo',
	'DIFF_SIDE_BY_SIDE'					=> 'In parallelo',
	'DIFF_UNIFIED'						=> 'Diff unificato',
	'DO_NOT_UPDATE'						=> 'Non aggiornare questo file',
	'DONE'								=> 'Fatto',
	'DOWNLOAD'							=> 'Scarica',
	'DOWNLOAD_AS'						=> 'Scarica come',
	'DOWNLOAD_UPDATE_METHOD_BUTTON'     => 'Scarica archivio file modificati (raccomandato)',
	'DOWNLOAD_CONFLICTS'				=> 'Conflitti di scaricamento per questo file',
    'DOWNLOAD_CONFLICTS_EXPLAIN'		=> 'Cerca &lt;&lt;&lt; per individuare i conflitti',
	'DOWNLOAD_UPDATE_METHOD'			=> 'Scarica archivio file modificati',
	'DOWNLOAD_UPDATE_METHOD_EXPLAIN'	=> 'Una volta scaricato devi decomprimere l’archivio. All’interno troverai i file modificati che devi caricare nella tua cartella phpBB. Dopo che hai caricato tutti i file controllali di nuovo con l’altro bottone qui sotto.',

	'ERROR'			=> 'Errore',
	'EDIT_USERNAME'	=> 'Modifica nome utente',

	'FILE_ALREADY_UP_TO_DATE'		=> 'Il file è già aggiornato.',
	'FILE_DIFF_NOT_ALLOWED'			=> 'File non abilitato per diff mode.',
	'FILE_USED'						=> 'Informazioni usate da',			// Single file
	'FILES_CONFLICT'				=> 'File conflittuali',
	'FILES_CONFLICT_EXPLAIN'		=> 'I seguenti file sono quelli modificati e non rappresentano i file originali della vecchia versione. PhpBB ha determinato che questi file creano conflitti se si tenta di unirli. Indaga i motivi dei conflitti e prova a risolverli manualmente oppure continua l’aggiornamento scegliendo il metodo di fusione preferito. Se risolvi i conflitti manualmente controlla nuovamente il file dopo la modifica. Puoi anche scegliere il metodo preferito di fusione per ogni file. Il primo porterà a un file dove le linee di conflitto del vostro vecchio file saranno perse, l’altro porterà alla perdita delle modifiche del nuovo file.',
	'FILES_MODIFIED'				=> 'File modificati',
	'FILES_MODIFIED_EXPLAIN'		=> 'I seguenti file sono quelli modificati e non rappresentano i file originali della vecchia versione. Il file aggiornato sarà un’unione delle tue modifiche e del nuovo file.',
	'FILES_NEW'						=> 'Nuovi file',
	'FILES_NEW_EXPLAIN'				=> 'I seguenti file attualmente non esistono all’interno della vostra installazione. Questi file saranno aggiunti alla vostra installazione',
	'FILES_NEW_CONFLICT'			=> 'Nuovi file in conflitto',
	'FILES_NEW_CONFLICT_EXPLAIN'	=> 'I seguenti file fanno parte dell’ultima versione ma è stato rilevato che esiste già un file con lo stesso nome all’interno della stessa posizione. Questo file sarà sovrascritto dal nuovo file.',
	'FILES_NOT_MODIFIED'			=> 'File non modificati',
	'FILES_NOT_MODIFIED_EXPLAIN'	=> 'I seguenti file non sono modificati e rappresentano i file originali della versione di phpBB da cui vuoi aggiornare.',
	'FILES_UP_TO_DATE'				=> 'File già aggiornati',
	'FILES_UP_TO_DATE_EXPLAIN'		=> 'I seguenti file sono già aggiornati, non necessitano di aggiornamento.',
	'FTP_SETTINGS'					=> 'Impostazioni FTP',
	'FTP_UPDATE_METHOD'				=> 'Caricamento via FTP',

	'INCOMPATIBLE_UPDATE_FILES'		=> 'I file di aggiornamento rilevati sono incompatibili con la versione attualmente installata. La tua versione è %1$s mentre i file di aggiornamento servono per phpBB %2$s a %3$s.',
	'INCOMPLETE_UPDATE_FILES'		=> 'I file di aggiornamento sono incompleti.',
	'INLINE_UPDATE_SUCCESSFUL'		=> 'L’aggiornamento del database è avvenuto correttamente. Adesso devi continuare il processo di aggiornamento.',

	'KEEP_OLD_NAME'		=> 'Mantieni nome utente',
	
	'LATEST_VERSION'		=> 'Ultima versione',
	'LINE'					=> 'Linea',
	'LINE_ADDED'			=> 'Aggiunta',
	'LINE_MODIFIED'			=> 'Modificata',
	'LINE_REMOVED'			=> 'Rimossa',
	'LINE_UNMODIFIED'		=> 'Non modificata',
	'LOGIN_UPDATE_EXPLAIN'	=> 'Per aggiornare la tua installazione devi prima effettuare il login.',

	'MAPPING_FILE_STRUCTURE'	=> 'Per facilitare l’invio eccoti l’ubicazione del file che traccia la mappa della tua installazione di phpBB.',
	
	'MERGE_MODIFICATIONS_OPTION'	=> 'Unisci le modifiche',
	
	'MERGE_NO_MERGE_NEW_OPTION'	=> 'Non unire - usa il nuovo file',
	'MERGE_NO_MERGE_MOD_OPTION'	=> 'Non unire - usa il file correntemente installato',
	'MERGE_MOD_FILE_OPTION'		=> 'Unisci le modifiche (tralasciando il nuovo codice phpBB all’interno del blocco in conflitto)',
	'MERGE_NEW_FILE_OPTION'		=> 'Unisci le modifiche (tralasciando il codice modificato all’interno del blocco in conflitto)',
	'MERGE_SELECT_ERROR'		=> 'Il metodo per unire i file conflittuali non è stato correttamente selezionato.',
	'MERGING_FILES'            => 'Unisco le differenze',
    'MERGING_FILES_EXPLAIN'      => 'Sto raccogliendo le modifiche finali ai file.<br /><br />Per favore, aspetta fino a quando phpBB non ha completato tutte le operazioni sui file cambiati.',


	'NEW_FILE'						=> 'Fine del conflitto',
	'NEW_USERNAME'					=> 'Nuovo nome utente',
	'NO_AUTH_UPDATE'				=> 'Non autorizzato ad aggiornare ',
	'NO_ERRORS'						=> 'Nessun errore',
	'NO_UPDATE_FILES'				=> 'Non aggiornare i seguenti file ',
	'NO_UPDATE_FILES_EXPLAIN'		=> 'I seguenti file sono nuovi o modificati ma la directory in cui normalmente risiedono potrebbe non essere raggiungibile nella tua installazione. Se questa lista contiene file diversi da language/ o styles/ allora devi aver modificato la struttura della tua directory e l’aggiornamento potrebbe essere incompleto.',
	'NO_UPDATE_FILES_OUTDATED'		=> 'Non è stata trovata alcuna directory di aggiornamento valida, assicurati di aver caricato i file relativi.<br /><br />La tua installazione sembra <strong>non</strong> essere aggiornata. Gli aggiornamenti per la tua versione di phpBB sono disponibili %1$s, visita <a href="http://www.phpbb.com/downloads/" rel="external">http://www.phpbb.com/downloads/</a> per scaricare il pacchetto corretto per aggiornare dalla Versione %2$s alla Versione %3$s.',
	'NO_UPDATE_FILES_UP_TO_DATE'	=> 'La tua versione è aggiornata. Non c’è bisogno di eseguire il tool per l’aggiornamento. Se desideri eseguire un controllo sull’integrità dei tuoi file assicurati di aver caricato i file di aggiornamento corretti.',
	'NO_UPDATE_INFO'				=> 'Le informazioni dei file di aggiornamento non sono state trovate.',
	'NO_UPDATES_REQUIRED'			=> 'Nessun aggiornamento richiesto',
	'NO_VISIBLE_CHANGES'			=> 'Nessun cambiamento visibile',
	'NOTICE'						=> 'Avviso',
	'NUM_CONFLICTS'					=> 'Numero di conflitti',
	'NUMBER_OF_FILES_COLLECTED'		=> 'Sto verificando le differenze per %1$d di %2$d file.<br />Per favore, attendi che la verifica dei file sia terminata.',
     

	'OLD_UPDATE_FILES'		=> 'I file di aggiornamento sono vecchi. I file di aggiornamento trovati sono per l’aggiornamento da phpBB %1$s a phpBB %2$s ma l’ultima versione di phpBB è %3$s.',

	'PACKAGE_UPDATES_TO'				=> 'Il pacchetto corrente aggiorna alla versione',
	'PERFORM_DATABASE_UPDATE'			=> 'Effettua aggiornamento database',
	'PERFORM_DATABASE_UPDATE_EXPLAIN'	=> 'Qui sotto troverai un collegamento alla procedura di aggiornamento del database. Questa procedura deve essere eseguita separatamente perchè aggiornando il database potrebbe causare un comportamento inatteso se siete connessi. L’aggiornamento al database può necessitare di alcuni istanti quindi non interrompere l’esecuzione anche se sembra che questa si blocchi. Dopo aver eseguito l’aggiornamento del database basta seguire il collegamento presentato per continuare il processo di aggiornamento.',
	'PREVIOUS_VERSION'					=> 'Versione precedente',
	'PROGRESS'							=> 'Avanzamento',

	'RESULT'					=> 'Risultato',
	'RUN_DATABASE_SCRIPT'		=> 'Aggiorna il database',

	'SELECT_DIFF_MODE'			=> 'Scegli diff mode',
	'SELECT_DOWNLOAD_FORMAT'	=> 'Scegli formato archivio',
	'SELECT_FTP_SETTINGS'		=> 'Scegli parametri FTP',
	'SHOW_DIFF_CONFLICT'		=> 'Evidenza differenze/conflitti',
	'SHOW_DIFF_FINAL'			=> 'Evidenza file risultante',
	'SHOW_DIFF_MODIFIED'		=> 'Evidenza unisci differenze',
	'SHOW_DIFF_NEW'				=> 'Evidenza contenuti file',
	'SHOW_DIFF_NEW_CONFLICT'	=> 'Evidenza differenze',
	'SHOW_DIFF_NOT_MODIFIED'	=> 'Evidenza differenze',
	'SOME_QUERIES_FAILED'		=> 'Alcune query non sono riuscite. Le istruzioni e gli errori sono elencati qui sotto.',
	'SQL'						=> 'SQL',
	'SQL_FAILURE_EXPLAIN'		=> 'Probabilmente non è niente di cui preoccuparsi, l’aggiornamento continuerà. Se questo dovesse ripetersi potresti aver bisogno di cercare aiuto presso le nostre sedi di supporto. Leggi <a href="../docs/README.html">README</a> per i particolari su come ottenere consiglio.',
	'STAGE_FILE_CHECK'			=> 'Controllo file',
	'STAGE_UPDATE_DB'			=> 'Aggiornamento database',
	'STAGE_UPDATE_FILES'		=> 'Aggiornamento file',
	'STAGE_VERSION_CHECK'		=> 'Controllo versione',
	'STATUS_CONFLICT'			=> 'Il file modificato crea conflitti',
	'STATUS_MODIFIED'			=> 'File modificato',
	'STATUS_NEW'				=> 'Nuovo file',
	'STATUS_NEW_CONFLICT'		=> 'Conflitto con il nuovo file',
	'STATUS_NOT_MODIFIED'		=> 'File non modificato',
	'STATUS_UP_TO_DATE'			=> 'File già aggiornato',
	'TOGGLE_DISPLAY'            => 'Visualizza/nascondi elenco file',
	'TRY_DOWNLOAD_METHOD' => 'Ti consigliamo di provare il download dell’archivio dei file modificati.<br />Questo metodo funziona sempre e soprattutto è il metodo di aggiornamento raccomandato.',
    'TRY_DOWNLOAD_METHOD_BUTTON'=> 'Prova questo metodo ora',

	'UPDATE_COMPLETED'				=> 'Aggiornamento completato',
	'UPDATE_DATABASE'				=> 'Aggiornamento database',
	'UPDATE_DATABASE_EXPLAIN'		=> 'Con il passo successivo il database sarà aggiornato.',
	'UPDATE_DATABASE_SCHEMA'		=> 'Aggiornamento schema database',
	'UPDATE_FILES'					=> 'Aggiornamento file',
	'UPDATE_FILES_NOTICE'			=> 'Assicurati di aggiornare anche i file della board, questo file aggiorna solo il tuo database.',
	'UPDATE_INSTALLATION'			=> 'Aggiornamento installazione phpBB',
	'UPDATE_INSTALLATION_EXPLAIN'	=> 'Con questa opzione è possibile aggiornare all’ultima versione l’installazione del tuo phpBB.<br />Durante il processo saranno controllati tutti i tuoi file per la loro integrità. Potrai esaminare tutte le differenze e i file prima dell’aggiornamento.<br /><br />L’aggiornamento del file può essere fatto in due modi diversi.</p><h2>Aggiornamento Manuale</h2><p> Con questo aggiornamento scarichi solo i tuoi file modificati, per assicurarti di non perdere le modifiche che potresti avere apportato. Dopo aver scaricato questo pacchetto devi caricare manualmente i file nella loro posizione corretta nella cartella "principale" del tuo phpBB. Una volta fatto questo, potrai eseguire nuovamente un controllo sui file per vedere se sono stati spostati nella cartella corretta.</p><h2>Aggiornamento automatico via FTP</h2><p>Questo metodo è simile al primo ma senza la necessità di scaricare i file modificati e di caricarli da soli. Verrà fatto in automatico. Per utilizzare questo metodo devi conoscere i dettagli del login per l’FTP poichè saranno richiesti. Appena finito sarai reindirizzato nuovamente al controllo dei file per assicurarti che tutto sia stato aggiornato correttamente.<br /><br />',
	'UPDATE_INSTRUCTIONS'			=> '

		<h1>Annuncio di rilascio</h1>

		<p>Leggi <a href="%1$s" title="%1$s"><strong>l’annuncio di rilascio dell’ultima versione</strong></a> prima di continuare il processo di aggiornamento, può contenere informazioni utili. E’ inoltre presente il collegamento al full-download ed allo storico dei cambiamenti.</p>

		<br />

		<h1>Come aggiornare la tua installazione con il Pacchetto di Aggiornamento Automatico</h1>

		<p>Le raccomandazioni per procedere con l’aggiornamento qui riportate sono valide solo per il pacchetto di aggiornamento automatico. Puoi aggiornare la tua installazione anche con altri metodi e li trovi elencati nel file INSTALL.html. La procedura per aggiornare automaticamente phpBB è:</p>

		<ul style="margin-left: 20px; font-size: 1.1em;">
			<li>Vai alla <a href="http://www.phpbb.com/downloads/" title="http://www.phpbb.com/downloads/">pagina di download di phpBB.com</a> e scarica il Pacchetto di Aggiornamento Automatico (Automatic Update Package).<br /><br /></li>
			<li>Decomprimi archivio.<br /><br /></li>
			<li>Invia le parti decompresse complete della cartella install alla radice del tuo phpBB (ovvero dove si trova il file config.php).<br /><br /></li>
		</ul>

		<p>Una volta inviati i file, la tua Board risulterà Off-line per gli utenti normali a causa della cartella di installazione che hai appena caricato.<br /><br />
		<strong><a href="%2$s" title="%2$s">Avvia il processo di aggiornamento indirizzando il tuo browser verso la cartella di installazione</a>.</strong><br />
		<br />
		Verrai guidato attraverso il processo di aggiornamento. Sarai informato quando l’aggiornamento sarà completato.
		</p>
	',
		'UPDATE_INSTRUCTIONS_INCOMPLETE'   => '

		<h1>Aggiornamento incompleto rilevato</h1>

		<p>phpBB ha rilevato un aggiornamento automatico incompleto. Controlla di aver seguito ogni passaggio della procedura di aggiornamento automatico. Sotto trovi nuovamente il link, oppure vai direttamente alla cartella install.</p>
	',
	'UPDATE_METHOD'					=> 'Metodo di aggiornamento',
	'UPDATE_METHOD_EXPLAIN'			=> 'Puoi scegliere il tuo metodo di aggiornamento preferito. Scegliendo FTP ti verrà mostrata una maschera che dovrai compilare con i tuoi dati. Con questo metodo i file saranno spostati automaticamente alla nuova ubicazione e i backup dei vecchi file verranno creati aggiungendo .bak al nome file. Se scegli di scaricare i file modificati potrai decomprimerli e caricarli manualmente nella giusta collocazione in seguito.',
	'UPDATE_REQUIRES_FILE'			=> 'La procedura di aggiornamento richiede che sia presente questo file: %s',
	'UPDATE_SUCCESS'				=> 'Aggiornamento riuscito',
	'UPDATE_SUCCESS_EXPLAIN'		=> 'Hai aggiornato correttamente tutti i file. Il punto seguente permette di controllare tutti i file per assicurarsi di ottenere un aggiornamento corretto.',
	'UPDATE_VERSION_OPTIMIZE'		=> 'Aggiornamento della versione ed ottimizzazione tabelle',
	'UPDATING_DATA'					=> 'Aggiornamento dati',
	'UPDATING_TO_LATEST_STABLE'		=> 'Aggiornamento database all’ultimo rilascio stabile',
	'UPDATED_VERSION'				=> 'Versione aggiornata',
	'UPLOAD_METHOD'					=> 'Metodo di invio',

	'UPDATE_DB_SUCCESS'				=> 'Aggiornamento database riuscito.',
	'USER_ACTIVE'					=> 'Utente attivo',
	'USER_INACTIVE'					=> 'Utente non attivo',

	'VERSION_CHECK'				=> 'Controllo versione',
	'VERSION_CHECK_EXPLAIN' => 'Verifica se il tuo phpBB è aggiornato.',
	'VERSION_NOT_UP_TO_DATE'	=> 'La tua installazione di phpBB non è aggiornata. Procedi con il processo di aggiornamento.',
	'VERSION_NOT_UP_TO_DATE_ACP'=> 'La tua installazione di phpBB non è aggiornata.<br />Qui sotto troverai un collegamento all’annuncio del rilascio dell’ultima versione, completo di istruzioni su come effettuare l’aggiornamento.',
	'VERSION_NOT_UP_TO_DATE_TITLE'	=> 'La tua installazione di phpBB non è aggiornata.',
	'VERSION_UP_TO_DATE'		=> 'La tua installazione di phpBB è aggiornata. Nonostante non siano presenti aggiornamenti, puoi comunque continuare e fare il controllo di validità dei file.',
	'VERSION_UP_TO_DATE_ACP'	=> 'La tua installazione di phpBB è aggiornata. Non sono disponibili aggiornamenti.',
	'VIEWING_FILE_CONTENTS'		=> 'Vedi contenuti del file',
	'VIEWING_FILE_DIFF'			=> 'Vedi differenze del file',

	'WRONG_INFO_FILE_FORMAT'	=> 'Formato errato del file di info',
));

// Default database schema entries...
$lang = array_merge($lang, array(
	'CONFIG_BOARD_EMAIL_SIG'		=> 'Grazie, L’amministrazione',
	'CONFIG_SITE_DESC'				=> 'Un breve testo per descrivere il tuo forum',
	'CONFIG_SITENAME'				=> 'tuodominio.it',

	'DEFAULT_INSTALL_POST'			=> 'Questo è un messaggio di esempio nella tua installazione di phpBB3. Ogni cosa sembra funzionare. Se vuoi, puoi cancellare questo messaggio e continuare a configurare il tuo forum. Durante il processo di installazione, alla tua prima categoria e al tuo primo forum è stato assegnato un opportuno set di permessi per i gruppi predefiniti (amministratori, bot, moderatori globali, ospiti, utenti registrati, utenti COPPA). Se decidi di cancellare il primo forum e la prima categoria, ricordati di assegnare i permessi a tutti questi gruppi per ogni categoria e ogni forum che viene creato. Raccomandiamo di rinominare la tua prima categoria e il tuo primo forum e di copiare i permessi da questi quando si creano nuove categorie e nuovi forum. Buon divertimento!',

	'EXT_GROUP_ARCHIVES'			=> 'Archivi',
	'EXT_GROUP_DOCUMENTS'			=> 'Documenti',
	'EXT_GROUP_DOWNLOADABLE_FILES'	=> 'File scaricabili',
	'EXT_GROUP_FLASH_FILES'			=> 'File flash',
	'EXT_GROUP_IMAGES'				=> 'Immagini',
	'EXT_GROUP_PLAIN_TEXT'			=> 'Testo normale',
	'EXT_GROUP_QUICKTIME_MEDIA'		=> 'Quicktime Media',
	'EXT_GROUP_REAL_MEDIA'			=> 'Real Media',
	'EXT_GROUP_WINDOWS_MEDIA'		=> 'Windows Media',

	'FORUMS_FIRST_CATEGORY'			=> 'La tua prima Categoria',
	'FORUMS_TEST_FORUM_DESC'		=> 'Descrizione del tuo primo forum.',
	'FORUMS_TEST_FORUM_TITLE'		=> 'Il tuo primo forum',

	'RANKS_SITE_ADMIN_TITLE'		=> 'Amministratore',
	'REPORT_WAREZ'					=> 'Il messaggio contiene collegamenti a software illegali o pirata.',
	'REPORT_SPAM'					=> 'Il messaggio segnalato ha l’unico scopo di fare pubblicità ad un sito web o un altro prodotto.',
	'REPORT_OFF_TOPIC'				=> 'Il messaggio segnalato è off topic.',
	'REPORT_OTHER'					=> 'Il messaggio segnalato non si adatta in altre categorie, usa il campo descrizione.',

	'SMILIES_ARROW'					=> 'Arrow',
	'SMILIES_CONFUSED'				=> 'Confused',
	'SMILIES_COOL'					=> 'Cool',
	'SMILIES_CRYING'				=> 'Crying or Very Sad',
	'SMILIES_EMARRASSED'			=> 'Embarrassed',
	'SMILIES_EVIL'					=> 'Evil or Very Mad',
	'SMILIES_EXCLAMATION'			=> 'Exclamation',
	'SMILIES_GEEK'					=> 'Geek',
	'SMILIES_IDEA'					=> 'Idea',
	'SMILIES_LAUGHING'				=> 'Laughing',
	'SMILIES_MAD'					=> 'Mad',
	'SMILIES_MR_GREEN'				=> 'Mr. Green',
	'SMILIES_NEUTRAL'				=> 'Neutral',
	'SMILIES_QUESTION'				=> 'Question',
	'SMILIES_RAZZ'					=> 'Razz',
	'SMILIES_ROLLING_EYES'			=> 'Rolling Eyes',
	'SMILIES_SAD'					=> 'Sad',
	'SMILIES_SHOCKED'				=> 'Shocked',
	'SMILIES_SMILE'					=> 'Smile',
	'SMILIES_SURPRISED'				=> 'Surprised',
	'SMILIES_TWISTED_EVIL'			=> 'Twisted Evil',
	'SMILIES_UBER_GEEK'				=> 'Uber Geek',
	'SMILIES_VERY_HAPPY'			=> 'Very Happy',
	'SMILIES_WINK'					=> 'Wink',

	'TOPICS_TOPIC_TITLE'			=> 'Benvenuto su phpBB3',
));

?>