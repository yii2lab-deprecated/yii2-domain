<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii2lab\helpers\Page;

$this->title = t('article/main', 'list');

$baseUrl = 'manage/';
$columns = [
	[
		'attribute' => 'title',
		'label' => t('main', 'title'),
	],
];

?>

<?= Page::snippet('list', '@common', compact('dataProvider', 'baseUrl', 'columns')) ?>

<?= Html::a(t('action', 'CREATE'), $baseUrl . 'create', ['class' => 'btn btn-success']) ?>