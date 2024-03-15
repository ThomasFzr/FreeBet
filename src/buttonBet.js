document.addEventListener('DOMContentLoaded', function () {
    // Declare betsContainer at the beginning
    const betsContainer = document.getElementById('bets-tbody');
    // Sample data for bets
    const betsData = [];


    var initialCoins = parseInt(document.getElementById('coinDiv').getAttribute('data-coin'), 10);
    var currentCoins = initialCoins; // Ceci gardera la trace du nombre actuel de coins après les paris


    // Function to add a bet
    window.addBet = function (button, match, opponent, bet, betName, amount) {
        betsData.push({ match, opponent, bet, betName, amount });
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

    function updateTotalAmount() {
        const amountInputs = document.querySelectorAll('input.input-bet-amount');
        let totalAmount = 0;
        amountInputs.forEach(input => {
            const amount = Number(input.value) || 0;
            totalAmount += amount;
        });

        const totalBetAmountMessage = document.getElementById('totalBetAmountMessage');
        if (totalBetAmountMessage) {
            totalBetAmountMessage.textContent = `credit parier : ${totalAmount}`;
        }

        // Déduire le montant total des paris du nombre initial de coins
        currentCoins = initialCoins - totalAmount;

        // Mettre à jour l'affichage des coins
        const coinsElement = document.getElementById('coinDiv');
        if (coinsElement) {
            coinsElement.textContent = `Coins restants : ${currentCoins}`;
        }
        // Mettre à jour le champ caché avec la nouvelle valeur de currentCoins
        document.getElementById('currentCoinsField').value = currentCoins.toString();




    }

    var coin = document.getElementById('coinDiv').getAttribute('data-coin');
    console.log("le nombre de coin est de :" + coin);

    function renderBets() {
        betsContainer.innerHTML = '';
        console.log("Taille de betsData:", betsData.length);

        betsData.forEach(bet => {
            const betElement = document.createElement('div');
            betElement.classList.add('bet-line'); // Ajouté pour le style
            betElement.innerHTML = `
                <div class="title-delete-container">
                    ⚽<div class="title">${bet.opponent} - OL</div>
                    <span class="delete-icon" onclick="deleteBet('${bet.match}')">&#10006;</span>
                    <input type="hidden" name="match_id[]" value="${bet.match}">
                </div>
                <div class="result-amount-container">
                    <div class="result">${bet.betName}</div>
                    <input type="hidden" name="result[]" value="${bet.bet}">
                    <input min="0" placeholder="Mise" class="input-bet-amount" name="amount[]" type="number" required>
                </div>
            `;
            betsContainer.appendChild(betElement);

            // Attacher un gestionnaire d'événement 'input' pour mettre à jour le montant total
            betElement.querySelector('input.input-bet-amount').addEventListener('input', updateTotalAmount);
        });

        // La mise à jour initiale du montant total est déclenchée ici
        updateTotalAmount();

        // Désactiver les boutons basés sur betsData, comme vous l'avez fait.
        const allButtons = document.querySelectorAll('.button');
        allButtons.forEach(button => {
            const matchId = button.getAttribute('data-match');
            button.disabled = betsData.some(bet => bet.match === matchId);
        });
    }
    

    renderBets();
});