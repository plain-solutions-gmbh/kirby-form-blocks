<?php if ($form->showForm()) : ?>

    <form method="post" id="<?= $form->id() ?>" action="<?= $page->url() . "#" . $form->id() ?>" novalidate>

        <div class="form-block grid">
            <?php if (!$form->isValid()) : ?>
                <div class="form-block-message form-block-invalid column" style="--columns: 12">
                    <?= $form->errorMessage() ?>
                </div>
            <?php endif ?>

            <?php foreach ($form->fields() as $field) : ?>

                <div class="form-block-field form-block-field-<?= $field->type(true) ?> column" style="--columns: <?= $field->width('grid') ?>" id="<?= $field->slug() ?>">
                    <?php if ($field->type() === 'formfields/radio' || $field->type() === 'formfields/checkbox') : ?>
                        <p for="<?= $field->slug() ?>" class="form-block-field-label">
                            <span class="form-block-field-label-text"><?= $field->label() ?></span>
                            <?php if ($field->required()->isTrue()) : ?>
                                <span class="form-block-field-label-required" aria-hidden="true">*</span>
                            <?php endif ?>
                        </p>
                    <?php else : ?>
                        <label for="<?= $field->slug() ?>" class="form-block-field-label">
                            <span class="form-block-field-label-text"><?= $field->label() ?></span>
                            <?php if ($field->required()->isTrue()) : ?>
                                <span class="form-block-field-label-required" aria-hidden="true">*</span>
                            <?php endif ?>
                        </label>
                    <?php endif ?>

                    <?php if (!$field->isValid()) : ?>
                        <span id="<?= $field->slug() ?>-error-message" class="form-block-message form-block-field-invalid"><?= $field->errorMessage() ?></span>
                    <?php endif ?>

                    <?= $field->toHtml() ?>


                </div>

            <?php endforeach ?>

            <div class="form-block-button form-block-submit column" style="--columns: 12">
                <input type="submit" name="<?= $form->id() ?>" value="<?= $form->message('send_button') ?>">
            </div>
        </div>
    </form>

<?php endif ?>


<?php if ($form->isFatal()) : ?>
    <div class="form-block-message form-block-fatal column">
        <?= $form->errorMessage() ?>
    </div>
<?php endif ?>

<?php if ($form->isSuccess()) : ?>
    <div class="form-block-message form-block-success column">
        <?= $form->successMessage() ?>
    </div>
<?php endif ?>

<style>
    .form-block {
        max-width: 500px;
    }

    form .grid {
        --gutter: .8rem;
    }

    .form-block-field {
        position: relative;
    }

    .form-block-field>label {
        display: block;
        padding-bottom: .3rem;
        font-weight: bold
    }

    .form-block-field-input input,
    .form-block-field-textarea textarea,
    .form-block-field-select select {
        line-height: 2rem;
        padding: 0 .5rem;
        box-sizing: border-box;
        font-size: 1rem;
        width: 100%;
    }

    .form-block-field-select select {
        height: 2.2rem;
    }


    .form-block input[type="checkbox"],
    .form-block input[type="radio"] {
        appearance: none;
        position: relative;
        margin-right: .3rem;
        margin-top: .2rem;
        font: inherit;
        width: 1.15em;
        height: 1.15em;
        border: 0.1em solid var(--color-black);
        display: inline-grid;
        place-content: center;
    }

    .form-block input[type="radio"],
    .form-block input[type="radio"]::before {
        border-radius: 50%;
    }


    .form-block input[type="checkbox"]::before,
    .form-block input[type="radio"]::before {

        content: "";
        width: 0.65em;
        height: 0.65em;
        opacity: 0;
        transition: opacity .2s;
        box-shadow: inset 1em 1em var(--color-black);
    }

    .form-block input[type="checkbox"]:checked::before,
    .form-block input[type="radio"]:checked::before {
        opacity: 1;
    }

    .form-block-button>input {
        display: block;
        margin-left: auto;
        padding: 0.5rem 2.5rem;
        background: none;
        border: 1px solid var(--color-black);
        font: inherit;
        font-weight: bold;
    }

    .form-block-field-textarea textarea {
        font-family: inherit;
        font-size: inherit;
        resize: none;

    }

    .form-block-field-invalid,
    .form-block-invalid {
        display: table;
        width: auto;
        margin-top: .5rem;
        padding: .3rem .5rem;
        font-size: .8rem;
        color: var(--color-white);
        background-color: var(--color-code-red);
    }

    .form-block-field-invalid {
        position: absolute;
        top: -1.2rem;
        right: 0;
    }

    .form-block-invalid {
        margin-top: -1.2rem;
    }

    .form-block-field-invalid:after {
        content: "";
        position: absolute;
        border-left: 0.5rem solid transparent;
        border-right: 0.5rem solid transparent;
        border-top: 0.5rem solid var(--color-code-red);
        bottom: -0.5rem;
        left: 0.5rem;
    }

    .form-block-fatal,
    .form-block-success {
        display: inline-block;
        padding: .5rem 1rem;
        color: var(--color-white);
    }

    .form-block-fatal {
        background-color: var(--color-code-red);
    }

    .form-block-success {
        background-color: var(--color-code-green);
    }
</style>
