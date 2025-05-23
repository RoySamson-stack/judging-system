document.addEventListener('DOMContentLoaded', function() {
    // Refresh scoreboard every 10 seconds
    setInterval(refreshScoreboard, 10000);
    
    function refreshScoreboard() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newScoreboard = doc.getElementById('scoreboard').innerHTML;
                document.getElementById('scoreboard').innerHTML = newScoreboard;
            })
            .catch(error => console.error('Error refreshing scoreboard:', error));
    }
});