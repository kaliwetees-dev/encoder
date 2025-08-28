function gcd(x, y) {
    while (y !== 0) {
        [x, y] = [y, x % y];
    }
    return x;
}

function findCoprime(n) {
    if (n <= 2) return 1;  // fallback for small inputs
    let a = 2;
    while (a < n) {
        if (gcd(a, n) === 1) {
            return a;
        }
        a++;
    }
    return 1;
}

function transformWord(word, hexval) {
    let transformed = word;
    let toggle = true;
    for (const digit of hexval) {
        if (toggle) transformed = transformed + digit;
        else transformed = digit + transformed;
        toggle = !toggle;
    }
    return transformed;
}

function simpleIndexShuffle(words) {
    const n = words.length;
    const a = findCoprime(n);
    const b = 1;
    if (a === 1) {
        // no valid shuffle possible, return original array
        return words.slice();
    }
    const shuffled = Array(n).fill(null);
    for (let i = 0; i < n; i++) {
        const newIndex = (a * i + b) % n;
        shuffled[newIndex] = words[i];
    }
    return shuffled;
}

function shiftChar(c, shift = 15, lower = 32, upper = 126) {
    const asciiVal = c.charCodeAt(0);
    if (asciiVal >= lower && asciiVal <= upper) {
        let shifted = asciiVal + shift;
        if (shifted > upper) {
            shifted = lower + (shifted - upper - 1);
        }
        return String.fromCharCode(shifted);
    } else {
        return c;
    }
}

function shiftString(s, shift = 15) {
    let result = "";
    for (const c of s) {
        result += shiftChar(c, shift);
    }
    return result;
}

function processSentence(sentence) {
    const words = sentence.split(" ");
    const n = words.length;
    const transformedWords = [];
    for (let i = 0; i < n; i++) {
        const num = n - i;
        const hexval = num.toString(16).padStart(2, "0");
        const transformed = transformWord(words[i], hexval);
        transformedWords.push(transformed);
    }
    const jumbled = simpleIndexShuffle(transformedWords);
    return jumbled;
}

// Encode button event handler
document.getElementById("encodeButton").addEventListener("click", () => {
    const input = document.getElementById("inputText").value.trim();
    if (!input) {
        alert("Please enter some text to encode.");
        return;
    }
    try {
        const jumbledWords = processSentence(input);
        const joined = jumbledWords.join(" ");
        const shifted = shiftString(joined, 15);
        document.getElementById("outputText").value = shifted;
    } catch (err) {
        alert("Error: " + err.message);
    }
});

// Copy button event handler
document.getElementById("copyButton").addEventListener("click", () => {
    const outputText = document.getElementById("outputText");
    if (!outputText.value) {
        alert("No encoded text to copy!");
        return;
    }
    navigator.clipboard.writeText(outputText.value).then(() => {
        alert("Copied to clipboard!");
    }).catch((err) => {
        alert("Failed to copy: " + err);
    });
});
