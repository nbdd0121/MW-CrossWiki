<?php
namespace CrossWiki;

class API extends \ApiBase {

    public function execute() {
        $data = $this->getMain()->getVal('data');
        $host = $this->getMain()->getVal('host');
        $sig  = $this->getMain()->getVal('signature');

        try {
            $result = \CrossWiki::receive($data, $host, $sig);

            $this->getResult()->addValue(null, $this->getModuleName(), $result);

        } catch (\Exception $e) {
            $this->dieUsage($e->getMessage(), 'invalidrequest');
            return true;
        }
        return true;
    }

    public function getAllowedParams() {
        return array(
            'data'      => array(
                \ApiBase::PARAM_TYPE     => 'string',
                \ApiBase::PARAM_REQUIRED => true,
            ),
            'host'      => array(
                \ApiBase::PARAM_TYPE     => 'string',
                \ApiBase::PARAM_REQUIRED => true,
            ),
            'signature' => array(
                \ApiBase::PARAM_TYPE     => 'string',
                \ApiBase::PARAM_REQUIRED => true,
            ),
        );
    }

    public function getExamplesMessages() {
        return array(
            'action=crosswiki&data=["type":"ping"]&host=localhost&signature=dummy' => 'apihelp-crosswiki-example-1',
        );
    }
}
