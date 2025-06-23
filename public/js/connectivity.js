// public/js/connectivity.js

/**
 * Verifica la conexión a la red local usando navigator.onLine.
 * @returns {boolean} True si el navegador detecta una conexión de red local, false en caso contrario.
 */
function checkNetworkConnection() {
    return navigator.onLine;
}

/**
 * Verifica el acceso a internet real intentando hacer un fetch a un servicio externo.
 * Incluye reintentos para mayor fiabilidad.
 * @param {number} retries - Número de veces que intentará la conexión si falla.
 * @param {number} delay - Retardo en milisegundos entre cada reintento.
 * @returns {Promise<boolean>} Una promesa que resuelve a true si hay acceso a internet, false en caso contrario.
 */
export async function checkInternetAccess(retries = 3, delay = 1000) {
    if (!checkNetworkConnection()) {
        // console.log("Offline: El navegador no detecta conexión a la red local.");
        return false;
    }

    let attempt = 1;
    let internetAccess = false;
    // Usamos un timestamp para evitar la caché y asegurar una petición fresca
    const testUrl = "https://jsonplaceholder.typicode.com/todos/1?" + new Date().getTime();

    while (attempt <= retries) {
        try {
            // console.log(`Verificando internet (Intento ${attempt}/${retries}): ${testUrl}`);
            const response = await fetch(testUrl, {
                method: "HEAD", // Solo pedimos las cabeceras, más eficiente
                cache: "no-store", // Evitar que el navegador use una respuesta en caché
                mode: "cors" // Asegurar que la petición siga las reglas CORS estándar
            });

            // response.ok es true para códigos de estado 200-299
            if (response.ok) {
                internetAccess = true;
                // console.log("¡Internet accesible!");
                break;
            }

            // Si llegamos aquí, la respuesta no fue 'ok' (ej. 404, 500), pero no fue un error de red directo.
            // Consideramos esto como un fallo para el chequeo de conectividad.
            throw new Error(`Respuesta no OK: ${response.status} ${response.statusText}`);

        } catch (err) {
            // Captura errores de red (DNS no resuelto, conexión rechazada, timeout, etc.)
            // console.error(`Error al verificar internet (Intento ${attempt}/${retries}): ${err.message}`);
            attempt++;
            if (attempt <= retries) {
                await new Promise(resolve => setTimeout(resolve, delay)); // Esperar antes del próximo reintento
            }
        }
    }
    if (!internetAccess) {
        // console.log("No se pudo confirmar el acceso a internet después de todos los reintentos.");
    }
    return internetAccess;
}

/**
 * Actualiza visualmente los iconos SVG de router para reflejar el estado de la conexión a internet.
 * Los iconos deben tener IDs 'router-on-icon' y 'router-off-icon'.
 */
export async function updateConnectionRouterIcons() {
    const routerOnIcon = document.getElementById('router-on-icon');
    const routerOffIcon = document.getElementById('router-off-icon');

    if (!routerOnIcon || !routerOffIcon) {
        console.warn("No se encontraron los elementos de iconos de router (router-on-icon o router-off-icon).");
        return;
    }

    const isConnected = await checkInternetAccess();

    if (isConnected) {
        // Muestra el icono de router conectado (verde)
        routerOnIcon.classList.remove('hidden');
        routerOnIcon.classList.add('text-green-500'); // Aplica color verde
        routerOnIcon.title = "Conectado a Internet";

        // Oculta el icono de router desconectado
        routerOffIcon.classList.add('hidden');
        routerOffIcon.classList.remove('text-red-500'); // Remueve color rojo
    } else {
        // Muestra el icono de router desconectado (rojo)
        routerOffIcon.classList.remove('hidden');
        routerOffIcon.classList.add('text-red-500'); // Aplica color rojo
        routerOffIcon.title = "Sin Conexión a Internet";

        // Oculta el icono de router conectado
        routerOnIcon.classList.add('hidden');
        routerOnIcon.classList.remove('text-green-500'); // Remueve color verde
    }
}

// Escucha los eventos online/offline del navegador para una reacción rápida
document.addEventListener('DOMContentLoaded', () => {
    window.addEventListener('online', async () => {
        // console.log("El navegador ha detectado que está online. Verificando acceso a internet real...");
        // Re-evaluar el estado real y actualizar los iconos
        await updateConnectionRouterIcons();
    });

    window.addEventListener('offline', () => {
        // console.log("El navegador ha detectado que está offline. Actualizando UI.");
        // Si el navegador reporta offline, actualiza los iconos inmediatamente
        const routerOnIcon = document.getElementById('router-on-icon');
        const routerOffIcon = document.getElementById('router-off-icon');
        if (routerOnIcon && routerOffIcon) {
            routerOnIcon.classList.add('hidden'); // Oculta el de conectado
            routerOnIcon.classList.remove('text-green-500'); // Remueve color verde

            routerOffIcon.classList.remove('hidden'); // Muestra el de desconectado
            routerOffIcon.classList.add('text-red-500'); // Aplica color rojo
            routerOffIcon.title = "Sin Conexión a Internet";
        }
    });
});