<?php

/* @var $this yii\web\View
 * @var $entity yii2lab\domain\BaseEntity
 */
use yii2lab\extension\yii\helpers\Html;

?>

<div class="pull-right">
	<?= Html::a(Html::fa('pencil', ['class' => 'text-primary']), ['/article/manage/update', 'id' => $entity->id], [
		'class' => 'btn btn-default',
		'title' => Yii::t('action', 'update'),
	]) ?>
	<?= Html::a(Html::fa('trash', ['class' => 'text-danger']), ['/article/manage/delete', 'id' => $entity->id], [
		'class' => 'btn btn-default',
		'title' => Yii::t('action', 'delete'),
		'data' => [
			'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
			'method' => 'post',
		],
	]) ?>
</div>

<div>
	<?= $entity->content ?>
</div>
