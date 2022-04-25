const regExp = new RegExp('\d+');

const routesNamesMap = new Map();
routesNamesMap.set("", "Home");
routesNamesMap.set("docs", "Documentación");
routesNamesMap.set("login", "Iniciar Sesión");
routesNamesMap.set("signup", "Crear Cuenta");
routesNamesMap.set("profile", "Perfil de Usuario");
routesNamesMap.set("admin", "Administración");
routesNamesMap.set(regExp, "test");

export default routesNamesMap