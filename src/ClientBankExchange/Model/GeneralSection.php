<?php

namespace Kily\Tools1C\ClientBankExchange\Model;

use Kily\Tools1C\ClientBankExchange\Component;

class GeneralSection extends Component
{
    public static function fields()
    {
        return [
            'ВерсияФормата',
            'Кодировка',
            'Отправитель',
            'Получатель',
            'ДатаСоздания',
            'ВремяСоздания',
            'ДатаНачала',
            'ДатаКонца',
            'РасчСчет',
        ];
    }

    public function __construct($data = [])
    {
        parent::__construct($data);
        if ($this->data['ДатаСоздания']) {
            $this->data['ДатаСоздания'] = $this->toDate($this->data['ДатаСоздания']);
        }
        if ($this->data['ВремяСоздания']) {
            $this->data['ВремяСоздания'] = $this->toTime($this->data['ВремяСоздания']);
        }

        foreach (['ДатаНачала'] as $k) {
            if ($this->data[$k]) {
                $this->data[$k] = $this->toDMYDate($this->data[$k]);
            }
        }

        foreach (['ДатаКонца'] as $k) {
            if ($this->data[$k]) {
                $this->data[$k] = $this->toDMYDate($this->data[$k]);
            }
        }
    }

    public function __toString() {
        $out = [];
        foreach($this->fields() as $f) {
            if($f == 'ДатаСоздания') {
                $out[$f] = $this->toDMYDate($this->data[$f]);
            } elseif($f == 'ВремяСоздания') {
                $out[$f] = $this->toHISDate($this->data[$f]);
            } else {
                $out[$f] = $this->data[$f];
            }
        }
        return implode("\n",array_map(function($k,$v){return $k.'='.$v;},array_keys($out),$out))."\n";
    }
}
