function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // raio da Terra em metros
    const φ1 = lat1 * Math.PI / 180;
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
              Math.cos(φ1) * Math.cos(φ2) *
              Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c; // retorna em metros
}

document.getElementById("pontoForm").addEventListener("submit", function(event) {
    event.preventDefault(); // previne envio por padrão

    let cracha = document.getElementById("cracha").value;
    if (cracha.length < 1) {
        alert("O número do crachá é obrigatório!");
        return;
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            const empresaLat = -23.487633;  // <<< coloque aqui a latitude da sua empresa
            const empresaLng = -46.626869;  // <<< e a longitude também

            const distancia = calcularDistancia(userLat, userLng, empresaLat, empresaLng);

            if (distancia > 136) {
                document.getElementById("pontoForm").submit();
            } else {
                alert("Você está fora da área permitida para bater o ponto.");
            }
        }, function(error) {
            alert("Erro ao obter localização: " + error.message);
        });
    } else {
        alert("Geolocalização não suportada pelo seu navegador.");
    }
});

// Configuração da câmera e leitor de código de barras
document.getElementById("abrirCamera").addEventListener("click", function() {
    const cameraContainer = document.getElementById("cameraContainer");
    const videoElement = document.getElementById("camera");
    cameraContainer.style.display = "block";

    navigator.mediaDevices.getUserMedia({ 
        video: {
            facingMode: "environment",
            width: 1280,
            height: 720
        } 
    }).then(function(stream) {
        videoElement.srcObject = stream;
        
        // Inicializa o Quagga para leitura de código de barras
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: videoElement,
                constraints: {
                    width: 1280,
                    height: 720,
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: ["i2of5_reader"]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error("Erro ao inicializar Quagga:", err);
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            document.getElementById("cracha").value = code;
            fecharCamera();
            alert("Crachá lido: " + code);
        });
    }).catch(function(err) {
        console.error("Erro ao acessar a câmera:", err);
        alert("Não foi possível acessar a câmera. Verifique as permissões.");
    });
});

function fecharCamera() {
    const videoElement = document.getElementById("camera");
    const stream = videoElement.srcObject;
    
    if (stream) {
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
    }
    
    if (Quagga) {
        Quagga.stop();
    }
    
    videoElement.srcObject = null;
    document.getElementById("cameraContainer").style.display = "none";
}

document.getElementById("fecharCamera").addEventListener("click", fecharCamera);

//Função responsável por exibir a data e hora atual
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
});
