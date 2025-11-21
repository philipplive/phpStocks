<?php

require_once 'Stock.php';

// Einfache Kursabfrage für Bitcoin in USD
echo Stock::getByTag('BTC-USD')->price;

// Einfache Kursabfrage für Bitcoin in USD
$bitcoin = Stock::getByTag('BTC-USD');
echo $bitcoin->dayHigh; // Tageshoch des Bitcoins
echo $bitcoin->dayLow; // Tagestief des Bitcoins

// Preisabfrage für ETF "Vanguard S&P 500"
echo Stock::getByTag('VOO')->price;

// Tageshoch / Tief Tesla Aktie
$share = Stock::getByTag('TSLA');
echo $share->volume; // Volumen
echo $share->volumeAverage10; // Durchschnittsvolumen der letzten 10 Tage