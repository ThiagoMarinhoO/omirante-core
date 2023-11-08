<?php
    function mega_menu() {
        ?>
    
        <header class="mega_menu">
    
            <?php
            wp_nav_menu(
                array(
                    'menu'            => 'Menu Cabeçalho Imagem Mirante',
                    'container_id'    => 'primary-menu',
                    'container_class' => 'items-center justify-between hidden w-full md:flex md:w-auto md:order-1',
                    'menu_class'      => 'menuListStyle',
                    'theme_location'  => 'primary',
                    'walker'          => new Custom_Walker_Nav_Menu(), // Adicione o walker personalizado
                )
            );
            ?>
    
        </header>
    
        <?php
    }
    add_shortcode('mega_menu', 'mega_menu');


    class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
        function start_lvl(&$output, $depth = 0, $args = null) {
            if ($depth === 0) {
                $output .= '<ul class="sub-menu">';
                $output .= '<div class="imageContainer"><img src="" alt="Imagem da categoria"></div>';
                $output .= '<div class="childrenLiDiv">';
                $output .= '<li>';
            } else {
                // Caso haja mais de um nível de submenu, adicione um novo <li>
                $output .= '<li>';
            }
        }
    
        function end_lvl(&$output, $depth = 0, $args = null) {
            if ($depth === 0) {
                $output .= '</div>';
                $output .= '</ul>';
            }
        }
    
        function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
            if ($depth === 0) {

                $product_category_id = $item->object_id;
                $custom_field_value = get_field('imagem', 'product_cat_' . $product_category_id);

                // log_to_file($custom_field_value);

                if($args->walker->has_children) {
                    $output .= '<li categoryImage="' . $custom_field_value . '" class="menu-item-has-children">';
                    $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '<i class="fas fa-angle-down premium-dropdown-icon"></i></a>';
                } else {
                    $output .= '<li>';
                    $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
                }

            } else {
                $product_category_id = $item->object_id;
                $custom_field_value = get_field('imagem', 'product_cat_' . $product_category_id);
                $output .= '<a categoryImage="' . $custom_field_value . '" href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
            }
            
        }
    
        function end_el(&$output, $item, $depth = 0, $args = null) {
            if ($depth === 0) {
                $output .= '</li>';
            }
        }
    }
    
    