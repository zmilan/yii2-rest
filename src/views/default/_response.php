<?php
use yii\helpers\Html;
use yii\web\Response;

/**
 * @var \yii\web\View $this
 * @var \zhuravljov\yii\rest\models\ResponseRecord $record
 */
?>
<div id="response" class="rest-response">
    <?php if ($record->status): ?>

        <ul class="nav nav-tabs">
            <li>
                <a href="#response-body" data-toggle="tab">
                    Response Body
                </a>
            </li>
            <li>
                <a href="#response-headers" data-toggle="tab">
                    Response Headers
                    <?= Html::tag('span', count($record->headers), [
                        'class' => 'counter' . (!count($record->headers) ? ' hidden' : '')
                    ]) ?>
                </a>
            </li>
            <li class="pull-right">
                <div class="info">
                    Duration:
                    <span class="label label-default">
                        <?= round($record->duration * 1000) ?> ms
                    </span>
                </div>
            </li>
            <li class="pull-right">
                <div class="info">
                    Status:
                    <?php
                    $class = 'label';
                    if ($record->status < 300) {
                        $class .= ' label-success';
                    } elseif ($record->status < 400) {
                        $class .= ' label-info';
                    } elseif ($record->status < 500) {
                        $class .= ' label-warning';
                    } else {
                        $class .= ' label-danger';
                    }
                    ?>
                    <span class="<?= $class ?>">
                        <?= Html::encode($record->status) ?>
                        <?= isset(Response::$httpStatuses[$record->status]) ? Response::$httpStatuses[$record->status] : '' ?>
                    </span>
                </div>
            </li>
        </ul>


        <div class="tab-content">

            <div id="response-body" class="tab-pane">
                <pre><?= Html::encode($record->content) ?></pre>
            </div><!-- #response-body -->

            <div id="response-headers" class="tab-pane">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($record->headers as $name => $values): ?>
                            <?php foreach ($values as $value): ?>
                                <tr>
                                    <th><?= Html::encode($name) ?></th>
                                    <td><samp><?= Html::encode($value) ?></samp></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php  ?>
            </div><!-- #response-headers -->

        </div>
    <?php endif; ?>
</div>
<?php
$this->registerJs(<<<JS

if (window.localStorage) {
    var responseTab = localStorage['responseTab'] || 'response-body';
    $('a[href=#' + responseTab + ']').tab('show');
    $('a[href=#response-body]').on('shown.bs.tab', function() {
        localStorage['responseTab'] = 'response-body';
    });
    $('a[href=#response-headers]').on('shown.bs.tab', function() {
        localStorage['responseTab'] = 'response-headers';
    });
}

JS
);