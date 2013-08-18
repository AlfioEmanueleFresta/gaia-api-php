<?php

/*
 * 
 * Gaia API 
 * - Classe per l'uso del servizio API di Gaia
 * 
 * Progetto Gaia: http://github.com/CroceRossaCatania/gaia
 * 
 * Copyright (c) 2013
 * Alfio Emanuele Fresta <alfio.emanuele.f@gmail.com>
 * Servizi Informatici Comitato Provinciale di Catania
 *   Croce Rossa Italiana <informatica@cricatania.it>
 * 
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class Gaia {
    
    /* 
     * Configurazione delle API 
     */
    private static 
            // Indirizzo del server di Gaia
            $_server = 'https://www.gaiacri.it',
            // Eventuale codice delle API
            $_apikey = '1234567890abcdefgh';
    
    
    /*
     * == NON MODIFICARE DA QUI IN POI ==
     */
    private
            $_gaia_sid  = null,
            $_args      = array(),
            $errore     = false,
            $_ver        = '1.2.1';
    
    public 
            $utente = null;
    
    public function __construct() {
        if ( isset($_COOKIE['gaia_sid']) ) {
            $this->_gaia_sid = $_COOKIE['gaia_sid'];
        }
        if (!function_exists('curl_init')) {
            die('Gaia: Richiesto il modulo CURL (pacchetto php5-curl)');
        }
        $this->__set('apikey',  self::$_apikey);
        $this->__set('sid',     $this->_gaia_sid);
        $this->welcome();
    }
    
    /*
     * Si occupa di impostare il Cookie di sessione
     */
    private function setCookie() {
        setcookie('gaia_sid', $this->_gaia_sid);
    }
    
    /*
     * Si occupa di modificare un parametro di richiesta
     */
    public function __set($n, $v) {
        if ( $v === null ) {
            unset($this->_args[$n]);
        } else {
            $this->_args[$n] = $v;
        }
    }
    
    /*
     * Ottiene un parametro se presente
     */
    public function __get($n) {
        if (isset($this->_args[$n])) {
            return $this->_args[$n];
        } else {
            return null;
        }
    }
    
    /* Ritorna l'errore */
    public function errore() {
        return $this->errore;
    }
    
    /*
     * Chiama la funzione corrispettiva
     */
    public function __call($azione, $args = null) {
        if ( $args ) {
            if (!is_array($args[0])) {
                die('Gaia: Parametri non validi.');
            }
            foreach ( $args[0] as $n => $v ) {
                $this->__set($n, $v);
            }
        }
        $this->__set('sid', $this->_gaia_sid);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$_server . '/api.php?a=' . $azione);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "API {$this->_ver}: " . self::$_apikey );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_args);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        if ( !$output ) {
            die('Gaia: Errore del server.');
        }
        $this->_gaia_sid = $output->session->id;
        $this->__set('sid', $output->session->id);
        $this->setCookie();
        $this->utente = $output->session->user;
        if ( @$output->response->error ) {
            $this->errore = $output->response->error;
        } else {
            $this->errore = false;
        }
        return $output->response;
    }
            
}
