<?php

use SimpleSAML\Error\Exception;
use SimpleSAML\Logger;


class BlockUserByAtrributeValue extends \SimpleSAML\Auth\ProcessingFilter
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
            if ($request['Attributes'][$attrName] !== null &&
                in_array($request['Attributes'][$attrName], $attrValues)) {
                // block the user
            }
        }




    }

}
