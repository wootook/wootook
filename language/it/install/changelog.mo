<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

$lang['Version']     = 'Versione';
$lang['Description'] = 'Descrizione';
$lang['changelog']   = array(
//'<font color="lime">0.8a</font>' => '',

'0.8' => 'Informazioni (di Chlorel)
- ADD: Aggiunta anche la lingua italiana (di Anakin-89)
- FIX: Skin sul nuovo installer
- DIV: Lavori estetici sull\'aspetto dei file
- FIX: Dimenticate alcune modifiche sullechiamate di alcune funzioni nuovamente modificate',

'0.7m' => 'Correzione di bug (di Chlorel)
- ADD: Interfaccia di attivazione della protezione dei pianeti
- FIX: Le lune si creano di nuovo quando sono state create dal pannello admin
- FIX: Il riepilogo della flotta (personale per il momento) utilizza lo stesso foglio di stile css (default.css)
- MOD: Adeguamento di diverse funzioni all\'utilizzo del css
- FIX: Chat interna (diverse correzioni) (di e-Zobar)',

'0.7k' => 'Correzione di bug (di Chlorel)
- FIX: Ritorno della flotta dal Trasporto
- ADD: Protezione dei pianeti dell\'Amministratore
- MOD: &Egrave; possibile ordinare la lista dei giocatori nel pannello admin
- MOD: La pagina admin possiede i link per ordinare le liste
- FIX: L\'utilizzo di una skin diversa da quella standard, viene applicata anche nel pannello admin
- FIX: Aggiunta delle lune nel pannello admin (di e-Zobar)
- ADD: Modalit&agrave; di trasferimento nell\'installer (di e-Zobar)',

'0.7j' => 'Correzione di bug (di Chlorel)
- FIX: &Egrave; possibile rimuovere una costruzione dalla coda
- FIX: &Egrave; possibile inviare una flotta in missione Trasporto fra due pianeti
- FIX: L\'elenco dei tasti di scelta rapida per la selezione di obiettivi funziona nuovamente
- FIX: Non si pu&ograve; pi%ugrave; distruggere un edificio che non si possiede
- ADD: Un nuovo installer (di e-Zobar)
- FIX: Correzione dei geroglifici (di e-Zobar)',

'0.7i' => 'Correzione di bug (di Chlorel)
- Eliminato il cheat +1
- Correzione della durata dei voli / consumi di carburante nel codice PHP e codice JAVA
- Si possono ordinare le colonie di un giocatore nel men&ugrave; opzioni
- Preparazione di multiskin nelle opzioni
- Diversi sviluppi nel codice per gli Amministratori (Lista messaggi, Lista giocatori)
- Lavori sulle skin (di e-Zobar)
- Lavori sull\'installer (di e-Zobar)',

'0.7h' => 'Correzione di bug (di Chlorel)
- Interfaccia Ufficiali rifatta
- Aggiunta del blocco "refresh meta"
- Correzione di diversi bug
- Correzioni varie (di flousedid)
- Correzione delle visuali di default (di e-Zobar)',

'0.7g' => 'Correzioni diverse (di Chlorel)
- Modifica dell\'ordine di trattamento della lista di costruzioni
- Conformizzato il codice per il solo comando "echo"
- Qualche riscrittura di alcuni moduli
- Correzione bug della duplicazione della flotta
- Aggiornamento dinamico delle dimensioni dei silo, produzione mineraria e dell\'energia
- Diversi adattamenti nel pannell admin (di e-Zobar)
- Sostanziale modifica dello stile XNova (di e-Zobar)',

'0.7f' => 'Informazioni e portale: (di Chlorel)
- Nuova pagina delle informazioni completamente riprogettata
- Nuova interfaccia del portale iperspaziale integrata con la pagina delle informazioni
- Nuova gestione della visione del fuoco rapido (RF) nella pagina delle informazioni
- Numerose correzioni eseguite per e-Zobar',

'0.7e' => 'Varie : (di Chlorel)
- Nuova pagina di registrazione (impostazione standard)
- Nuova pagina dei record (messa in linea con lostile del sito)
- Modificato kernel (non &egrave; possibile descrivere tutte le modifiche poich&egrave; molte persone non capirebbero)',

'0.7d' => 'Pannello admin : (di e-Zobar)
- Migliorati alcuni moduli
- Allineamento del men&ugrave; allo stile di funzionamento del sito
- Traduzione completa in francese di ci&ograve; che mancava (e in italiano :P)',

'0.7c' => 'Statistiche : (di Chlorel)
- Eliminazione delle chiamate aldatabase dal vecchio sistema di Statistiche
- Bug di impossibilit&agrave; di costruire difese o navi senza utilizzare metallo
- Bug di certi buontemponi che si divertono a mettere in coda un numero enorme di navi o difese, &egrave; stato inserito un limite massimo di costruzione a 1000 difese o navi per volta !!
- Bug di errore alla selezione di un pianeta dalla combobox
- Aggiornamento dell\'installer',

'0.7b' => 'Statistiche : (di Chlorel)
- Riscrittura della pagina Statistiche (avviso per l\'utente)
- Le statistiche dell\'alleanza appaiono !
- Scrittura del generatore admin degllestatistiche
- Separazione delle statistiche della registrazione dell\'utente (le statistiche si leggono dal database)',

'0.7a' => 'Diversi : (di Chlorel)
- Bug Tecnologia (la durata delle ricerche appare nuovamente quando si ritorna al Laboratorio)
- Bug Missili (corretta la portata dei missili interplanetari ed inserito il limite di costruzione in base alla dimensione del silo missilistico)
- Corretto il bug della portata della Falange; (non si pu� pi&ugrave; falangiare tutta la galassia)
- Corretto il bug del consumo di deuterio quando si passa nel men&ugrave; galassia',

'0.7' => 'Edifici :
- Riscrittura della pagina
- Modularizzazione
- Corretti bug nelle Statistiche
- Debug della coda di costruzione
- Effettuate diverse modifiche (di Chlorel)
- Effettuati diversi debug (di e-Zobar)
- Aggiunta di funzioni sul Riepilogo (di Tom1991)',

'0.6b' => 'Divers :
- Correzzioni e aggiunte di funzioni per gli Ufficiali (di Tom1991)
- Incluso Menage nei javascripts (di Chlorel)
- Corretti diversi bug (di Chlorel)
- Aggiunta la versione 0.5 della coda di costruzione (di Chlorel)',

'0.6a' => 'Grafica :
- Aggiunta Skin XNova (di e-Zobar)
- Correzione effetti strani (di e-Zobar)
- Corretti bug involontari (di Chlorel)',

'0.6' => 'Galassia (suite): (di Chlorel)
- Modifica e riscrittura del file flottenajax.php
- Modifica delle routine javascripts e ajax per permettere le modifiche dinamiche della galassia
- Correzione bug di certi link dei popup
- Definizione del nuovo protocollo di richiamo, anche sulla luna, la galassia appare nella giusta posizione
- Correzione del richiamo dal riciclaggio
- Aggiunto modulo Ufficiali (di Tom1991)',

'0.5' => 'Galassia: (di Chlorel)
- Decoupage del vecchio modulo
- Modifica del sistema di generazione dei popup nella visione galassia
- Modularizzazione della generazione delle pagine',

'0.4' => 'Riepilogo: (di Chlorel)
- Formattazione del vecchio modulo
- Gestione della visualizzazione delle proprie flotte 100%
- Modifica della visualizzazione delle lune (se presenti)
- Correzione bug per rinominare la luna (Ora si possono modificare)',

'0.3' => 'Gestione flotte: (di Chlorel)
- Modifiche / modularizzazione / documentazione dei controlli di volo 100%
- Modificata missione Spionaggio 100%
- Modificata missione Colonizzazione 100%
- Modificata missione Trasporto 100%
- Modificata missione Schieramento 100%
- Modificata missione Riciclaggio 100%',

'0.2' => 'Correzioni
- Aggiunta della versione 0.5 di Exploration (di Tom1991)
- Modifica del controllo delle flotte al 10% (di Chlorel)',

'0.1' => 'Unione della versione con le flotte:
- Aggiunta la strategia di sviluppo
- Aggiunte le nuove pagine di gestione delle flotte',

'0.0' => 'Versione iniziale:
- Basata sulla versione di Tom1991',
);

?>