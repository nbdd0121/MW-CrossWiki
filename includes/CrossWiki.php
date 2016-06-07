<?php

class CrossWiki {

    public static function send($data, $host) {
        global $wgCrossWiki;
        global $wgCrossWikiPrivKey;
        global $wgCrossWikiHostnameOverride;
        global $wgServerName;

        // Host must be in configuration
        if (!isset($wgCrossWiki[$host])) {
            throw new \Exception('Host ' . $host . ' is not registered');
        }

        $encData   = \FormatJSON::encode($data);
        $signature = '';

        $pkey = openssl_pkey_get_private($wgCrossWikiPrivKey);
        if ($pkey === false) {
            throw new \Exception('Private key is wrongly configured');
        }
        $success = openssl_sign($encData, $signature, $pkey, 'SHA256');
        openssl_free_key($pkey);
        if (!$success) {
            throw new \Exception('Signature signing failed');
        }

        $result = Http::post($wgCrossWiki[$host]['entrypoint'], [
            'postData' => [
                'format'    => 'json',
                'action'    => 'crosswiki',
                'data'      => $encData,
                'host'      => $wgCrossWikiHostnameOverride ? $wgCrossWikiHostnameOverride : $wgServerName,
                'signature' => base64_encode($signature),
            ],
        ]);

        if ($result === false) {
            throw new \Exception('Network error');
        } else {
            $status = \FormatJSON::parse($result, \FormatJSON::FORCE_ASSOC);
            if (!$status->isGood()) {
                throw new \Exception('Response is malformed');
            }

            $result = $status->getValue();

            if (isset($result['error']) && isset($result['error']['info'])) {
                throw new \Exception($result['error']['info']);
            }

            if (isset($result['crosswiki'])) {
                return $result['crosswiki'];
            }

            throw new \Exception('Response is malformed');
        }

    }

    public static function receive($data, $host, $signature) {
        global $wgCrossWiki;

        // Host must be in configuration
        if (!isset($wgCrossWiki[$host])) {
            throw new \Exception('Host is not trusted: ' . $host);
        }

        $pkey = openssl_pkey_get_public($wgCrossWiki[$host]['public_key']);
        if ($pkey === false) {
            throw new \Exception('Public key is wrongly configured');
        }
        $success = openssl_verify($data, base64_decode($signature), $pkey, 'SHA256');
        openssl_free_key($pkey);
        if ($success !== 1) {
            throw new \Exception('Signature verification failed');
        }

        // Must be valid JSON
        $status = \FormatJSON::parse($data, \FormatJSON::FORCE_ASSOC);
        if (!$status->isGood()) {
            throw new \Exception('Request is malformed');
        }

        // Type must be set for dispatching
        $result = $status->getValue();
        if (!isset($result['type'])) {
            throw new \Exception('Request is malformed');
        }

        $ret = null;

        Hooks::run('CrossWikiReceive', [$result, $host, &$ret]);

        if ($ret === null) {
            throw new \Exception('Cannot respond to the request');
        } else {
            return $ret;
        }
    }

}
