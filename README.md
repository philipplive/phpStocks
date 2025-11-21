# Kurswerte von Aktien oder Währungen mittels PHP abfragen

Mit dieser ganz einfachen PHP-Klasse können Kurswerte von Aktien oder Währungen abgefragt werden. So z.B. die aktuellen Kurse für Bitcoin, CHF, Gold, Einzelwerte und ETF.

## Wichtig
Da die Daten direkt von Yahoo! bezogen werden, ist es dringend notwendig, **die Abfragen zu cachen!** Die Anzahl Abfragen sollte möglichst gering gehalten werden. Bei einer höheren Anzahl an Abfragen steht eine separate API von Yahoo! zur Verfügung.

## Beispiele
```
// Einfache Kursabfrage für Bitcoin in USD
echo Stock::getByTag('BTC-USD')->price;

// Tageshoch / Tief Tesla Aktie
$share = Stock::getByTag('TSLA');
echo $share->dayHigh;
echo $share->dayLow;
```

## TODO
Weitere Datenfelder einbinden:
* History
* Empfehlungs-Trends / Bewertungen