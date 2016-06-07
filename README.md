# CrossWiki 0.0.1
Inter-wiki communication infrastructure

## Install
* Clone the respository, rename it to CrossWiki and copy to extensions folder
* Add `wfLoadExtension('CrossWiki')`; to your LocalSettings.php
* You are done!

## Configuration
* Before proceeding, generate an RSA public/private pair for each wiki.
* `$wgCrossWikiPrivKey` (string): This wiki's RSA private key, either path to pem file or a string in pem format. This option is required.
* `$wgCrossWiki` (dictonary of dictonary): Desciption of wikis which this wiki can communicate with. Key of each entry is the host name, and value of each entry is a dictonary of following format:
	* `$wgCrossWiki[host]['entrypoint']` (string): URL of api.php of target wiki.
	* `$wgCrossWiki[host]['public_key']` (string): Public key of target wiki. Can be a pem format string or path to pem file.
	* `$wgCrossWiki[host]['interwiki']` (string): The interwiki prefix associated with this host.
	* `$wgCrossWiki[host]['name']` (string): Human friendly name describing target wiki.
* `$wgCrossWikiHostnameOverride` (string): Host name of this wiki used during cross wiki communcation. If omitted, $wgServerName will be used by default. If you only have one wiki per host name, you do not have to override this option.
