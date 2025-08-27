<?php
/**
 * Main function to encode a sentence based on the specified algorithm.
 * @param string $text The input text to be encoded.
 * @return string The final encoded text or an error message.
 */
function encode_sentence($text) {
    // Step 1: Split the text into words.
    $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    if (empty($words)) {
        return "Please enter some text to encode.";
    }

    // Step 2: Get unique words in the order they appear.
    $unique_words = [];
    $seen = [];
    foreach ($words as $word) {
        if (!in_array($word, $seen)) {
            $unique_words[] = $word;
            $seen[] = $word;
        }
    }

    // Step 3: Count the number of unique words.
    $n = count($unique_words);

    // Step 4: Assign numbers starting from the last number backward.
    $assigned_numbers = [];
    for ($i = 0; $i < $n; $i++) {
        $assigned_numbers[] = $n - $i;
    }

    // Step 5: Convert assigned numbers to hex ASCII codes.
    $word_hex_ascii = [];
    foreach ($unique_words as $index => $word) {
        $num = $assigned_numbers[$index];
        $char = chr($num);
        $word_hex_ascii[$word] = sprintf("%02X", ord($char));
    }

    // Step 6: Swap hex digits and modify words.
    $modified_words = [];
    foreach ($word_hex_ascii as $word => $hex_val) {
        $modified_words[] = $hex_val[1] . $word . $hex_val[0];
    }

    // Step 7: Apply a simple deterministic shuffle.
    $shuffled_words = simple_shuffle($modified_words);
    $shuffled_text = implode(" ", $shuffled_words);

    // Step 8: Apply the final character shift.
    $final_text = shift_chars($shuffled_text);

    return $final_text;
}

/**
 * Performs a simple deterministic shuffle on an array.
 * @param array $list The array to be shuffled.
 * @return array The shuffled array.
 */
function simple_shuffle($list) {
    $n_list = count($list);
    for ($i = $n_list - 1; $i > 0; $i--) {
        $j = ($i * 7 + 3) % $n_list;
        list($list[$i], $list[$j]) = array($list[$j], $list[$i]);
    }
    return $list;
}

/**
 * Shifts characters by a fixed value.
 * @param string $text The text to be shifted.
 * @return string The shifted text.
 */
function shift_chars($text) {
    $shifted = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $ch = $text[$i];
        $ascii_val = ord($ch);
        // Only shift printable ASCII characters.
        if ($ascii_val >= 32 && $ascii_val <= 126) {
            $shifted_val = $ascii_val + 15;
            // Wrap around if the value exceeds the printable range.
            if ($shifted_val > 126) {
                $shifted_val = 32 + ($shifted_val - 127);
            }
            $shifted .= chr($shifted_val);
        } else {
            $shifted .= $ch;
        }
    }
    return $shifted;
}
?>
