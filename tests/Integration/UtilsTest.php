<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Request\Utils\ResultOfConvertAddress;
use Extraton\TonClient\TonClient;
use Extraton\TonClient\Utils;
use Generator;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    private Utils $utils;

    public function setUp(): void
    {
        $tonClient = new TonClient(
            [
                'network' => [
                    'server_address'             => 'net.ton.dev',
                    'message_retries_count'      => 5,
                    'message_processing_timeout' => 40000,
                    'wait_for_timeout'           => 40000,
                    'out_of_sync_threshold'      => 15000,
                    'access_key'                 => ''
                ],
                'crypto'  => [
                    'fish_param' => ''
                ],
                'abi'     => [
                    'message_expiration_timeout'             => 40000,
                    'message_expiration_timeout_grow_factor' => 1.5
                ]
            ],
            Binding::createDefault()
        );

        $this->utils = $tonClient->getUtils();
    }

    public function testConvertAddressToAccountIdSuccessResult(): void
    {
        $address = '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528';

        $expected = new ResultOfConvertAddress(
            [
                'address' => 'ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            ]
        );

        self::assertEquals($expected, $this->utils->convertAddressToAccountId($address));
    }

    public function testConvertAddressToHexSuccessResult(): void
    {
        $address = '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528';

        $expected = new ResultOfConvertAddress(
            [
                'address' => '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            ]
        );

        self::assertEquals($expected, $this->utils->convertAddressToHex($address));
    }

    /**
     * @dataProvider dataForTestConvertAddressToBase64SuccessResult
     *
     * @param string $expectedAddress
     * @param string $sourceAddress
     * @param bool $url
     * @param bool $test
     * @param bool $bounce
     */
    public function testConvertAddressToBase64SuccessResult(
        string $expectedAddress,
        string $sourceAddress,
        bool $url = false,
        bool $test = false,
        bool $bounce = false
    ): void {
        $expected = new ResultOfConvertAddress(
            [
                'address' => $expectedAddress,
            ]
        );

        self::assertEquals(
            $expected,
            $this->utils->convertAddressToBase64(
                $sourceAddress,
                $url,
                $test,
                $bounce
            )
        );
    }

    /**
     * @return Generator
     */
    public function dataForTestConvertAddressToBase64SuccessResult(): Generator
    {
        yield [
            'kQDuZdFwgwE2JTrYvSEWoo_L1KxGLG8iL0mhUF0vp_f1KGjN',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            true,
            true,
            true
        ];

        yield [
            'kQDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KGjN',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            true,
            true
        ];

        yield [
            'EQDuZdFwgwE2JTrYvSEWoo_L1KxGLG8iL0mhUF0vp_f1KNNH',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            true,
            false,
            true
        ];

        yield [
            '0QDuZdFwgwE2JTrYvSEWoo_L1KxGLG8iL0mhUF0vp_f1KDUI',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            true,
            true,
            false
        ];

        yield [
            'EQDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KNNH',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            false,
            true
        ];

        yield [
            '0QDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KDUI',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            true,
            false
        ];

        yield [
            'UQDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KI6C',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            false,
            false
        ];
    }
}
