<div class="column form-block-field-options">

    <?php foreach ($formfield->options() as $option) : ?>

        <div class="column form-block-field-option" >
            <label for="<?= $formfield->id() . '-' . $option->slug() ?>">
                <input
                    type="radio"
                    id="<?= $formfield->id() . '-' . $option->slug() ?>"
                    name="<?= $formfield->slug() ?>"
                    value="<?= $option->slug() ?>"
                    <?= $formfield->autofill(true) ?>
                    <?= e($option->selected()->isTrue(), " checked") ?>
                    <?= $formfield->required('attr') ?>
                    <?= $formfield->ariaAttr() ?>
                >
                <?= $option->label() ?>
            </label>
        </div>

    <?php endforeach ?>

</div>