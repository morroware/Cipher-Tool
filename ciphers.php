<?php
/**
 * Cipher Functions and Form Processing
 *
 * This file contains all the cipher functions used by the Cipher Tool application.
 * It also includes the processForm function, which handles form data processing,
 * input validation, and calls the appropriate cipher functions based on user input.
 *
 * Ciphers Included:
 * - Caesar Cipher
 * - Atbash Cipher
 * - Vigenère Cipher
 * - Affine Cipher
 * - Rail Fence Cipher
 * - Playfair Cipher
 * - Columnar Transposition Cipher
 * - Morse Code Encoder/Decoder
 *
 * Author: [Your Name]
 * Version: 1.3
 */

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ------------------------- Cipher Functions ------------------------- */

/**
 * Encrypts or decrypts text using the Caesar cipher.
 *
 * @param string $text    The input text to encrypt or decrypt.
 * @param int    $shift   The number of positions to shift (1-25).
 * @param bool   $decrypt True to decrypt, false to encrypt.
 * @return string The encrypted or decrypted text.
 */
function caesarCipher($text, $shift, $decrypt = false) {
    $result = '';
    $shift = $decrypt ? 26 - $shift : $shift;
    $text = strtoupper($text);
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $code = ord($char);
            $code = (($code - 65 + $shift) % 26) + 65;
            $result .= chr($code);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

/**
 * Encrypts or decrypts text using the Atbash cipher.
 *
 * @param string $text The input text to process.
 * @return string The processed text.
 */
function atbashCipher($text) {
    $result = '';
    $text = strtoupper($text);
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $code = ord('Z') - (ord($char) - ord('A'));
            $result .= chr($code);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

/**
 * Encrypts text using the Vigenère cipher.
 *
 * @param string $text The input text to encrypt.
 * @param string $key  The keyword used for encryption.
 * @return string The encrypted text.
 */
function vigenereEncode($text, $key) {
    $result = '';
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);
    $keyIndex = 0;
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $shift = ord($key[$keyIndex % $keyLength]) - ord('A');
            $code = ((ord($char) - ord('A') + $shift) % 26) + ord('A');
            $result .= chr($code);
            $keyIndex++;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

/**
 * Decrypts text using the Vigenère cipher.
 *
 * @param string $text The input text to decrypt.
 * @param string $key  The keyword used for decryption.
 * @return string The decrypted text.
 */
function vigenereDecode($text, $key) {
    $result = '';
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);
    $keyIndex = 0;
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $shift = ord($key[$keyIndex % $keyLength]) - ord('A');
            $code = ((ord($char) - ord('A') - $shift + 26) % 26) + ord('A');
            $result .= chr($code);
            $keyIndex++;
        } else {
            $result .= $char;
        }
    }
    return $result;
}

/**
 * Encrypts or decrypts text using the Affine cipher.
 *
 * @param string $text    The input text to encrypt or decrypt.
 * @param int    $a       The multiplicative key (must be coprime with 26).
 * @param int    $b       The additive key.
 * @param bool   $decrypt True to decrypt, false to encrypt.
 * @return string The encrypted or decrypted text, or an error message.
 */
function affineCipher($text, $a, $b, $decrypt = false) {
    $result = '';
    $text = strtoupper($text);
    if (gcd($a, 26) != 1) {
        return "Error: 'a' must be coprime with 26.";
    }
    $a_inv = 0;
    if ($decrypt) {
        $a_inv = modInverse($a, 26);
        if ($a_inv == null) {
            return "Error: Multiplicative inverse of 'a' does not exist.";
        }
    }
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if (ctype_alpha($char)) {
            $x = ord($char) - ord('A');
            if ($decrypt) {
                $code = ($a_inv * ($x - $b + 26)) % 26;
            } else {
                $code = ($a * $x + $b) % 26;
            }
            $result .= chr($code + ord('A'));
        } else {
            $result .= $char;
        }
    }
    return $result;
}

