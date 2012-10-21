<?php

/**
 * computer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    wakeup
 * @subpackage model
 * @author     Alexandru Bumbacea
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class computer extends Basecomputer
{
    const IP_REGEX = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
    const MAC_REGEX = '/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/';
    const TYPE_WINDOWS = 0;
    const TYPE_LINUX = 1;
    const TYPE_WEBSERVER = 2;
    const TYPE_OTHER = 3;

    private $_types = array(
        0 => 'Windows',
        1 => 'Linux',
        2 => 'Web server',
        3 => 'Other',
    );

    public function verityStatus() {
        switch ($this->getType()) {
            case self::TYPE_WINDOWS:
                return $this->verifyByRdp();
            case self::TYPE_LINUX:
                return $this->verifyBySsh();
            case self::TYPE_WEBSERVER:
                return $this->verifyByHttp();
            case self::TYPE_OTHER:
                return $this->verifyByPing();
        }
    }

    protected function verifyByPort($port){
        $timeout = "10";
        return @fsockopen($this->getIp(), $port, $errno, $errstr, $timeout);
    }
    protected function verifyByRdp() {
        return $this->verifyByPort(3389);
    }
    protected function verifyBySsh() {
        return $this->verifyByPort(22);
    }
    protected function verifyByHttp() {
        return $this->verifyByPort(80);
    }
    protected function verifyByPing() {
        throw new Exception('Not implemented');
    }

    public function getAvailableComputerTypes(){
        return $this->_types;
    }

    public function getTypeLabel(){
        return $this->_types[$this->getType()];
    }

    public function wakeUp() {
        $broadcast = '255.255.255.255';
        $mac_array = explode(':', $this->getMac());

        $hwaddr = '';

        foreach($mac_array AS $octet)
        {
            $hwaddr .= chr(hexdec($octet));
        }

        // Create Magic Packet

        $packet = '';
        for ($i = 1; $i <= 6; $i++)
        {
            $packet .= chr(255);
        }

        for ($i = 1; $i <= 16; $i++)
        {
            $packet .= $hwaddr;
        }

        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if ($sock)
        {
            $options = socket_set_option($sock, 1, 6, true);

            if ($options >=0)
            {
                $e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 7);
                socket_close($sock);
            }
        }
        return true;
    }

}
