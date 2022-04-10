const arrayRoutes = [
    {
        path: "/",        
        name: "Home",
        Component: "Home",
        navbar: true,
        index: false,
    },
    {
        path: "docs",        
        name: "Documentación",
        Component: "Documentation",
        navbar: true,
        index: false,
    },
    {
        path: "login",        
        name: "Iniciar Sesión",
        Component: "Login",
        navbar: true,
        index: false,
    },
    {
        path: "signup",        
        name: "Crear Cuenta",
        Component: "SignUp",
        navbar: true,
        index: false,
    },
    {
        path: "admin",        
        name: "Administración",
        Component: null,
        navbar: false,
        index: false,
    },
];

export default arrayRoutes