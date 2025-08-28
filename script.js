function encodeMessage(text) {
  // Step 1: Split text into words
  let words = text.split(/\s+/);

  // Step 2: Get unique words in original order
  let seen = new Set();
  let uniqueWords = [];
  words.forEach(word => {
    if (!seen.has(word)) {
      uniqueWords.push(word);
      seen.add(word);
    }
  });

  // Step 3: Number of unique words
  let n = uniqueWords.length;

  // Step 4: Assign numbers starting from the last backward
  let assignedNumbers = [];
  for (let i = 0; i < n; i++) {
    assignedNumbers.push(n - i);
  }

  // Step 5: Convert assigned numbers to ASCII char then to hex ASCII codes
  let wordHexAscii = {};
  for (let i = 0; i < n; i++) {
    let num = assignedNumbers[i];
    let char = String.fromCharCode(num);
    let hexVal = num.toString(16).toUpperCase().padStart(2, '0');
    wordHexAscii[uniqueWords[i]] = hexVal;
  }

  // Step 6: Swap two hex digits and put second digit at word start, first at word end
  let modifiedWords = [];
  for (const [word, hexVal] of Object.entries(wordHexAscii)) {
    modifiedWords.push(hexVal[1] + word + hexVal[0]);
  }

  // Step 7: Simple deterministic shuffle
  function simpleShuffle(lst) {
    let arr = [...lst];
    let n = arr.length;
    for (let i = n - 1; i > 0; i--) {
      let j = (i * 7 + 3) % n;
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
  }
  let shuffledWords = simpleShuffle(modifiedWords);

  // Step 8: Shift chars +15 only if ASCII between 32 and 126; else unchanged
  function shiftChars(text) {
    let shifted = '';
    for (let ch of text) {
      let asciiVal = ch.charCodeAt(0);
      if (asciiVal >= 32 && asciiVal <= 126) {
        let shiftedVal = asciiVal + 5;
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

  let joinedText = shuffledWords.join(' ');
  return shiftChars(joinedText);
}

document.getElementById('encodeButton').addEventListener('click', () => {
  let input = document.getElementById('inputText').value;
  if (!input.trim()) return;
  let encoded = encodeMessage(input);
  let resultDiv = document.getElementById('result');
  document.getElementById('encodedText').textContent = encoded;
  resultDiv.style.display = 'block';
});
