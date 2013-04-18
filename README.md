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
    'email'     =>  'indirizzo@email.it',
    'password'  =>  'password'
));

if ( $risposta->login ) {
    echo 'Login andato a buon fine!';
} else {
    echo 'Login non riuscito!';
}
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

