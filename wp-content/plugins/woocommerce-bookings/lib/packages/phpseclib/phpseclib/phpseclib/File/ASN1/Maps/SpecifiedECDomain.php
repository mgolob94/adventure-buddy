<?php

/**
 * SpecifiedECDomain
 *
 * From: http://www.secg.org/sec1-v2.pdf#page=109
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by woocommerce on 14-June-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1\Maps;

use Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1;

/**
 * SpecifiedECDomain
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class SpecifiedECDomain
{
    const MAP = [
        'type' => ASN1::TYPE_SEQUENCE,
        'children' => [
            'version' => [
                'type' => ASN1::TYPE_INTEGER,
                'mapping' => [1 => 'ecdpVer1', 'ecdpVer2', 'ecdpVer3']
            ],
            'fieldID' => FieldID::MAP,
            'curve' => Curve::MAP,
            'base' => ECPoint::MAP,
            'order' => ['type' => ASN1::TYPE_INTEGER],
            'cofactor' => [
                'type' => ASN1::TYPE_INTEGER,
                'optional' => true
            ],
            'hash' => ['optional' => true] + HashAlgorithm::MAP
        ]
    ];
}
