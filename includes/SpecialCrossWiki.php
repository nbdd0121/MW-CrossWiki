<?php
namespace CrossWiki;

class SpecialCrossWiki extends \UnlistedSpecialPage {

    public function __construct() {
        parent::__construct('CrossWiki');
    }

    public function execute($par) {
        $req = $this->getRequest();

        global $wgCrossWiki;

        $this->setHeaders();
        $this->outputHeader();
        $output = $this->getOutput();

        foreach ($wgCrossWiki as $host => $val) {
            try {
                $resp = \CrossWiki::send(['type' => 'ping'], $host);
                $output->addWikiMsg('crosswiki_success', $host);
            } catch (\Exception $ex) {
                $output->addWikiMsg('crosswiki_failure', $host, $ex->getMessage());
            }
        }

    }

}
