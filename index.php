<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentence Encoder</title>
    <link rel="stylesheet" href="style.css">
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
        // This line includes the PHP script with the encoding logic.
        require_once 'encoder.php'; 

        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['text_input'])) {
            $text = $_POST['text_input'];
            
            // This function call will use the logic from encoder.php.
            $final_text = encode_sentence($text);
            
            echo '<h2>Encoded Sentence</h2>';
            echo '<div class="output">' . htmlspecialchars($final_text) . '</div>';
        }
        ?>
    </div>
</body>
</html>
