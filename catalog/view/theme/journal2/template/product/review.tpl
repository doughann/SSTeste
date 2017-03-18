<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<div class="review-list" itemprop="review" itemscope itemtype="http://schema.org/Review">
  <div class="author"><b itemprop="author"><?php echo $review['author']; ?></b> <?php echo $text_on; ?> <span itemprop="datePublished"><?php echo $review['date_added']; ?></span></div>
  <div class="rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"><img width="83" height="15" src="catalog/view/theme/default/image/stars-<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['rating']; ?>" title="<?php echo $review['rating']; ?> "/><meta itemprop="worstRating" content="1"> ( <span itemprop="ratingValue"><?php echo $review['rating']; ?></span> / <span itemprop="bestRating">5</span> )</div>
  <div class="text" itemprop="description"><?php echo $review['text']; ?></div>
</div>
<?php } ?>
<div class="pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="content"><?php echo $text_no_reviews; ?></div>
<?php } ?>
