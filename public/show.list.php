<?php
require_once '../lib/bootstrap.php';
$list = $model->getList();
echo $twig->render(
    'pages/show.list.html.twig',
    [
        'ListOfCurrencies' => $list,
    ]
);
