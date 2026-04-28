<?php foreach ($posts as $post) { ?>
    <?php $this->load->view('feed/post_item', ['post' => $post]); ?>
<?php } ?>