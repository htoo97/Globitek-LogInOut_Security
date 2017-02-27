<?php
  require_once('../private/initialize.php');
?>
<?php $page_title = 'Set Secret Cookie'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<?php include(SHARED_PATH . '/public_menu.php'); ?>

    <?php
        const CIPHER_METHOD = 'AES-256-CBC';

        $name = 'scrt';
        $value = 'I have a secret to tell.';
        $key = 'a1b2c3d4e5';

        // Needs a key of length 32 (256-bit)
        $key = str_pad($key, 32, '*');

        // Create an initialization vector which randomizes the
        // initial settings of the algorithm, making it harder to decrypt.
        // Start by finding the correct size of an initialization vector
        // for this cipher method.
        $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
        $iv = openssl_random_pseudo_bytes($iv_length);

        // Encrypt
        $encrypted = openssl_encrypt($value, CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);

        // Return $iv at front of string, need it for decoding
        $message = $iv . $encrypted;

        // sign, encrypt and set cookie with 'scrt' key
        setcookie($name, sign_string(base64_encode($message)));
        echo 'Set-Cookie: "' . $name . '"="' . $value . '"';
    ?>

<?php include(SHARED_PATH . '/footer.php'); ?>
