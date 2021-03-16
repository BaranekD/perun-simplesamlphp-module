<?php

namespace SimpleSAML\Module\perun\Auth\Process;

use SimpleSAML\Error\Exception;
use SimpleSAML\Logger;
use SimpleSAML\Module;
use SimpleSAML\Utils\HTTP;

class BlockUserByAttributeValue extends \SimpleSAML\Auth\ProcessingFilter
{
    private $attrMap;

    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);

        assert(is_array($config));

        if (!isset($config['attrMap'])) {
            throw new Exception(
                'perun:BlockUserByAttributeValue: missing mandatory configuration option \'attrMap\'.'
            );
        }

        $this->attrMap = (array)$config['attrMap'];
    }

    public function process(&$request)
    {
        assert(is_array($request));

        foreach ($this->attrMap as $attrName => $attrValues) {
            if (!empty($request['Attributes'][$attrName]) &&
                !empty($intersect = array_intersect($request['Attributes'][$attrName], $attrValues))) {
                Logger::debug(
                    'User ' . $request['perun']['user']->getName() . ' with id: ' .
                    $request['perun']['user']->getId() . ' was blocked due to the value of his attribute: ' .
                    $attrName . ' => ' . print_r($intersect, true)
                );

                $url = Module::getModuleURL('perun/block_user.php');
                HTTP::redirectTrustedURL($url);
            }
        }
    }
}
