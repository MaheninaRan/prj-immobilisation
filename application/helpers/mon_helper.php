<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');function nombreEnLettres($chiffre) {
    $unite = array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf');
    $dizaine = array('', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix');
    $puissances = array('', 'mille', 'million', 'milliard', 'billiard', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion');

    if ($chiffre == 0) {
        return 'zéro';
    }

    $chiffres = str_split(strrev($chiffre));
    $resultat = '';

    for ($i = 0; $i < count($chiffres); $i++) {
        $chiffreCourant = (int) $chiffres[$i];

        if ($i % 3 == 0) {
            if ($chiffreCourant > 9) {
                $resultat = $unite[$chiffreCourant] . ' cent ' . $resultat;
            } elseif ($chiffreCourant >= 10) {
                $resultat = 'vingt ' . $unite[$chiffreCourant - 10] . ' ' . $resultat;
            } else {
                $resultat = $unite[$chiffreCourant] . ' ' . $resultat;
            }
        } elseif ($i % 3 == 1) {
            $resultat = $dizaine[$chiffreCourant] . ' ' . $resultat;
        } else {
            if ($chiffreCourant == 0) {
                // Ignorer le chiffre
            } else {
                $resultat = $puissances[$i / 3] . ' ' . $resultat;
            }
        }
    }

    return trim($resultat);
}


?>