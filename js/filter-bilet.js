// Обработчик изменения класса рейса
document.getElementById('trip-class').addEventListener('change', function () {
    const flightId = document.getElementById('flight_id').value;
    const tripClass = this.value;

    // Запрашиваем доступные места для выбранного рейса и класса
    fetch(`get_seats.php?flight_id=${flightId}&trip_class=${tripClass}`)
        .then(response => response.json())
        .then(seats => {
            const seatsSelection = document.getElementById('seats-selection');
            seatsSelection.innerHTML = ''; // Очищаем предыдущие места

            seats.forEach(seat => {
                let seatButton = document.createElement('button');
                seatButton.type = 'button';
                seatButton.classList.add('seat');
                seatButton.textContent = 'Место ' + seat.seat_number;
                seatButton.dataset.seatNumber = seat.seat_number;
                if (seat.is_booked) {
                    seatButton.disabled = true; // Заблокировать занятые места
                }
                seatsSelection.appendChild(seatButton);
            });
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
});
