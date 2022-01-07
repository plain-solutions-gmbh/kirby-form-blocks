<div class="grid">

    <?php foreach ($formfield->options() as $option) : ?>

        <div class="column form-block-field-option" style="--columns: <?= $formfield->columns('grid') ?>">
            <label for="<?= $option->slug() ?>">
                <input type="checkbox" id="<?= $option->slug() ?>" name="<?= $option->slug() ?>" value="<?= $option->slug() ?>" <?= e($option->selected()->isTrue(), " checked") ?><?= e($formfield->required()->isTrue(), " required") ?>>
                <?= $option->label() ?>
            </label>
        </div>

    <?php endforeach ?>

</div>
