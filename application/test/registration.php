<?php

include './bootstrap.php';


header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="test.csv"');

/*
$datas = array(
    'galaxy' => array(),
    'system' => array(),
    );
*/
for ($i = 0; $i < 10000; $i++) {
    $collection = Legacies_Empire_Model_Planet::searchMostFreeSystems();
    $collection->limit(1)->load();
    $systemInfo = $collection->current();

    //var_dump($systemInfo->getallDatas());
    echo $systemInfo->getData('galaxy') . ';' . $systemInfo->getData('system') . PHP_EOL;
/*
    $galaxy = $systemInfo->getData('galaxy');
    if (!isset($datas['galaxy'][$galaxy])) {
        $datas['galaxy'][$galaxy] = 1;
    } else {
        $datas['galaxy'][$galaxy]++;
    }

    $system = $systemInfo->getData('system');
    if (!isset($datas['system'][$system])) {
        $datas['system'][$system] = 1;
    } else {
        $datas['system'][$system]++;
    }
*/
}
/*
asort($datas['galaxy'], SORT_NUMERIC);
asort($datas['system'], SORT_NUMERIC);

var_dump($datas);
*/