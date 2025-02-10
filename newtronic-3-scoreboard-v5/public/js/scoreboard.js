document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[id$='-update']").forEach(button => {
        button.addEventListener("click", function () {
            let sport = this.id.split("-")[0];
            let data = {
                sport: sport,
                teamA: document.getElementById(`${sport}-teamA`).value,
                teamB: document.getElementById(`${sport}-teamB`).value,
                scoreA: document.getElementById(`${sport}-scoreA`).value,
                scoreB: document.getElementById(`${sport}-scoreB`).value,
                additional_info: document.getElementById(`${sport}-set`).value
            };

            console.log('tes');

            fetch("/newtronic-3-scoreboard-v5/public/score/update", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    scoreA: document.getElementById('football-scoreA').value,
                    scoreB: document.getElementById('football-scoreB').value,
                    // Tambahkan data lain jika diperlukan
                })
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error("Error:", error));
        });
    });
});
