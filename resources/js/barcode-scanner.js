import Quagga from 'quagga';

document.addEventListener('DOMContentLoaded', function() {
    const scanButton = document.getElementById('scan-barcode');
    const scannerArea = document.getElementById('scanner-container');
    const resultContainer = document.getElementById('barcode-result');
    
    if (scanButton) {
        scanButton.addEventListener('click', function() {
            scannerArea.style.display = 'block';
            startScanner();
        });
    }
    
    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: scannerArea,
                constraints: {
                    width: 480,
                    height: 320,
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: [
                    "ean_reader",
                    "ean_8_reader",
                    "code_128_reader",
                    "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader"
                ],
                debug: {
                    showCanvas: true,
                    showPatches: true,
                    showFoundPatches: true,
                    showSkeleton: true,
                    showLabels: true,
                    showPatchLabels: true,
                    showRemainingPatchLabels: true,
                    boxFromPatches: {
                        showTransformed: true,
                        showTransformedBox: true,
                        showBB: true
                    }
                }
            },
        }, function(err) {
            if (err) {
                console.log(err);
                return;
            }
            
            console.log("QuaggaJS iniciado");
            Quagga.start();
            
            Quagga.onDetected(function(result) {
                var code = result.codeResult.code;
                
                // Detener el escáner
                Quagga.stop();
                scannerArea.style.display = 'none';
                
                // Mostrar el resultado
                resultContainer.innerHTML = `<p>Código detectado: ${code}</p>`;
                
                // Enviar al servidor para buscar
                searchBarcode(code);
            });
        });
    }
    
    function searchBarcode(barcode) {
        fetch('/products/barcode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.product) {
                    window.location.href = `/products/${data.product.id}`;
                } else {
                    resultContainer.innerHTML += `<p>Código encontrado pero no hay producto asociado.</p>`;
                }
            } else {
                resultContainer.innerHTML += `<p>Error: ${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultContainer.innerHTML += `<p>Error al procesar la solicitud.</p>`;
        });
    }
});