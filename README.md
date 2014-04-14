# Gaia API

Il progetto implementa una classe PHP per l'uso rapido delle API di Gaia.


## Requisiti

* PHP versione 5.2+ 
* Modulo CURL (presente su Windows, `php5-curl` su Linux/Mac)

## Uso

### Inclusione 

In testa alla pagina, usare:

```php
<?php

// 1. Includo la libreria:
require_once 'Gaia.php';

// 2. Mi connetto a Gaia:
$gaia = new Gaia();
```

### Esempio di login

```php

$risposta = $gaia->login (array(
    // Imposta l'URL di ritorno dopo il login
    'redirect'  =>  'http://www.miohost.it/torna_qui.php'
));

// Redirect alla pagina di login
header("Location: {$risposta->url}");
exit(0);
```

### Controllo se sono loggato

```php

// $gaia->utente contiene informazioni sull'utente loggato

if ( $gaia->utente ) {

    echo "Benvenuto, ";
    echo $gaia->utente->nome;
    echo ". Codice utente: ";
    echo $gaia->utente->id;

} else {

    echo "NON SEI LOGGATO";

}

```


### Ricerca di volontari o elenco completo

```php

$ricerca = $gaia->volontari_cerca (array( 
    'query'     =>  '',     //  Vuoto   => Elenco completo
                            //  Stringa => Ricerca
    'perPagina' =>  30,
    'pagina'    =>  1
));

echo "Trovati {$ricerca->totale} risultati in {$ricerca->tempo} secondi.\n";

foreach ( $ricerca->risultati as $volontario ) {

    echo "ID:           {$volontario->id}\n";
    echo "Nome:         {$volontario->nome}\n";
    echo "Cognome:      {$volontario->cognome}\n";
    echo "Email:        {$volontario->email}\n";
    echo "Cod.Fisc:     {$volontario->codiceFiscale}\n";
    echo "Comitato ID:  {$volontario->comitato->id}\n";
    echo "Comitato nome:{$volontario->comitato->nome}\n";
    echo "---------------------------------------------\n\n";
    
}
    
```

**Richiede login** - 
La ricerca viene effettuata all'interno del comitato di appartenenza dell'utente identificato, più eventuali comitati di competenza (PRESIDENTE).


### Elenco e dettagli comitati, unità territoriali, coordinate, volontari, ecc.

```php

$info = $gaia->comitati();

var_dump($info); // Stampa dettagli della risposta

//es.:
foreach ( $info as $nazionale ) {

  echo $nazionale->nome;
  foreach ( $nazionale->regionali as $regionale ) {
  
    echo $regionale->nome;
    foreach ( $regionale->provinciali as $provinciale ) {
    
      echo $provinciale->nome;
      foreach ( $provinciale->comitati as $comitato ) {
      
        echo $comitato->nome;
        foreach ( $comitato->unita as $unita ) {
        
          echo $unita->nome;
          echo $unita->indirizzo;
          echo $unita->telefono;
          echo $unita->email;
          echo $unita->volontari;
          
          $lat = $unita->coordinate[0];
          $lng = $unita->coordinate[1];
          echo "Coordinate: $lat, $lng";
          
        } // Fine elenco unità del comitato
        
      } // Fine elenco comitati del provinciale
      
    } // Fine elenco provinciali del regionale
    
  } // Fine elenco regionali del nazionale
  
} // Fine elenco nazionali (teorico... un solo nazionale)

```


