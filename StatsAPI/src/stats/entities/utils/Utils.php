<?php

namespace stats\entities\utils;

class Utils
{

	public static function skinDataFromImage($img)
	{
		$bytes = '';
		for ($y = 0; $y < imagesy($img); $y++) {
			for ($x = 0; $x < imagesx($img); $x++) {
				$rgba = @imagecolorat($img, $x, $y);
				$a = ((~((int)($rgba >> 24))) << 1) & 0xff;
				$r = ($rgba >> 16) & 0xff;
				$g = ($rgba >> 8) & 0xff;
				$b = $rgba & 0xff;
				$bytes .= chr($r) . chr($g) . chr($b) . chr($a);
			}
		}
		@imagedestroy($img);
		return $bytes;
	}

	public static function formatNumber(int $number): string {
		return number_format($number, 0, ",", ".");
	}

}