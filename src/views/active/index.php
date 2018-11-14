<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii2lab\extension\web\helpers\Page;

$this->title = Yii::t('article/main', 'list');

$baseUrl = 'manage/';
$columns = [
	[
		'attribute' => 'title',
		'label' => Yii::t('main', 'title'),
	],
];

?>

<?= Page::snippet('list', '@common', compact('dataProvider', 'baseUrl', 'columns')) ?>

<?= Html::a(Yii::t('action', 'create'), $baseUrl . 'create', ['class' => 'btn btn-success']) ?>