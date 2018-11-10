<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 10/11/2018
 * Time: 09:23
 */

namespace common\components\BlockChain;


class BlockChain extends Block
{
    public $chain = array();

    public function __construct()
    {
        $this->chain[] = $this->createGenesisBlock();
    }

    public function createGenesisBlock()
    {
        return new Block(0,'hash_dau_tien', time(), 'data');
    }

    private function getLatestBlock()
    {
        return $this->chain[(count($this->chain) - 1)];
    }

    public function addaBlock($index, $timeHash, $data)
    {
        $previousHash = $this->getLatestBlock()->hash;
        $this->chain[] = new Block($index, $previousHash, $timeHash, $data);
    }
}