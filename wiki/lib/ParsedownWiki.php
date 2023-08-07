<?php

class ParsedownWiki extends \Parsedown {
	function __construct() {
		$this->InlineTypes['^'][]= 'Superscript';
		$this->inlineMarkerList .= '^';

		$this->InlineTypes['['][]= 'Wikilink';
		$this->inlineMarkerList .= '[';
	}

	protected function inlineSuperscript($excerpt) {
		if (preg_match('/\^(.*?)\^/', $excerpt['text'], $matches)) {
			return [
				'extent' => strlen($matches[0]),
				'element' => ['name' => 'sup', 'text' => $matches[1]],
			];
		}
	}

	protected function inlineWikilink($excerpt) {
		if (preg_match('/\[\[(.*?)\]\]/', $excerpt['text'], $matches)) {
			$customClass = null;
			if (!checkPageExistance($matches[1]))
				$customClass = 'nonexistant';

			return [
				'extent' => strlen($matches[0]),
				'element' => [
					'name' => 'a',
					'text' => $matches[1],
					'attributes' => [
						'class' => $customClass,
						'href' => '/wiki/'.str_replace(' ', '_', $matches[1])
					]
				],
			];
		}
	}

	protected $selectors = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

	/**
	 * Heading process.
	 * Creates heading block element and stores to the ToC list. It overrides
	 * the parent method: \Parsedown::blockHeader() and returns $Block array if
	 * the $Line is a heading element.
	 *
	 * @param  array $Line  Array that Parsedown detected as a block type element.
	 * @return void|array   Array of Heading Block.
	 */
	protected function blockHeader($Line) {
		// Use parent blockHeader method to process the $Line to $Block
		$Block = parent::blockHeader($Line);

		if (!empty($Block)) {
			// Get the text of the heading
			if (isset($Block['element']['handler']['argument'])) {
				$text = $Block['element']['handler']['argument'];
			}

			// Get the heading level. Levels are h1, h2, ..., h6
			$level = $Block['element']['name'];

			// Get the anchor of the heading to link from the ToC list
			$id = isset($Block['element']['attributes']['id']) ?
				$Block['element']['attributes']['id'] : $this->createAnchorID($text);

			// Set attributes to head tags
			$Block['element']['attributes']['id'] = $id;

			// Check if level are defined as a selector
			if (in_array($level, $this->selectors)) {
				// Add/stores the heading element info to the ToC list
				$this->setContentsList([
					'text'  => $text,
					'id'    => $id,
					'level' => $level
				]);
			}

			return $Block;
		}
	}

	/**
	* Heading process.
	* Creates heading block element and stores to the ToC list. It overrides
	* the parent method: \Parsedown::blockSetextHeader() and returns $Block array if
	* the $Line is a heading element.
	*
	* @param  array $Line  Array that Parsedown detected as a block type element.
	* @return void|array   Array of Heading Block.
	 */
	protected function blockSetextHeader($Line, array $Block = null) {
		// Use parent blockHeader method to process the $Line to $Block
		$Block = parent::blockSetextHeader($Line, $Block);

		if (!empty($Block)) {
			// Get the text of the heading
			if (isset($Block['element']['text'])) {
				$text = $Block['element']['text'];
			}

			// Get the heading level. Levels are h1, h2, ..., h6
			$level = $Block['element']['name'];

			// Get the anchor of the heading to link from the ToC list
			$id = isset($Block['element']['attributes']['id']) ?
			$Block['element']['attributes']['id'] : $this->createAnchorID($text);

			// Set attributes to head tags
			$Block['element']['attributes']['id'] = $id;

			// Check if level are defined as a selector
			if (in_array($level, $this->selectors)) {
				// Add/stores the heading element info to the ToC list
				$this->setContentsList([
					'text'  => $text,
					'id'    => $id,
					'level' => $level
				]);
			}

			return $Block;
		}
	}

	/**
	 * Returns the parsed ToC.
	 *
	 * @return string|array         HTML/JSON string, or array of ToC.
	 */
	public function contentsList() {
		$result = '';
		if (!empty($this->contentsListString)) {
			// Parses the ToC list in markdown to HTML
			$result = parent::text($this->contentsListString);
		}
		return $result;
	}

