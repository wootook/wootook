<?php
/**
*
* search [Italian]
*
* @package language
* @version $Id: search.php 10004 2009-08-17 13:25:04Z rxu $
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
	'ALL_AVAILABLE'			=> 'Tutto disponibile',
	'ALL_RESULTS'			=> 'Tutti i risultati',

	'DISPLAY_RESULTS'		=> 'Mostra i risultati come',

	'FOUND_SEARCH_MATCH'		=> 'La ricerca ha trovato %d risultato',
	'FOUND_SEARCH_MATCHES'		=> 'La ricerca ha trovato %d risultati',
	'FOUND_MORE_SEARCH_MATCHES'	=> 'La ricerca ha trovato più di %d risultati',

	'GLOBAL'				=> 'Annuncio globale',

	'IGNORED_TERMS'			=> 'ignora',
	'IGNORED_TERMS_EXPLAIN'	=> 'Nella tua ricerca le seguenti parole sono state ignorate perchè troppo comuni: <strong>%s</strong>.',

	'JUMP_TO_POST'			=> 'Vai al messaggio',

	'LOGIN_EXPLAIN_EGOSEARCH'	=> 'Devi essere registrato ed aver effettuato l’accesso per poter leggere i tuoi messaggi.',
	'LOGIN_EXPLAIN_UNREADSEARCH'=> 'Devi essere registrato ed aver effettuato l’accesso per poter visualizzare i messaggi non letti.',

	'MAX_NUM_SEARCH_KEYWORDS_REFINE'   => 'Hai indicato troppe parole da ricercare. Per favore inserisci non più di %1$d parole.',

	'NO_KEYWORDS'			=> 'Devi specificare almeno una parola da cercare, ciascuna parola deve essere di almeno %d caratteri e non deve contenere più di %d caratteri, escluse le abbreviazioni.',
	'NO_RECENT_SEARCHES'	=> 'Nessuna ricerca è stata effettuata recentemente.',
	'NO_SEARCH'				=> 'Non ti è permesso di utilizzare il sistema di ricerca.',
	'NO_SEARCH_RESULTS'		=> 'Nessun argomento o messaggio con questo criterio di ricerca.',
	'NO_SEARCH_TIME'		=> 'Al momento non ti è permesso fare ricerche. Attendi qualche minuto.',
	'WORD_IN_NO_POST'		=> 'Non sono stati trovati argomenti perchè la parola <strong>%s</strong> non è contenuta in nessun messaggio.',
	'WORDS_IN_NO_POST'		=> 'Non sono stati trovati argomenti perchè le parole <strong>%s</strong> non sono contenute in nessun messaggio.',

	'POST_CHARACTERS'		=> 'Caratteri dei messaggi',

	'RECENT_SEARCHES'		=> 'Ricerche recenti',
	'RESULT_DAYS'			=> 'Limita risultati a',
	'RESULT_SORT'			=> 'Ordina risultati per',
	'RETURN_FIRST'			=> 'Ritorna ai primi',
	'RETURN_TO_SEARCH_ADV'	=> 'Ritorna alla ricerca avanzata',

	'SEARCHED_FOR'				=> 'Termine di ricerca usato',
	'SEARCHED_TOPIC'			=> 'Argomento cercato',
	'SEARCH_ALL_TERMS'			=> 'Cerca per parola o usa frase esatta',
	'SEARCH_ANY_TERMS'			=> 'Ricerca qualsiasi termine',
	'SEARCH_AUTHOR'				=> 'Ricerca per autore',
	'SEARCH_AUTHOR_EXPLAIN'		=> 'Usa * come abbreviazione per parole parziali.',
	'SEARCH_FIRST_POST'			=> 'Solo il primo messaggio dell’argomento',
	'SEARCH_FORUMS'				=> 'Ricerca nei forum',
	'SEARCH_FORUMS_EXPLAIN'		=> 'Seleziona il/i forum in cui vuoi cercare. Per velocizzare la ricerca nei subforum seleziona il forum principale e abilita la ricerca.',
	'SEARCH_IN_RESULTS'			=> 'Cerca tra questi risultati',
	'SEARCH_KEYWORDS_EXPLAIN'	=> 'Metti un <strong>+</strong> davanti alla parola che deve essere cercata e un <strong>-</strong> davanti a quella che deve essere ignorata. Inserisci una lista di parole separate da <strong>|</strong> tra parentesi se solo una delle parole deve essere cercata. Usa * come abbreviazione per parole parziali.',
	'SEARCH_MSG_ONLY'			=> 'Solo testo del messaggio',
	'SEARCH_OPTIONS'			=> 'Opzioni di Ricerca',
	'SEARCH_QUERY'				=> 'Motore di ricerca',
	'SEARCH_SUBFORUMS'			=> 'Cerca nei subforum',
	'SEARCH_TITLE_MSG'			=> 'Titolo e testo del messaggio',
	'SEARCH_TITLE_ONLY'			=> 'Solo titoli degli argomenti',
	'SEARCH_WITHIN'				=> 'Cerca in',
	'SORT_ASCENDING'			=> 'Crescente',
	'SORT_AUTHOR'				=> 'Autore',
	'SORT_DESCENDING'			=> 'Decrescente',
	'SORT_FORUM'				=> 'Forum',
	'SORT_POST_SUBJECT'			=> 'Titolo messaggio',
	'SORT_TIME'					=> 'Data messaggio',

	'TOO_FEW_AUTHOR_CHARS'	=> 'Devi specificare almeno %d caratteri del nome dell’autore.',
));

?>