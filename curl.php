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

// 0 - навзание
// 1 - инструкция
// 2 - МНН
// 3 - лекарственная форма
// 4 - дозировка
// 5 - фассовка
// 6 - производитель
// 7 - страна производства
// 8 - держатель свидетельсвта
// 9 - Страна держателя свидетельства
// 10 - АТХ
// 11 - Фармакотерапевтическая группа
// 12 - ПЖВЛС
// 13 - Условия отпуска из аптек
// 14 - № свидетельства
// 15 - Дата выдачи
// 16 - EAN13

$dom = str_get_html($html);
$lab = $dom->find('td');

$arrayToJson = [];
$index = 0;
while ($lab[$index] != null) {
//  echo $lab[$index]->plaintext . '<br>';
//  $key = $lab[$index]->plaintext;
//  array_push($arrayToJson, $lab[$index]->plaintext);
  $model = [
    'name' => $lab[$index]->plaintext,
    'instruction' => $lab[$index+1]->plaintext,
    'mnn' => $lab[$index+2]->plaintext,
    'form' => $lab[$index+3]->plaintext,
    'dosage' => $lab[$index+4]->plaintext,
    'packing' => $lab[$index+5]->plaintext,
    'manufacturer' => $lab[$index+6]->plaintext,
    'country_origin' => $lab[$index+7]->plaintext,
    'certificate_holder' => $lab[$index+8]->plaintext,
    'country_certificate_holder' => $lab[$index+9]->plaintext,
    'atx' => $lab[$index+10]->plaintext,
    'pharma_group' => $lab[$index+11]->plaintext,
    'pgvls' => $lab[$index+12]->plaintext,
    'vacation_conditions' => $lab[$index+13]->plaintext,
    'certificate' => $lab[$index+14]->plaintext,
    'date_certificate' => $lab[$index+15]->plaintext,
    'ean' => $lab[$index+16]->plaintext
  ];

  array_push($arrayToJson, $model);
  $index += 17;
}

echo print_r($arrayToJson);

file_put_contents('file.json', json_encode($arrayToJson));
//readfile('file.json');