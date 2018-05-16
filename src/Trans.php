<?php
/**
 * Created by PhpStorm.
 * User: jeeinn
 * Date: 2018/5/15
 * Time: 17:39
 */

namespace LinkTokenTrans;

use GuzzleHttp\Client;

class Trans{
    const BASE_URI = 'https://walletapi.onethingpcs.com/';
    const GET_RECORDS = 'getTransactionRecords';
    const GET_BALANCE = 'getBalance';
    const GET_TRANS_COUNT = 'getTransactionCount';
    const SEND_RAW_TRANS = 'sendRawTransaction';

    protected $client;
    protected $address;
    protected $guzzleConfig = [
        'verify' => false, // 禁用https验证
        'base_uri' => self::BASE_URI,
        'timeout' => 5,
        'headers' => [
            'User-Agent' => 'LinkToken-Server/1.0',
            'Accept' => 'application/json',
            'Connection' => 'close',
        ]
    ];

    /**
     * Trans constructor.
     * @param string $address
     * @param null $guzzleConfig
     * @throws \Exception
     */
    public function __construct($address='', $guzzleConfig=null)
    {
        $this->checkAddr($address);
        $this->address = $address;
        if (!is_array($guzzleConfig) or !$guzzleConfig){
            $guzzleConfig = $this->guzzleConfig;
        }

        $this->client = new Client($guzzleConfig);
    }

    public function test()
    {
        echo 'This is test.';
        exit(0);
    }

    /**
     * @param string $address
     * @throws \Exception
     */
    private function checkAddr(string $address)
    {
        if (!$address || !preg_match('/^0x/',$address)) {
            throw new \Exception('The LinkToken account invalid.');
        }
    }

    /**
     * 获取账户当前链克数
     * @return bool|float|int|string
     * @throws \Exception
     */
    public function getBalance()
    {
        $body = [
            "jsonrpc"=> "2.0",
            "method"=> "eth_getBalance",
            "params"=> [$this->address, "latest"],
            "id"=> 1
        ];
        $response = $this->client->post(self::BASE_URI.self::GET_BALANCE, [
            'json' => $body
        ]);

        $res = json_decode($response->getBody());
        $balance = \Wikimedia\base_convert(ltrim($res->result, '0x'),16,10);
        $balance = $balance/pow(10,18);
        return $balance;
    }

    /**
     * 获取帐户交易的次数
     * @param string $status
     * @return bool|string
     * @throws \Exception
     */
    public function getTransCount($status='')
    {
        $status = 'pending';// pending:发起的交易， 其他待发掘
        $body = [
            "jsonrpc"=> "2.0",
            "method"=> "eth_getTransactionCount",
            "params"=> [$this->address, $status],
            "id"=> 1
        ];
        $response = $this->client->post(self::BASE_URI.self::GET_TRANS_COUNT, [
            'json' => $body
        ]);
        $res = json_decode($response->getBody());
        if (property_exists($res,'error')) throw new \Exception($res->error->message);
        $tansCount = \Wikimedia\base_convert(ltrim($res->result, '0x'),16,10);
        return $tansCount;
    }

    /**
     * 获取账户的交易记录
     * @param int $page
     * @param int $pageCount
     * @return array
     * @throws \Exception
     */
    public function getRecords(int $page=1, int $pageCount=20)
    {
        // [地址, --, --,第x页, 每页条数]
        $body = ["$this->address", "0", "0", "$page", "$pageCount"];
        $response = $this->client->post(self::BASE_URI.self::GET_RECORDS, [
            'json' => $body
        ]);
        // 暂不处理
//        if ($response->getStatusCode() != 200) return $response->getBody();

        $res = json_decode($response->getBody(), true);
        $data = [];
        if ($res['totalnum'] > 0) {
            foreach ($res['result'] as $item) {
                // amount HexToDec & 10^18
                $amount = \Wikimedia\base_convert(ltrim($item['amount'], '0x'),16,10);
                $amount = $amount/pow(10,18);
                $cost = \Wikimedia\base_convert(ltrim($item['cost'], '0x'),16,10);
                $cost = $cost/pow(10,18);

                $item['amount'] = $amount;
                $item['cost'] = $cost;
                $item['timestamp'] = date('Y-m-d H:i:s', $item['timestamp']);
                array_push($data, $item);
            }
        }
        return $data;
    }


    public function signTrans()
    {

    }

    public function sendRawTrans()
    {

    }
}