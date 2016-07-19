<?php

/* @var $this yii\web\View */

$this->title = \Yii::t('app', 'Random game');
$hasGame = $data['hasGame'];
?>
<script>var isGuest = <?php echo \Yii::$app->user->isGuest ? '1' : '0'; ?></script>
<div class="jumbotron">
    <h1><?= \Yii::t('app', 'Hello friend!') ?></h1>
    <button class="btn btn-lg btn-default" name="playmoney" id="playmoney" data-toggle="modal"
            data-target="#mailModal"><?= \Yii::t('app', 'I am lucky, Bet money') ?></button>
    </p>

    <div class="row">
        <div id="messages" class="col-md-7"><?= $msg ?></div>
        <div class="col-md-5">
            <div>
                <span style="width:95px;display:inline-block;"><?= \Yii::t('app', 'Your Drop') ?></span>
                <span id="yourDrop"></span>
            </div>
            <div>
                <span style="width:95px;display:inline-block;"><?= \Yii::t('app', 'Opponent Drop') ?></span>
                <span id="oppDrop"></span>
            </div>
        </div>
    </div>
    <div class="lead">
        <div class="row mb">
            <div class="col-sm-3 col-xs-6"><?= \Yii::t('app', 'My credits') ?></div>
            <div class="col-sm-4">
                <span id="credits"><?= @$data['credits'] ?></span>
                <span class="pull-right">(<a class="has-spinner" id="creditRequest" href="javascript:creditRequest();">попросить
                        неденег<span class="spinner"><i class="fa fa-cog fa-spin"></i></span></a>)</span>
            </div>
        </div>
        <div class="row"></div>
        <div class="row">
            <div class="col-sm-3 col-xs-6"><?= \Yii::t('app', 'Bet:') ?></div>
            <div class="col-sm-3 col-md-2">
                <div class="betbtn input-group">
                    <span id="more" class="input-group-addon">+</span>
                    <input id="bet" type="text" class="form-control"
                           value="<?php echo (\Yii::$app->session->get('fixedBet') > 0) ? \Yii::$app->session->get('fixedBet') : 1; ?>"
                           onchange="if (this.value>10) this.value=10;if ($('#fixedbet').prop('checked')) $('#fixedbet').click();">
                    <span id="less" class="input-group-addon">-</span>
                </div>
            </div>
            <div class="col-sm-3">
                <label for="fixedbet"><input type="checkbox" name="fixedbet"
                                             id="fixedbet"<?php echo \Yii::$app->session->get('fixedBet') ? ' checked' : ''; ?>>
                    запомнить</label>
            </div>
        </div>
    </div>

    <p>
        <?php if (!$data['credits']): ?>
        <?php if (\Yii::$app->user->isGuest): ?>

    <div class="round-button ">
        <div class="round-button-circle">
            <button class="btn btn-lg btn-success input-lg" name="play" id="play"
                    data-state="needRegister"><?= \Yii::t('app', 'Please register to continue') ?></button>
        </div>
    </div>
    <?php else: ?>
        <button class="btn btn-lg btn-success" name="play" id="play" data-state="ready"
                disabled="disabled"><?= \Yii::t('app', 'We need more Gold! (C)') ?></button>
    <?php
    endif;
    ?>
    <?php elseif (!$hasGame): ?>
        <div class="round-button">
            <div class="round-button-circle">
                <button class="round-button" name="play" id="play"
                        data-state="needRegister"><?= \Yii::t('app', 'Play game') ?></button>
            </div>
        </div>
    <?php
    else: ?>
        <button class="btn btn-lg btn-success" name="play" id="play" data-state="waitOpponent"
                disabled="disabled"><?= \Yii::t('app', 'Wait for opponent') ?></button>
    <?php
    endif;
    ?>
    </p>
</div>

<!-- Modal -->
<div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?= \Yii::t('app', 'Get your email') ?></h4>
                </div>
                <div class="modal-body">
                    <?= \Yii::t('app', 'Your invite will be send to email') ?>
                    <input class="form-input" name="email" type="email" id="email" required
                           value="<?= @$data['email'] ?>"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                    <button type="submit" id="sendInvite" name="sendInvite"
                            class="btn btn-primary"><?= \Yii::t('app', 'Get invite') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
