function loadRecords() {
    fetch('listar.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById("tabelaRegistros").innerHTML = data;
        })
        .catch(error => console.error('Erro ao carregar registros:', error));
}

function initializeClock() {
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const dateString = now.toLocaleDateString('pt-BR', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        document.getElementById("currentTime").textContent = timeString;
        document.getElementById("currentDate").textContent = dateString;
    }
    
    updateClock();
    setInterval(updateClock, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    initializeClock();
    document.getElementById("currentYear").textContent = new Date().getFullYear();
    loadRecords();
    setInterval(loadRecords, 5000);
});

function handleOrientationChange() {
    if (window.matchMedia("(orientation: portrait)").matches) {
      console.log("Modo retrato");
      // Ajustes específicos para retrato
    }
    
    if (window.matchMedia("(orientation: landscape)").matches) {
      console.log("Modo paisagem");
      // Ajustes específicos para paisagem
    }
  }
  
  // Inicializar e adicionar listener
  window.addEventListener('DOMContentLoaded', () => {
    handleOrientationChange();
    window.addEventListener('resize', handleOrientationChange);
  });