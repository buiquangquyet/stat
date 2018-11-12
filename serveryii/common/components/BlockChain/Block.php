<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 10/11/2018
 * Time: 09:22
 */

namespace common\components\BlockChain;


class Block
{
    public $index;
    public $previousHash;
    public $timeHash;
    public $data;
    public $hash;

    public function __construct($index = 0, $previousHash = '', $timeHash = '', $data = '')
    {

        $this->index = $index;
        $this->previousHash = $previousHash;
        $this->timeHash = $timeHash;
        $this->data = $data;
        $this->hash = $this->execHash();
        //fwrite('/blockchain/'.$this->hash.'.json', json_encode($this));

        $content = json_encode($this);
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "blockchain/$this->hash".'.json','wb');
        //$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "blockchain/myText.txt","wb");
        fwrite($fp,$content);
        fclose($fp);

    }

    public function execHash()
    {

        if(is_array($this->data))
        {
            $data_content = json_encode($this->data);
        }else{
            $data_content = $this->data;
        }

        return hash('sha256', $this->index.$this->previousHash.$this->timeHash.$data_content);
    }
}