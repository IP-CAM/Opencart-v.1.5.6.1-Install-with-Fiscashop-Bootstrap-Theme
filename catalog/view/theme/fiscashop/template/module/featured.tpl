<div class="row fetured-product">
    <!-- <div class="box-heading col-md-12"><?php echo $heading_title; ?></div> -->
    <h2><?php echo $heading_title; ?></h2>
    
    <div class="box-content col-md-12">
        <div class="box-product row">
            <?php foreach ($products as $product) { ?>
            
            <div class="col-xs-6 col-sm-4 col-md-3 fetured-product-box">
                
                <?php if ($product['thumb']) { ?>                
                <div class="image">
                    <a href="<?php echo $product['href']; ?>">
                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                    </a>
                </div>
                <?php } ?>
                
                <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                <?php if ($product['price']) { ?>
                <div class="price">
                    <?php if (!$product['special']) { ?>
                    <?php echo $product['price']; ?>
                    <?php } else { ?>
                    <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
                <?php } ?>
                <div class="cart"><input type="button" value="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button btn btn-default" /></div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
