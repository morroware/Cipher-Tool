<?php
/**
 * Cipher Tool - Index Page
 *
 * This page provides the user interface for the Cipher Tool application.
 * It includes a form for user input, processes form submissions, displays error messages,
 * and shows the result of the encryption or decryption. Cipher descriptions are displayed
 * above the text area and below the scrolling text when a cipher is selected.
 *
 * Author: [Your Name]
 * Version: 1.6
 */

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the ciphers.php file, which contains all cipher functions and the processForm function.
include 'ciphers.php';

// Initialize variables to store the result and any error messages.
$result = '';
$error = '';

// Check if the form has been submitted.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Call the processForm function and pass the $_POST data.
    list($result, $error) = processForm($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--
    Cipher Tool HTML Document - Dark Mode with 90's Internet Style

    This HTML document provides the user interface for the cipher tool.
    It includes a form for user input, displays error messages, and shows
    the result of the encryption or decryption. It links to external CSS
    and JavaScript files for styling and interactivity.

    Author: [Your Name]
    Version: 1.6
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cipher Tool</title>
    <!-- Link to external CSS stylesheet -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Cipher Tool</h1>

    <!-- Optional Marquee for 90's Style -->
    <marquee behavior="scroll" direction="left" scrollamount="5" style="color: #00FF00; font-family: 'Lucida Console', Monaco, monospace;">
        Cipher Tool! Encrypt and decrypt your messages with style!
    </marquee>

    <!-- Cipher Description Section -->
    <div id="cipher-description" class="cipher-description">
        <!-- The description of the selected cipher will be displayed here -->
    </div>
    <div>
    <?php if ($result): ?>
        <div class="result">
            <strong>Result:</strong><br>
            <?php echo nl2br(htmlspecialchars($result, ENT_QUOTES, 'UTF-8')); ?>
        </div>
    <?php endif; ?></div>
    <!-- Display error messages if any -->
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <!-- Cipher Tool Form -->
    <form method="post" action="">
        <!-- Cipher Selection Dropdown -->
        <div class="form-group">
            <label for="cipher">Select Cipher:</label>
            <!-- Dropdown menu -->
            <select name="cipher" id="cipher" required onchange="showOptions()">
                <option value="">Choose a cipher...</option>
                <option value="caesar" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'caesar' ? 'selected' : ''; ?>>Caesar Cipher</option>
                <option value="atbash" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'atbash' ? 'selected' : ''; ?>>Atbash Cipher</option>
                <option value="vigenere" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'vigenere' ? 'selected' : ''; ?>>Vigenère Cipher</option>
                <option value="affine" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'affine' ? 'selected' : ''; ?>>Affine Cipher</option>
                <option value="railfence" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'railfence' ? 'selected' : ''; ?>>Rail Fence Cipher</option>
                <option value="playfair" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'playfair' ? 'selected' : ''; ?>>Playfair Cipher</option>
                <option value="columnar" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'columnar' ? 'selected' : ''; ?>>Columnar Transposition Cipher</option>
                <option value="morse" <?php echo isset($_POST['cipher']) && $_POST['cipher'] === 'morse' ? 'selected' : ''; ?>>Morse Code</option>
            </select>
        </div>

        <!-- Cipher-Specific Options -->
        <!-- These sections are displayed or hidden based on the selected cipher -->

        <!-- Caesar Cipher Options -->
        <div id="caesar-options" class="cipher-options">
            <div class="form-group">
                <label for="shift">Shift Amount (1-25):</label>
                <input type="number" name="shift" id="shift" min="1" max="25" value="<?php echo isset($_POST['shift']) ? intval($_POST['shift']) : '3'; ?>">
            </div>
        </div>

        <!-- Atbash Cipher has no additional options -->

        <!-- Vigenère Cipher Options -->
        <div id="vigenere-options" class="cipher-options">
            <div class="form-group">
                <label for="key">Keyword (Only letters):</label>
                <input type="text" name="key" id="key" pattern="[A-Za-z]+" title="Only letters allowed" value="<?php echo isset($_POST['key']) ? htmlspecialchars($_POST['key'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
        </div>

        <!-- Affine Cipher Options -->
        <div id="affine-options" class="cipher-options">
            <div class="form-group">
                <label for="a">Multiplicative Key 'a' (must be coprime with 26):</label>
                <input type="number" name="a" id="a" min="1" max="25" value="<?php echo isset($_POST['a']) ? intval($_POST['a']) : '5'; ?>">
            </div>
            <div class="form-group">
                <label for="b">Additive Key 'b' (0-25):</label>
                <input type="number" name="b" id="b" min="0" max="25" value="<?php echo isset($_POST['b']) ? intval($_POST['b']) : '8'; ?>">
            </div>
        </div>

        <!-- Rail Fence Cipher Options -->
        <div id="railfence-options" class="cipher-options">
            <div class="form-group">
                <label for="rails">Number of Rails (2 or more):</label>
                <input type="number" name="rails" id="rails" min="2" value="<?php echo isset($_POST['rails']) ? intval($_POST['rails']) : '3'; ?>">
            </div>
        </div>

        <!-- Playfair Cipher Options -->
        <div id="playfair-options" class="cipher-options">
            <div class="form-group">
                <label for="playfair-key">Keyword (Only letters):</label>
                <input type="text" name="playfair_key" id="playfair-key" pattern="[A-Za-z]+" title="Only letters allowed" value="<?php echo isset($_POST['playfair_key']) ? htmlspecialchars($_POST['playfair_key'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
        </div>

        <!-- Columnar Transposition Cipher Options -->
        <div id="columnar-options" class="cipher-options">
            <div class="form-group">
                <label for="columnar-key">Keyword (Only letters):</label>
                <input type="text" name="columnar_key" id="columnar-key" pattern="[A-Za-z]+" title="Only letters allowed" value="<?php echo isset($_POST['columnar_key']) ? htmlspecialchars($_POST['columnar_key'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
        </div>

        <!-- Morse Code has no additional options -->

        <!-- Text Input Field -->
        <div class="form-group">
            <label for="text">Enter Text:</label>
            <!-- Textarea for user input -->
            <textarea name="text" id="text" rows="4" required><?php echo isset($_POST['text']) ? htmlspecialchars($_POST['text'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
        </div>

        <!-- Action Selection (Encrypt or Decrypt) -->
        <div class="form-group">
            <label>Action:</label>
            <input type="radio" name="action" value="encrypt" id="encrypt" <?php echo !isset($_POST['action']) || $_POST['action'] === 'encrypt' ? 'checked' : ''; ?>>
            <label for="encrypt">Encrypt</label>
            <input type="radio" name="action" value="decrypt" id="decrypt" <?php echo isset($_POST['action']) && $_POST['action'] === 'decrypt' ? 'checked' : ''; ?>>
            <label for="decrypt">Decrypt</label>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit">Process</button>
        </div>
    </form>

    <!-- Display the result if available -->
    <?php if ($result): ?>
        <div class="result">
            <strong>Result:</strong><br>
            <?php echo nl2br(htmlspecialchars($result, ENT_QUOTES, 'UTF-8')); ?>
        </div>
    <?php endif; ?>

    <!-- Include external JavaScript file -->
    <script src="scripts.js"></script>
</body>
</html>
