function decodeMessage(text) {
    // Step 1: Shift ASCII chars by -15 inside printable range (32-126)
    let shifted = "";
    for (let i = 0; i < text.length; i++) {
        let code = text.charCodeAt(i);
        if (code >= 32 && code <= 126) {
            let newCode = ((code - 32 - 15) % 95 + 95) % 95 + 32;
            shifted += String.fromCharCode(newCode);
        } else {
            shifted += text.charAt(i);
        }
    }

    let words = shifted.split(/\s+/);
    let numberedWords = [];

    // Steps 2 & 3: Rearrange letters + interpret first two as hex
    words.forEach(w => {
        if (w.length >= 2) {
            let rearranged = w.charAt(w.length - 1) + w.charAt(0) + w.slice(1, -1);
            let prefix = rearranged.slice(0, 2);
            let num = parseInt(prefix, 16);
            if (isNaN(num)) num = 0;
            let rest = rearranged.slice(2);
            numberedWords.push([num, rest]);
        } else {
            numberedWords.push([0, w]);
        }
    });

    // Step 4: Sort words by number descending
    numberedWords.sort((a, b) => b[0] - a[0]);

    // Join result
    return numberedWords.map(pair => pair[1]).join(" ");
}

document.getElementById("decodeButton").addEventListener("click", () => {
    let inputText = document.getElementById("encoded").value;
    let decoded = decodeMessage(inputText);
    const resultDiv = document.getElementById("result");
    document.getElementById("decodedText").textContent = decoded;
    resultDiv.style.display = "block";
});
