<?php
function input_hidden(){
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat', // Taxonomia de categorias de produtos do WooCommerce
        'hide_empty' => false, // Inclua categorias vazias, se necessário
    ));

    if (!empty($product_categories)) {
        foreach ($product_categories as $category) {
            $category_name = $category->name;
            $acf_image = get_field('imagem', 'product_cat_' . $category->term_id); // Substitua 'campo_do_acf_imagem' pelo nome do campo ACF de imagem

            if ($acf_image) {
                echo '<input type="hidden" name="category_image" category-name="' . esc_attr($category_name) . '" value="' . esc_attr($acf_image) . '">';
            }
        }
    }
    ?>
    <div class="hidden-input-menu"></div>
    <?php
}

add_shortcode('input_hidden' , 'input_hidden');
?>