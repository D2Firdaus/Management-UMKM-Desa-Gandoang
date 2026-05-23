<?php
declare(strict_types=1);

/**
 * Encryption
 * 
 * Helper terpusat untuk enkripsi dan dekripsi data sensitif menggunakan
 * AES-256-CBC via PHP OpenSSL extension.
 *
 * Kunci enkripsi diambil dari SECRET_KEY di env.php.
 * Format data terenkripsi: base64(iv + ciphertext) — disimpan sebagai teks biasa di kolom database.
 *
 * Kolom yang dienkripsi (data PII/sensitif):
 *   - profile.nik
 *   - profile.no_hp
 *   - profile.no_kk
 */
class Encryption
{
    private const CIPHER    = 'AES-256-CBC';
    private const SEPARATOR = '::';

    /**
     * Kunci enkripsi 32-byte yang diturunkan dari SECRET_KEY di env.php.
     * Menggunakan SHA-256 untuk memastikan panjang kunci selalu tepat 256-bit.
     */
    private static function getKey(): string
    {
        $env = require __DIR__ . '/../env.php';
        $secret = $env['SECRET_KEY'] ?? 'fallback_key_change_me';
        return hash('sha256', $secret, true); // binary string 32 bytes
    }

    /**
     * Enkripsi teks biasa menjadi ciphertext (base64).
     * 
     * @param string $plaintext Teks asli yang akan dienkripsi
     * @return string           Ciphertext dalam format base64
     */
    public static function encrypt(string $plaintext): string
    {
        if (empty($plaintext)) {
            return '';
        }

        $key    = self::getKey();
        $ivLen  = openssl_cipher_iv_length(self::CIPHER);
        $iv     = openssl_random_pseudo_bytes($ivLen);

        $encrypted = openssl_encrypt($plaintext, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new RuntimeException('Enkripsi gagal: ' . openssl_error_string());
        }

        // Gabungkan IV + ciphertext, lalu encode ke base64
        return base64_encode($iv . $encrypted);
    }

    /**
     * Dekripsi ciphertext (base64) kembali menjadi teks asli.
     * 
     * @param string $ciphertext Ciphertext dalam format base64
     * @return string            Teks asli
     */
    public static function decrypt(string $ciphertext): string
    {
        if (empty($ciphertext)) {
            return '';
        }

        // Jika data bukan base64 valid atau belum dienkripsi (data lama), kembalikan apa adanya
        $decoded = base64_decode($ciphertext, strict: true);
        if ($decoded === false) {
            return $ciphertext; // data lama yang belum dienkripsi
        }

        $key    = self::getKey();
        $ivLen  = openssl_cipher_iv_length(self::CIPHER);

        // Pisahkan IV dari ciphertext
        if (strlen($decoded) <= $ivLen) {
            return $ciphertext; // data tidak valid, kembalikan apa adanya
        }

        $iv         = substr($decoded, 0, $ivLen);
        $encrypted  = substr($decoded, $ivLen);

        $decrypted = openssl_decrypt($encrypted, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            // Mungkin data lama yang belum dienkripsi — kembalikan apa adanya
            return $ciphertext;
        }

        return $decrypted;
    }

    /**
     * Periksa apakah sebuah string tampak sudah dienkripsi
     * (berupa string base64 yang valid dan panjangnya lebih dari IV).
     * 
     * @param string $value
     * @return bool
     */
    public static function isEncrypted(string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        $decoded = base64_decode($value, strict: true);
        if ($decoded === false) {
            return false;
        }

        $ivLen = openssl_cipher_iv_length(self::CIPHER);
        return strlen($decoded) > $ivLen;
    }
}
