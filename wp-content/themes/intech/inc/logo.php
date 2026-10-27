<?php
/**
 * Логотип ИНТЕХ (векторный).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Логотип для вставки в страницу.
 *
 * @param string $variant full — знак и надпись, mark — только знак.
 * @param string $class   CSS-класс.
 */
function intech_logo_svg( $variant = 'full', $class = 'logo__svg' ) {
	static $n = 0;
	$n++;
	$id   = 'intech-lg-' . $n;
	$mark = 'M138 247L97 225L97 153L97 153L38 194L8 178L8 73L57 50L57 125L138 84L138 84ZM159 225L156 225L156 73L138 62L97 84L97 84L97 35L138 8L253 65L253 114L206 94L206 199Z';
	$word = 'M325 194L307 194L307 55L307 55L345 55L345 55L345 131L404 55L442 55L442 55L442 194L442 194L404 194L404 194L404 117L345 194ZM482 194L463 194L463 55L463 55L501 55L501 55L501 106L501 106L553 106L553 106L553 55L553 55L591 55L591 55L591 194L591 194L553 194L553 194L553 140L553 140L501 140L501 140L501 194ZM666 194L645 194L645 88L645 88L608 88L608 88L608 55L608 55L723 55L723 55L723 88L723 88L686 88L686 88L686 194ZM792 194L740 194L740 194L740 55L740 55L841 55L841 55L841 88L841 88L780 88L780 88L780 106L841 106L841 106L841 140L841 140L780 140L780 159L780 159L841 159L841 194ZM879 194L859 194L859 194L902 123L859 55L902 55L928 88L954 55L996 55L954 123L996 194L954 194L928 159L928 159L902 194Z';
	$grad = '<defs><linearGradient id="' . $id . '" x1="0" y1="1" x2="1" y2="0"><stop offset="0" stop-color="#1FD1E8"/><stop offset="1" stop-color="#3B63F3"/></linearGradient></defs>';
	if ( 'mark' === $variant ) {
		return '<svg class="' . esc_attr( $class ) . '" viewBox="4 3 250 250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="ИНТЕХ">' . $grad . '<path fill="url(#' . $id . ')" d="' . $mark . '"/></svg>';
	}
	return '<svg class="' . esc_attr( $class ) . '" viewBox="4 3 996 250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="ИНТЕХ">' . $grad . '<path fill="url(#' . $id . ')" d="' . $mark . '"/><path fill="currentColor" d="' . $word . '"/></svg>';
}
