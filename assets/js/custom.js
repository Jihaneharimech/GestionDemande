const currentDate = new Date();
const year = currentDate.getFullYear();
const month = currentDate.getMonth() + 1;
const day = currentDate.getDate();
const hours = currentDate.getHours();
const minutes = currentDate.getMinutes();
const formattedDate = `${day}/${month}/${year} ${hours}:${minutes}`;

const eventSource = new EventSource("{{ mercure('https://example.com/books/1')|escape('js') }}");
eventSource.onmessage = event => {
    const message = (JSON.parse(event.data)).status;
    const idVilleDemande = (JSON.parse(event.data)).idVilleDemande; // Récupérer la variable de id ville de la demande qui a ete creer par le manager
    const userCity = '{{ app.user.ville.id }}';

    if (idVilleDemande == userCity) { //Verifier si utilisateur connecter a la meme ville que la demande
    const messages = JSON.parse(localStorage.getItem("messages")) || [];
    messages.push({
        date: formattedDate,
        message: message
    });
    localStorage.setItem("messages", JSON.stringify(messages));
    updateMessages();
    } 
};

function updateMessages() {
    const messages = JSON.parse(localStorage.getItem("messages")) || [];
    const messageContainer = document.getElementById("message");
    messageContainer.innerHTML = "";
    messages.forEach(msg => {
        messageContainer.innerHTML += "<a class='dropdown-item d-flex align-items-center' href='#'><div class='mr-3'><div class='icon-circle bg-primary'><i class='fas fa-file-alt text-white'></i></div></div><div><div class='small text-gray-500'>"+msg.date+"</div>"+msg.message+"</div></a>";
    });

    // Update the counter element
    const counterNotifElement = document.getElementById("counterNotif");
    counterNotifElement.textContent = messages.length;
    if (messages.length > 0) {
        counterNotifElement.style.display = "inline-block";
    } else {
        counterNotifElement.style.display = "none";
    }
}   
window.addEventListener("load", updateMessages);