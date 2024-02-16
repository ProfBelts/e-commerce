<?php 
$date_string = $product["updated_at"];
$formatted_date = date("F j, Y", strtotime($date_string));
?>

<body>
    <nav> 
        <ul> 
            <li>V88 Merchandise</li>
        <?php 
            $user = $this->session->userdata("user"); 
            if($user["is_admin"] == 1) { ?> 
                <li><a href = "<?= base_url('dashboard/admin') ?>">Dashboard</a></li>
        <?php } else { ?>
            <li><a href = "<?= base_url('dashboard') ?>">Dashboard</a></li>
        <?php }   ?>
            
            <li><a href = "#">Profile</a></li>
        </ul>
        <a class = "log_out" href = "<?= base_url('dashboard/logout') ?>">Log-out</a>
    </nav>

    <main> 
        <h1><?= $product["name"] ?> (&#8369;<?= $product["price"]; ?>)</h1>
        <div class = "item_description">
            <ul>
                <li>Added since: <?= $formatted_date ?></li>
                <li>Product ID: <?= $product["id"] ?></li>
                <li>Description: <?= $product["description"] ?> </li>
                <li>Total sold: <?= $product["quantity_sold"] ?> </li>
                <li>Number of available stocks: <?= $product["inventory_count"] ?> </li>
            </ul>
        </div>
    </main>


    <section class="reviews">
                <h2>Leave a Review </h2>
                <form method="POST" action="<?= base_url("reviews/process_review/" . $product["id"]) ?>">
                    <input type = "hidden" name = "users_id" value = <?= $user_id ?> />
                    <input type = "hidden" name = "product_id" value = <?= $product["id"] ?> />
                    <textarea cols="2" rows="50" placeholder="Please leave your review here..." name="user_review"></textarea>
                    <input type="submit" name="submit" class="post-review" />
                </form>
            </section>
            <section class="comments">
    <h1>Review Section:</h1>

    <section class="comments">
    <?php foreach ($reviews as $review) { 
    // Fetch replies for the current review
    $replies = $this->review->show_replies($review["review_id"]);

    // Calculate elapsed time for the review
    date_default_timezone_set("Asia/Manila");
    $current_time = strtotime(date("Y-m-d H:i:s")); // Current time as timestamp
    $time_stamp = strtotime($review["comment_time"]); // Comment time as timestamp
    $time_difference_seconds = $current_time - $time_stamp; // Difference in seconds
    
    // Convert seconds to minutes, hours, days, etc.
    if ($time_difference_seconds < 60) {
        $elapsed = 'Just now';
    } elseif ($time_difference_seconds < 3600) {
        $elapsed = floor($time_difference_seconds / 60) . ' minutes ago';
    } elseif ($time_difference_seconds < 86400) {
        $elapsed = floor($time_difference_seconds / 3600) . ' hours ago';
    } else {
        $elapsed = floor($time_difference_seconds / 86400) . ' days ago';
    }
?>
    <h3><?= $review["name"] ?> - <?= $elapsed ?></h3>
    <p><?= $review["content"] ?></p>

    <!-- Display replies for the current review -->
    <?php foreach($replies as $reply_content): 
        $current_time = strtotime(date("Y-m-d H:i:s")); 
        $time_stamp = strtotime($reply_content["date"]); 
        $time_difference_seconds = $current_time - $time_stamp; 
        if ($time_difference_seconds < 60) {
            $elapsed = 'Just now';
        } elseif ($time_difference_seconds < 3600) {
            $elapsed = floor($time_difference_seconds / 60) . ' minutes ago';
        } elseif ($time_difference_seconds < 86400) {
            $elapsed = floor($time_difference_seconds / 3600) . ' hours ago';
        } else {
            $elapsed = floor($time_difference_seconds / 86400) . ' days ago';
        } ?>
        <div class="reply_section"> 
            <?php if (isset($reply_content["name"])): ?>
                <h3><?= $reply_content["name"] ?> - <?= $elapsed ?> </h3>
            <?php else: ?>
                <h3>Unknown User</h3>
            <?php endif; ?>
            <?php if (isset($reply_content["content"])): ?>
                <p><?= $reply_content["content"] ?></p>
            <?php else: ?>
                <p>No content available</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <!-- Add reply form for the current review -->
    <?php if ($reply === FALSE) { ?>
        <form method="POST" action="<?= base_url("reviews/reply_button") ?>">
            <input type="hidden" name="product_id" value="<?= $product["id"] ?>" />
            <input type="hidden" name="comment_id" value="<?= $review['review_id'] ?>" />
            <input type="submit" name="reply" value="Reply" id="reply_btn" />
        </form>
    <?php } else if ($this->session->userdata("reply_comment_id") == $review["review_id"]) { ?>
        <form method="POST" action="<?= base_url("reviews/submit_reply") ?>"> 
            <input type="hidden" name="product_id" value="<?= $product["id"] ?>" />
            <input type="hidden" name="comment_id" value="<?= $review['review_id'] ?>" />
            <textarea cols="2" rows="50" class="reply_content" placeholder="Reply here..." name="reply_content"></textarea>
            <input type="submit" name="back" value="Back" class="back_btn"/>
            <input type="submit" name="reply" value="Reply" class="reply_btn" />
        </form>
    <?php } ?>
<?php } ?>

</section>






   
</body>
