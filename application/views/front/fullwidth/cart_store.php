<?php include('header.php'); ?>

<!-- Page Content Wraper -->
    <div class="page-content-wraper">
        <!-- Bread Crumb -->
        <section class="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumb-link">
                            <a href="<?php echo base_url()?>">Home</a>
                            <span><?php echo $this->page ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Bread Crumb -->

        <!-- Page Content -->
        <section class="content-page">
            <div class="container mb-80">
                <div class="row">
                    <div class="col-sm-12">
                        <article class="post-8">
                            <form class="cart-form" action="#" method="post">
                                <div class="cart-product-table-wrap responsive-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product-remove"></th>
                                                <th class="product-thumbnail"></th>
                                                <th class="product-name">Product</th>
                                                <th class="product-price">Price</th>
                                                <th class="product-quantity">Quantity</th>
                                                <th class="product-subtotal">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="product-remove">
                                                    <a href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                                </td>
                                                <td class="product-thumbnail">
                                                    <a>
                                                        <img src="img/product-img/small/product_12547554.jpg" alt="" /></a>
                                                </td>
                                                <td class="product-name">
                                                    <a>Alpha Block Black Polo T-Shirt</a>
                                                </td>
                                                <td class="product-price">
                                                    <span class="product-price-amount amount"><span class="currency-sign">$</span>399.00</span>
                                                </td>
                                                <td>
                                                    <div class="product-quantity">
                                                        <span data-value="+" class="quantity-btn quantityPlus"></span>
                                                        <input class="quantity input-lg" step="1" min="1" max="9" name="quantity" value="1" title="Quantity" type="number" />
                                                        <span data-value="-" class="quantity-btn quantityMinus"></span>
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span class="product-price-sub_totle amount"><span class="currency-sign">$</span>399.00</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="product-remove">
                                                    <a href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                                </td>
                                                <td class="product-thumbnail">
                                                    <a>
                                                        <img src="img/product-img/small/product_12547555.jpg" alt="" /></a>
                                                </td>
                                                <td class="product-name">
                                                    <a>Red Printed Round Neck T-Shirt</a>
                                                </td>
                                                <td class="product-price">
                                                    <span class="product-price-amount amount"><span class="currency-sign">$</span>299.00</span>
                                                </td>
                                                <td>
                                                    <div class="product-quantity">
                                                        <span data-value="+" class="quantity-btn quantityPlus"></span>
                                                        <input class="quantity input-lg" step="1" min="1" max="9" name="quantity" value="2" title="Quantity" type="number" />
                                                        <span data-value="-" class="quantity-btn quantityMinus"></span>
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span class="product-price-sub_totle amount"><span class="currency-sign">$</span>598.00</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row cart-actions">
                                    <div class="col-md-6">
                                        <div class="coupon">
                                            <input class="input-md" id="coupon_code" name="coupon_code" title="Coupon Code" value="" placeholder="Enter Coupon Code" type="text">
                                            <input class="btn btn-md btn-black" name="coupon_code" value="Apply Coupon" type="submit" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <input class="btn btn-md btn-gray" name="update_cart" value="Update cart" disabled="" type="submit">
                                    </div>
                                </div>
                            </form>
                            <div class="cart-collateral">
                                <div class="cart_totals">
                                    <h3>Cart totals</h3>
                                    <div class="responsive-table">
                                        <table>
                                            <tbody>
                                                <tr class="cart-subtotal">
                                                    <th>Subtotal</th>
                                                    <td><span class="product-price-amount amount"><span class="currency-sign">$</span>997.00</span></td>
                                                </tr>
                                                <tr class="shipping">
                                                    <th>Shipping</th>
                                                    <td>
                                                        <ul id="shipping_method">
                                                            <li>
                                                                <input name="shipping_method[0]" data-index="0" id="shipping_method_0_legacy_flat_rate" value="legacy_flat_rate" class="shipping_method" checked="checked" type="radio">
                                                                <label for="shipping_method_0_legacy_flat_rate">Flat Rate: <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>12.00</span></label>
                                                            </li>
                                                            <li>
                                                                <input name="shipping_method[0]" data-index="0" id="shipping_method_0_legacy_free_shipping" value="legacy_free_shipping" class="shipping_method" type="radio">
                                                                <label for="shipping_method_0_legacy_free_shipping">Free Shipping</label>
                                                            </li>
                                                            <li>
                                                                <input name="shipping_method[0]" data-index="0" id="shipping_method_0_legacy_local_delivery" value="legacy_local_delivery" class="shipping_method" type="radio">
                                                                <label for="shipping_method_0_legacy_local_delivery">Local Delivery</label>
                                                            </li>
                                                        </ul>
                                                        <form>
                                                            <p>
                                                                <a href="#">Calculate shipping</a>
                                                            </p>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr class="order-total">
                                                    <th>Total</th>
                                                    <td><span class="product-price-amount amount"><span class="currency-sign">$</span>1009.00</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="product-proceed-to-checkout">
                                        <a class="btn btn-lg btn-color form-full-width" href="checkout.html">Proceed to checkout</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>