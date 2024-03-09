document.addEventListener('DOMContentLoaded', function () {
    // Declare betsContainer at the beginning
    const betsContainer = document.getElementById('bets-tbody');
    // Sample data for bets
    const betsData = [];

    // Function to add a bet
    window.addBet = function (button, match, opponent, betType, amount) {
        betsData.push({ match, opponent, betType, amount });
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
            const betElement = document.createElement('div');
            betElement.innerHTML = `<div class="bet-line">
							<div class="title-delete-container">
								⚽<div class="title">${bet.opponent} - OL</div>
								<span class="delete-icon" onclick="deleteBet('${bet.match}')">&#10006;</span>
                                <input type="hidden" name="match_id[]" value="${bet.match}">
							</div>
							<div class="result-amount-container">
								<div class="result">${bet.betType}</div>
                                <input type="hidden" name="result[]" value="${bet.betType}">
								<input placeholder="Mise" class="input-bet-amount" name="amount[]" type="number" required>
							</div>                            
						</div>`;




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