const API_BASE = 'http://localhost/fintech/backend/api';

async function apiFetch(endpoint, opciones = {}) {
    const token = localStorage.getItem('jwt_token'); // Cabeceras por defecto: Content-Type + Authorization JWT
    const cabeceras = {'Content-Type' : 'application/json', ...(token && { 'Authorization': `Bearer ${token}` }), ...opciones.headers};
    try {
        const respuesta = await fetch(`${API_BASE}/${endpoint}`, {...opciones, headers:cabeceras}); // Parsear siempre el JSON aunque haya error HTTP const datos = await
        respuesta.json(); // Gestión centralizada de errores HTTP 
        if (!respuesta.ok) {
            gestionarErrorHTTP(respuesta.status, datos.message);
            return null;
        }
        return datos; // Objeto JSON del servidor
    } 
    
    catch (error) { // Error de red: sin conexión, timeout, CORS...
        mostrarNotificacion('Error de conexión con el servidor.', 'error');
        console.error('Error de red: ', error);
        return null;
    }
} // Gestión centralizada por código de estado

function gestionarErrorHTTP(status, mensaje) {
    switch (status) {
        case 400:
            mostrarNotificacion(mensaje || 'Datos incorrectos.', 'advertencia');
            break;

        case 401:
            mostrarNotificacion('Sesión expirada.Redirigiendo...', 'error');
            localStorage.removeItem('jwt_token');
            setTimeout(() => window.location.href = 'index.html', 1500);
            break;

        case 404:
            mostrarNotificacion('Recurso no encontrado.', 'advertencia');
            break;

        case 500:
            mostrarNotificacion('Error interno del servidor.', 'error');
            break;

        default:
            mostrarNotificacion(`Error inesperado (${status}).`, 'error');
    }
}