/**
 * scripts.js - JavaScript Functions for Cipher Tool
 *
 * This script contains JavaScript functions that enhance the user interface
 * of the Cipher Tool application. It handles dynamic display of cipher-specific
 * options and updates the cipher description displayed to the user.
 *
 * Author: [Your Name]
 * Version: 1.5
 */

/**
 * Cipher Descriptions
 *
 * An object mapping cipher identifiers to their descriptions and histories.
 */
const cipherDescriptions = {
    caesar: `
        <h2>Caesar Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            The Caesar Cipher is a substitution cipher in which each letter is shifted a certain number of places down the alphabet.
        </p>
        <p>
            <strong>History:</strong><br>
            Named after Julius Caesar, who used it to communicate with his generals.
        </p>
    `,
    atbash: `
        <h2>Atbash Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            The Atbash Cipher replaces each letter with its counterpart from the reversed alphabet.
        </p>
        <p>
            <strong>History:</strong><br>
            An ancient cipher used in Hebrew scripts dating back to 500-600 BCE.
        </p>
    `,
    vigenere: `
        <h2>Vigenère Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            A method of encrypting text by applying a series of Caesar ciphers based on the letters of a keyword.
        </p>
        <p>
            <strong>History:</strong><br>
            Developed by Blaise de Vigenère in the 16th century, considered unbreakable for centuries.
        </p>
    `,
    affine: `
        <h2>Affine Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            A cipher using a mathematical function to encrypt letters: E(x) = (a * x + b) mod 26.
        </p>
        <p>
            <strong>History:</strong><br>
            An extension of the Caesar Cipher, adding complexity through multiplication and addition.
        </p>
    `,
    railfence: `
        <h2>Rail Fence Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            A transposition cipher that writes the message diagonally over a number of "rails" and reads it row by row.
        </p>
        <p>
            <strong>History:</strong><br>
            Named for its resemblance to the zigzag pattern of a rail fence.
        </p>
    `,
    playfair: `
        <h2>Playfair Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            Encrypts pairs of letters using a 5x5 key square constructed from a keyword.
        </p>
        <p>
            <strong>History:</strong><br>
            Invented by Charles Wheatstone, promoted by Lord Playfair in the 19th century.
        </p>
    `,
    columnar: `
        <h2>Columnar Transposition Cipher</h2>
        <p>
            <strong>Description:</strong><br>
            Writes the plaintext in rows and reads the columns in a specific order based on a keyword.
        </p>
        <p>
            <strong>History:</strong><br>
            Used historically for its simplicity in manual encryption during wars.
        </p>
    `,
    morse: `
        <h2>Morse Code</h2>
        <p>
            <strong>Description:</strong><br>
            Encodes text characters as standardized sequences of dots and dashes.
        </p>
        <p>
            <strong>History:</strong><br>
            Developed by Samuel Morse in the 1830s, revolutionizing long-distance communication.
        </p>
    `
};

/**
 * showOptions Function
 *
 * Displays cipher-specific options based on the user's selection from the
 * dropdown menu. It also updates the cipher description displayed to the user.
 */
function showOptions() {
    // Get the selected cipher method.
    const cipher = document.getElementById('cipher').value;

    // Hide all cipher options by default.
    document.querySelectorAll('.cipher-options').forEach(function(el) {
        el.style.display = 'none';
    });

    // Show the options for the selected cipher, if any.
    if (cipher) {
        const optionsDiv = document.getElementById(cipher + '-options');
        if (optionsDiv) {
            optionsDiv.style.display = 'block';
        }

        // Update the cipher description.
        const descriptionDiv = document.getElementById('cipher-description');
        descriptionDiv.innerHTML = cipherDescriptions[cipher];
        descriptionDiv.style.display = 'block';
    } else {
        // If no cipher is selected, hide the description.
        const descriptionDiv = document.getElementById('cipher-description');
        descriptionDiv.innerHTML = '';
        descriptionDiv.style.display = 'none';
    }
}

// Add an event listener to call showOptions on page load.
document.addEventListener('DOMContentLoaded', function() {
    showOptions();

    // Add an event listener to the cipher selection dropdown to update options on change.
    const cipherSelect = document.getElementById('cipher');
    cipherSelect.addEventListener('change', showOptions);
});