	/**
	 * Generates an anchor text that are link-able even the heading is not in
	 * ASCII.
	 *
	 * @param  string $text
	 * @return string
	 */
	protected function createAnchorID($str) : string {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

		$char_map = [
			// Latin
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'AA', 'Æ' => 'AE', 'Ç' => 'C',
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
			'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
			'Ø' => 'OE', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
			'ß' => 'ss',
			'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'aa', 'æ' => 'ae', 'ç' => 'c',
			'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
			'ø' => 'oe', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
			'ÿ' => 'y',

			// Latin symbols
			'©' => '(c)','®' => '(r)','™' => '(tm)',
		];

		// Transliterate characters to ASCII
		if (false) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}

		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $str);

		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote('-', '/') . '){2,}/', '$1', $str);

		// Remove delimiter from ends
		$str = trim($str, '-');

		$str = mb_strtolower($str, 'UTF-8');

		$str = $this->incrementAnchorId($str);

		return $str;
	}

	/**
	 * Set/stores the heading block to ToC list in a string and array format.
	 *
	 * @param  array $Content   Heading info such as "level","id" and "text".
	 * @return void
	 */
	protected function setContentsList(array $Content) {
		// Stores as an array
		$this->setContentsListAsArray($Content);
		// Stores as string in markdown list format.
		$this->setContentsListAsString($Content);
	}

	/**
	 * Sets/stores the heading block info as an array.
	 *
	 * @param  array $Content
	 * @return void
	 */
	protected function setContentsListAsArray(array $Content) {
		$this->contentsListArray[] = $Content;
	}

	protected $contentsListArray = [];

	/**
	 * Sets/stores the heading block info as a list in markdown format.
	 *
	 * @param  array $Content  Heading info such as "level","id" and "text".
	 * @return void
	 */
	protected function setContentsListAsString(array $Content) {
		$text  = trim(strip_tags($this->line($Content['text'])));
		$id    = $Content['id'];
		$level = (integer) trim($Content['level'], 'h');
		$link  = "[${text}](#${id})";

		if ($this->firstHeadLevel === 0) {
			$this->firstHeadLevel = $level;
		}
		$cutIndent = $this->firstHeadLevel - 1;
		if ($cutIndent > $level) {
			$level = 1;
		} else {
			$level = $level - $cutIndent;
		}

		$indent = str_repeat('  ', $level);

		// Stores in markdown list format as below:
		// - [Header1](#Header1)
		//   - [Header2-1](#Header2-1)
		//     - [Header3](#Header3)
		//   - [Header2-2](#Header2-2)
		// ...
		$this->contentsListString .= "${indent}- ${link}" . PHP_EOL;
	}
	protected $contentsListString = '';
	protected $firstHeadLevel = 0;

	/**
	 * Parses markdown string to HTML and also the "[toc]" tag as well.
	 * It overrides the parent method: \Parsedown::text().
	 *
	 * @param  string $text
	 * @return string
	 */
	public function text($text) {
		// Parses the markdown text except the ToC tag. This also searches
		// the list of contents and available to get from "contentsList()"
		// method.
		$html = parent::text($text);

		if (!str_contains($text, '[toc]'))
			return $html;

		$toc_data = $this->contentsList();
		$replace = <<<HTML
<div class="toc" id="toc">
	<div class="toc_title"><strong>Contents</strong> [<a href="javascript:toggleVis('toc_content', 'toc_toggle')" id="toc_toggle">Hide</a>]</div>
	<div class="toc_content" id="toc_content">${toc_data}</div>
</div>
HTML;

		return str_replace('<p>[toc]</p>', $replace, $html);
	}

	protected $anchorDuplicates = [];

	/**
	 * Collect and count anchors in use to prevent duplicated ids. Return string
	 * with incremental, numeric suffix.
	 *
	 * @param  string $str
	 * @return string
	 */
	protected function incrementAnchorId($str) {
		$this->anchorDuplicates[$str] = !isset($this->anchorDuplicates[$str]) ? 0 : ++$this->anchorDuplicates[$str];

		$newStr = $str;

		if ($count = $this->anchorDuplicates[$str]) {
			$newStr .= "-{$count}";

			// increment until conversion doesn't produce new duplicates anymore
			if (isset($this->anchorDuplicates[$newStr])) {
				$newStr = $this->incrementAnchorId($str);
			} else {
				$this->anchorDuplicates[$newStr] = 0;
			}
		}

		return $newStr;
	}
}