/**
 * Calculates the Greatest Common Divisor (GCD) of two numbers using the Euclidean algorithm.
 *
 * @param int $a First number.
 * @param int $b Second number.
 * @return int The GCD of $a and $b.
 */
function gcd($a, $b) {
    while ($b != 0) {
        $temp = $a % $b;
        $a = $b;
        $b = $temp;
    }
    return $a;
}

/**
 * Calculates the Modular Multiplicative Inverse of 'a' modulo 'm'.
 *
 * @param int $a The number to find the inverse of.
 * @param int $m The modulus.
 * @return int|null The inverse of $a modulo $m, or null if none exists.
 */
function modInverse($a, $m) {
    $a = $a % $m;
    for ($x = 1; $x < $m; $x++) {
        if (($a * $x) % $m == 1) {
            return $x;
        }
    }
    return null;
}

/**
 * Encrypts or decrypts text using the Rail Fence cipher.
 *
 * @param string $text    The input text to encrypt or decrypt.
 * @param int    $rails   The number of rails to use.
 * @param bool   $decrypt True to decrypt, false to encrypt.
 * @return string The encrypted or decrypted text.
 */
function railFenceCipher($text, $rails, $decrypt = false) {
    if ($rails <= 1) {
        return "Error: Number of rails must be greater than 1.";
    }
    $text = preg_replace('/\s+/', '', $text); // Remove spaces
    $text = strtoupper($text);

    if ($decrypt) {
        // Decryption
        $rail = array_fill(0, $rails, []);
        $dir_down = false;
        $row = 0;
        $index = 0;

        // Mark the positions in the rail matrix
        for ($i = 0; $i < strlen($text); $i++) {
            if ($row == 0 || $row == $rails - 1) {
                $dir_down = !$dir_down;
            }
            $rail[$row][$i] = '*';
            $row += $dir_down ? 1 : -1;
        }

        // Fill the rail matrix with the ciphertext
        for ($r = 0; $r < $rails; $r++) {
            for ($c = 0; $c < strlen($text); $c++) {
                if (isset($rail[$r][$c]) && $rail[$r][$c] == '*') {
                    $rail[$r][$c] = $text[$index++];
                }
            }
        }

        // Read the plaintext from the rail matrix
        $result = '';
        $row = 0;
        $dir_down = false;
        for ($i = 0; $i < strlen($text); $i++) {
            if ($row == 0 || $row == $rails - 1) {
                $dir_down = !$dir_down;
            }
            $result .= $rail[$row][$i];
            $row += $dir_down ? 1 : -1;
        }
    } else {
        // Encryption
        $rail = array_fill(0, $rails, '');
        $dir_down = false;
        $row = 0;
        for ($i = 0; $i < strlen($text); $i++) {
            if ($row == 0 || $row == $rails - 1) {
                $dir_down = !$dir_down;
            }
            $rail[$row] .= $text[$i];
            $row += $dir_down ? 1 : -1;
        }
        $result = implode('', $rail);
    }

    return $result;
}

/* ---------------------- New Cipher Functions ---------------------- */

/**
 * Encrypts text using the Playfair cipher.
 *
 * The Playfair cipher encrypts pairs of letters (digraphs) using a 5x5 key square.
 * It replaces each pair of letters with another pair based on their positions in the key square.
 *
 * Limitations:
 * - The letter 'J' is replaced with 'I' to fit into the 5x5 grid.
 *
 * @param string $text The input text to encrypt.
 * @param string $key  The keyword used to generate the key square.
 * @return string The encrypted text.
 */
