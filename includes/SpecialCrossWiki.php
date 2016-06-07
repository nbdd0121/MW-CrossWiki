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

        $out = '';
        $out .= \Html::openElement('table', ['class' => 'wikitable']);
        $out .= \Html::openElement('tr');
        $out .= \Html::element('th', [], $this->msg('crosswiki_title_name')->text());
        $out .= \Html::element('th', [], $this->msg('crosswiki_title_interwiki')->text());
        $out .= \Html::element('th', [], $this->msg('crosswiki_title_entrypoint')->text());
        $out .= \Html::element('th', [], $this->msg('crosswiki_title_status')->text());
        $out .= \Html::closeElement('tr');

        foreach ($wgCrossWiki as $host => $val) {
            $out .= \Html::openElement('tr');
            $out .= \Html::element('td', [], $val['name']);
            $out .= \Html::element('td', [], $val['interwiki']);
            $out .= \Html::element('td', [], $val['entrypoint']);
            try {
                $resp = \CrossWiki::send(['type' => 'ping'], $host);
                $out .= \Html::element('td', ['style' => 'background-color: #afc;'],
                    $this->msg('crosswiki_success', $val['name'], $host)->text());
            } catch (\Exception $ex) {
                $out .= \Html::element('td', ['style' => 'background: pink;'],
                    $this->msg('crosswiki_failure', $val['name'], $host, $ex->getMessage())->text());
            }
            $out .= \Html::closeElement('tr');
        }

        $out .= \Html::closeElement('table');

        $output->addHTML($out);

    }

}
