document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("decodeForm");
    form.addEventListener("submit", () => {
        const button = form.querySelector("button");
        button.textContent = "Decoding...";
        setTimeout(() => button.textContent = "Decode", 1000);
    });
});
