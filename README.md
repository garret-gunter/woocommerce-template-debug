# Woocommerce Template Debug
Wordpress plugin for debugging Woocommerce templates.

## Usage
1. Install and activate as a Wordpress plugin.
2. Set `WP_DEBUG` to true to enable debug message output.

## Example output

```html
<!-- WOO DEBUG TEMPLATE START: loop/result-count.php

    Located Template: /wp-content/plugins/woocommerce/templates/loop/result-count.php
    Template Path: 
    Template Arguments: 
                        Array
                        (
                            [total] => 21
                            [per_page] => 16
                            [current] => 1
                        )
                        
-->
<p class="woocommerce-result-count">
	Showing 1–16 of 21 results</p>
<!-- WOO DEBUG TEMPLATE END: loop/result-count.php -->

<!-- WOO DEBUG TEMPLATE START: loop/orderby.php

    Located Template: /wp-content/plugins/woocommerce/templates/loop/orderby.php
    Template Path: 
    Template Arguments: 
                        Array
                        (
                            [catalog_orderby_options] => Array
                                (
                                    [menu_order] => Default sorting
                                    [popularity] => Sort by popularity
                                    [rating] => Sort by average rating
                                    [date] => Sort by latest
                                    [price] => Sort by price: low to high
                                    [price-desc] => Sort by price: high to low
                                )
                        
                            [orderby] => menu_order
                            [show_default_orderby] => 1
                        )
                        
-->
<form class="woocommerce-ordering" method="get">
	<select name="orderby" class="orderby" aria-label="Shop order">
		<option value="menu_order" selected="selected">Default sorting</option>
		<option value="popularity">Sort by popularity</option>
		<option value="rating">Sort by average rating</option>
		<option value="date">Sort by latest</option>
		<option value="price">Sort by price: low to high</option>
		<option value="price-desc">Sort by price: high to low</option>
	</select>
	<input type="hidden" name="paged" value="1">
</form>
<!-- WOO DEBUG TEMPLATE END: loop/orderby.php -->
```
