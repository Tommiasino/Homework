<?php
class Network
{
    private $adressNumbers = array();
    private $prefix;
    private $hostCount;
    private $network = "";
    private $firstHost = "";
    private $lastHost = "";
    private $broadcast = "";
    private $mask = "";

    public function __construct($n1, $n2, $n3, $n4, $prefix)
    {
        $this->adressNumbers[0] = $n1;
        $this->adressNumbers[1] = $n2;
        $this->adressNumbers[2] = $n3;
        $this->adressNumbers[3] = $n4;
        $this->prefix = $prefix;
        $this->hostCount = pow(2, 32 - $prefix) - 2;

        $this->network = $n1 . "." . $n2 . "." . $n3 . "." . $n4;
        echo "Network: " . $this->giveColor($this->network) . " / " . $this->prefix . "<br>";
        echo "Network BIN: " . $this->decToBin($this->network) . "<br>";
        $this->createMask();
        $this->firstHost();
        $this->lastHost($this->network);
        $this->broadcast($this->lastHost);
        echo "Mask: " . $this->giveColor($this->mask). "<br>";
        echo "Mask BIN: " . $this->decToBin($this->mask) . "<br>";
        echo "First Host: " . $this->firstHost . "<br>";
        echo "First Host BIN: " . $this->decToBin($this->firstHost) . "<br>";
        echo "Last Host: " . $this->giveColor($this->lastHost) . "<br>";
        echo "Last Host BIN: " . $this->decToBin($this->lastHost) . "<br>";
        echo "Broadcast: " .$this->giveColor($this->broadcast) . "<br>";
        echo "Broadcast BIN: " . $this->decToBin($this->broadcast) . "<br>";
    }

    public function createMask()
    {
        $mask = "";
        for ($x = 1; $x <= 32; $x++) {
            $param = $x % 8;
            if ($x > $this->prefix) {
                $mask .= "0";
            } else {
                $mask .= "1";
            }
            if ($param == 0 && $x < 32) {
                $mask .= ".";
            }
        }
        $this->mask = $this->maskBinToDec($mask);
    }

    public function firstHost()
    {
        $newN4 = $this->adressNumbers[3] + 1;
        $this->firstHost = $this->adressNumbers[0] . "." . $this->adressNumbers[1] . "." . $this->adressNumbers[2] . "." . $newN4;
    }

    public function lastHost($network)
    {
        $binaryAdress = $this->decToBin($this->network);
        $exp = explode(".", $binaryAdress);
        $binAddressNum = $exp[0] . $exp[1] . $exp[2] . $exp[3];
        $adressNumber = bindec($binAddressNum);
        $lastHostNumber = $adressNumber + $this->hostCount;
        $lastHostNumber = str_pad(decbin($lastHostNumber), 32, 0, STR_PAD_LEFT);
        $n1 = bindec(substr($lastHostNumber, 0, 8));
        $n2 = bindec(substr($lastHostNumber, 8, 8));
        $n3 = bindec(substr($lastHostNumber, 16, 8));
        $n4 = bindec(substr($lastHostNumber, 24, 8));
        $this->lastHost = $n1 . "." . $n2 . "." . $n3 . "." . $n4;
    }

    public function broadcast($lastHost)
    {
        $lastHost[strlen($lastHost) - 1] = $lastHost[strlen($lastHost) - 1] + 1;
        $this->broadcast = $lastHost;
    }

    public function maskBinToDec($binMask)
    {
        $decValues = explode(".", $binMask);
        for ($x = 0; $x < count($decValues); $x++) {
            $decValues[$x] = bindec($decValues[$x]);
        }
        return $decValues[0] . "." . $decValues[1] . "." . $decValues[2] . "." . $decValues[3];
    }

    public function decToBin($dec)
    {
        $values = explode(".", $dec);
        for ($x = 0; $x < count($values); $x++) {
            $values[$x] = str_pad(decbin($values[$x]), 8, 0, STR_PAD_LEFT);
        }

        if ($this->prefix >= 24) {
            $dots = 3;
        } else if ($this->prefix >= 16) {
            $dots = 2;
        } else if ($this->prefix >= 8) {
            $dots = 1;
        } else if ($this->prefix >= 0) {
            $dots = 0;
        }



        $binAdress = $values[0] . "." . $values[1] . "." . $values[2] . "." . $values[3];
        $networkSpace = substr($binAdress, 0, $this->prefix + $dots);
        $hostSpace = substr($binAdress, $this->prefix + $dots);
        $networkSpace = '<font color="red">' . $networkSpace . '</font>';
        $hostSpace = '<font color="blue">' . $hostSpace . '</font><br>';
        $binAdress = $networkSpace . $hostSpace;
        return $binAdress;
    }

    public function getHostCount()
    {
        echo "Host Count: " . $this->hostCount;
    }

    public function giveColor($adress)
    {
        $octets = explode(".", $adress);
        if ($this->prefix >= 24) {
            return '<font color="red">' . $octets[0] . '.' . $octets[1] . '.' . $octets[2] . '.</font><font color="blue">' . $octets[3] . '</font>';
        } else if ($this->prefix >= 16) {
            return '<font color="red">' . $octets[0] . '.' . $octets[1] . '.</font><font color="blue">' . $octets[2] . '.' . $octets[3] . '</font>';
        } else if ($this->prefix >= 8) {
            return '<font color="red">' . $octets[0] . '.</font><font color="blue">' . $octets[1] . '.' . $octets[2] . '.' . $octets[3] . '</font>';
        } else {
            return  '<font color="blue">' . $octets[0] . '.' . $octets[1] . '.' . $octets[2] . '.' . $octets[3] . '</font>';
        }
    }
}
