const boardSizeInput = document.getElementById('board-size');
const sizeForm = document.getElementById('size-form');
const gameBoard = document.getElementById('game-board');
let boardSize, board, currentPlayer;

const generateBoard = (size) => {
    // Reset the winner count
    playerWins = 0;
    computerWins = 0;

    boardSize = size;
    board = Array(boardSize).fill(null).map(() => Array(boardSize).fill(null));
    currentPlayer = '★';

    gameBoard.innerHTML = '';
    for (let i = 0; i < boardSize; i++) {
        const row = document.createElement('tr');
        for (let j = 0; j < boardSize; j++) {
            const cell = document.createElement('td');
            cell.addEventListener('click', () => handleMove(i, j));
            row.appendChild(cell);
        }
        gameBoard.appendChild(row);
    }
};




const checkDraw = () => {
    for (let i = 0; i < boardSize; i++) {
        for (let j = 0; j < boardSize; j++) {
            if (!board[i][j]) {
                return false;
            }
        }
    }
    return true;
};
let playerWins = 0;
let computerWins = 0;
const updateWinnerCount = () => {
    for (let row = 0; row < boardSize; row++) {
        for (let col = 0; col < boardSize; col++) {
            if (checkWinner(row, col)) {
                if (board[row][col] === '★') {
                    playerWins++;
                } else if (board[row][col] === '☆') {
                    computerWins++;
                }
            }
        }
    }
};

const handleMove = (row, col) => {
    if (!board[row][col]) {
        // Player move
        board[row][col] = currentPlayer;
        gameBoard.rows[row].cells[col].innerText = currentPlayer;

        // Switch player
        currentPlayer = currentPlayer === '★' ? '☆' : '★';

        // Computer move
        computerMove();

        if (checkDraw()) {
            updateWinnerCount();

            setTimeout(() => {
                if (playerWins > computerWins) {
                    alert(" ★  wins!");
                } else if (computerWins > playerWins) {
                    alert(" ☆  wins!");
                } else {
                    alert("Pas de gagnant!");
                }
                generateBoard(boardSize);
            }, 100);
        }
    }
};

const computerMove = () => {
    const emptyCells = [];
    for (let i = 0; i < boardSize; i++) {
        for (let j = 0; j < boardSize; j++) {
            if (!board[i][j]) {
                emptyCells.push({ row: i, col: j });
            }
        }
    }

    if (emptyCells.length > 0) {
        const randomIndex = Math.floor(Math.random() * emptyCells.length);
        const { row, col } = emptyCells[randomIndex];
        board[row][col] = currentPlayer;
        gameBoard.rows[row].cells[col].innerText = currentPlayer;

        updateWinnerCount(row, col);

        // Switch player
        currentPlayer = currentPlayer === '★' ? '☆' : '★';
    }
};


const checkWinner = (row, col) => {
    const player = board[row][col];
    let rowCount = 0;
    let colCount = 0;
    let diag1Count = 0;
    let diag2Count = 0;

    for (let i = 0; i < boardSize; i++) {
        // Check row and column
        if (board[row][i] === player) rowCount++;
        if (board[i][col] === player) colCount++;

        // Check diagonals
        if (board[i][i] === player) diag1Count++;
        if (board[i][boardSize - 1 - i] === player) diag2Count++;
    }

    return rowCount === boardSize || colCount === boardSize || diag1Count === boardSize || diag2Count === boardSize;
};


// Start the game with the default size (4x4)
generateBoard(4);

// Add event listener for the size form
sizeForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const size = parseInt(boardSizeInput.value, 10);
    if (size >= 4 && size <= 10) {
        generateBoard(size);
    } else {
        alert("Please choose a board size between 4 and 10.");
    }
});

