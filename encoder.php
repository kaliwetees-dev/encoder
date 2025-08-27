<?php
function encode_sentence($text) {
    // Step 1: Split the text into words
    $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    if (empty($words)) {
        return "Please enter some text to encode.";
    }

    // Step 2: Get unique words in the order they appear
    $unique_words = [];
    $seen = [];
    foreach ($words as $word) {
        if (!in_array($word, $seen)) {
            $unique_words[] = $word;
            $seen[] = $word;
        }
    }

    // Step 3: Number of unique words
    $n = count($unique_words);

    // Step 4: Assign numbers starting from the last number backward
    $assigned_numbers = [];
    for ($i = 0; $i < $n; $i++) {
        $assigned_numbers[] = $n - $i;
    }

    // Step 5: Convert assigned numbers to ASCII char then to hex ASCII codes
    $word_hex_ascii = [];
    foreach ($unique_words as $index => $word) {
        $num = $assigned_numbers[$index];
        $char = chr($num);
        $word_hex_ascii[$word] = sprintf("%02X", ord($char));
    }

    // Step 6: Swap the two hex digits and put second digit at word start, first at word end
    $modified_words = [];
    foreach ($word_hex_ascii as $word => $hex_val) {
        $modified_words[] = $hex_val[1] . $word . $hex_val[0];
    }

    // Step 7: Simple deterministic shuffle
    $shuffled_words = simple_shuffle($modified_words);
    $shuffled_text = implode(" ", $shuffled_words);

    // Step 8: Shift chars +15 only if ASCII is between 32 and 126 inclusive; else unchanged
    $final_text = shift_chars($shuffled_text);

    return $final_text;
}

// Simple deterministic shuffle function
function simple_shuffle($list) {
    $n_list = count($list);
    for ($i = $n_list - 1; $i > 0; $i--) {
        $j = ($i * 7 + 3) % $n_list;
        list($list[$i], $list[$j]) = array($list[$j], $list[$i]);
    }
    return $list;
}

// Shift chars function
function shift_chars($text) {
    $shifted = '';
    for ($i = 0; $i < strlen($text); $i++) {
        $ch = $text[$i];
        $ascii_val = ord($ch);
        if ($ascii_val >= 32 && $ascii_val <= 126) {
            $shifted_val = $ascii_val + 15;
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
