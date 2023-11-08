<?php
function search_form_shortcode() {
    ob_start();
    ?>
    <form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
        <input type="text" value="" name="s" id="s" class="searchProductsForm" placeholder="O que você está procurando ?">
        <!-- <input type="submit" id="searchsubmit" value="Pesquisar"> -->
        <div class="searchLupa"><i aria-hidden="true" class="fas fa-search"></i></div>
    </form>
    <div id="search-results"></div>


    <?php
    return ob_get_clean();
}
add_shortcode('search_form', 'search_form_shortcode');

function search_products() {
    $searchTerm = sanitize_text_field($_POST['searchTerm']);
    $args = array(
        'post_type' => 'product', // Substitua 'product' pelo tipo de postagem de seus produtos
        's' => $searchTerm,
    );

    $searched_product_query = new WP_Query($args);

    if ($searched_product_query->have_posts()) {
        while ($searched_product_query->have_posts()) {
            $product = $searched_product_query->the_post();
            ?>

            <a href="<?php echo get_permalink(); ?>" class="searched-product-card">
                    <div class="searched-card-image">
                        <?php
                        if (has_post_thumbnail()) {
                            $thumbnail_url = get_the_post_thumbnail_url(null, 'thumbnail');
                            echo '<img src="' . esc_url($thumbnail_url) . '" class="searched-product-image" alt="' . esc_attr(get_the_title()) . '" style="width: 80px; height: 80px;">';
                        } else {
                            // Substitua 'URL_DA_IMAGEM_PADRAO' pela URL da imagem padrão
                            echo '<img src="URL_DA_IMAGEM_PADRAO" class="searched-product-image" alt="' . esc_attr(get_the_title()) . '" style="width: 80px; height: 80px;">';
                        }
                        ?>
                    </div>
                    <div class="searched-card-body">
                        <div class="card-body">
                            <h5 class=""><?php the_title(); ?></h5>
                        </div>
                    </div>
                </a>
            </div>

            <?php
        }
    } else {
        echo 'Nenhum resultado encontrado.';
    }

    wp_die(); // Encerre a execução para evitar saída HTML adicional
}
add_action('wp_ajax_search_products', 'search_products');
add_action('wp_ajax_nopriv_search_products', 'search_products');
