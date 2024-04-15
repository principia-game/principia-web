<?php

// Cut down version of RelativeTime: https://github.com/mpratt/RelativeTime

/**
* The Main Class of the library
*/
class RelativeTime {

	/** @var array Array With configuration options **/
	protected $config = [];

	protected $strings = [
		'seconds' => [
			'plural' => '%d seconds',
			'singular' => '%d second',
		],
		'minutes' => [
			'plural' => '%d minutes',
			'singular' => '%d minute',
		],
		'hours' => [
			'plural' => '%d hours',
			'singular' => '%d hour',
		],
		'days' => [
			'plural' => '%d days',
			'singular' => '%d day',
		],
		'months' => [
			'plural' => '%d months',
			'singular' => '%d month',
		],
		'years' => [
			'plural' => '%d years',
			'singular' => '%d year',
		],
	];

	/**
	 * Construct
	 *
	 * @param array $config Associative array with configuration directives
	 */
	public function __construct(array $config = []) {
		$this->config = array_merge([
			'separator' => ', ',
			'suffix' => true,
			'truncate' => 0,
		], $config);
	}

	/**
	 * Converts 2 dates to its relative time.
	 *
	 * @param string $fromTime
	 * @param string $toTime When null is given, uses the current date.
	 * @return string
	 */
	public function convert($fromTime, $toTime = null)
	{
		$interval = $this->getInterval($fromTime, $toTime);
		$units = $this->calculateUnits($interval);

		return $this->translate($units, $interval->invert);
	}

	/**
	 * Tells the time passed between the current date and the given date
	 *
	 * @param string $date
	 * @return string
	 */
	public function timeAgo($date) {
		$interval = $this->getInterval(time(), $date);
		if ($interval->invert)
			return $this->convert(time(), $date);

		return $this->translate();
	}

	/**
	 * Calculates the interval between the dates and returns
	 * an array with the valid time.
	 *
	 * @param string $fromTime
	 * @param string $toTime When null is given, uses the current date.
	 * @return DateInterval
	 */
	protected function getInterval($fromTime, $toTime = null) {
		$fromTime = new DateTime($this->normalizeDate($fromTime));
		$toTime = new DateTime($this->normalizeDate($toTime));

		return $fromTime->diff($toTime);
	}

	/**
	 * Normalizes a date for the DateTime class
	 *
	 * @param string $date
	 * @return string
	 */
	protected function normalizeDate($date) {
		$date = str_replace(['/', '|'], '-', $date);

		if (empty($date))
			return date('Y-m-d H:i:s');
		else if (ctype_digit($date))
			return date('Y-m-d H:i:s', $date);

		return $date;
	}

	/**
	 * Given a DateInterval, creates an array with the time
	 * units and truncates it when necesary.
	 *
	 * @param DateInterval $interval
	 * @return array
	 */
	protected function calculateUnits(DateInterval $interval) {
		$units = array_filter([
			'years'   => (int)$interval->y,
			'months'  => (int)$interval->m,
			'days'    => (int)$interval->d,
			'hours'   => (int)$interval->h,
			'minutes' => (int)$interval->i,
			'seconds' => (int)$interval->s,
		]);

		if (empty($units))
			return [];
		elseif ((int)$this->config['truncate'] > 0)
			return array_slice($units, 0, (int)$this->config['truncate']);

		return $units;
	}

	/**
	 * Actually translates the units into words
	 *
	 * @param array $units
	 * @param int $direction
	 * @return string
	 */
	protected function translate(array $units = [], $direction = 0) {
		if (empty($units))
			return 'just now';

		$translation = [];
		foreach ($units as $unit => $v) {
			if ($v == 1)
				$translation[] = sprintf($this->strings[$unit]['singular'], $v);
			else
				$translation[] = sprintf($this->strings[$unit]['plural'], $v);
		}

		$string = implode($this->config['separator'], $translation);
		if (!$this->config['suffix'])
			return $string;

		if ($direction > 0)
			return sprintf("%s ago", $string);
		else
			return sprintf("%s left", $string);
	}
}
