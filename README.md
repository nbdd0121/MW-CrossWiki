# CrossWiki 0.0.1
Inter-wiki communication infrastructure

## Install
* Clone the respository, rename it to CrossWiki and copy to extensions folder
* Add `wfLoadExtension('CrossWiki')`; to your LocalSettings.php
* You are done!

## Configuration
* Generate an RSA public/private pair for each wiki
* For each wiki:
	* Set `$wgCrossWikiPrivKey` to its own private key
	* Set `$wgCrossWiki` as a dictionary `'HOST_NAME' => ['entrypoint' => 'PATH_TO_SPECIAL_CROSSWIKI', 'public_key' => 'PUBLIC_KEY_OF_THE_WIKI']
	`
