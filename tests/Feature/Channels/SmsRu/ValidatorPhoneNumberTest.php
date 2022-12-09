<?php

namespace Tests\Feature\Channels\SmsRu;

use App\Channels\SmsRu\ValidatorPhoneNumber;
use Tests\TestCase;

final class ValidatorPhoneNumberTest extends TestCase
{
    public function testCreate(): ValidatorPhoneNumber
    {
        $obj = new ValidatorPhoneNumber();
        $this->assertInstanceOf(ValidatorPhoneNumber::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     * @dataProvider providerVerifyEmpty
     */
    public function testVerifyEmpty(?string $phone, bool $expected, ValidatorPhoneNumber $obj): void
    {
        $this->assertEquals($expected, $obj->verifyEmpty($phone));
    }

    /**
     * @return array[]
     */
    public function providerVerifyEmpty(): array
    {
        return [
            ["", false],
            [null, false],
            ["0", false],
            ["1234", true],
            ["9876", true],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerVerifyOnlyNumbers
     */
    public function testVerifyOnlyNumbers(?string $phone, bool $expected, ValidatorPhoneNumber $obj): void
    {
        $this->assertEquals($expected, $obj->verifyOnlyNumbers($phone));
    }

    /**
     * @return array[]
     */
    public function providerVerifyOnlyNumbers(): array
    {
        return [
            ["qwee344343", false],
            ["23rt", false],
            ["q23", false],
            ["999123456g", false],
            ["w991234561", false],
            ["9991234561", true],
            ["0991234561", true],
        ];
    }

/**
     * @depends testCreate
     * @dataProvider providerVerifyCount
     */
    public function testVerifyCount(?string $phone, bool $expected, ValidatorPhoneNumber $obj): void
    {
        $this->assertEquals($expected, $obj->verifyCount($phone));
    }

    /**
     * @return array[]
     */
    public function providerVerifyCount(): array
    {
        return [
            ["9011234567890222", false],
            ["901", false],
            ["2234354", false],
            ["1", false],
            ["2317", false],
            ["9994441010", true],
            ["8887771111", true],
        ];
    }
}
