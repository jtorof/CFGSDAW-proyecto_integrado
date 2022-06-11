import React, { useContext, useState } from 'react';
import {
  MDBContainer,
  MDBNavbar,
  MDBNavbarBrand,
  MDBNavbarToggler,
  MDBIcon,
  MDBNavbarNav,
  MDBNavbarItem,
  MDBNavbarLink,
  MDBCollapse,
  MDBRow,
} from 'mdb-react-ui-kit';
import { Link } from 'react-router-dom';
import Breadcrumbs from './Breadcrumbs';
import { UserContext } from '../helpers/context';
import fetchData from '../helpers/fetchData';

const logoutHeaders = {
  'Content-Type': 'application/json',
}

const Navigation = ({ showBreadcrumbs }) => {
  const context = useContext(UserContext);
  const [showBasic, setShowBasic] = useState(false);

  const handleLogout = async (e) => {
    e.preventDefault();
    const data = await fetchData('/logout', 'POST', logoutHeaders);
    // console.log(data);
    context.setGlobalUser({});
  };

  return (
    <>
      <MDBNavbar expand='md' dark bgColor='dark' className="fixed-top">
        <MDBContainer breakpoint="xl">
          <MDBNavbarBrand tag={Link} to='/'>
            <img src='/img/logo-white.svg' alt='Logo de APIParaPracticar' id='site-logo-header'></img>
          </MDBNavbarBrand>
          {"email" in context.globalUser ?
            <MDBNavbarNav fullWidth={false}>
              <MDBNavbarItem className='navbar-text'>
                <span className='d-none d-md-inline-block'>Sesión iniciada como: </span><span></span>{context.globalUser.email}
              </MDBNavbarItem>
            </MDBNavbarNav> :
            null
          }
          <MDBNavbarToggler
            aria-controls='navbarSupportedContent'
            aria-expanded='false'
            aria-label='Toggle navigation'
            onClick={() => setShowBasic(!showBasic)}
          >
            <MDBIcon icon='bars' fas />
          </MDBNavbarToggler>

          <MDBCollapse navbar show={showBasic}>
            <MDBNavbarNav right fullWidth={false}>
              <MDBNavbarItem>
                <MDBNavbarLink tag={Link} to='/'>
                  Home
                </MDBNavbarLink>
              </MDBNavbarItem>
              <MDBNavbarItem>
                <MDBNavbarLink tag={Link} to='docs'>
                  Documentación
                </MDBNavbarLink>
              </MDBNavbarItem>
              {"email" in context.globalUser ?
                <>
                  <MDBNavbarItem>
                    <MDBNavbarLink tag={Link} to='perfil'>
                      Perfil
                    </MDBNavbarLink>
                  </MDBNavbarItem>
                  <MDBNavbarItem onClick={handleLogout}>
                    <MDBNavbarLink>
                      Cerrar Sesión
                    </MDBNavbarLink>
                  </MDBNavbarItem>
                </> :
                <>
                  <MDBNavbarItem>
                    <MDBNavbarLink tag={Link} to='login'>
                      Iniciar Sesión
                    </MDBNavbarLink>
                  </MDBNavbarItem>
                  <MDBNavbarItem>
                    <MDBNavbarLink tag={Link} to='registro'>
                      Crear Cuenta
                    </MDBNavbarLink>
                  </MDBNavbarItem>
                </>
              }
            </MDBNavbarNav>
          </MDBCollapse>
        </MDBContainer>
      </MDBNavbar>
      <div className='text-center bg-light custom-header'>
        <h1 className='mb-3 pt-1'>APIParaPracticar</h1>
        <h4 className='mb-3 d-none d-md-block'>API REST para aprender a atacar APIs</h4>
      </div>
      {showBreadcrumbs ? <Breadcrumbs /> : null}
    </>
  )
}

export default Navigation