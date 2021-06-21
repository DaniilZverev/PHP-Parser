<?php
include_once ('simple_html_dom.php');

function curl_get($url, $postData = null, $referer = 'http://www.google.com'){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64;
ry:38.0) Gecko/20100101 Firefox/38.0");
  curl_setopt($ch, CURLOPT_REFERER, $referer);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  if ($postData){
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  }

  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

$post = [
  'name'      => '',
  'mnn'       => '',
  'proizvod'  => '',
  'ftg'       => '',
  'ath'       => 'A01',
  'ean'       => ''
];

$html = curl_get('http://212.112.103.101/reestr', $post);

//echo $html;

$dom = str_get_html($html);
$lab = $dom->find('td');

$arrayToJson = [];
$index = 0;
ini_set('display_errors','Off');

while ($lab[$index] != null) {
  $model = [
    'name' => $lab[$index]->plaintext, // 0 - навзание
//    'instruction' => $lab[$index+1]->plaintext, // 1 - инструкция
    'mnn' => $lab[$index+2]->plaintext, // 2 - МНН
    'form' => $lab[$index+3]->plaintext, // 3 - лекарственная форма
    'dosage' => $lab[$index+4]->plaintext, // 4 - дозировка
    'packing' => $lab[$index+5]->plaintext, // 5 - фассовка
    'manufacturer' => $lab[$index+6]->plaintext, // 6 - производитель
    'country_origin' => $lab[$index+7]->plaintext, // 7 - страна производства
    'certificate_holder' => $lab[$index+8]->plaintext, // 8 - держатель свидетельсвта
    'country_certificate_holder' => $lab[$index+9]->plaintext, // 9 - Страна держателя свидетельства
    'atx' => $lab[$index+10]->plaintext, // 10 - АТХ
    'pharma_group' => $lab[$index+11]->plaintext, // 11 - Фармакотерапевтическая группа
    'pgvls' => $lab[$index+12]->plaintext, // 12 - ПЖВЛС
    'vacation_conditions' => $lab[$index+13]->plaintext, // 13 - Условия отпуска из аптек
    'certificate' => $lab[$index+14]->plaintext, // 14 - № свидетельства
    'date_certificate' => $lab[$index+15]->plaintext, // 15 - Дата выдачи
    'ean' => $lab[$index+16]->plaintext // 16 - EAN13
  ];
  array_push($arrayToJson, $model);
  $index += 17;
}
ini_set('display_errors','on');

file_put_contents('file.json', json_encode($arrayToJson, JSON_UNESCAPED_UNICODE));

echo '<a href="/file.json" download="">Скачать</a>';