<?php

function summ($a = '', $b = '') {
	if (strlen($b) > strlen($a))
		return summ($b, $a);
	$a = strrev($a);
	$b = strrev($b);

	$next = 0;
	$out = [];
	for ($i = 0, $n = strlen($a); $i < $n; $i++) {
		$temp = (int) $a{$i} + (int) ($i >= strlen($b) ? 0 : $b{$i});
		$out[$i] = $next;
		if ($temp > 9) {
			$temp -= 10;
			$next = 1;
		} else
			$next = 0;
		$out[$i] += $temp;
	}
	if ($next)
		$out[$i + 1] = $next;

	return implode('', array_reverse($out));
}

echo summ('1734', '456');