function playfairEncrypt($text, $key) {
    // Validate the key
    if (empty($key) || !ctype_alpha($key)) {
        return "Error: The Playfair key must contain only alphabetic characters.";
    }

    // Prepare the key square
    $keySquare = generatePlayfairKeySquare($key);

    // Prepare the plaintext
    $text = strtoupper($text);
    $text = preg_replace('/[^A-Z]/', '', $text);
    $text = str_replace('J', 'I', $text); // Replace 'J' with 'I'

    // Prepare digraphs
    $digraphs = [];
    $i = 0;
    while ($i < strlen($text)) {
        $a = $text[$i];
        $b = ($i + 1) < strlen($text) ? $text[$i + 1] : 'X';
        if ($a == $b) {
            $b = 'X';
            $i++;
        } else {
            $i += 2;
        }
        $digraphs[] = [$a, $b];
    }

    // Encrypt digraphs
    $result = '';
    foreach ($digraphs as $pair) {
        list($row1, $col1) = findPosition($keySquare, $pair[0]);
        list($row2, $col2) = findPosition($keySquare, $pair[1]);

        if ($row1 === null || $row2 === null) {
            return "Error: Invalid character in text.";
        }

        if ($row1 == $row2) {
            // Same row, shift columns right
            $col1 = ($col1 + 1) % 5;
            $col2 = ($col2 + 1) % 5;
        } elseif ($col1 == $col2) {
            // Same column, shift rows down
            $row1 = ($row1 + 1) % 5;
            $row2 = ($row2 + 1) % 5;
        } else {
            // Rectangle swap columns
            $temp = $col1;
            $col1 = $col2;
            $col2 = $temp;
        }

        $result .= $keySquare[$row1][$col1] . $keySquare[$row2][$col2];
    }

    return $result;
}

/**
 * Decrypts text using the Playfair cipher.
 *
 * @param string $text The input text to decrypt.
 * @param string $key  The keyword used to generate the key square.
 * @return string The decrypted text.
 */
function playfairDecrypt($text, $key) {
    // Validate the key
    if (empty($key) || !ctype_alpha($key)) {
        return "Error: The Playfair key must contain only alphabetic characters.";
    }

    // Prepare the key square
    $keySquare = generatePlayfairKeySquare($key);

    // Prepare the ciphertext
    $text = strtoupper($text);
    $text = preg_replace('/[^A-Z]/', '', $text);
    $text = str_replace('J', 'I', $text); // Replace 'J' with 'I'

    // Prepare digraphs
    $digraphs = [];
    $i = 0;
    while ($i < strlen($text)) {
        $a = $text[$i];
        $b = ($i + 1) < strlen($text) ? $text[$i + 1] : 'X';
        $i += 2;
        $digraphs[] = [$a, $b];
    }

    // Decrypt digraphs
    $result = '';
    foreach ($digraphs as $pair) {
        list($row1, $col1) = findPosition($keySquare, $pair[0]);
        list($row2, $col2) = findPosition($keySquare, $pair[1]);

        if ($row1 === null || $row2 === null) {
            return "Error: Invalid character in text.";
        }

        if ($row1 == $row2) {
            // Same row, shift columns left
            $col1 = ($col1 + 4) % 5;
            $col2 = ($col2 + 4) % 5;
        } elseif ($col1 == $col2) {
            // Same column, shift rows up
            $row1 = ($row1 + 4) % 5;
            $row2 = ($row2 + 4) % 5;
        } else {
            // Rectangle swap columns
            $temp = $col1;
            $col1 = $col2;
            $col2 = $temp;
        }

        $result .= $keySquare[$row1][$col1] . $keySquare[$row2][$col2];
    }

    return $result;
}

/**
 * Generates the key square for the Playfair cipher.
 *
 * The key square is a 5x5 grid of letters constructed from the keyword.
 * It contains each letter of the alphabet (excluding 'J') exactly once.
 *
 * @param string $key The keyword used to generate the key square.
 * @return array The 5x5 key square.
 */
function generatePlayfairKeySquare($key) {
    $key = strtoupper($key);
    $key = preg_replace('/[^A-Z]/', '', $key);
    $key = str_replace('J', 'I', $key);
    $letters = [];
    foreach (str_split($key) as $char) {
        if (!in_array($char, $letters) && $char != 'J') {
            $letters[] = $char;
        }
    }
    foreach (range('A', 'Z') as $char) {
        if ($char == 'J') continue;
        if (!in_array($char, $letters)) {
            $letters[] = $char;
        }
    }
    // Create 5x5 key square
    $keySquare = array_chunk($letters, 5);
    return $keySquare;
}

