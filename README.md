# 1CClientBankExchange parser
Парсер формата обмена данными [1CClientBankExchange](http://v8.1c.ru/edi/edi_stnd/100/101.htm) (версии 1.03)

Установка
------------

Рекомендуемый способ установки через
[Composer](http://getcomposer.org):

```
$ composer require kilylabs/client-bank-exchange-php
```

Использование
-----

Пример кода

```php
<?php

use Kily\Tools1C\ClientBankExchange\Parser;
use Kily\Tools1C\ClientBankExchange\Order;

require('vendor/autoload.php');

// Парсинг выписки 1С
$p = new Parser('1c_export.txt');
var_dump($p->general); // general info
var_dump($p->filter); // selection settings
var_dump($p->remainings); // to see bank account remainings
foreach($p->documents as $d) {
    echo $d['type'], " => "; // document type
    echo $d->{'Номер'}; // some fields
    echo "\n";
}

// Создание платежного поручения
$o = new Order('40802810700000002864');
$o->addFromArray([
    'Номер'=>'16',
    'Дата'=>new DateTime,
    'Сумма'=>(float)6603.75,
    'ПлательщикСчет'=>'123123123123123123',
    'Плательщик'=>'Иванов Иван Иванович, ИП',
    'ПлательщикРасчСчет'=>'123123123123123',
    'ПлательщикБанк1'=>'ОАО СБЕРБАНКА',
    'ПлательщикБанк2'=>'г. Москва',
    'ПлательщикБИК'=>'044525974',
    'ПлательщикКорсчет'=>'123123123123123123',
    'ПолучательСчет'=>'123123123123123123',
    'Получатель'=>'Управление Федерального казначейства по г. Москве (ИФНС России № 18 по г.Москве)',
    'ПолучательИНН'=>'7718111790',
    'Получатель1'=>'Управление Федерального казначейства по г. Москве (ИФНС России № 18 по г.Москве)',
    'ПолучательРасчСчет'=>'40101810045250010041',
    'ПолучательБанк1'=>'ГУ БАНКА РОССИИ ПО ЦФО',
    'ПолучательБанк2'=>'Г. МОСКВА 35',
    'ПолучательБИК'=>'044525000',
    'ВидОплаты'=>'01',
    'СтатусСоставителя'=>'09',
    'ПлательщикКПП'=>0,
    'ПолучательКПП'=>'771801001',
    'ПоказательКБК'=>'18210202140061110160',
    'ОКАТО'=>'45316000',
    'ПоказательОснования'=>'ТП',
    'ПоказательПериода'=>'ГД.00.2019',
    'ПоказательНомера'=>0,
    'ПоказательДаты'=>0,
    'ПоказательТипа'=>'',
    'НазначениеПлатежа'=>'Страховые взносы, исчисленные с суммы дохода',
    'НазначениеПлатежа1'=>'Страховые взносы, исчисленные с суммы дохода',
    'Код'=>0,
]);

echo $o->__toString();
$o->save('order.txt');

```

TODO
-----
- сделать проверку файла на корректность структуры
- написать генератор
- добавить валидацию полей
