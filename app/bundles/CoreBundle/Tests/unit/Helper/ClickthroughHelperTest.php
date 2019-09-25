<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Mautic, Inc.
 *
 * @link        https://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\CoreBundle\Tests\Helper;

use Mautic\CoreBundle\Helper\ClickthroughHelper;
use Symfony\Component\HttpFoundation\Request;

class ClickthroughHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testEncodingCanBeDecoded()
    {
        $array = ['foo' => 'bar'];

        $this->assertEquals($array, ClickthroughHelper::decodeArrayFromUrl(ClickthroughHelper::encodeArrayForUrl($array)));
    }

    public function testObjectInArrayIsDetected()
    {
        $this->expectException(\InvalidArgumentException::class);

        $array = ['foo' => new Request()];

        $this->assertEquals($array, ClickthroughHelper::decodeArrayFromUrl(ClickthroughHelper::encodeArrayForUrl($array)));
    }

    public function testOnlyArraysCanBeDecodedToPreventObjectWakeupVulnerability()
    {
        $this->expectException(\InvalidArgumentException::class);

        ClickthroughHelper::decodeArrayFromUrl(urlencode(base64_encode(serialize(new \stdClass()))));
    }

    public function testEmptyStringDoesNotThrowException()
    {
        $array = [];

        $this->assertEquals($array, ClickthroughHelper::decodeArrayFromUrl(''));
    }
}
