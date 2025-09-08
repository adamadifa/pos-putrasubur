/**
 * QZ Tray Configuration untuk mengatasi "Untrusted Website"
 * Bypass security untuk development di localhost
 */

// Bypass QZ security untuk development
qz.security.setCertificatePromise(function (resolve, reject) {
    if (window.location.hostname === 'localhost' ||
        window.location.hostname === '127.0.0.1') {
        resolve(); // Skip certificate check
    } else {
        // Untuk production nanti
        fetch('/certificates/qz-tray.crt')
            .then(response => response.text())
            .then(resolve)
            .catch(reject);
    }
});

qz.security.setSignaturePromise(function (toSign) {
    return function (resolve, reject) {
        if (window.location.hostname === 'localhost' ||
            window.location.hostname === '127.0.0.1') {
            resolve(''); // Empty signature
        } else {
            resolve('production-signature');
        }
    };
});