/**
 * Finds the position of a character in the key square.
 *
 * @param array  $keySquare The 5x5 key square.
 * @param string $char      The character to find.
 * @return array An array containing the row and column indices.
 */
function findPosition($keySquare, $char) {
    for ($row = 0; $row < 5; $row++) {
        for ($col = 0; $col < 5; $col++) {
            if ($keySquare[$row][$col] == $char) {
                return [$row, $col];
            }
        }
    }
    return [null, null];
}

/**
 * Encrypts text using the Columnar Transposition cipher.
 *
 * The Columnar Transposition cipher writes the plaintext into rows of a grid
 * based on the length of the keyword, then reads the columns in the order
 * determined by the alphabetical order of the keyword's letters.
 *
 * Limitations:
 * - Only alphabetic characters are considered; non-alphabetic characters are ignored.
 *
 * @param string $text The input text to encrypt.
 * @param string $key  The keyword used for the transposition.
 * @return string The encrypted text.
 */
function columnarTranspositionEncrypt($text, $key) {
    // Validate the key
    if (empty($key) || !ctype_alpha($key)) {
        return "Error: The Columnar Transposition key must contain only alphabetic characters.";
    }

    $text = preg_replace('/[^A-Za-z]/', '', strtoupper($text));
    $key = strtoupper($key);
    $keyLength = strlen($key);
    $textLength = strlen($text);

    // Create an array of columns
    $columns = array_fill(0, $keyLength, '');

    // Fill the columns with characters from the text
    for ($i = 0; $i < $textLength; $i++) {
        $columns[$i % $keyLength] .= $text[$i];
    }

    // Order the columns based on the keyword
    $order = getColumnarOrder($key);

    // Read the columns in the specified order
    $result = '';
    foreach ($order as $index) {
        $result .= $columns[$index];
    }

    return $result;
}

/**
 * Decrypts text using the Columnar Transposition cipher.
 *
 * @param string $text The input text to decrypt.
 * @param string $key  The keyword used for the transposition.
 * @return string The decrypted text.
 */
function columnarTranspositionDecrypt($text, $key) {
    // Validate the key
    if (empty($key) || !ctype_alpha($key)) {
        return "Error: The Columnar Transposition key must contain only alphabetic characters.";
    }

    $text = preg_replace('/[^A-Za-z]/', '', strtoupper($text));
    $key = strtoupper($key);
    $keyLength = strlen($key);
    $textLength = strlen($text);
    $numRows = ceil($textLength / $keyLength);

    // Calculate the number of full columns
    $fullColumns = $textLength % $keyLength;
    if ($fullColumns == 0) {
        $fullColumns = $keyLength;
    }

    // Determine the length of each column
    $columnLengths = array_fill(0, $keyLength, $numRows);
    for ($i = $fullColumns; $i < $keyLength; $i++) {
        $columnLengths[$i]--;
    }

    // Order the columns based on the keyword
    $order = getColumnarOrder($key);

    // Create an array to hold the columns
    $columns = [];
    $position = 0;
    foreach ($order as $index) {
        $length = $columnLengths[$index];
        $columns[$index] = substr($text, $position, $length);
        $position += $length;
    }

    // Read the plaintext row-wise
    $result = '';
    for ($i = 0; $i < $numRows; $i++) {
        for ($j = 0; $j < $keyLength; $j++) {
            if (isset($columns[$j][$i])) {
                $result .= $columns[$j][$i];
            }
        }
    }

    return $result;
}

/**
 * Generates the order of columns based on the keyword.
 *
 * @param string $key The keyword used for the transposition.
 * @return array An array of column indices in the order to read.
 */
