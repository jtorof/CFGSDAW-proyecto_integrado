const regExp = new RegExp('\d+');

const routesNamesMap = new Map();
routesNamesMap.set("", "Home");
routesNamesMap.set("docs", "Documentación");
routesNamesMap.set("login", "Iniciar Sesión");
routesNamesMap.set("registro", "Crear Cuenta");
routesNamesMap.set("perfil", "Perfil de Usuario");
routesNamesMap.set("opciones-avanzadas", "Opciones Avanzadas");
routesNamesMap.set("admin", "Administración");
routesNamesMap.set("acceso-restringido", "Acceso Restringido");
// routesNamesMap.set(regExp, "test");

export default routesNamesMap