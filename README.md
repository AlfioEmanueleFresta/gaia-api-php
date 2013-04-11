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