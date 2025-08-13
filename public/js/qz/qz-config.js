/**
 * QZ Tray Configuration untuk mengatasi "Untrusted Website"
 * Metode: Self-signed certificate dengan server-side signing
 */

window.QZConfig = {
    // Setup security untuk QZ Tray
    setupSecurity: function () {
        // Set certificate promise
        qz.security.setCertificatePromise(function (resolve, reject) {
            // Untuk development, kita bisa menggunakan certificate yang sudah ada
            // atau membuat self-signed certificate
            fetch('/qz-certificate')
                .then(response => response.text())
                .then(function (certificate) {
                    console.log('Certificate loaded');
                    resolve(certificate);
                })
                .catch(function (error) {
                    console.warn('Certificate loading failed, using unsigned mode:', error);
                    // Fallback ke unsigned mode untuk development
                    resolve();
                });
        });

        // Set signature promise
        qz.security.setSignaturePromise(function (toSign) {
            return new Promise(function (resolve, reject) {
                // Kirim request ke server untuk signing
                fetch('/qz-sign', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        request: toSign
                    })
                })
                    .then(response => {
                        console.log('Sign response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(resolve)
                    .catch(function (error) {
                        console.warn('Signing failed, using unsigned mode:', error);
                        // Fallback ke unsigned mode untuk development
                        resolve(toSign);
                    });
            });
        });
    },

    // Setup untuk development (tanpa certificate)
    setupDevelopment: function () {
        console.log('Setting up QZ development mode...');

        qz.security.setCertificatePromise(function (resolve, reject) {
            console.log('Certificate promise - using unsigned mode');
            resolve(); // Allow unsigned requests
        });

        qz.security.setSignaturePromise(function (toSign) {
            return function (resolve, reject) {
                console.log('Signature promise - returning unsigned');
                resolve(toSign); // Allow unsigned requests
            };
        });
    }
};
