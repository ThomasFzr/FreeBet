document.addEventListener('DOMContentLoaded', function () {
    // Declare betsContainer at the beginning
    const betsContainer = document.getElementById('bets-tbody');
    // Sample data for bets
    const betsData = [];

    // Function to add a bet
    window.addBet = function (button, match, betType, amount) {
        betsData.push({ match, betType, amount });
        renderBets();

        // Disable all buttons in the same row
        const row = button.closest('tr');
        const buttonsInRow = row.querySelectorAll('.button');
        buttonsInRow.forEach(btn => {
            btn.disabled = true;
        });
    };

    // Function to delete a bet
    window.deleteBet = function (match) {
        const index = betsData.findIndex(bet => bet.match === match);
        if (index !== -1) {
            betsData.splice(index, 1);
            renderBets();
        }

        // Enable buttons for the deleted match
        const allButtons = document.querySelectorAll('.button');
        allButtons.forEach(button => {
            const row = button.closest('tr');
            if (row.querySelector('td:first-child').textContent.trim() === match) {
                button.disabled = false;
            }
        });
    }

    // Updated function to render bets
    function renderBets() {
        betsContainer.innerHTML = '';
    
        betsData.forEach(bet => {
            const betElement = document.createElement('tr');
            betElement.innerHTML = `<td>Match ${bet.match} - Result : ${bet.betType}</td>
                                           <td><span class="delete-icon" onclick="deleteBet('${bet.match}')">&#10006;</span></td>`;
            betsContainer.appendChild(betElement);
        });
    
        // Disable buttons based on betsData
        const allButtons = document.querySelectorAll('.button');
        allButtons.forEach(button => {
            const matchId = button.getAttribute('data-match');
            button.disabled = betsData.some(bet => bet.match === matchId);
        });
    }
    
    
    

    renderBets();
});