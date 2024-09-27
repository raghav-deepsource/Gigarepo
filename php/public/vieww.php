<?php

use common\enum\FileExtensionsEnum;
use common\models\Tasks;
use frontend\models\files\UploadFileModel;
use kartik\file\FileInput;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model UploadFileModel */
/* @var $task Tasks */
/* @var $attempt common\models\TaskAttempt */

$this->title = $task->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#task" data-bs-toggle="tab">Task</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#files" data-bs-toggle="tab">Files</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="task" role="tabpanel" aria-labelledby="task-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <h1><?= $task->title ?></h1>
                            <?= Html::decode($task->description) ?>
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="mr-3">Allowed extensions:</div>
                                <?php
                                $extensions = explode(',', $task->file_types);
foreach ($extensions as $extension) {
  $src = FileExtensionsEnum::ICON[$extension];
  echo "<div class=\"d-flex flex-column align-items-center mr-3\"><img src=\"{$src}\" alt=\"{$extension}\" width=\"30\" height=\"30\"><span>.{$extension}</span></div>";
}
?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php $form = ActiveForm::begin([
'id' => 'upload-file-form',
'options' => ['enctype' => 'multipart/form-data'],
                            ]); ?>
                           <?= $form->field($model, 'files[]')->widget(
                             FileInput::class,
                             [
                              'options'=>[
                              'multiple'=>true,
                              ],
                              'pluginOptions' => [
                              'maxFileCount' => 10,
                              'allowedFileExtensions' => $extensions,
                              'previewFileType' => 'any',
                              ]
                              ]
                           ) ?>
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">File name</th>
                                    <th scope="col">File size</th>
                                    <th scope="col">Uploaded at</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($attempt->files as $file): ?>
                                    <tr>
                                        <th scope="row"><?= $file->id ?></th>
                                        <td><?= $file->file->name ?></td>
                                        <td><?= round($file->file->size / 1024, 1) ?>KB</td>
                                        <td><?= date('Y-m-d g:i:s A', strtotime($file->file->created_at) + 7200) ?></td>
                                        <td>
                                            <a href="<?= Url::to(['tasks/download-file', 'id' => $attempt->id, 'fileId' => $file->id]) ?>" class="btn btn-primary">Download</a>
                                            <a href="<?= Url::to(['tasks/delete-file', 'id' => $attempt->id, 'fileId' => $file->id]); ?>" class="btn btn-danger">Remove</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
