<?php
// Adicione campos personalizados ao pedido
add_action('woocommerce_admin_order_data_after_billing_address', 'add_custom_fields_to_order');
function add_custom_fields_to_order($order){
    echo '<p><strong>Dados do Cliente</strong></p>';
    woocommerce_wp_text_input(array(
        'id' => 'nome_cliente',
        'label' => 'Nome do Cliente',
        'wrapper_class' => 'form-field-wide',
    ));
    woocommerce_wp_text_input(array(
        'id' => 'email_cliente',
        'label' => 'E-mail do Cliente',
        'wrapper_class' => 'form-field-wide',
    ));
    woocommerce_wp_text_input(array(
        'id' => 'phone_cliente',
        'label' => 'Telefone do Cliente',
        'wrapper_class' => 'form-field-wide',
    ));
    woocommerce_wp_text_input(array(
        'id' => 'cnpj_cliente',
        'label' => 'CNPJ do Cliente',
        'wrapper_class' => 'form-field-wide',
    ));
}

// Salvar os campos personalizados quando o pedido é salvo
add_action('woocommerce_process_shop_order_meta', 'save_custom_fields');
function save_custom_fields($order_id) {
    $nome_cliente = sanitize_text_field($_POST['nome_cliente']);
    $email_cliente = sanitize_email($_POST['email_cliente']);
    $telefone_cliente = sanitize_text_field($_POST['phone_cliente']);
    $cnpj_cliente = sanitize_text_field($_POST['cnpj_cliente']);
    
    update_post_meta($order_id, 'nome_cliente', $nome_cliente);
    update_post_meta($order_id, 'email_cliente', $email_cliente);
    update_post_meta($order_id, 'phone_cliente', $telefone_cliente);
    update_post_meta($order_id, 'cnpj_cliente', $cnpj_cliente);

    // Verifica se o usuário já existe com base no e-mail
    $user = get_user_by('email', $email_cliente);

    if (!$user) {
        // Se o usuário não existir, crie um novo
        $customer_data = array(
            'user_email' => $email_cliente,
            'user_login' => $email_cliente,
            'first_name' => $nome_cliente,
            'role' => 'customer',
        );

        $user_id = wp_insert_user($customer_data);

        if (!is_wp_error($user_id)) {
            // Atualize os campos de endereço de faturamento do usuário
            update_user_meta($user_id, 'billing_phone', $telefone_cliente);
            update_user_meta($user_id, 'billing_cnpj', $cnpj_cliente);
            update_user_meta($user_id, 'shipping_first_name' , $nome_cliente);
            update_post_meta($order_id , '_billing_first_name' , $nome_cliente);
        } else {
            // Lida com erros
            $error_message = $user_id->get_error_message();
            log_to_file('Erro ao criar o usuário: ' . $error_message);
        }
    } else {
        // Se o usuário já existe, atualize os campos de endereço de faturamento
        update_user_meta($user->ID, 'billing_phone', $telefone_cliente);
        update_user_meta($user->ID, 'billing_cnpj', $cnpj_cliente);
    }
    
    // Associe o usuário criado ou existente ao pedido
    wp_update_post(array(
        'ID' => $order_id,
        'post_author' => $user->ID,
    ));
}


