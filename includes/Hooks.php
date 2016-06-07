<?php
namespace CrossWiki;

class Hooks {

    public static function onCrossWikiReceive($data, $host, &$ret) {
        // This is an example response
        if ($data['type'] === 'ping') {
            $ret = 'pong';
        }
    }

}
