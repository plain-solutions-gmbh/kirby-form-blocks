<div class="column form-block-field-options" style="display:grid;grid-gap: 1em 0.5em; grid-template-columns: repeat(12, 1fr);">

    <?php foreach ($formfield->options() as $option) : ?>

        <div class="column form-block-field-option" style="grid-column: span <?= $formfield->columns('grid') ?>">
            <label for="<?= $option->slug() ?>">
                <input type="radio" id="<?= $option->slug() ?>" name="<?= $formfield->slug() ?>" value="<?= $option->slug() ?>" <?= e($option->selected()->isTrue(), " checked") ?>>
                <?= $option->label() ?>
            </label>
        </div>

    <?php endforeach ?>

</div>