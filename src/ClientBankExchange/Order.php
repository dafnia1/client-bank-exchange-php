<?php

namespace Kily\Tools1C\ClientBankExchange;

use Kily\Tools1C\ClientBankExchange\Model\RemainingsSection;

class Order
{
    protected $encoding;
    protected $result;

    protected $start = null;
    protected $general = null;
    protected $filter = null;
    protected $remainings = null;
    protected $documenits = [];

    public function __construct($version = '1.02', $sender, $startDate, $endDate, $ownerRschet, $incomingSum, $encoding = 'cp1251')
    {
        $this->encoding = $encoding;
        $this->start = new Model\StartSection();
        $this->general = new Model\GeneralSection([
            'ВерсияФормата'=>$version,
            'Кодировка'=>'Windows',
            'Отправитель'=>$sender,
            'Получатель'=>'',
            'ДатаСоздания'=>new \DateTime,
            'ВремяСоздания'=>new \DateTime,
            'ДатаНачала'=>$startDate,
            'ДатаКонца'=>$endDate,
            'РасчСчет'=>$ownerRschet,
        ]);
        $this->filter = new Model\FilterSection([
            'ДатаНачала'=>$startDate,
            'ДатаКонца'=>$endDate,
            'РасчСчет'=>$ownerRschet,
        ]);

        $this->remainings = new Model\RemainingsSection([
            'ДатаНачала'=>$startDate,
            'ДатаКонца'=>$endDate,
            'РасчСчет'=>$ownerRschet,
            'НачальныйОстаток'=>$incomingSum,
            'ВсегоПоступило'=>null,
            'ВсегоСписано'=>null,
            'КонечныйОстаток'=>null,
        ]);
    }

    public function addFromArray($documentType = 'Платежное поручение', $arr = [])
    {
        $this->documents[] = new Model\DocumentSection($documentType,$arr);
    }

    public function updateRemainings($startDate, $endDate, $ownerRschet, $incomingSum, $totalIn, $totalOut) {
        $outgoingSum = ($incomingSum + $totalIn) - $totalOut;
        $this->remainings = new Model\RemainingsSection([
            'ДатаНачала'=>$startDate,
            'ДатаКонца'=>$endDate,
            'РасчСчет'=>$ownerRschet,
            'НачальныйОстаток'=>$incomingSum,
            'ВсегоПоступило'=>$totalIn,
            'ВсегоСписано'=>$totalOut,
            'КонечныйОстаток'=> round($outgoingSum, 2),
        ]);
    }

    public function save($file) {
        return file_put_contents($file,$this->__toString());
    }

    public function __toString() {
        $out = '';
        foreach([$this->start,$this->general,$this->remainings,$this->documents] as $item) {
            if(is_array($item)) {
                foreach($item as $_item) {
                    $out .= $_item->__toString();
                }
            } else {
                $out .= $item->__toString();
            }
        }
        return iconv('UTF-8',$this->encoding,$out."КонецФайла\n");
    }
}
