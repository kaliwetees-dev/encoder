<?php
function decode_message($text) {
    // Step 1: Shift ASCII characters by -15 (within 32–126)
    $shifted = "";
    $chars = str_split($text);

    foreach ($chars as $ch) {
        $code = ord($ch);
        if ($code >= 32 && $code <= 126) {
            $new_code = (($code - 32 - 15) % 95 + 95) % 95 + 32; 
            $shifted .= chr($new_code);
        } else {
            $shifted .= $ch;
        }
    }

    $words = explode(" ", $shifted);
    $numbered_words = [];

    // Step 2 & 3: Rearrange + interpret hex
    foreach ($words as $w) {
        if (strlen($w) >= 2) {
            $rearranged = substr($w, -1) . substr($w, 0, 1) . substr($w, 1, -1);
            $prefix = substr($rearranged, 0, 2);

            if (ctype_xdigit($prefix)) {
                $num = hexdec($prefix);
            } else {
                $num = 0;
            }

            $rest = substr($rearranged, 2);
            $numbered_words[] = [$num, $rest];
        } else {
            $numbered_words[] = [0, $w];
        }
    }

    // Step 4: Sort by hex number descending
    usort($numbered_words, function($a, $b) {
        return $b[0] <=> $a[0];
    });

    // Rebuild message
    $final_words = array_map(fn($x) => $x[1], $numbered_words);
    return implode(" ", $final_words);
}

// Handle form submission
$result = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = $_POST["encoded"] ?? "";
    $result = decode_message($input);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message Decoder</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Message Decoder</h2>
        <form method="POST" id="decodeForm">
            <textarea name="encoded" id="encoded" placeholder="Enter encoded text here..."><?php echo htmlspecialchars($_POST["encoded"] ?? ""); ?></textarea>
            <button type="submit">Decode</button>
        </form>

        <?php if ($result !== ""): ?>
            <div class="result">
                <h3>Decoded Message:</h3>
                <p><?php echo htmlspecialchars($result); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
