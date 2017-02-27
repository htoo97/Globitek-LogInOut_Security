<?php
  require_once('../private/initialize.php');
?>
<?php $page_title = 'Get Secret Cookie'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<?php include(SHARED_PATH . '/public_menu.php'); ?>

    <?php
        const CIPHER_METHOD = 'AES-256-CBC';

        $name = 'scrt';
        $message = $_COOKIE[$name];
        $key = 'a1b2c3d4e5';

        // check if signed correctly
        if (signed_string_is_valid($message)) {
            $message = unsign_string($message);
        }
        else {
            exit("Cookie is not signed correctly.");
        }

        // Needs a key of length 32 (256-bit)
        $key = str_pad($key, 32, '*');

        // Base64 decode before decrypting
        $iv_with_ciphertext = base64_decode($message);

        // Separate initialization vector and encrypted string
        $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
        $iv = substr($iv_with_ciphertext, 0, $iv_length);
        $ciphertext = substr($iv_with_ciphertext, $iv_length);

        // Decrypt
        $plaintext = openssl_decrypt($ciphertext, CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);

        echo 'Get cookie: "' . $name . '"="' . $plaintext . '"';
?>

<?php include(SHARED_PATH . '/footer.php'); ?>