//manejo del calendario



const calendarGrid = document.getElementById("calendarGrid");
const monthName = document.getElementById("monthName");

let currentDate = new Date();
let events = JSON.parse(localStorage.getItem("events")) || {};

function renderCalendar() {
    calendarGrid.innerHTML = "";

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    monthName.textContent = currentDate.toLocaleString("es-ES", {
        month: "long",
        year: "numeric"
    });

    // Espacios vacíos antes del día 1
    for (let i = 0; i < (firstDay === 0 ? 6 : firstDay - 1); i++) {
        const empty = document.createElement("div");
        calendarGrid.appendChild(empty);
    }

    // Días del mes
    for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement("div");
        cell.classList.add("day");
        cell.dataset.date = `${year}-${month + 1}-${day}`;

        cell.innerHTML = `<strong>${day}</strong>`;

        // Mostrar eventos guardados
        const dateKey = cell.dataset.date;
        if (events[dateKey]) {
            events[dateKey].forEach(ev => {
                const evDiv = document.createElement("div");
                evDiv.classList.add("event");
                evDiv.textContent = ev;
                cell.appendChild(evDiv);
            });
        }

        cell.addEventListener("click", () => openModal(dateKey));
        calendarGrid.appendChild(cell);
    }
}

document.getElementById("prevMonth").onclick = () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
};

document.getElementById("nextMonth").onclick = () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
};

// Modal
const modal = document.getElementById("eventModal");
const selectedDateText = document.getElementById("selectedDate");
let selectedDate = null;

function openModal(date) {
    selectedDate = date;
    selectedDateText.textContent = `Fecha: ${date}`;
    modal.classList.remove("hidden");
}

document.getElementById("closeModal").onclick = () => {
    modal.classList.add("hidden");
};

document.getElementById("saveEvent").onclick = () => {
    const type = document.getElementById("eventType").value;

    if (!events[selectedDate]) events[selectedDate] = [];
    events[selectedDate].push(type);

    localStorage.setItem("events", JSON.stringify(events));

    modal.classList.add("hidden");
    renderCalendar();
};

renderCalendar();
