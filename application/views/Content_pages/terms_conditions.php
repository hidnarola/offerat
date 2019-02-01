<div class="panel-body">
    <div class="summernote">
        <?php
        if (!empty($pages)) {
            foreach ($pages as $page) {
                ?>
                <h4><strong><?= $page['page_title'] ?></strong></h4>
                <p><?= $page['page_content'] ?></p>
                <br>
                <?php
            }
        }
        ?>
        <?php ?>
    </div>
</div>