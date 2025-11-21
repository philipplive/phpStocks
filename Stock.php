<?php

class Stock {
	/**
	 * Daten
	 */
	private array $data;

	/**
	 * Name
	 */
	public string $name;

	/**
	 * Währung
	 */
	public string $currency;

	/**
	 * Beschreibung
	 */
	public string $description;

	/**
	 * Preis
	 */
	public float $price;

	/**
	 * Tageshoch
	 */
	public float $dayHigh;

	/**
	 * Tagestief
	 */
	public float $dayLow;

	/**
	 * Tagesänderung in %
	 */
	public float $priceChangePercent;

	/**
	 * Volumen
	 */
	public float $volume;

	/**
	 * Durchschnittliches Tagesvolumen der letzte 10 Tage
	 */
	public float $volumeAverage10;

	/**
	 * Durchschnittliches Tagesvolumen der letzte 90 Tage
	 */
	public float $volumeAverage90;

	/**
	 * Geldkurs
	 */
	public ?float $bid;

	/**
	 * Briefkurs
	 */
	public ?float $ask;

	/**
	 * Angebot
	 */
	public ?float $bidSize;

	/**
	 * Nachfrage
	 */
	public ?float $askSize;

	/**
	 * Dividenden in %
	 */
	public ?float $dividends;

	/**
	 * @param array $data Json-Daten
	 */
	public function __construct(array $data) {
		$this->data = $data["context"]["dispatcher"]["stores"]['QuoteSummaryStore'];

		$this->name = $this->parse(['price', 'shortName']);
		$this->currency = $this->parse(['price', 'currency']);
		$this->description = $this->data['summaryProfile']['description'] ?? $this->data['summaryProfile']['longBusinessSummary'];
		$this->price = $this->parse(['price', 'regularMarketPrice', 'raw']);
		$this->priceChangePercent = $this->parse(['price', 'regularMarketChangePercent', 'raw']) * 100;
		$this->volume = $this->parse(['price', 'regularMarketVolume', 'raw']);
		$this->volumeAverage10 = $this->parse(['price', 'averageDailyVolume10Day', 'raw']);
		$this->volumeAverage90 = $this->parse(['price', 'averageDailyVolume3Month', 'raw']);
		$this->dayHigh = $this->parse(['price', 'regularMarketDayHigh', 'raw']);
		$this->dayLow = $this->parse(['price', 'regularMarketDayLow', 'raw']);
		$this->ask = $this->parse(['summaryDetail', 'ask', 'raw']);
		$this->askSize = $this->parse(['summaryDetail', 'askSize', 'raw']);
		$this->bid = $this->parse(['summaryDetail', 'bid', 'raw']);
		$this->bidSize = $this->parse(['summaryDetail', 'bidSize', 'raw']);
		$this->dividends = isset($this->data['summaryDetail']['dividendYield']['raw']) ? $this->data['summaryDetail']['dividendYield']['raw'] * 100 : 0;
	}

	/**
	 * @param array $query
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	private function parse(array $query, mixed $defaultValue = null): mixed {
		$ref = &$this->data;

		do {
			$v = current($query);

			if (!isset($ref[$v]))
				return $defaultValue;

			$ref = &$ref[$v];
		} while (next($query));

		return $ref;
	}

	/**
	 * @param string $tag Kürzel, .z.B. "BAS.DE" = BASF gehandelt auf XETRA, "BTC-USD" oder "BTC-EUR" = Bitcoin in USD oder EUR... Die Kürzel können von Yahoo! übernommen werden.
	 * @return self
	 */
	public static function getByTag(string $tag): self {
		$data = file_get_contents('https://finance.yahoo.com/quote/'.$tag);
		$jsonString = null;
		preg_match('/root.App.main = (.*);/', $data, $jsonString);
		$json = json_decode($jsonString[1], true);

		return new self($json);
	}
}