function getColumnarOrder($key) {
    $keyArray = str_split($key);
    $keySorted = $keyArray;
    sort($keySorted);

    $order = [];
    foreach ($keySorted as $char) {
        $indices = array_keys($keyArray, $char);
        foreach ($indices as $index) {
            if (!in_array($index, $order)) {
                $order[] = $index;
                break;
            }
        }
    }
    return $order;
}

/**
 * Encodes text into Morse code.
 *
 * Limitations:
 * - Only letters, numbers, and certain punctuation marks are supported.
 * - Users should use '/' to separate words when decoding Morse code.
 *
 * @param string $text The input text to encode.
 * @return string The encoded Morse code.
 */
function morseEncode($text) {
    $morseCode = getMorseCodeMap();
    $text = strtoupper($text);
    $result = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        if ($char == ' ') {
            $result .= ' / '; // Use '/' to separate words
        } elseif (isset($morseCode[$char])) {
            $result .= $morseCode[$char] . ' ';
        } else {
            // Ignore unsupported characters
        }
    }
    return trim($result);
}

/**
 * Decodes Morse code into text.
 *
 * Users should use '/' to separate words in the Morse code input.
 *
 * @param string $text The Morse code to decode.
 * @return string The decoded text.
 */
function morseDecode($text) {
    $morseCode = getMorseCodeMap();
    $inverseMorseCode = array_flip($morseCode);
    $words = explode(' / ', $text);
    $result = '';
    foreach ($words as $word) {
        $symbols = explode(' ', trim($word));
        foreach ($symbols as $symbol) {
            if (isset($inverseMorseCode[$symbol])) {
                $result .= $inverseMorseCode[$symbol];
            } else {
                // Ignore invalid Morse code sequences
            }
        }
        $result .= ' ';
    }
    return trim($result);
}

/**
 * Returns the Morse code mapping.
 *
 * @return array The Morse code mapping.
 */
function getMorseCodeMap() {
    return [
        'A' => '.-',
        'B' => '-...',
        'C' => '-.-.',
        'D' => '-..',
        'E' => '.',
        'F' => '..-.',
        'G' => '--.',
        'H' => '....',
        'I' => '..',
        'J' => '.---',
        'K' => '-.-',
        'L' => '.-..',
        'M' => '--',
        'N' => '-.',
        'O' => '---',
        'P' => '.--.',
        'Q' => '--.-',
        'R' => '.-.',
        'S' => '...',
        'T' => '-',
        'U' => '..-',
        'V' => '...-',
        'W' => '.--',
        'X' => '-..-',
        'Y' => '-.--',
        'Z' => '--..',
        '0' => '-----',
        '1' => '.----',
        '2' => '..---',
        '3' => '...--',
        '4' => '....-',
        '5' => '.....',
        '6' => '-....',
        '7' => '--...',
        '8' => '---..',
        '9' => '----.',
        '.' => '.-.-.-',
        ',' => '--..--',
        '?' => '..--..',
        '\'' => '.----.',
        '!' => '-.-.--',
        '/' => '-..-.',
        '(' => '-.--.',
        ')' => '-.--.-',
        '&' => '.-...',
        ':' => '---...',
        ';' => '-.-.-.',
        '=' => '-...-',
        '+' => '.-.-.',
        '-' => '-....-',
        '_' => '..--.-',
        '"' => '.-..-.',
        '$' => '...-..-',
        '@' => '.--.-.',
    ];
}

/* --------------------- Form Processing Function --------------------- */

/**
 * Processes the form data and returns the result and any error messages.
 *
 * @param array $postData The $_POST data from the form submission.
 * @return array An array containing the result and error message.
 */
