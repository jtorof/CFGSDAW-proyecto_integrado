import React, { useState } from 'react';
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

const Navigation = ({ showBreadcrumbs }) => {
  const [showBasic, setShowBasic] = useState(false);

  return (
    <>
      <MDBNavbar expand='md' dark bgColor='dark'>
        {/* <MDBContainer fluid> */}
          <MDBNavbarBrand href='#'>LOGO</MDBNavbarBrand>
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
              <MDBNavbarItem>
                <MDBNavbarLink tag={Link} to='login'>
                  Iniciar Sesión
                </MDBNavbarLink>
              </MDBNavbarItem>
              <MDBNavbarItem>
                <MDBNavbarLink tag={Link} to='signup'>
                  Crear Cuenta
                </MDBNavbarLink>
              </MDBNavbarItem>
            </MDBNavbarNav>
          </MDBCollapse>
       {/*  </MDBContainer> */}
      </MDBNavbar>
      <MDBRow>
        <div className='p-4 text-center bg-light d-none d-md-block'>
          <h1 className='mb-3'>Heading</h1>
          <h4 className='mb-3'>Subheading</h4>
        </div>
      </MDBRow>
      <MDBRow className='bg-light'>
        {showBreadcrumbs ? <Breadcrumbs /> : null}
      </MDBRow>
    </>
  )
}

export default Navigation