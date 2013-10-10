<?php class InfoSwap {

	public static function toDrag0n() {
		$ct = dirname(__file__)."/../..";
		@rename("$ct/Info.plist","$ct/ChromiumInfo.plist");
		@rename("$ct/drag0nInfo.plist","$ct/Info.plist");
	}
	
	public static function toChromium() {
		$ct = dirname(__file__)."/../..";
		@rename("$ct/Info.plist","$ct/drag0nInfo.plist");
		@rename("$ct/ChromiumInfo.plist","$ct/Info.plist");
	}

} ?>