function processForm($postData) {
    $result = '';
    $error = '';

    // Sanitize user inputs to prevent code injection and XSS attacks.
    $text = isset($postData['text']) ? htmlspecialchars($postData['text'], ENT_QUOTES, 'UTF-8') : '';
    $cipher = isset($postData['cipher']) ? $postData['cipher'] : '';
    $action = isset($postData['action']) ? $postData['action'] : '';
    $shift = isset($postData['shift']) ? intval($postData['shift']) : 0;
    $key = isset($postData['key']) ? htmlspecialchars($postData['key'], ENT_QUOTES, 'UTF-8') : '';
    $a = isset($postData['a']) ? intval($postData['a']) : 0;
    $b = isset($postData['b']) ? intval($postData['b']) : 0;
    $rails = isset($postData['rails']) ? intval($postData['rails']) : 0;
    $playfairKey = isset($postData['playfair_key']) ? htmlspecialchars($postData['playfair_key'], ENT_QUOTES, 'UTF-8') : '';
    $columnarKey = isset($postData['columnar_key']) ? htmlspecialchars($postData['columnar_key'], ENT_QUOTES, 'UTF-8') : '';

    if (empty($text)) {
        $error = 'Please enter text to encrypt/decrypt.';
    } else {
        switch ($cipher) {
            case 'caesar':
                if ($shift < 1 || $shift > 25) {
                    $error = 'Shift amount must be between 1 and 25.';
                } else {
                    $result = caesarCipher($text, $shift, $action === 'decrypt');
                }
                break;

            case 'atbash':
                $result = atbashCipher($text);
                break;

            case 'vigenere':
                if (empty($key)) {
                    $error = 'Please enter a key for the Vigenère cipher.';
                } elseif (!ctype_alpha($key)) {
                    $error = 'The Vigenère key must contain only alphabetic characters.';
                } else {
                    $result = $action === 'encrypt' ?
                        vigenereEncode($text, $key) :
                        vigenereDecode($text, $key);
                }
                break;

            case 'affine':
                if ($a <= 0 || $b < 0 || $b >= 26) {
                    $error = 'Please enter valid keys for the Affine cipher.';
                } else {
                    $affineResult = affineCipher($text, $a, $b, $action === 'decrypt');
                    if (strpos($affineResult, 'Error') === 0) {
                        $error = $affineResult;
                    } else {
                        $result = $affineResult;
                    }
                }
                break;

            case 'railfence':
                if ($rails < 2) {
                    $error = 'Number of rails must be at least 2.';
                } else {
                    $result = railFenceCipher($text, $rails, $action === 'decrypt');
                }
                break;

            case 'playfair':
                if (empty($playfairKey)) {
                    $error = 'Please enter a keyword for the Playfair cipher.';
                } elseif (!ctype_alpha($playfairKey)) {
                    $error = 'The Playfair key must contain only alphabetic characters.';
                } else {
                    $playfairResult = $action === 'encrypt' ?
                        playfairEncrypt($text, $playfairKey) :
                        playfairDecrypt($text, $playfairKey);
                    if (strpos($playfairResult, 'Error') === 0) {
                        $error = $playfairResult;
                    } else {
                        $result = $playfairResult;
                    }
                }
                break;

            case 'columnar':
                if (empty($columnarKey)) {
                    $error = 'Please enter a keyword for the Columnar Transposition cipher.';
                } elseif (!ctype_alpha($columnarKey)) {
                    $error = 'The Columnar Transposition key must contain only alphabetic characters.';
                } else {
                    $columnarResult = $action === 'encrypt' ?
                        columnarTranspositionEncrypt($text, $columnarKey) :
                        columnarTranspositionDecrypt($text, $columnarKey);
                    if (strpos($columnarResult, 'Error') === 0) {
                        $error = $columnarResult;
                    } else {
                        $result = $columnarResult;
                    }
                }
                break;

            case 'morse':
                if ($action === 'encrypt') {
                    $result = morseEncode($text);
                } else {
                    // For Morse code decoding, instruct the user to use '/' to separate words
                    if (preg_match('/[^.\- \/]/', $text)) {
                        $error = 'Invalid characters in Morse code. Use dots (.), dashes (-), and spaces.';
                    } else {
                        $result = morseDecode($text);
                    }
                }
                break;

            default:
                $error = 'Please select a cipher method.';
        }
    }

    return [$result, $error];
}
?>
