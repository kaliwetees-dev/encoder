<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentence Encoder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #555;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .output {
            margin-top: 20px;
            padding: 15px;
            background-color: #e2eafc;
            border: 1px solid #c8d9ef;
            border-radius: 4px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sentence Encoder</h1>
        <p>Enter a sentence or phrase below to encode it using a custom algorithm.</p>
        <form method="post" action="">
            <textarea name="text_input" placeholder="Enter your text here..."><?php echo htmlspecialchars($_POST['text_input'] ?? ''); ?></textarea>
            <input type="submit" value="Encode Sentence">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['text_input'])) {
            $text = $_POST['text_input'];

            // Step 1: Split the text into words
            $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
            if (empty($words)) {
                echo '<div class="output">Please enter some text to encode.</div>';
            } else {
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
                function simple_shuffle($list) {
                    $n_list = count($list);
                    for ($i = $n_list - 1; $i > 0; $i--) {
                        $j = ($i * 7 + 3) % $n_list;
                        list($list[$i], $list[$j]) = array($list[$j], $list[$i]);
                    }
                    return $list;
                }

                $shuffled_words = simple_shuffle($modified_words);
                $shuffled_text = implode(" ", $shuffled_words);

                // Step 8: Shift chars +15 only if ASCII is between 32 and 126 inclusive; else unchanged
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

                $final_text = shift_chars($shuffled_text);

                echo '<h2>Encoded Sentence</h2>';
                echo '<div class="output">' . htmlspecialchars($final_text) . '</div>';
            }
        }
        ?>
    </div>
</body>
</html>
