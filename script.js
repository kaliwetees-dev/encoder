document.addEventListener('DOMContentLoaded', () => {
  function encodeMessage(text) {
    // Step 1: Split input text into words (including duplicates)
    let words = text.split(/\s+/);
    console.log("Original words:", words);

    // Step 2: Number of words (including duplicates)
    let n = words.length;

    // Step 3: Assign numbers starting from the last backward for every word
    let assignedNumbers = [];
    for (let i = 0; i < n; i++) {
      assignedNumbers.push(n - i);
    }

    // Step 4: Convert assigned numbers to hex ASCII codes for each word
    let modifiedWords = [];
    for (let i = 0; i < n; i++) {
      let num = assignedNumbers[i];
      let hexVal = num.toString(16).toUpperCase().padStart(2, '0');
      // Swap two hex digits and place second digit at word start, first at word end
      let modifiedWord = hexVal[1] + words[i] + hexVal[0];
      modifiedWords.push(modifiedWord);
    }
    console.log("Modified words BEFORE shuffle:", modifiedWords);

    // Step 5: Simple deterministic shuffle function
    function simpleShuffle(lst) {
      let arr = [...lst];
      let length = arr.length;
      for (let i = length - 1; i > 0; i--) {
        let j = (i * 7 + 3) % length;
        [arr[i], arr[j]] = [arr[j], arr[i]];
      }
      return arr;
    }
    let shuffledWords = simpleShuffle(modifiedWords);
    console.log("Shuffled words AFTER shuffle:", shuffledWords);

    // Step 6: Shift all chars +5 within printable ASCII (32-126); else unchanged
    function shiftChars(text) {
      let shifted = '';
      for (let ch of text) {
        let asciiVal = ch.charCodeAt(0);
        if (asciiVal >= 32 && asciiVal <= 126) {
          let shiftedVal = asciiVal + 15;
          if (shiftedVal > 126) {
            shiftedVal = 32 + (shiftedVal - 127);
          }
          shifted += String.fromCharCode(shiftedVal);
        } else {
          shifted += ch;
        }
      }
      return shifted;
    }

    // Join shuffled words with space and shift characters
    let joinedText = shuffledWords.join(' ');
    console.log("Joined text BEFORE shift:", joinedText);

    let shiftedText = shiftChars(joinedText);
    console.log("Shifted text AFTER shift:", shiftedText);

    return shiftedText;
  }

  document.getElementById('encodeButton').addEventListener('click', () => {
    let input = document.getElementById('inputText').value;
    if (!input.trim()) return; // Ignore empty input
    let encoded = encodeMessage(input);
    document.getElementById('encodedText').textContent = encoded;
    document.getElementById('result').style.display = 'block';
  });
});
