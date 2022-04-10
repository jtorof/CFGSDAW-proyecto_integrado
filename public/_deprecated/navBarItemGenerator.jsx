{arrayRoutes.map((ruta) => {
  if (!ruta.navbar) {
    return null;
  }
  return <MDBNavbarItem><MDBNavbarLink tag={Link} to={ruta.path}>{ruta.name}</MDBNavbarLink></MDBNavbarItem>;
  })}