<div class="errors">
    <?php
        if ($this->session->flashdata("errors")) :
            foreach ($this->session->flashdata("errors") as $value) :
    ?>
    <p><?= $value ?></p>
    <?php
        endforeach;
    endif;
    ?>
</div>

