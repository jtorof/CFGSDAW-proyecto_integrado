const regExp = new RegExp('\d+');

const routesNamesMap = new Map();
routesNamesMap.set("", "Home");
routesNamesMap.set("docs", "Documentación");
routesNamesMap.set("login", "Iniciar Sesión");
routesNamesMap.set("registro", "Crear Cuenta");
routesNamesMap.set("perfil", "Perfil de Usuario");
routesNamesMap.set("opciones-avanzadas", "Opciones Avanzadas");
// routesNamesMap.set("admin", "Administración");
routesNamesMap.set("creditos", "Créditos");
routesNamesMap.set("acceso-restringido", "Acceso Restringido");

export default routesNamesMap