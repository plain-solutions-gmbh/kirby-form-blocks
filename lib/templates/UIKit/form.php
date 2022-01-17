<?php if ($form->showForm()) : ?>

    <form method="post" id="<?= $form->id() ?>" action="<?= $page->url() . "#" . $form->id() ?>" novalidate>

        <div class="form-block" uk-grid>
            <?php if (!$form->isValid()) : ?>
                <div class="uk-width-1-1">
                    <div class="uk-alert-warning form-block-message form-block-invalid" uk-alert>
                        <?= $form->errorMessage() ?>
                    </div>
                </div>
            <?php endif ?>

            <?php foreach ($form->fields() as $field) : ?>

                <div class="form-block-field form-block-field-<?= $field->type(true) ?> uk-width-1-1 uk-width-<?= $field->width('dash') ?>@m" id="<?= $field->slug() ?>">
                    <legend class="uk-legend" for="<?= $field->slug() ?>"><?= $field->label() ?> <?= e($field->required()->isTrue(), " *") ?></legend>
                    <div class="uk-margin" uk-tooltip="<?= $field->errorMessage() ?>">
                        <?= $field->toHtml() ?>
                    </div>
                </div>
            <?php endforeach ?>

            <div class="form-block-button form-block-submit column uk-width-1-1">
                <input type="submit" class="uk-button uk-button-default" name="<?= $form->id() ?>" value="<?= $form->message('send_button') ?>">
            </div>
        </div>
    </form>

<?php endif ?>


<?php if ($form->isFatal()) : ?>

    <div class="uk-width-1-1">
        <div class="uk-alert-danger form-block-message form-block-error" uk-alert>
            <?= $form->errorMessage() ?>
        </div>
    </div>

<?php endif ?>

<?php if ($form->isSuccess()) : ?>

    <div class="uk-width-1-1">
        <div class="uk-alert-success form-block-message form-block-success" uk-alert>
            <?= $form->successMessage() ?>
        </div>
    </div>

<?php endif ?>
